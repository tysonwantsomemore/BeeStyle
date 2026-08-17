<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = EcommerceDataService::getProducts();
        $orders = EcommerceDataService::getOrders();
        $customers = EcommerceDataService::getCustomers();
        $categories = EcommerceDataService::getCategories();

        $stats = [
            'total_revenue' => 158900000,
            'revenue_growth' => '+14.5%',
            'total_orders' => 428,
            'orders_growth' => '+8.2%',
            'total_customers' => 1250,
            'customers_growth' => '+18.4%',
            'total_products' => count($products),
            'conversion_rate' => '3.42%'
        ];

        return view('admin.dashboard', compact('stats', 'orders', 'products', 'customers', 'categories'));
    }
}
