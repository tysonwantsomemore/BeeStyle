<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->query('code', 'BEE-2026-0816-01');
        $orders = EcommerceDataService::getOrders();
        
        $currentOrder = $orders[0];
        foreach ($orders as $o) {
            if ($o['order_code'] === $code) {
                $currentOrder = $o;
                break;
            }
        }

        return view('client.order-tracking', compact('currentOrder', 'code'));
    }
}
