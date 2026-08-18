<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục thời trang.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? 'fa-solid fa-shirt',
            'description' => $validated['description'] ?? null,
            'is_active' => true,
            'sort_order' => Category::count() + 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục thời trang mới thành công!');
    }
}
