<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = EcommerceDataService::getCategories();
        $products = EcommerceDataService::getProducts();
        $coupons = EcommerceDataService::getCoupons();

        $featuredProducts = array_filter($products, fn($p) => $p['is_featured']);
        $bestSellers = array_filter($products, fn($p) => $p['is_best_seller']);
        $newArrivals = array_filter($products, fn($p) => $p['is_new']);

        return view('client.home', compact('categories', 'products', 'featuredProducts', 'bestSellers', 'newArrivals', 'coupons'));
    }
}
