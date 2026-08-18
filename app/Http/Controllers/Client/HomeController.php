<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $products = Product::with('category')->active()->latest()->take(8)->get();
        $featuredProducts = Product::with('category')->featured()->latest()->take(8)->get();
        $bestSellers = Product::with('category')->bestSeller()->orderByDesc('sold_count')->take(8)->get();
        $newArrivals = Product::with('category')->newArrivals()->latest()->take(8)->get();
        $coupons = Coupon::where('is_active', true)->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->take(4)->get();

        return view('client.home', compact('categories', 'products', 'featuredProducts', 'bestSellers', 'newArrivals', 'coupons'));
    }
}
