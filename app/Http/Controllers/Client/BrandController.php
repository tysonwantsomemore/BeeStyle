<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các thương hiệu đang hoạt động
     */
    public function index()
    {
        $brands = Brand::withCount(['products' => function($q) {
            $q->where('status', 'active');
        }])->where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('client.brands', compact('brands'));
    }

    /**
     * Hiển thị trang chi tiết thương hiệu cùng danh mục và sản phẩm thuộc thương hiệu
     */
    public function show(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $query = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->where('brand_id', $brand->id);

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                if ($category->children()->exists()) {
                    $catIds = $category->children->pluck('id')->push($category->id);
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('sold_count', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::parents()->withCount(['products' => function($q) use ($brand) {
            $q->where('brand_id', $brand->id)->where('status', 'active');
        }])->get();

        return view('client.brand-detail', compact('brand', 'products', 'categories'));
    }
}
