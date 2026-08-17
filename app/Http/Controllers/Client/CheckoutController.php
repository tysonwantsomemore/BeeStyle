<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Áo Polo Nam BeeStyle Premium Cotton Dệt Tổ Ong',
                'image' => '/assets/img/products/1.png',
                'color' => 'Xanh Navy',
                'size' => 'L',
                'price' => 389000,
                'quantity' => 2
            ],
            [
                'id' => 2,
                'name' => 'Áo Blazer Nam Form Rộng Phong Cách Hàn Quốc Minimalist',
                'image' => '/assets/img/products/2.png',
                'color' => 'Đen',
                'size' => 'L',
                'price' => 890000,
                'quantity' => 1
            ]
        ];

        $subtotal = 1668000;
        $discount = 50000;
        $shipping = 0;
        $total = $subtotal - $discount + $shipping;

        return view('client.checkout', compact('cartItems', 'subtotal', 'discount', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        return redirect()->route('client.order-tracking', ['code' => 'BEE-2026-0816-01'])
            ->with('success', 'Chúc mừng bạn đã đặt hàng thành công tại BeeStyle! Mã đơn hàng của bạn là BEE-2026-0816-01.');
    }
}
