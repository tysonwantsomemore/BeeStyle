<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = EcommerceDataService::getOrders();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $orders = EcommerceDataService::getOrders();
        $order = $orders[0];
        foreach ($orders as $o) {
            if ($o['id'] == $id) {
                $order = $o;
                break;
            }
        }
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công!');
    }
}
