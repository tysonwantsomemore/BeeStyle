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
        $categories = Category::with('parent')
            ->withCount('products')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $parentCategories = Category::whereNull('parent_id')->get();

        return view(
            'admin.categories.index',
            compact('categories', 'parentCategories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục thời trang.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha được chọn không hợp lệ.',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
            'icon' => $validated['icon'] ?? 'fa-solid fa-shirt',
            'description' => $validated['description'] ?? null,
            'is_active' => true,
            'sort_order' => $validated['sort_order']
                ?? (Category::count() + 1),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Đã thêm danh mục thời trang mới thành công!'
            );
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,

            'parent_id' => [
                'nullable',
                'exists:categories,id',

                function ($attribute, $value, $fail) use ($category) {
                    if ($value == $category->id) {
                        $fail(
                            'Danh mục không thể chọn chính nó làm danh mục cha.'
                        );
                    }
                },
            ],

            'icon' => 'nullable|string|max:100',

            'description' => 'nullable|string|max:1000',

            'sort_order' => 'nullable|integer|min:0',

            'is_active' => 'nullable|boolean',

        ], [
            'name.required' => 'Vui lòng nhập tên danh mục thời trang.',

            'name.unique' => 'Tên danh mục này đã tồn tại.',

            'parent_id.exists' => 'Danh mục cha được chọn không hợp lệ.',
        ]);

        $category->update([
            'name' => $validated['name'],

            'slug' => Str::slug($validated['name']),

            'parent_id' => $validated['parent_id'] ?? null,

            'icon' => $validated['icon']
                ?? $category->icon
                ?? 'fa-solid fa-shirt',

            'description' => $validated['description']
                ?? $category->description,

            'sort_order' => isset($validated['sort_order'])
                ? (int) $validated['sort_order']
                : $category->sort_order,

            'is_active' => $request->has('is_active')
                ? (bool) $request->is_active
                : $category->is_active,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Đã cập nhật thông tin danh mục thành công!'
            );
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')
            ->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'error',
                    "Không thể xóa danh mục '{$category->name}' vì đang chứa {$category->products_count} sản phẩm! Vui lòng chuyển các sản phẩm sang danh mục khác trước khi xóa."
                );
        }

        // Chuyển các danh mục con thành danh mục gốc
        // trước khi xóa danh mục cha
        Category::where('parent_id', $category->id)
            ->update([
                'parent_id' => null,
            ]);

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Đã xóa danh mục thời trang thành công!'
            );
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);

        $category->is_active = !$category->is_active;

        $category->save();

        $statusText = $category->is_active
            ? 'Đã bật hoạt động'
            : 'Đã tạm ẩn';

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                "{$statusText} cho danh mục '{$category->name}'."
            );
    }
}