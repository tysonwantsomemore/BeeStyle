<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $code = trim($request->query('code', ''));
        $currentOrder = null;

        if ($code) {
            $currentOrder = Order::with(['items.product', 'user'])->where('order_code', $code)->first();
        } else {
            // Mặc định hiển thị đơn hàng mới nhất nếu không truyền mã (phục vụ trải nghiệm)
            $currentOrder = Order::with(['items.product', 'user'])->latest()->first();
            if ($currentOrder) {
                $code = $currentOrder->order_code;
            }
        }

        return view('client.order-tracking', compact('currentOrder', 'code'));
    }

    /**
     * Khách hàng xác nhận đã chuyển khoản VietQR thành công
     */
    public function confirmTransfer($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        
        $order->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('success', "Thành công! BeeStyle đã nhận được xác nhận thanh toán VietQR cho đơn hàng #{$code}. Chúng tôi đang chuẩn bị gửi hàng cho bạn!");
    }
}

