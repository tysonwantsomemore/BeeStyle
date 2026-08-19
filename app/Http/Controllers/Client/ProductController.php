<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->orderBy('sort_order', 'asc')
            ->get();
        // Update count for BST Mới
        foreach ($categories as $c) {
            if ($c->slug === 'bo-suu-tap-ao-moi') {
                $c->products_count = Product::active()->where('is_new', true)->count();
            }
        }

        $categorySlug = $request->query('category');
        $search = $request->query('q');
        $sort = $request->query('sort', 'popular');
        $priceRange = $request->query('price_range');
        $selectedSize = $request->query('size');

        $query = Product::with('category')->active();

        // Filter Category
        if ($categorySlug === 'bo-suu-tap-ao-moi') {
            $query->where('is_new', true);
        } elseif ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter Keyword
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Filter Price Range
        if ($priceRange === 'under_500') {
            $query->where('price', '<', 500000);
        } elseif ($priceRange === '500_1000') {
            $query->whereBetween('price', [500000, 1000000]);
        } elseif ($priceRange === 'over_1000') {
            $query->where('price', '>', 1000000);
        }

        // Filter Size
        if ($selectedSize) {
            $query->whereJsonContains('sizes', $selectedSize);
        }

        // Sorting
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

        return view('client.products.index', compact('categories', 'products', 'categorySlug', 'search', 'sort', 'priceRange', 'selectedSize'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images', 'reviews'])->active()->findOrFail($id);
        $relatedProducts = Product::with('category')
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('client.products.show', compact('product', 'relatedProducts', 'categories'));
    }
}
