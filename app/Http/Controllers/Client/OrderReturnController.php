<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderReturnController extends Controller
{
    /**
     * Khách hàng tự hủy đơn hàng hoặc gửi yêu cầu đổi size/màu, hủy đơn hoàn tiền
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $order = Order::with(['items.product.variants'])->where('user_id', $user->id)->findOrFail($id);

        if ($order->shipping_status === 'shipping' || ($order->status_step ?? 1) >= 4 || !empty($order->shipper_id)) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đã được bưu tá tiếp nhận và đang trên đường giao đến bạn. Để đảm bảo an toàn đơn hàng, bạn không thể hủy ngay. Vui lòng nhận kiện hàng và ấn "Hủy Hàng Hoàn Tiền" hoặc "Đổi Trả" sau khi nhận hàng.');
        }

        if (!$order->canBeCancelledByCustomer()) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' không ở trạng thái được phép hủy đơn!');
        }

        $reason = $request->input('reason', '');

        // TRƯỜNG HỢP 1: Khách hàng muốn đổi Size hoặc Màu sắc sản phẩm -> Gửi yêu cầu cho Admin duyệt
        if ($reason === 'Tôi muốn thay đổi Size hoặc Màu sắc sản phẩm' || $request->filled('exchange_size') || $request->filled('exchange_color')) {
            if ($order->shipping_status === 'shipping' || ($order->status_step ?? 1) >= 4 || !empty($order->shipper_id)) {
                return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đang trong hành trình giao hàng, không thể đổi size/màu. Quý khách vui lòng nhận hàng và chọn Đổi Hàng Miễn Phí Tận Nhà sau khi nhận kiện hàng.');
            }

            $validated = $request->validate([
                'exchange_size' => 'required|string|max:50',
                'exchange_color' => 'required|string|max:50',
                'order_item_id' => 'nullable|exists:order_items,id',
                'notes' => 'nullable|string|max:500',
            ], [
                'exchange_size.required' => 'Vui lòng chọn kích cỡ (size) mới mong muốn đổi.',
                'exchange_color.required' => 'Vui lòng chọn màu sắc mới mong muốn đổi.',
            ]);

            $selectedItem = null;
            if (!empty($validated['order_item_id'])) {
                $selectedItem = $order->items->firstWhere('id', $validated['order_item_id']);
            }
            if (!$selectedItem) {
                $selectedItem = $order->items->first();
            }

            $returnCode = 'EXC-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $changeNote = "Khách hàng gửi yêu cầu đổi Size: {$validated['exchange_size']}, Màu: {$validated['exchange_color']}" . ($request->filled('notes') ? ' | Ghi chú: ' . trim($validated['notes']) : '');

            DB::transaction(function () use ($order, $user, $validated, $selectedItem, $returnCode, $changeNote) {
                OrderReturn::create([
                    'return_code' => $returnCode,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'order_item_id' => $selectedItem ? $selectedItem->id : null,
                    'type' => 'exchange',
                    'reason' => 'Khách muốn đổi Size/Màu: ' . ($selectedItem ? $selectedItem->product_name : 'Sản phẩm'),
                    'customer_notes' => $changeNote,
                    'exchange_size' => $validated['exchange_size'],
                    'exchange_color' => $validated['exchange_color'],
                    'status' => 'pending',
                    'refund_amount' => 0,
                    'admin_notes' => "Yêu cầu đổi sang Size {$validated['exchange_size']}, Màu {$validated['exchange_color']} trước khi đơn hàng xuất kho",
                ]);

                $order->update([
                    'admin_notes' => ($order->admin_notes ? $order->admin_notes . " | " : "") . "[Yêu cầu đổi Size {$validated['exchange_size']}, Màu {$validated['exchange_color']} (Mã RMA: {$returnCode}) lúc " . now()->format('d/m/Y H:i') . " - Chờ Admin duyệt]",
                ]);
            });

            return back()->with('success', "Yêu cầu đổi sang Size: {$validated['exchange_size']}, Màu: {$validated['exchange_color']} cho đơn hàng #{$order->order_code} đã được gửi thành công đến Quản trị viên để duyệt! BeeStyle sẽ kiểm tra tồn kho và phản hồi sớm nhất cho bạn.");
        }

        // TRƯỜNG HỢP 2: Hủy đơn thông thường hoặc Hủy đơn yêu cầu hoàn tiền
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
        ], [
            'reason.required' => 'Vui lòng chọn lý do hủy đơn hàng.',
        ]);

        $cancelReason = $validated['reason'] . ($request->filled('notes') ? ' - ' . trim($validated['notes']) : '');
        $isPrePaid = in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID']);

        // Cập nhật tài khoản ngân hàng của user nếu được cung cấp
        if ($request->filled('bank_name') && $request->filled('bank_account_number')) {
            $user->update([
                'bank_name' => $request->input('bank_name'),
                'bank_account_number' => trim($request->input('bank_account_number')),
                'bank_account_name' => mb_strtoupper(trim($request->input('bank_account_name') ?: $user->name), 'UTF-8'),
                'bank_branch' => trim($request->input('bank_branch', '')),
            ]);
        }

        DB::transaction(function () use ($order, $cancelReason, $user, $isPrePaid, $request) {
            // 1. Hoàn trả tồn kho cho các sản phẩm & biến thể về kho ngay lập tức
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    $prod = Product::find($item->product_id);
                    if ($prod && $prod->sold_count >= $item->quantity) {
                        $prod->decrement('sold_count', $item->quantity);
                    }

                    if (!empty($item->color) && !empty($item->size)) {
                        ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            // 2. Khôi phục lượt sử dụng mã giảm giá (Voucher)
            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)->first();
                if ($coupon && $coupon->used_count > 0) {
                    $coupon->decrement('used_count');
                }
            }

            // 3. Nếu đơn đã thanh toán online: Tạo phiếu hoàn tiền gửi Admin duyệt chuyển khoản
            if ($isPrePaid) {
                $returnCode = 'REF-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                $refundAmount = ($order->is_deposit_required && $order->payment_status === 'deposit_paid')
                    ? (float)$order->deposit_amount
                    : (float)$order->total_amount;

                OrderReturn::create([
                    'return_code' => $returnCode,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'type' => 'return_refund',
                    'reason' => $cancelReason,
                    'customer_notes' => $request->input('notes'),
                    'refund_amount' => $refundAmount,
                    'refund_method' => 'bank',
                    'bank_name' => $request->input('bank_name', $user->bank_name),
                    'bank_account_number' => $request->input('bank_account_number', $user->bank_account_number),
                    'bank_account_name' => mb_strtoupper($request->input('bank_account_name', $user->bank_account_name ?: $user->name), 'UTF-8'),
                    'bank_branch' => $request->input('bank_branch', $user->bank_branch),
                    'status' => 'pending',
                    'admin_notes' => 'Khách hủy đơn online đã thanh toán - Đã hoàn kho hệ thống, chờ Admin duyệt chuyển khoản hoàn tiền',
                ]);

                $order->update([
                    'shipping_status' => 'cancelled',
                    'payment_status' => 'refund_pending',
                    'status_step' => 0,
                    'cancel_reason' => $cancelReason,
                    'cancelled_by' => 'customer_refund',
                    'cancelled_at' => now(),
                ]);
            } else {
                // Đơn COD hoặc chưa thanh toán
                $order->update([
                    'shipping_status' => 'cancelled',
                    'payment_status' => 'cancelled',
                    'status_step' => 0,
                    'cancel_reason' => $cancelReason,
                    'cancelled_by' => 'customer',
                    'cancelled_at' => now(),
                ]);
            }
        });

        if ($isPrePaid) {
            return back()->with('success', "Đơn hàng #{$order->order_code} đã được hủy thành công và yêu cầu hoàn tiền đã được gửi đến Quản trị viên để duyệt và chuyển khoản vào tài khoản ngân hàng của bạn.");
        }

        return back()->with('success', "Đơn hàng #{$order->order_code} đã được hủy thành công và mã giảm giá (voucher) đã được khôi phục!");
    }

    /**
     * Khách hàng đổi địa chỉ nhận hàng cho đơn hàng:
     * - Lựa chọn 1: Cập nhật địa chỉ mới và tiếp tục giao hàng
     * - Lựa chọn 2: Cập nhật địa chỉ xong nhưng xác nhận hủy đơn do không muốn đặt nữa
     */
    public function updateOrderAddress(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $order = Order::with('items')->where('user_id', $user->id)->findOrFail($id);

        if (!in_array($order->shipping_status, ['pending', 'confirmed', 'processing']) 
            || $order->shipping_status === 'cancelled'
            || !empty($order->shipper_id)
            || ($order->status_step ?? 1) >= 4) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đã được bàn giao cho bưu tá hoặc đang trên đường giao, không thể thay đổi địa chỉ nhận hàng!');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'action_after' => 'required|string|in:keep,cancel',
            'cancel_notes' => 'nullable|string|max:500',
        ], [
            'customer_name.required' => 'Vui lòng nhập tên người nhận hàng mới.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ chi tiết giao hàng mới.',
            'action_after.required' => 'Vui lòng chọn tiếp tục giao hàng hay hủy đơn.',
        ]);

        $newFullAddress = trim($validated['shipping_address']);
        $parts = array_filter([$validated['ward'] ?? '', $validated['district'] ?? '', $validated['city'] ?? '']);
        if (!empty($parts)) {
            $newFullAddress .= ', ' . implode(', ', $parts);
        }

        if ($validated['action_after'] === 'keep') {
            // Khách đổi địa chỉ và VẪN MUỐN ĐƠN HÀNG GIAO TIẾP
            $oldAddress = $order->shipping_address . ($order->city ? ', ' . $order->city : '');
            $logNote = "[Khách đổi địa chỉ giao lúc " . now()->format('d/m/Y H:i') . " từ: '{$oldAddress}' sang: '{$newFullAddress}' (Người nhận: {$validated['customer_name']} - {$validated['customer_phone']})]";

            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'] ?? $order->city,
                'district' => $validated['district'] ?? $order->district,
                'ward' => $validated['ward'] ?? $order->ward,
                'shipping_address_snapshot' => $newFullAddress,
                'admin_notes' => ($order->admin_notes ? $order->admin_notes . " | " : "") . $logNote,
            ]);

            return back()->with('success', "Đã cập nhật địa chỉ giao hàng mới cho đơn hàng #{$order->order_code} thành công! Đơn hàng sẽ tiếp tục được giao đến: {$newFullAddress}.");
        } else {
            // Khách đổi địa chỉ xong nhưng chọn HỦY DO KHÔNG MUỐN ĐẶT NỮA
            $cancelReason = "Khách thay đổi địa chỉ nhưng quyết định hủy do không có nhu cầu đặt nữa" . ($request->filled('cancel_notes') ? ' - ' . trim($request->input('cancel_notes')) : '');
            $isPrePaid = in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID']);

            DB::transaction(function () use ($order, $cancelReason, $validated, $newFullAddress, $isPrePaid, $user) {
                // 1. Cập nhật địa chỉ mới vào đơn hàng để lưu vết
                $order->update([
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'city' => $validated['city'] ?? $order->city,
                    'district' => $validated['district'] ?? $order->district,
                    'ward' => $validated['ward'] ?? $order->ward,
                    'shipping_address_snapshot' => $newFullAddress,
                ]);

                // 2. Hoàn lại tồn kho cho sản phẩm & biến thể
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                        $prod = Product::find($item->product_id);
                        if ($prod && $prod->sold_count >= $item->quantity) {
                            $prod->decrement('sold_count', $item->quantity);
                        }

                        if (!empty($item->color) && !empty($item->size)) {
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('color', $item->color)
                                ->where('size', $item->size)
                                ->increment('stock', $item->quantity);
                        }
                    }
                }

                // 3. Khôi phục voucher
                if ($order->coupon_code) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }

                // 4. Nếu đơn đã thanh toán: Tạo phiếu hoàn tiền chờ Admin duyệt
                if ($isPrePaid) {
                    $returnCode = 'REF-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                    OrderReturn::create([
                        'return_code' => $returnCode,
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'type' => 'return_refund',
                        'reason' => $cancelReason,
                        'refund_amount' => $order->total_amount,
                        'refund_method' => 'bank',
                        'bank_name' => $user->bank_name,
                        'bank_account_number' => $user->bank_account_number,
                        'bank_account_name' => $user->bank_account_name,
                        'status' => 'pending',
                        'admin_notes' => 'Tự động tạo lệnh hoàn tiền khi khách đổi địa chỉ và xác nhận hủy đơn',
                    ]);

                    $order->update([
                        'shipping_status' => 'cancelled',
                        'payment_status' => 'refund_pending',
                        'status_step' => 0,
                        'cancel_reason' => $cancelReason,
                        'cancelled_by' => 'customer_refund',
                        'cancelled_at' => now(),
                    ]);
                } else {
                    $order->update([
                        'shipping_status' => 'cancelled',
                        'payment_status' => 'cancelled',
                        'status_step' => 0,
                        'cancel_reason' => $cancelReason,
                        'cancelled_by' => 'customer',
                        'cancelled_at' => now(),
                    ]);
                }
            });

            return back()->with('success', "Đơn hàng #{$order->order_code} đã được hủy theo yêu cầu của bạn và mã giảm giá (voucher) đã được khôi phục.");
        }
    }

    /**
     * Khách hàng gửi yêu cầu Đổi trả / Hoàn tiền (RMA) cho đơn hàng đã giao
     */
    public function storeReturn(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $order = Order::with('items')->where('user_id', $user->id)->findOrFail($id);

        if ($order->shipping_status === 'shipping' || ($order->status_step ?? 1) == 4 || !empty($order->shipper_id)) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đang được bưu tá phát tận nơi. Vui lòng nhận kiện hàng rồi gửi yêu cầu Hủy Hàng Hoàn Tiền hoặc Đổi Trả.');
        }

        if (!$order->canBeReturnedByCustomer()) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' không đủ điều kiện đổi trả hoặc đã có yêu cầu đang được xử lý!');
        }

        $validated = $request->validate([
            'type' => 'required|string|in:return_refund,exchange,refund_only',
            'reason' => 'required|string|max:255',
            'customer_notes' => 'nullable|string|max:1000',
            'order_item_id' => 'nullable|exists:order_items,id',
            'exchange_size' => 'nullable|string|max:20',
            'exchange_color' => 'nullable|string|max:50',
            'refund_method' => 'nullable|string|in:bank,voucher',
            'bank_name' => 'required_if:type,return_refund|nullable|string|max:100',
            'bank_account_number' => 'required_if:type,return_refund|nullable|string|max:50',
            'bank_account_name' => 'required_if:type,return_refund|nullable|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
            'image_proofs' => 'nullable|array|max:5',
            'image_proofs.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'proof_images' => 'nullable|array|max:5',
            'proof_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'video_unbox' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
            'video_proof' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ], [
            'type.required' => 'Vui lòng chọn hình thức yêu cầu (Trả hàng hoàn tiền / Đổi size / Hoàn tiền).',
            'reason.required' => 'Vui lòng chọn lý do đổi trả hàng.',
            'bank_name.required_if' => 'Vui lòng chọn ngân hàng nhận tiền hoàn.',
            'bank_account_number.required_if' => 'Vui lòng nhập số tài khoản ngân hàng để nhận tiền hoàn.',
            'bank_account_name.required_if' => 'Vui lòng nhập tên chủ tài khoản ngân hàng.',
            'image_proofs.*.image' => 'Ảnh bằng chứng phải đúng định dạng hình ảnh (JPEG, PNG, WEBP).',
            'image_proofs.*.max' => 'Dung lượng mỗi ảnh không quá 8MB.',
            'proof_images.*.image' => 'Ảnh bằng chứng phải đúng định dạng hình ảnh (JPEG, PNG, WEBP).',
            'proof_images.*.max' => 'Dung lượng mỗi ảnh không quá 8MB.',
            'video_unbox.mimes' => 'Video clip unbox phải có định dạng MP4, MOV, AVI hoặc WEBM.',
            'video_unbox.max' => 'Dung lượng video unbox không quá 50MB.',
        ]);

        // Upload ảnh minh chứng (chấp nhận cả image_proofs và proof_images)
        $imageUrls = [];
        $uploadedImages = $request->file('image_proofs') ?: $request->file('proof_images');
        if ($uploadedImages && is_array($uploadedImages)) {
            foreach ($uploadedImages as $image) {
                if ($image && $image->isValid()) {
                    $path = $image->store('returns/images', 'public');
                    $imageUrls[] = '/storage/' . $path;
                }
            }
        }

        // Upload video unbox mở hộp nếu có
        $videoUrl = null;
        $videoFile = $request->file('video_unbox') ?: $request->file('video_proof');
        if ($videoFile && $videoFile->isValid()) {
            $videoPath = $videoFile->store('returns/videos', 'public');
            $videoUrl = '/storage/' . $videoPath;
        }

        // Tính số tiền hoàn dự kiến
        $isDepositOnly = ($order->is_deposit_required && $order->payment_status === 'deposit_paid');
        $refundAmount = $isDepositOnly ? (float)$order->deposit_amount : (float)$order->total_amount;
        if (!empty($validated['order_item_id'])) {
            $selectedItem = $order->items->firstWhere('id', $validated['order_item_id']);
            if ($selectedItem) {
                $rawSubtotal = (float)($selectedItem->subtotal ?: ($selectedItem->price * $selectedItem->quantity));
                $refundAmount = $isDepositOnly ? round($rawSubtotal * 0.5) : $rawSubtotal;
                if ($isDepositOnly && $refundAmount > (float)$order->deposit_amount) {
                    $refundAmount = (float)$order->deposit_amount;
                }
            }
        }
        if ($validated['type'] === 'exchange') {
            $refundAmount = 0;
        }

        // Cập nhật thông tin tài khoản ngân hàng của user nếu nhập mới
        $bankName = $validated['bank_name'] ?? $user->bank_name;
        $bankAccNum = !empty($validated['bank_account_number']) ? trim($validated['bank_account_number']) : $user->bank_account_number;
        $bankAccName = !empty($validated['bank_account_name']) ? mb_strtoupper(trim($validated['bank_account_name']), 'UTF-8') : ($user->bank_account_name ?? $user->name);
        $bankBranch = !empty($validated['bank_branch']) ? trim($validated['bank_branch']) : $user->bank_branch;

        if (!empty($validated['bank_name']) && !empty($validated['bank_account_number'])) {
            $user->update([
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccNum,
                'bank_account_name' => $bankAccName,
                'bank_branch' => $bankBranch,
            ]);
        }

        $returnCode = 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        DB::transaction(function () use ($order, $user, $validated, $imageUrls, $videoUrl, $refundAmount, $bankName, $bankAccNum, $bankAccName, $bankBranch, $returnCode) {
            OrderReturn::create([
                'return_code' => $returnCode,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'order_item_id' => $validated['order_item_id'] ?? null,
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'customer_notes' => $validated['customer_notes'] ?? null,
                'image_proofs' => $imageUrls,
                'video_proof' => $videoUrl,
                'exchange_size' => $validated['exchange_size'] ?? null,
                'exchange_color' => $validated['exchange_color'] ?? null,
                'refund_amount' => $refundAmount,
                'refund_method' => $validated['refund_method'] ?? 'bank',
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccNum,
                'bank_account_name' => $bankAccName,
                'bank_branch' => $bankBranch,
                'status' => 'pending',
            ]);

            $isPrePaid = in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID']);

            // Nếu đơn chưa giao hoàn tất (đang ở pending, confirmed, processing, shipping, delivered) và khách chọn hoàn tiền:
            if ($validated['type'] !== 'exchange' && $order->shipping_status !== 'completed') {
                $order->update([
                    'shipping_status' => 'cancelled',
                    'payment_status' => $isPrePaid ? 'refund_pending' : 'cancelled',
                    'status_step' => 0,
                    'cancel_reason' => "Khách hàng yêu cầu hủy đơn & hoàn tiền [{$returnCode}]: " . $validated['reason'],
                    'cancelled_by' => 'customer_refund',
                    'cancelled_at' => now(),
                ]);

                // Hoàn lại kho sản phẩm và biến thể
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                        $prod = Product::find($item->product_id);
                        if ($prod && $prod->sold_count >= $item->quantity) {
                            $prod->decrement('sold_count', $item->quantity);
                        }

                        if (!empty($item->color) && !empty($item->size)) {
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('color', $item->color)
                                ->where('size', $item->size)
                                ->increment('stock', $item->quantity);
                        }
                    }
                }

                // Khôi phục mã giảm giá
                if ($order->coupon_code) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }
            } else {
                // Đơn đã giao hoàn tất: cập nhật trạng thái payment_status = refund_pending nếu đã trả tiền
                if ($isPrePaid && $validated['type'] === 'return_refund') {
                    $order->update(['payment_status' => 'refund_pending']);
                }
            }
        });

        return back()->with('success', "Yêu cầu đổi trả / hoàn tiền (#{$returnCode}) cho đơn hàng #{$order->order_code} đã được gửi thành công! Bộ phận CSKH BeeStyle sẽ tiếp nhận và chuyển khoản hoàn tiền cho bạn trong vòng 24h làm việc.");
    }
}