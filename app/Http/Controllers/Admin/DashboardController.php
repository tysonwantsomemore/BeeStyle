<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->take(6)->get();
        $orders = Order::with('items')->latest()->take(6)->get();
        $customers = User::where('role', 'customer')->latest()->take(6)->get();
        $categories = Category::withCount('products')->get();

        $totalRevenue = Order::where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $totalOrdersCount = Order::count();
        $totalCustomersCount = User::where('role', 'customer')->count();
        $totalProductsCount = Product::count();

        $stats = [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => '+14.5%',
            'total_orders' => $totalOrdersCount,
            'orders_growth' => '+8.2%',
            'total_customers' => $totalCustomersCount,
            'customers_growth' => '+18.4%',
            'total_products' => $totalProductsCount,
            'conversion_rate' => '3.85%'
        ];

        return view('admin.dashboard', compact('stats', 'orders', 'products', 'customers', 'categories'));
    }
}
