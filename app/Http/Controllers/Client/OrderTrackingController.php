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
            $currentOrder = Order::with(['items.product', 'user'])
                ->where('order_code', $code)
                ->orWhere('tracking_code', $code)
                ->first();
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
}

