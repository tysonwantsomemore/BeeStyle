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
     * Khách hàng tự hủy đơn hàng khi đơn còn ở trạng thái Chờ xác nhận (pending / confirmed)
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $order = Order::with('items')->where('user_id', $user->id)->findOrFail($id);

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

        DB::transaction(function () use ($order, $cancelReason, $user) {
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

            // 3. Xử lý trạng thái hoàn tiền nếu đơn đã thanh toán trước
            $paymentStatus = $order->payment_status;
            if ($order->payment_status === 'paid') {
                $paymentStatus = 'refunded';
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

        return back()->with('success', "Đơn hàng #{$order->order_code} đã được hủy thành công. Tồn kho sản phẩm và mã giảm giá của bạn đã được khôi phục!");
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
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
            'image_proofs' => 'required|array|min:1|max:5',
            'image_proofs.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'video_unbox' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
            'video_proof' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ], [
            'type.required' => 'Vui lòng chọn hình thức yêu cầu (Trả hàng hoàn tiền / Đổi size / Hoàn tiền).',
            'reason.required' => 'Vui lòng chọn lý do đổi trả hàng.',
            'image_proofs.required' => 'Vui lòng tải lên ít nhất 1 hình ảnh chụp chi tiết sản phẩm / tem mác để làm chứng cứ!',
            'image_proofs.min' => 'Vui lòng tải lên ít nhất 1 hình ảnh sản phẩm cần đổi trả.',
            'image_proofs.*.image' => 'Ảnh bằng chứng phải đúng định dạng hình ảnh (JPEG, PNG, WEBP).',
            'image_proofs.*.max' => 'Dung lượng mỗi ảnh không quá 8MB.',
            'video_unbox.mimes' => 'Video clip unbox phải có định dạng MP4, MOV, AVI hoặc WEBM.',
            'video_unbox.max' => 'Dung lượng video unbox không quá 50MB.',
        ]);

        // Upload ảnh minh chứng
        $imageUrls = [];
        if ($request->hasFile('image_proofs')) {
            foreach ($request->file('image_proofs') as $image) {
                $path = $image->store('returns/images', 'public');
                $imageUrls[] = '/storage/' . $path;
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
        $refundAmount = $order->total_amount;
        if (!empty($validated['order_item_id'])) {
            $selectedItem = $order->items->firstWhere('id', $validated['order_item_id']);
            if ($selectedItem) {
                $refundAmount = $selectedItem->subtotal ?: ($selectedItem->price * $selectedItem->quantity);
            }
        }

        // Cập nhật thông tin tài khoản ngân hàng của user nếu nhập mới
        if (!empty($validated['bank_name']) && !empty($validated['bank_account_number'])) {
            $user->update([
                'bank_name' => $validated['bank_name'],
                'bank_account_number' => trim($validated['bank_account_number']),
                'bank_account_name' => mb_strtoupper(trim($validated['bank_account_name'] ?? $user->name), 'UTF-8'),
                'bank_branch' => $validated['bank_branch'] ? trim($validated['bank_branch']) : null,
            ]);
        }

        $bankName = $validated['bank_name'] ?? $user->bank_name;
        $bankAccNum = $validated['bank_account_number'] ?? $user->bank_account_number;
        $bankAccName = $validated['bank_account_name'] ?? $user->bank_account_name;
        $bankBranch = $validated['bank_branch'] ?? $user->bank_branch;

        $returnCode = 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(5));

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

        return back()->with('success', "Yêu cầu đổi trả / hoàn tiền (#{$returnCode}) cho đơn hàng #{$order->order_code} đã được gửi thành công! Bộ phận CSKH BeeStyle sẽ tiếp nhận và phản hồi bạn trong 24h.");
    }
}
