<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách danh mục hoạt động kèm số lượng sản phẩm
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->orderBy('sort_order', 'asc')
            ->get();

        // Lấy thương hiệu đối tác
        $brands = Brand::active()->take(6)->get();

        // Lấy danh sách sản phẩm với các quan hệ đầy đủ
        $products = Product::with(['category', 'brand', 'variants'])->active()->latest()->take(8)->get();
        $featuredProducts = Product::with(['category', 'brand', 'variants'])->featured()->latest()->take(8)->get();
        $bestSellers = Product::with(['category', 'brand', 'variants'])->bestSeller()->orderByDesc('sold_count')->take(8)->get();
        $newArrivals = Product::with(['category', 'brand', 'variants'])->newArrivals()->latest()->take(8)->get();
        
        // Category spotlights
        $poloSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'ao-polo-nam'))->take(4)->get();
        $shirtSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'ao-so-mi-nam'))->take(4)->get();
        $blazerSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'ao-khoac-blazer-nam'))->take(4)->get();
        $thunSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'ao-thun-nam'))->take(4)->get();
        $thuDongSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'ao-thu-dong-nam'))->take(4)->get();
        $summerSpotlight = Product::with(['category', 'brand', 'variants'])->active()->whereHas('category', fn($q) => $q->where('slug', 'bo-suu-tap-mua-he'))->take(4)->get();

        // Mã giảm giá còn hạn
        $coupons = Coupon::where('is_active', true)->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->take(4)->get();

        // Đánh giá được duyệt
        $reviews = Review::where('status', 'approved')->with('product')->latest()->take(6)->get();

        return view('client.home', compact(
            'categories',
            'brands',
            'products',
            'featuredProducts',
            'bestSellers',
            'newArrivals',
            'poloSpotlight',
            'shirtSpotlight',
            'blazerSpotlight',
            'thunSpotlight',
            'thuDongSpotlight',
            'summerSpotlight',
            'coupons',
            'reviews'
        ));
    }
}