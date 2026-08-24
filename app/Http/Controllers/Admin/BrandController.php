<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'logo_url' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu này đã tồn tại.',
            'website.url' => 'Địa chỉ website không đúng định dạng URL.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $logoPath = '/storage/' . $path;
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->logo_url;
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('brands/banners', 'public');
            $bannerPath = '/storage/' . $path;
        }

        Brand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'logo' => $logoPath,
            'banner' => $bannerPath,
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'is_active' => true,
            'sort_order' => $validated['sort_order'] ?? (Brand::count() + 1),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Đã thêm thương hiệu mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'logo_url' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu này đã tồn tại.',
            'website.url' => 'Địa chỉ website không đúng định dạng URL.',
        ]);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $logoPath = '/storage/' . $path;
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->logo_url;
        }

        $bannerPath = $brand->banner;
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('brands/banners', 'public');
            $bannerPath = '/storage/' . $path;
        }

        $brand->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'logo' => $logoPath,
            'banner' => $bannerPath,
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'sort_order' => isset($validated['sort_order']) ? (int)$validated['sort_order'] : $brand->sort_order,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $brand->is_active,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thông tin thương hiệu thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::withCount('products')->findOrFail($id);

        if ($brand->products_count > 0) {
            return redirect()->route('admin.brands.index')
                ->with('error', "Không thể xóa thương hiệu '{$brand->name}' vì đang chứa {$brand->products_count} sản phẩm! Vui lòng gỡ hoặc chuyển các sản phẩm sang thương hiệu khác trước khi xóa.");
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Đã xóa thương hiệu thành công!');
    }

    public function toggleStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->is_active = !$brand->is_active;
        $brand->save();

        $statusText = $brand->is_active ? 'Đã bật hoạt động' : 'Đã tạm ẩn';
        return redirect()->route('admin.brands.index')->with('success', "{$statusText} cho thương hiệu '{$brand->name}'.");
    }
}
