<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('q');

        $query = Product::with('category')->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories', 'categoryId', 'search'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'colors' => 'nullable|array',
            'sizes' => 'nullable|array',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
        ]);

        $imagePath = '/assets/img/products/1.png'; // Ảnh mặc định dự phòng

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $product = Product::create([
            'name' => $validated['name'],
            'sku' => strtoupper($validated['sku']),
            'slug' => Str::slug($validated['name']),
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'stock' => $validated['stock'],
            'sold_count' => 0,
            'rating' => 5.0,
            'reviews_count' => 0,
            'colors' => $validated['colors'] ?? ['Đen', 'Trắng'],
            'sizes' => $validated['sizes'] ?? ['S', 'M', 'L', 'XL'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new', true),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'status' => 'active',
        ]);

        // Lưu vào bộ sưu tập ảnh sản phẩm
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
            'sort_order' => 1,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm mới sản phẩm thời trang thành công!');
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'colors' => 'nullable|array',
            'sizes' => 'nullable|array',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'status' => 'nullable|string|in:active,inactive,draft',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $product->update([
            'name' => $validated['name'],
            'sku' => strtoupper($validated['sku']),
            'slug' => Str::slug($validated['name']),
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'stock' => $validated['stock'],
            'colors' => $validated['colors'] ?? $product->colors,
            'sizes' => $validated['sizes'] ?? $product->sizes,
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thông tin sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm khỏi hệ thống!');
    }
}
