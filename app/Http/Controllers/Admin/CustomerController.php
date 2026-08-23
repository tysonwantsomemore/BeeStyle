<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = User::where('role', 'customer')
            ->withCount(['orders', 'reviews'])
            ->withSum(['orders as actual_total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')], 'total_amount')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();

        // Thống kê toàn bộ khách hàng và chi tiêu
        $totalAllCustomersSpent = Order::where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $totalCompletedSpent = Order::whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $totalRegisteredCustomers = User::where('role', 'customer')->count();
        $totalPurchasingAccounts = User::where('role', 'customer')->whereHas('orders', fn($q) => $q->where('shipping_status', '!=', 'cancelled'))->count();
        $totalOrdersCount = Order::where('shipping_status', '!=', 'cancelled')->count();
        $averageSpendPerCustomer = $totalPurchasingAccounts > 0 ? round($totalAllCustomersSpent / $totalPurchasingAccounts) : 0;

        return view('admin.customers.index', compact(
            'customers',
            'search',
            'totalAllCustomersSpent',
            'totalCompletedSpent',
            'totalRegisteredCustomers',
            'totalPurchasingAccounts',
            'totalOrdersCount',
            'averageSpendPerCustomer'
        ));
    }

    /**
     * Xem thông tin chi tiết tài khoản khách hàng, lịch sử mua hàng, đánh giá
     * và tổng quan chi tiêu của tất cả các khách hàng đã mua hàng từ trước đến nay
     */
    public function show($id)
    {
        $customer = User::with([
            'orders' => fn($q) => $q->with('items')->latest(),
            'reviews' => fn($q) => $q->with('product')->latest(),
            'addresses'
        ])->where('role', 'customer')->findOrFail($id);

        // Chi tiêu của khách hàng hiện tại
        $customerTotalSpent = $customer->orders->where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $customerCompletedSpent = $customer->orders->whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $customerOrdersCount = $customer->orders->count();
        $customerCompletedOrdersCount = $customer->orders->whereIn('shipping_status', ['completed', 'delivered'])->count();
        $customerAverageOrderValue = $customerOrdersCount > 0 ? round($customerTotalSpent / $customerOrdersCount) : 0;

        // Thống kê toàn bộ các khách hàng và toàn shop từ trước đến nay
        $totalAllCustomersSpent = Order::where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $totalCompletedSpent = Order::whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $totalAllRegisteredCustomers = User::where('role', 'customer')->count();
        $totalShopOrdersCount = Order::where('shipping_status', '!=', 'cancelled')->count();

        // Danh sách tất cả các tài khoản khách hàng đã từng mua hàng từ trước đến nay
        $allPurchasingCustomers = User::where('role', 'customer')
            ->withCount([
                'orders' => fn($q) => $q->where('shipping_status', '!=', 'cancelled'),
                'reviews'
            ])
            ->withSum([
                'orders as total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')
            ], 'total_amount')
            ->having('orders_count', '>', 0)
            ->orderByDesc('total_spent')
            ->get();

        $totalPurchasingAccounts = $allPurchasingCustomers->count();
        $averageSpendPerAccount = $totalPurchasingAccounts > 0 ? round($totalAllCustomersSpent / $totalPurchasingAccounts) : 0;

        // Tỷ lệ đóng góp chi tiêu của khách này trong tổng chi tiêu toàn shop
        $customerContributionPercent = $totalAllCustomersSpent > 0 ? round(($customerTotalSpent / $totalAllCustomersSpent) * 100, 2) : 0;

        // Vị trí xếp hạng chi tiêu của khách hàng này trong shop
        $rankIndex = $allPurchasingCustomers->search(fn($c) => $c->id === $customer->id);
        $customerRankPosition = ($rankIndex !== false) ? ($rankIndex + 1) : '—';

        return view('admin.customers.show', compact(
            'customer',
            'customerTotalSpent',
            'customerCompletedSpent',
            'customerOrdersCount',
            'customerCompletedOrdersCount',
            'customerAverageOrderValue',
            'customerContributionPercent',
            'customerRankPosition',
            'totalAllCustomersSpent',
            'totalCompletedSpent',
            'totalAllRegisteredCustomers',
            'totalPurchasingAccounts',
            'totalShopOrdersCount',
            'averageSpendPerAccount',
            'allPurchasingCustomers'
        ));
    }
}

