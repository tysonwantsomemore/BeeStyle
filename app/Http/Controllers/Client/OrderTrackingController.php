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
     * Khách hàng xác nhận đã chuyển khoản VietQR / Ngân hàng thành công
     */
    public function confirmTransfer($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        
        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 2,
        ]);

        return redirect()->route('client.home')
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $order->total_amount)
            ->with('payment_success_method', $order->payment_method_name)
            ->with('success', "Thành công! BeeStyle đã nhận được thanh toán cho đơn hàng #{$code}. Kho hàng đang tiến hành đóng gói để gửi hàng đến bạn!");
    }
}


