<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EcommerceDataService::getCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục thời trang mới thành công!');
    }
}
