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
            // Default show the latest order for demonstration if available
            $currentOrder = Order::with(['items.product', 'user'])->latest()->first();
            if ($currentOrder) {
                $code = $currentOrder->order_code;
            }
        }

        return view('client.order-tracking', compact('currentOrder', 'code'));
    }
}
