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

        $query = Order::with(['items.product', 'user'])->orderBy('id', 'desc');

        if ($status) {
            $query->where('shipping_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
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

        // Xử lý tự động thu tiền COD khi shipper giao hàng thành công (delivered / completed)
        $paymentStatus = $validated['payment_status'] ?? $order->payment_status;
        if (in_array($validated['shipping_status'], ['delivered', 'completed']) && $order->payment_method === 'cod') {
            $paymentStatus = 'paid';
        }

        // Tích lũy điểm thưởng & tổng chi tiêu khi đơn hàng hoàn tất
        if ($validated['shipping_status'] === 'completed' && $order->shipping_status !== 'completed' && $order->user_id) {
            $user = \App\Models\User::find($order->user_id);
            if ($user) {
                $earnedPoints = (int)floor($order->total_amount / 10000);
                $user->increment('points', $earnedPoints);
                $user->increment('total_spent', $order->total_amount);
            }
        }

        // Xử lý thông tin Đơn vị vận chuyển & Mã vận đơn nếu có
        $adminNotes = $validated['admin_notes'] ?? $order->admin_notes;
        if ($request->filled('shipping_carrier') && $request->filled('tracking_code')) {
            $carrierInfo = "[ĐVVC: " . $request->input('shipping_carrier') . " | Vận đơn: " . $request->input('tracking_code') . "]";
            $adminNotes = $carrierInfo . ($adminNotes ? " - " . $adminNotes : "");
        }

        // Nếu đơn hàng bị hủy, hoàn trả lại số lượng tồn kho cho các sản phẩm & phân loại biến thể
        if ($validated['shipping_status'] === 'cancelled' && $order->shipping_status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    \App\Models\Product::where('id', $item->product_id)->decrement('sold_count', $item->quantity);

                    if (!empty($item->color) && !empty($item->size)) {
                        \App\Models\ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }
            }
        }

        $order->update([
            'shipping_status' => $validated['shipping_status'],
            'payment_status' => $paymentStatus,
            'status_step' => $stepMap[$validated['shipping_status']] ?? 1,
            'admin_notes' => $adminNotes,
        ]);

        return back()->with('success', "Trạng thái đơn hàng #{$order->order_code} đã được cập nhật thành công ({$order->status_label})!");
    }
}


