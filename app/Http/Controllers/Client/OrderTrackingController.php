<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}

