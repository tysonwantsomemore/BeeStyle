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

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? $category->icon,
            'description' => $validated['description'] ?? $category->description,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật thông tin danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục đang có chứa ' . $category->products_count . ' sản phẩm!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục thành công!');
    }
}
