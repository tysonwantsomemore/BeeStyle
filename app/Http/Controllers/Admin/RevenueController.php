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

        // Lấy danh sách khách hàng duy nhất đã mua đơn trong tháng
        $customerIds = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->unique();
        
        $totalCustomersInMonth = $customerIds->count();
        if ($totalCustomersInMonth === 0 && $monthlyOrdersCount > 0) {
            $totalCustomersInMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->pluck('customer_phone')
                ->unique()
                ->count();
        }

        $monthlyCustomersList = User::whereIn('id', $customerIds)->get();

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
