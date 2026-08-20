<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::parents()
            ->with(['activeChildren' => function($q) {
                $q->withCount(['products' => fn($p) => $p->where('status', 'active')]);
            }])
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->get();

        $brands = Brand::active()
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->get();
            
        // Update count for BST Mới
        foreach ($categories as $c) {
            if ($c->slug === 'bo-suu-tap-ao-moi') {
                $c->products_count = Product::active()->where('is_new', true)->count();
            }
        }

        $categorySlug = $request->query('category');
        $brandSlug = $request->query('brand');
        $search = $request->query('q');
        $sort = $request->query('sort', 'popular');
        $priceRange = $request->query('price_range');
        $selectedSize = $request->query('size');
        $selectedColor = $request->query('color');

        $query = Product::with(['category', 'brand', 'variants'])->active();

        // Bộ lọc Danh mục (Kết hợp: Xử lý BST Mới + Phân cấp danh mục cha/con)
        if ($categorySlug === 'bo-suu-tap-ao-moi') {
            $query->where('is_new', true);
        } elseif ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                if ($cat->children()->exists()) {
                    $catIds = $cat->children->pluck('id')->push($cat->id);
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $cat->id);
                }
            }
        }

        // Bộ lọc Thương hiệu thời trang
        if ($brandSlug) {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Bộ lọc Từ khóa tìm kiếm theo tên, SKU, mô tả ngắn
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Bộ lọc Khoảng giá bán
        if ($priceRange === 'under_500') {
            $query->where('price', '<', 500000);
        } elseif ($priceRange === '500_1000') {
            $query->whereBetween('price', [500000, 1000000]);
        } elseif ($priceRange === 'over_1000') {
            $query->where('price', '>', 1000000);
        }

        // Bộ lọc Kích cỡ (Size) trong mảng size hoặc bảng biến thể
        if ($selectedSize) {
            $query->where(function($q) use ($selectedSize) {
                $q->whereJsonContains('sizes', $selectedSize)
                  ->orWhereHas('variants', function($v) use ($selectedSize) {
                      $v->where('size', $selectedSize)->where('status', 'active');
                  });
            });
        }

        // Bộ lọc Màu sắc trong mảng màu hoặc bảng biến thể
        if ($selectedColor) {
            $query->where(function($q) use ($selectedColor) {
                $q->whereJsonContains('colors', $selectedColor)
                  ->orWhereHas('variants', function($v) use ($selectedColor) {
                      $v->where('color', $selectedColor)->where('status', 'active');
                  });
            });
        }

        // Sắp xếp kết quả tìm kiếm theo tiêu chí chọn
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->latest();
        } else {
            $query->orderByDesc('sold_count')->orderByDesc('rating');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('client.products.index', compact(
            'categories',
            'brands',
            'products',
            'categorySlug',
            'brandSlug',
            'search',
            'sort',
            'priceRange',
            'selectedSize',
            'selectedColor'
        ));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants' => fn($q) => $q->active(), 'images', 'reviews'])->active()->findOrFail($id);

        $relatedProducts = Product::with(['category', 'brand', 'variants'])
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        $categories = Category::parents()->with('activeChildren')->get();

        return view('client.products.show', compact('product', 'relatedProducts', 'categories'));
    }
}