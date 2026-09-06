<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các danh mục sản phẩm thời trang
     */
    public function index(Request $request)
    {
        $categories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->with(['activeProducts' => function ($q) {
                $q->take(4);
            }])
            ->get();

        $totalProducts = Product::where('status', 'active')->count();
        $featuredProducts = Product::with(['category', 'brand'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->take(8)
            ->get();

        return view('client.categories.index', compact('categories', 'totalProducts', 'featuredProducts'));
    }

    /**
     * Hiển thị trang danh mục sản phẩm chi tiết theo slug kèm bộ lọc chuyên sâu
     */
    public function show($request, $slug = null)
    {
        if (is_string($request) && $slug === null) {
            $slug = $request;
            $request = request();
        } elseif (!$request instanceof Request) {
            $request = request();
        }
        $category = Category::where('slug', $slug)->where('is_active', true)->first();

        // Fallback thông minh nếu khách hàng vào link cũ hoặc slug biến thể
        if (!$category) {
            $cleanSlug = preg_replace('/-(dep|nam|moi|hot)$/', '', $slug);
            $fallbackCategory = Category::where('slug', $cleanSlug)
                ->orWhere('slug', 'LIKE', "%{$cleanSlug}%")
                ->where('is_active', true)
                ->first();

            if ($fallbackCategory) {
                return redirect()->route('client.categories.show', $fallbackCategory->slug, 301);
            }

            return redirect()->route('client.products.index')->with('info', 'Danh mục sản phẩm không tồn tại hoặc đã được cập nhật.');
        }

        $allCategories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $query = Product::with(['category', 'brand', 'variants'])->where('status', 'active');

        // Lấy sản phẩm thuộc danh mục hiện tại và các danh mục con (nếu có)
        if ($category->children()->exists()) {
            $catIds = $category->children->pluck('id')->push($category->id);
            $query->whereIn('category_id', $catIds);
            $filterCatIds = $catIds;
        } else {
            $query->where('category_id', $category->id);
            $filterCatIds = [$category->id];
        }

        // Lấy danh sách thương hiệu có sản phẩm trong danh mục này
        $brands = Brand::active()
            ->whereHas('products', function ($q) use ($filterCatIds) {
                $q->where('status', 'active')->whereIn('category_id', $filterCatIds);
            })
            ->withCount(['products' => function ($q) use ($filterCatIds) {
                $q->where('status', 'active')->whereIn('category_id', $filterCatIds);
            }])
            ->get();

        // 1. Lọc theo thương hiệu
        $brandSlug = $request->query('brand');
        if ($brandSlug) {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // 2. Tìm kiếm từ khoá trong danh mục
        $search = $request->query('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // 3. Lọc khoảng giá
        $priceRange = $request->query('price_range');
        if ($priceRange === 'under_500') {
            $query->where('price', '<', 500000);
        } elseif ($priceRange === '500_1000') {
            $query->whereBetween('price', [500000, 1000000]);
        } elseif ($priceRange === 'over_1000') {
            $query->where('price', '>', 1000000);
        }

        // 4. Lọc kích cỡ (Size)
        $selectedSize = $request->query('size');
        if ($selectedSize) {
            $query->where(function ($q) use ($selectedSize) {
                $q->whereJsonContains('sizes', $selectedSize)
                  ->orWhereHas('variants', function ($v) use ($selectedSize) {
                      $v->where('size', $selectedSize)->where('status', 'active');
                  });
            });
        }

        // 5. Lọc màu sắc
        $selectedColor = $request->query('color');
        if ($selectedColor) {
            $query->where(function ($q) use ($selectedColor) {
                $q->whereJsonContains('colors', $selectedColor)
                  ->orWhereHas('variants', function ($v) use ($selectedColor) {
                      $v->where('color', $selectedColor)->where('status', 'active');
                  });
            });
        }

        // 6. Sắp xếp
        $sort = $request->query('sort', 'popular');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'views_desc') {
            $query->orderByDesc('views');
        } elseif ($sort === 'rating') {
            $query->orderByDesc('rating')->orderByDesc('sold_count');
        } else {
            $query->orderByDesc('sold_count')->orderByDesc('rating');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('client.categories.show', compact(
            'category',
            'allCategories',
            'products',
            'brands',
            'brandSlug',
            'search',
            'sort',
            'priceRange',
            'selectedSize',
            'selectedColor'
        ));
    }
}
