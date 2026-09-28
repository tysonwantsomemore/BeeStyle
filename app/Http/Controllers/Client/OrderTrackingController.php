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
        // 1. Tự động quét và hủy các đơn hàng trực tuyến quá 15 phút chưa thanh toán
        Order::cancelAllExpiredPendingOnlineOrders();

        // 2. Tự động quét và hoàn tất các đơn hàng đã giao quá 7 ngày
        Order::autoCompleteEligibleDeliveredOrders();

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

        // Nếu đơn hàng đang tra cứu đã hết hạn 15 phút thanh toán online: hủy ngay
        if ($currentOrder && $currentOrder->isOnlinePaymentExpired()) {
            $currentOrder->cancelAsExpiredOnlinePayment();
            $currentOrder->refresh();
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

        $vietnamBanks = [
            'Ngân hàng phổ biến nhất' => [
                ['code' => 'VCB', 'short_name' => 'Vietcombank', 'full_name' => 'Vietcombank - Ngân hàng Ngoại Thương Việt Nam (VCB)'],
                ['code' => 'MB', 'short_name' => 'MB Bank', 'full_name' => 'MB Bank - Ngân hàng TMCP Quân Đội (MB)'],
                ['code' => 'TCB', 'short_name' => 'Techcombank', 'full_name' => 'Techcombank - Ngân hàng Kỹ Thương Việt Nam (TCB)'],
                ['code' => 'CTG', 'short_name' => 'VietinBank', 'full_name' => 'VietinBank - Ngân hàng Công Thương Việt Nam (CTG)'],
                ['code' => 'BIDV', 'short_name' => 'BIDV', 'full_name' => 'BIDV - Ngân hàng Đầu Tư & Phát Triển Việt Nam'],
                ['code' => 'VPB', 'short_name' => 'VPBank', 'full_name' => 'VPBank - Ngân hàng Việt Nam Thịnh Vượng (VPB)'],
                ['code' => 'ACB', 'short_name' => 'ACB', 'full_name' => 'ACB - Ngân hàng TMCP Á Châu (ACB)'],
                ['code' => 'TPB', 'short_name' => 'TPBank', 'full_name' => 'TPBank - Ngân hàng Tiên Phong (TPB)'],
                ['code' => 'STB', 'short_name' => 'Sacombank', 'full_name' => 'Sacombank - Ngân hàng Sài Gòn Thương Tín (STB)'],
                ['code' => 'VBA', 'short_name' => 'Agribank', 'full_name' => 'Agribank - Ngân hàng Nông Nghiệp & PTNT (VBA)'],
            ],
            'Ngân hàng Thương mại Cổ phần' => [
                ['code' => 'VIB', 'short_name' => 'VIB', 'full_name' => 'VIB - Ngân hàng Quốc Tế Việt Nam'],
                ['code' => 'HDB', 'short_name' => 'HDBank', 'full_name' => 'HDBank - Ngân hàng Phát Triển TP.HCM'],
                ['code' => 'SHB', 'short_name' => 'SHB', 'full_name' => 'SHB - Ngân hàng Sài Gòn - Hà Nội'],
                ['code' => 'MSB', 'short_name' => 'MSB', 'full_name' => 'MSB - Ngân hàng Hàng Hải Việt Nam'],
                ['code' => 'OCB', 'short_name' => 'OCB', 'full_name' => 'OCB - Ngân hàng Phương Đông'],
                ['code' => 'SSB', 'short_name' => 'SeABank', 'full_name' => 'SeABank - Ngân hàng Đông Nam Á'],
                ['code' => 'LPB', 'short_name' => 'LPBank', 'full_name' => 'LPBank - Ngân hàng Lộc Phát Việt Nam'],
                ['code' => 'EIB', 'short_name' => 'Eximbank', 'full_name' => 'Eximbank - Ngân hàng Xuất Nhập Khẩu Việt Nam'],
                ['code' => 'PVB', 'short_name' => 'PVcomBank', 'full_name' => 'PVcomBank - Ngân hàng Đại Chúng Việt Nam'],
                ['code' => 'BAB', 'short_name' => 'Bac A Bank', 'full_name' => 'Bac A Bank - Ngân hàng TMCP Bắc Á'],
                ['code' => 'BVB', 'short_name' => 'BaoViet Bank', 'full_name' => 'BaoViet Bank - Ngân hàng Bảo Việt'],
                ['code' => 'ABB', 'short_name' => 'ABBANK', 'full_name' => 'ABBANK - Ngân hàng An Bình'],
                ['code' => 'NAB', 'short_name' => 'Nam A Bank', 'full_name' => 'Nam A Bank - Ngân hàng Nam Á'],
                ['code' => 'KLB', 'short_name' => 'Kienlongbank', 'full_name' => 'Kienlongbank - Ngân hàng Kiên Long'],
                ['code' => 'BVBANK', 'short_name' => 'BVBank', 'full_name' => 'BVBank - Ngân hàng Bản Việt'],
                ['code' => 'PGB', 'short_name' => 'PG Bank', 'full_name' => 'PG Bank - Ngân hàng Xăng Dầu Petrolimex'],
                ['code' => 'SGB', 'short_name' => 'Saigonbank', 'full_name' => 'Saigonbank - Ngân hàng Sài Gòn Công Thương'],
                ['code' => 'VAB', 'short_name' => 'VietABank', 'full_name' => 'VietABank - Ngân hàng Việt Á'],
            ],
            'Ngân hàng số & Ví điện tử' => [
                ['code' => 'CAKE', 'short_name' => 'Cake by VPBank', 'full_name' => 'Cake by VPBank - Ngân hàng số Cake'],
                ['code' => 'TNEX', 'short_name' => 'TNEX', 'full_name' => 'TNEX - Ngân hàng số TNEX (MSB)'],
                ['code' => 'TIMO', 'short_name' => 'Timo', 'full_name' => 'Timo - Ngân hàng số Timo (BVBank)'],
                ['code' => 'VIETTEL', 'short_name' => 'Viettel Money', 'full_name' => 'Viettel Money - Dịch vụ số Viettel'],
                ['code' => 'VNPT', 'short_name' => 'VNPT Money', 'full_name' => 'VNPT Money - Tập đoàn VNPT'],
            ],
            'Ngân hàng Quốc tế & Liên doanh' => [
                ['code' => 'SHBVN', 'short_name' => 'Shinhan Bank', 'full_name' => 'Shinhan Bank - Ngân hàng MTV Shinhan Việt Nam'],
                ['code' => 'WRB', 'short_name' => 'Woori Bank', 'full_name' => 'Woori Bank - Ngân hàng MTV Woori Việt Nam'],
                ['code' => 'HSBC', 'short_name' => 'HSBC', 'full_name' => 'HSBC - Ngân hàng HSBC Việt Nam'],
                ['code' => 'SCVN', 'short_name' => 'Standard Chartered', 'full_name' => 'Standard Chartered - Standard Chartered VN'],
            ],
        ];

        return view('client.order-tracking', compact('currentOrder', 'code', 'searchType', 'userRecentOrders', 'sampleOrders', 'matchedBy', 'vietnamBanks'));
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
            'shipping_status' => 'pending',
            'status_step' => 1,
            'paid_at' => now(),
        ]);

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', "Thành công! BeeStyle đã nhận được xác nhận thanh toán VietQR cho đơn hàng #{$code}. Đơn hàng đã chuyển sang bộ phận duyệt đơn & đóng gói!");
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

        if ($order->shipping_status !== 'delivered' && ($order->status_step ?? 1) < 5) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đang trong quá trình vận chuyển và chưa được giao tới tay bạn. Vui lòng nhận kiện hàng từ bưu tá trước khi ấn xác nhận.');
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
            $updateData['remaining_amount'] = 0;
            $updateData['paid_at'] = $now;
        } else {
            $updateData['remaining_amount'] = 0;
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
            'bank_name' => 'required_if:type,return_refund|nullable|string|max:100',
            'bank_account_number' => 'required_if:type,return_refund|nullable|string|max:50',
            'bank_account_name' => 'required_if:type,return_refund|nullable|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
            'image_proofs' => 'nullable|array|max:5',
            'image_proofs.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'proof_images' => 'nullable|array|max:5',
            'proof_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ], [
            'type.required' => 'Vui lòng chọn hình thức mong muốn (Trả Hàng & Hoàn Tiền hoặc Đổi Size / Đổi Màu).',
            'reason.required' => 'Vui lòng chọn lý do đổi trả / hoàn tiền.',
            'bank_name.required_if' => 'Vui lòng chọn ngân hàng thụ hưởng nhận tiền hoàn.',
            'bank_account_number.required_if' => 'Vui lòng nhập số tài khoản ngân hàng để nhận tiền hoàn.',
            'bank_account_name.required_if' => 'Vui lòng nhập tên chủ tài khoản nhận tiền hoàn.',
            'image_proofs.*.image' => 'File tải lên phải là hình ảnh (JPEG, PNG, WEBP).',
            'image_proofs.*.max' => 'Dung lượng mỗi ảnh không vượt quá 8MB.',
            'proof_images.*.image' => 'File tải lên phải là hình ảnh (JPEG, PNG, WEBP).',
            'proof_images.*.max' => 'Dung lượng mỗi ảnh không vượt quá 8MB.',
        ]);

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

        // Tính số tiền hoàn: nếu là đổi hàng thì refund_amount = 0; nếu hoàn tiền thì lấy theo sản phẩm hoặc toàn bộ đơn
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

        $prefix = match ($validated['type']) {
            'exchange' => 'EXC-',
            'refund_only' => 'CAN-',
            default => 'REF-',
        };
        $returnCode = $prefix . date('Ymd') . '-' . strtoupper(Str::random(5));
        $userId = $order->user_id ?? Auth::id();

        DB::transaction(function () use ($order, $validated, $imageUrls, $refundAmount, $returnCode, $userId) {
            $bankAccName = !empty($validated['bank_account_name']) ? mb_strtoupper(trim($validated['bank_account_name']), 'UTF-8') : null;
            $bankAccNum = !empty($validated['bank_account_number']) ? trim($validated['bank_account_number']) : null;
            $bankName = $validated['bank_name'] ?? null;
            $bankBranch = !empty($validated['bank_branch']) ? trim($validated['bank_branch']) : null;

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
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccNum,
                'bank_account_name' => $bankAccName,
                'bank_branch' => $bankBranch,
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

            // Nếu là hủy đơn/hoàn tiền khi đơn chưa hoàn tất (pending, confirmed, processing, shipping, delivered):
            if ($validated['type'] === 'refund_only' || $order->shipping_status !== 'completed') {
                if ($validated['type'] !== 'exchange') {
                    $updateData['shipping_status'] = 'cancelled';
                    $updateData['status_step'] = 0;
                    $updateData['payment_status'] = $isPrePaid ? 'refund_pending' : 'cancelled';

                    // Hoàn lại tồn kho cho sản phẩm & biến thể
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
                    // Nếu là exchange khi chưa hoàn tất: giữ đơn
                    unset($updateData['cancelled_by']);
                    unset($updateData['cancelled_at']);
                }
            } else {
                // Đơn hàng đã completed:
                if ($validated['type'] === 'return_refund' && $isPrePaid) {
                    $updateData['payment_status'] = 'refund_pending';
                }
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
            if (Auth::check() && !empty($bankName) && !empty($bankAccNum)) {
                Auth::user()->update([
                    'bank_name' => $bankName,
                    'bank_account_number' => $bankAccNum,
                    'bank_account_name' => $bankAccName,
                    'bank_branch' => $bankBranch,
                ]);
            }
        });

        if ($validated['type'] === 'exchange') {
            $msg = "Đã gửi yêu cầu ĐỔI HÀNG (Size/Màu) thành công [Mã: {$returnCode}]! CSKH BeeStyle sẽ liên hệ xác nhận và điều phối shipper giao sản phẩm mới tận nhà cho bạn trong 24-48h.";
        } elseif ($validated['type'] === 'refund_only') {
            $msg = "Đã ghi nhận yêu cầu TỪ CHỐI NHẬN HÀNG [Mã: {$returnCode}]. Kiện hàng sẽ được bưu tá chuyển hoàn về kho BeeStyle.";
        } else {
            $msg = "Yêu cầu hủy đơn & hoàn tiền [Mã: {$returnCode}] cho đơn hàng #{$order->order_code} đã được gửi thành công! CSKH BeeStyle sẽ xác nhận và chuyển khoản hoàn tiền vào tài khoản của bạn trong vòng 24h làm việc.";
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

    /**
     * Khách hàng hủy đơn hàng trực tiếp từ trang tra cứu (hỗ trợ cả khách vãng lai và thành viên)
     */
    public function cancelTrackingOrder(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        // Kiểm tra quyền sở hữu đơn hàng nếu đã đăng nhập và đơn có user_id
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này trên đơn hàng.');
        }

        if (!$order->canBeCancelledByCustomer()) {
            return back()->with('error', 'Đơn hàng #' . $order->order_code . ' đang trong quá trình vận chuyển hoặc đã hoàn tất, không thể tự hủy!');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ], [
            'reason.required' => 'Vui lòng chọn lý do hủy đơn hàng.',
        ]);

        $cancelReason = $validated['reason'] . ($request->filled('notes') ? ' - ' . trim($validated['notes']) : '');

        DB::transaction(function () use ($order, $cancelReason) {
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

            // 3. Xử lý trạng thái thanh toán nếu đơn đã thanh toán trước
            $paymentStatus = $order->payment_status;
            if (in_array($order->payment_status, ['paid', 'deposit_paid'])) {
                $paymentStatus = 'refund_pending';
            } else {
                $paymentStatus = 'cancelled';
            }

            // 4. Cập nhật đơn hàng sang Đã Hủy
            $order->update([
                'shipping_status' => 'cancelled',
                'payment_status' => $paymentStatus,
                'status_step' => 0,
                'cancel_reason' => $cancelReason,
                'cancelled_by' => 'customer',
                'cancelled_at' => now(),
            ]);
        });

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', "Đơn hàng #{$order->order_code} đã được hủy thành công. Tồn kho sản phẩm và mã giảm giá đã được khôi phục!");
    }
}

