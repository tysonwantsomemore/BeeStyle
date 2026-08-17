<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = EcommerceDataService::getCategories();
        $allProducts = EcommerceDataService::getProducts();
        
        $categorySlug = $request->query('category');
        $search = $request->query('q');
        $sort = $request->query('sort', 'popular');

        $products = $allProducts;

        if ($categorySlug) {
            $products = array_filter($products, fn($p) => $p['category_slug'] === $categorySlug);
        }

        if ($search) {
            $products = array_filter($products, function($p) use ($search) {
                return stripos($p['name'], $search) !== false || stripos($p['sku'], $search) !== false;
            });
        }

        // Sorting
        if ($sort === 'price_asc') {
            usort($products, fn($a, $b) => $a['price'] <=> $b['price']);
        } elseif ($sort === 'price_desc') {
            usort($products, fn($a, $b) => $b['price'] <=> $a['price']);
        } elseif ($sort === 'newest') {
            usort($products, fn($a, $b) => $b['id'] <=> $a['id']);
        }

        return view('client.products.index', compact('categories', 'products', 'categorySlug', 'search', 'sort'));
    }

    public function show($id)
    {
        $product = EcommerceDataService::getProductById($id);
        $allProducts = EcommerceDataService::getProducts();
        $relatedProducts = array_filter($allProducts, fn($p) => $p['id'] != $id);
        $categories = EcommerceDataService::getCategories();

        return view('client.products.show', compact('product', 'relatedProducts', 'categories'));
    }
}
