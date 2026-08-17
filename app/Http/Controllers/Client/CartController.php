<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Sample cart items
        $cartItems = [
            [
                'id' => 1,
                'product_id' => 1,
                'name' => 'Áo Polo Nam BeeStyle Premium Cotton Dệt Tổ Ong',
                'sku' => 'BS-PL-001',
                'image' => '/assets/img/products/1.png',
                'color' => 'Xanh Navy',
                'size' => 'L',
                'price' => 389000,
                'original_price' => 499000,
                'quantity' => 2
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'name' => 'Áo Blazer Nam Form Rộng Phong Cách Hàn Quốc Minimalist',
                'sku' => 'BS-BLZ-002',
                'image' => '/assets/img/products/2.png',
                'color' => 'Đen',
                'size' => 'L',
                'price' => 890000,
                'original_price' => 1150000,
                'quantity' => 1
            ]
        ];

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 50000;
        $shipping = 0; // Freeship
        $total = $subtotal - $discount + $shipping;

        $coupons = EcommerceDataService::getCoupons();

        return view('client.cart', compact('cartItems', 'subtotal', 'discount', 'shipping', 'total', 'coupons'));
    }
}
