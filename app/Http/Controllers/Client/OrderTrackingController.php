<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $code = trim($request->query('code', ''));
        $searchType = $request->query('type', 'auto'); // 'order', 'tracking', or 'auto'
        $cleanCode = ltrim($code, '#');
        $currentOrder = null;

        if ($cleanCode) {
            $currentOrder = Order::with(['items.product', 'user', 'latestReturn'])
                ->where('order_code', $cleanCode)
                ->orWhere('tracking_code', $cleanCode)
                ->orWhere('order_code', $code)
                ->orWhere('tracking_code', $code)
                ->orWhere('order_code', strtoupper($cleanCode))
                ->orWhere('tracking_code', strtoupper($cleanCode))
                ->first();
        } else {
            // Mặc định hiển thị đơn hàng mới nhất của người dùng nếu đã đăng nhập, hoặc đơn mới nhất toàn hệ thống
            if (Auth::check()) {
                $currentOrder = Order::with(['items.product', 'user', 'latestReturn'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->first();
            }
            if (!$currentOrder) {
                $currentOrder = Order::with(['items.product', 'user', 'latestReturn'])->latest()->first();
            }
            if ($currentOrder) {
                $code = $currentOrder->order_code;
            }
        }

        // Lấy danh sách đơn hàng gần đây của khách hàng để gợi ý tra cứu nhanh
        $userRecentOrders = collect();
        if (Auth::check()) {
            $userRecentOrders = Order::where('user_id', Auth::id())->latest()->take(5)->get();
        }

        // Lấy danh sách đơn mẫu đại diện cho các hãng vận chuyển
        $sampleOrders = Order::whereNotNull('tracking_code')->where('tracking_code', '!=', '')->latest()->take(3)->get();

        // Xác định loại mã người dùng vừa tra cứu (Mã đơn hàng hay Mã vận đơn)
        $matchedBy = 'order';
        if ($currentOrder && $cleanCode && strcasecmp((string)$currentOrder->tracking_code, $cleanCode) === 0) {
            $matchedBy = 'tracking';
        }

        return view('client.order-tracking', compact('currentOrder', 'code', 'searchType', 'userRecentOrders', 'sampleOrders', 'matchedBy'));
    }

    /**
     * Cổng Tra Cứu Vận Đơn Bưu Tá Trực Tuyến (GHTK, GHN, Viettel Post, J&T...)
     * Hiển thị 100% dữ liệu thật của đơn hàng: người gửi, người nhận, bưu tá, sản phẩm, lộ trình bưu kiện
     */
    public function carrierTracking($request = null, $code = null)
    {
        if (is_string($request) && $code === null) {
            $code = $request;
            $request = request();
        } elseif (!$request instanceof Request) {
            $request = request();
        }
        $code = $code ? trim($code) : trim($request->query('code', ''));
        $order = null;

        if ($code) {
            $order = Order::with(['items.product', 'user'])
                ->where('tracking_code', $code)
                ->orWhere('order_code', $code)
                ->first();
        }

        if (!$order) {
            // Lấy đơn hàng mới nhất có mã vận đơn để người dùng trải nghiệm ngay
            $order = Order::with(['items.product', 'user'])
                ->whereNotNull('tracking_code')
                ->where('tracking_code', '!=', '')
                ->latest()
                ->first();

            if ($order && !$code) {
                $code = $order->tracking_code;
            }
        }

        return view('client.carrier-tracking', compact('order', 'code'));
    }

    /**
     * Khách hàng xác nhận đã chuyển khoản VietQR / Ngân hàng thành công
     */
    public function confirmTransfer($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        
        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 3,
            'paid_at' => now(),
            'confirmed_at' => $order->confirmed_at ?: now(),
            'processing_at' => now(),
        ]);

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', "Thành công! BeeStyle đã nhận được xác nhận thanh toán VietQR cho đơn hàng #{$code}. Chúng tôi đang chuẩn bị gửi hàng cho bạn!");
    }

    /**
     * Khách hàng xác nhận đã nhận được hàng thành công (Hoàn tất đơn hàng & kích hoạt thông báo đánh giá)
     */
    public function confirmDelivered(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        // Kiểm tra quyền sở hữu đơn hàng nếu đã đăng nhập
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này trên đơn hàng.');
        }

        if ($order->shipping_status === 'completed') {
            return back()->with('info', 'Đơn hàng này đã được xác nhận hoàn tất trước đó.');
        }

        if ($order->shipping_status === 'cancelled') {
            return back()->with('error', 'Không thể xác nhận nhận hàng cho đơn hàng đã bị hủy.');
        }

        $now = now();
        $updateData = [
            'shipping_status' => 'completed',
            'status_step' => 6,
            'completed_at' => $now,
            'review_notified' => false, // Reset cờ để kích hoạt thông báo & modal mời khách hàng tự đánh giá
        ];

        if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
        if (!$order->processing_at) $updateData['processing_at'] = $now;
        if (!$order->shipping_at) $updateData['shipping_at'] = $now;
        if (!$order->delivered_at) $updateData['delivered_at'] = $now;

        if ($order->payment_status !== 'paid') {
            $updateData['payment_status'] = 'paid';
            $updateData['paid_at'] = $now;
        }

        $order->update($updateData);

        // Cộng điểm thưởng tích lũy và tổng chi tiêu cho tài khoản thành viên
        if ($order->user_id) {
            $user = User::find($order->user_id);
            if ($user) {
                $earnedPoints = (int)floor($order->total_amount / 10000);
                $user->increment('points', $earnedPoints);
                $user->increment('total_spent', $order->total_amount);
            }
        }

        $firstItem = $order->items->first();
        $firstProductId = $firstItem ? $firstItem->product_id : null;

        $msg = "Tuyệt vời! Bạn đã xác nhận nhận hàng thành công cho đơn hàng #{$order->order_code}. Hãy chia sẻ đánh giá sản phẩm để BeeStyle ngày càng hoàn thiện nhé!";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'order_code' => $order->order_code,
                'product_id' => $firstProductId,
            ]);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', $msg)
            ->with('open_review_modal_product_id', $firstProductId);
    }

    /**
     * Khách hàng từ chối nhận hàng khi shipper giao (Không nhận hàng / Chuyển hoàn về kho)
     */
    public function rejectDelivery(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        // Kiểm tra quyền sở hữu đơn hàng nếu đã đăng nhập và đơn có user_id
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này trên đơn hàng.');
        }

        if ($order->shipping_status === 'cancelled') {
            return back()->with('info', 'Đơn hàng này đã ở trạng thái hủy/từ chối nhận trước đó.');
        }

        if ($order->shipping_status === 'completed') {
            return back()->with('error', 'Đơn hàng này đã được xác nhận hoàn tất, không thể từ chối nhận. Vui lòng gửi yêu cầu Đổi trả / Hoàn tiền nếu có vấn đề về sản phẩm.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'reason.required' => 'Vui lòng chọn lý do không nhận bưu phẩm.',
        ]);

        $cancelReason = $validated['reason'] . ($request->filled('notes') ? ' - ' . trim($validated['notes']) : '');
        $proofPath = null;

        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('delivery-proofs/rejects', 'public');
        }

        DB::transaction(function () use ($order, $cancelReason, $proofPath) {
            // 1. Hoàn trả tồn kho cho các sản phẩm & biến thể
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

            // 3. Xử lý trạng thái thanh toán (Nếu đã trả hoặc cọc)
            $paymentStatus = $order->payment_status;
            if (in_array($order->payment_status, ['paid', 'deposit_paid'])) {
                $paymentStatus = 'refund_pending';
            } else {
                $paymentStatus = 'cancelled';
            }

            // 4. Cập nhật đơn hàng sang Đã Hủy với cancelled_by = 'customer_rejected'
            $updateData = [
                'shipping_status' => 'cancelled',
                'payment_status' => $paymentStatus,
                'status_step' => 0,
                'cancel_reason' => $cancelReason,
                'cancelled_by' => 'customer_rejected',
                'cancelled_at' => now(),
            ];

            if ($proofPath) {
                $updateData['delivery_proof_image'] = '/storage/' . $proofPath;
                $updateData['delivery_proof_note'] = 'Ảnh khách hàng/bưu tá đính kèm khi từ chối nhận hàng';
                $updateData['delivery_proof_at'] = now();
            }

            $order->update($updateData);
        });

        $msg = "Đã ghi nhận yêu cầu KHÔNG NHẬN HÀNG cho đơn #{$order->order_code}. Bưu tá sẽ lập biên bản và chuyển hoàn kiện hàng về kho BeeStyle. Cảm ơn bạn đã phản hồi!";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'order_code' => $order->order_code,
            ]);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('warning', $msg);
    }

    /**
     * Khách hàng gửi yêu cầu Hủy hàng & Hoàn tiền khi đơn hàng giao đến nơi
     */
    public function requestRefund(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        // Kiểm tra quyền sở hữu đơn hàng nếu đã đăng nhập và đơn có user_id
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này trên đơn hàng.');
        }

        // Kiểm tra xem đã có yêu cầu hoàn tiền đang chờ duyệt chưa
        $existingReturn = OrderReturn::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved', 'received'])
            ->first();
        if ($existingReturn) {
            return back()->with('info', "Đơn hàng #{$code} đã có yêu cầu hoàn tiền #{$existingReturn->return_code} đang được xử lý.");
        }

        $validated = $request->validate([
            'type' => 'required|string|in:return_refund,refund_only,exchange',
            'order_item_id' => 'nullable|integer',
            'exchange_size' => 'nullable|string|max:50',
            'exchange_color' => 'nullable|string|max:50',
            'reason' => 'required|string|max:255',
            'customer_notes' => 'nullable|string|max:1000',
            'refund_method' => 'nullable|string|in:bank,voucher',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
            'image_proofs' => 'nullable|array|max:5',
            'image_proofs.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ], [
            'type.required' => 'Vui lòng chọn hình thức hoàn tiền hoặc đổi hàng.',
            'reason.required' => 'Vui lòng chọn lý do yêu cầu đổi trả.',
            'image_proofs.*.image' => 'File tải lên phải là hình ảnh (JPEG, PNG, WEBP).',
            'image_proofs.*.max' => 'Dung lượng mỗi ảnh không vượt quá 8MB.',
        ]);

        $imageUrls = [];
        if ($request->hasFile('image_proofs')) {
            foreach ($request->file('image_proofs') as $image) {
                $path = $image->store('returns/images', 'public');
                $imageUrls[] = '/storage/' . $path;
            }
        }

        // Tính số tiền hoàn: nếu là đổi hàng thì refund_amount = 0; nếu hoàn tiền thì lấy tổng tiền/tiền cọc
        $refundAmount = $order->total_amount;
        if ($order->is_deposit_required && $order->payment_status === 'deposit_paid') {
            $refundAmount = $order->deposit_amount;
        }
        if ($validated['type'] === 'exchange') {
            $refundAmount = 0;
        }

        $prefix = match ($validated['type']) {
            'exchange' => 'EXC-',
            'refund_only' => 'CAN-',
            default => 'REF-',
        };
        $returnCode = $prefix . date('Ymd') . '-' . strtoupper(Str::random(5));
        $userId = $order->user_id ?? Auth::id();

        DB::transaction(function () use ($order, $validated, $imageUrls, $refundAmount, $returnCode, $userId) {
            // 1. Tạo bản ghi OrderReturn
            OrderReturn::create([
                'return_code' => $returnCode,
                'order_id' => $order->id,
                'user_id' => $userId,
                'order_item_id' => $validated['order_item_id'] ?? null,
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'customer_notes' => $validated['customer_notes'] ?? null,
                'image_proofs' => $imageUrls,
                'exchange_size' => $validated['exchange_size'] ?? null,
                'exchange_color' => $validated['exchange_color'] ?? null,
                'refund_amount' => $refundAmount,
                'refund_method' => $validated['refund_method'] ?? 'bank',
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'bank_account_name' => !empty($validated['bank_account_name']) ? mb_strtoupper(trim($validated['bank_account_name']), 'UTF-8') : null,
                'bank_branch' => $validated['bank_branch'] ?? null,
                'status' => 'pending',
            ]);

            // 2. Cập nhật trạng thái đơn hàng
            $isPrePaid = in_array($order->payment_status, ['paid', 'deposit_paid']);
            $actionLabel = match ($validated['type']) {
                'exchange' => 'Đổi Hàng (Size/Màu)',
                'refund_only' => 'Từ Chối Nhận Hàng',
                default => 'Trả Hàng Hoàn Tiền',
            };
            $cancelReason = "Khách hàng gửi yêu cầu {$actionLabel} [{$returnCode}]: " . $validated['reason'];
            if (!empty($validated['exchange_size'])) {
                $cancelReason .= " (Đổi sang Size: " . $validated['exchange_size'] . ")";
            }

            $updateData = [
                'cancel_reason' => $cancelReason,
                'cancelled_by' => 'customer_refund',
                'cancelled_at' => now(),
            ];

            // Nếu là từ chối nhận lúc shipper giao (hoặc hủy đơn khi chưa hoàn tất):
            if ($validated['type'] === 'refund_only' || in_array($order->shipping_status, ['shipping', 'delivered'])) {
                $updateData['shipping_status'] = 'cancelled';
                $updateData['status_step'] = 0;
                $updateData['payment_status'] = $isPrePaid ? 'refund_pending' : 'cancelled';

                // Hoàn lại tồn kho cho sản phẩm
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

                // Khôi phục coupon
                if ($order->coupon_code) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }
            } else {
                // Đơn hàng đã completed:
                if ($validated['type'] === 'return_refund' && $isPrePaid) {
                    $updateData['payment_status'] = 'refund_pending';
                }
                // Nếu là exchange (đổi hàng): không đổi shipping_status thành cancelled mà giữ completed
                if ($validated['type'] === 'exchange') {
                    unset($updateData['cancelled_by']);
                    unset($updateData['cancelled_at']);
                }
            }

            if (!empty($imageUrls[0])) {
                $updateData['delivery_proof_image'] = $imageUrls[0];
                $updateData['delivery_proof_note'] = "Ảnh khách hàng đính kèm khi yêu cầu {$actionLabel}";
                $updateData['delivery_proof_at'] = now();
            }

            $order->update($updateData);

            // Lưu STK vào tài khoản user nếu có
            if (Auth::check() && !empty($validated['bank_name']) && !empty($validated['bank_account_number'])) {
                Auth::user()->update([
                    'bank_name' => $validated['bank_name'],
                    'bank_account_number' => trim($validated['bank_account_number']),
                    'bank_account_name' => !empty($validated['bank_account_name']) ? mb_strtoupper(trim($validated['bank_account_name']), 'UTF-8') : null,
                    'bank_branch' => !empty($validated['bank_branch']) ? trim($validated['bank_branch']) : null,
                ]);
            }
        });

        if ($validated['type'] === 'exchange') {
            $msg = "Đã gửi yêu cầu ĐỔI HÀNG (Size/Màu) thành công [Mã: {$returnCode}]! CSKH BeeStyle sẽ liên hệ xác nhận và điều phối shipper giao sản phẩm mới tận nhà cho bạn trong 24-48h.";
        } elseif ($validated['type'] === 'refund_only') {
            $msg = "Đã ghi nhận yêu cầu TỪ CHỐI NHẬN HÀNG [Mã: {$returnCode}]. Kiện hàng sẽ được bưu tá chuyển hoàn về kho BeeStyle.";
        } else {
            $msg = "Đã gửi yêu cầu TRẢ HÀNG & HOÀN TIỀN thành công [Mã: {$returnCode}]! CSKH BeeStyle sẽ liên hệ hướng dẫn thu hồi sản phẩm và hoàn tiền vào tài khoản ngân hàng của bạn.";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'order_code' => $order->order_code,
                'return_code' => $returnCode,
            ]);
        }

        if ($request->filled('from_profile') || str_contains(url()->previous(), 'tai-khoan')) {
            return redirect()->route('client.profile', ['tab' => 'returns'])->with('success', $msg);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', $msg);
    }
}

