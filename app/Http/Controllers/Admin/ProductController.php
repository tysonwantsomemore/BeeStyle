<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = EcommerceDataService::getProducts();
        $categories = EcommerceDataService::getCategories();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = EcommerceDataService::getCategories();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.products.index')->with('success', 'Thêm mới sản phẩm thời trang thành công!');
    }

    public function edit($id)
    {
        $product = EcommerceDataService::getProductById($id);
        $categories = EcommerceDataService::getCategories();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thông tin sản phẩm thành công!');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm khỏi hệ thống!');
    }
}
