<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Order::with('items')->latest();

        if ($status) {
            $query->where('shipping_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders', 'status', 'search'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'shipping_status' => 'required|string|in:pending,confirmed,processing,shipping,delivered,completed,cancelled',
            'payment_status' => 'nullable|string|in:unpaid,paid,refunded',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $stepMap = [
            'pending' => 1,
            'confirmed' => 2,
            'processing' => 3,
            'shipping' => 4,
            'delivered' => 5,
            'completed' => 6,
            'cancelled' => 0,
        ];

        $order->update([
            'shipping_status' => $validated['shipping_status'],
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
            'status_step' => $stepMap[$validated['shipping_status']] ?? 1,
            'admin_notes' => $validated['admin_notes'] ?? $order->admin_notes,
        ]);

        return back()->with('success', "Trạng thái đơn hàng #{$order->order_code} đã được cập nhật thành công!");
    }
}
