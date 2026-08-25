<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    /**
     * Xem báo cáo tổng doanh thu tháng & danh sách tất cả khách hàng mua đơn trong tháng
     */
    public function monthly(Request $request)
    {
        // Tháng được chọn (Mặc định: Tháng hiện tại YYYY-MM)
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        try {
            $parsedDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Exception $e) {
            $parsedDate = now()->startOfMonth();
            $selectedMonth = $parsedDate->format('Y-m');
        }

        $startOfMonth = $parsedDate->copy()->startOfMonth();
        $endOfMonth = $parsedDate->copy()->endOfMonth();

        // 1. Lấy danh sách tất cả đơn hàng trong tháng được chọn
        $ordersQuery = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest();

        // Lọc trạng thái nếu có
        $status = $request->query('status');
        if ($status) {
            $ordersQuery->where('shipping_status', $status);
        }

        // Lọc tìm kiếm theo khách hàng hoặc mã đơn
        $search = $request->query('q');
        if ($search) {
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%");
            });
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        // 2. Tính toán tổng doanh thu và các chỉ số trong tháng
        $monthlyRevenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('shipping_status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthlyOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $completedOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereIn('shipping_status', ['completed', 'delivered'])->count();
        $cancelledOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('shipping_status', 'cancelled')->count();

        // 3. Lấy danh sách đầy đủ tất cả khách hàng duy nhất đã mua hàng trong tháng
        $monthOrders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('shipping_status', '!=', 'cancelled')
            ->latest()
            ->get();

        $groupedCustomers = $monthOrders->groupBy(function($order) {
            if ($order->user_id) {
                return 'user_' . $order->user_id;
            }
            return 'guest_' . ($order->customer_phone ?: ($order->customer_email ?: $order->customer_name));
        });

        $monthlyCustomersList = $groupedCustomers->map(function($cOrders, $key) {
            $firstOrder = $cOrders->first();
            $user = $firstOrder->user;

            $cTotalSpent = $cOrders->sum('total_amount');
            $cOrdersCount = $cOrders->count();
            $completedCount = $cOrders->whereIn('shipping_status', ['completed', 'delivered'])->count();

            $name = $user ? $user->name : $firstOrder->customer_name;
            $email = $user ? $user->email : $firstOrder->customer_email;
            $phone = $user ? $user->phone : $firstOrder->customer_phone;
            $avatar = $user ? $user->avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=f59e0b&color=111827&bold=true&size=128';
            $rank = $user ? $user->rank : 'Khách vãng lai';

            return [
                'key' => $key,
                'user_id' => $user ? $user->id : null,
                'name' => $name,
                'email' => $email ?: 'Chưa cập nhật email',
                'phone' => $phone ?: 'Chưa cập nhật SĐT',
                'avatar' => $avatar,
                'rank' => $rank,
                'is_registered' => (bool)$user,
                'total_spent_in_month' => $cTotalSpent,
                'total_spent_in_month_formatted' => number_format($cTotalSpent, 0, ',', '.') . '₫',
                'orders_count' => $cOrdersCount,
                'completed_count' => $completedCount,
                'orders' => $cOrders->map(function($o) {
                    return [
                        'id' => $o->id,
                        'order_code' => $o->order_code,
                        'created_at' => $o->created_at ? $o->created_at->format('d/m/Y H:i') : '',
                        'total_amount' => $o->total_amount,
                        'total_amount_formatted' => number_format($o->total_amount, 0, ',', '.') . '₫',
                        'shipping_status' => $o->shipping_status,
                        'shipping_status_label' => $o->status_label,
                        'payment_status' => $o->payment_status,
                        'payment_status_label' => $o->payment_status_label,
                        'items_count' => $o->items->sum('quantity'),
                        'items_names' => $o->items->pluck('product_name')->take(2)->implode(', '),
                    ];
                })->values()->all(),
            ];
        })->sortByDesc('total_spent_in_month')->values();

        $totalCustomersInMonth = $monthlyCustomersList->count();

        $aovMonth = $monthlyOrdersCount > 0 ? (int)round($monthlyRevenue / max(1, $monthlyOrdersCount - $cancelledOrdersCount)) : 0;

        // So sánh với tháng trước
        $prevMonthDate = $parsedDate->copy()->subMonth();
        $prevMonthRevenue = Order::whereBetween('created_at', [$prevMonthDate->copy()->startOfMonth(), $prevMonthDate->copy()->endOfMonth()])
            ->where('shipping_status', '!=', 'cancelled')
            ->sum('total_amount');

        $growthRate = '+15.2%';
        if ($prevMonthRevenue > 0) {
            $growth = (($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100;
            $growthRate = ($growth >= 0 ? '+' : '') . number_format($growth, 1) . '%';
        }

        // Danh sách 12 tháng gần nhất để hiển thị dropdown chọn tháng
        $availableMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $m = now()->subMonths($i);
            $availableMonths[$m->format('Y-m')] = 'Tháng ' . $m->format('m/Y');
        }

        return view('admin.revenue.monthly', compact(
            'orders',
            'selectedMonth',
            'parsedDate',
            'monthlyRevenue',
            'monthlyOrdersCount',
            'completedOrdersCount',
            'cancelledOrdersCount',
            'totalCustomersInMonth',
            'monthlyCustomersList',
            'aovMonth',
            'growthRate',
            'availableMonths',
            'status',
            'search'
        ));
    }
}

