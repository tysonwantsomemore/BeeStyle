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
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Product::with(['category', 'variants'])->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status === 'active') {
            $query->where('status', 'active')->where('stock', '>', 0);
        } elseif ($status === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($status === 'out_of_stock') {
            $query->where('stock', '<=', 5);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        // Thống kê nhanh chỉ số kho hàng
        $totalProductsCount = Product::count();
        $activeProductsCount = Product::where('status', 'active')->where('stock', '>', 0)->count();
        $inactiveProductsCount = Product::where('status', 'inactive')->count();
        $lowStockProductsCount = Product::where('stock', '<=', 5)->count();

        // Dữ liệu chi tiết cho 4 nhóm KPI khi bấm vào từng thẻ
        $allProducts = Product::with(['category'])->get();

        $formatProduct = function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'category' => $p->category->name ?? 'Thời trang nam',
                'price' => $p->price,
                'price_formatted' => number_format($p->price, 0, ',', '.') . '₫',
                'original_price' => $p->original_price,
                'original_price_formatted' => $p->original_price ? number_format($p->original_price, 0, ',', '.') . '₫' : null,
                'stock' => $p->stock,
                'sold_count' => $p->sold_count,
                'rating' => $p->rating,
                'reviews_count' => $p->reviews_count,
                'status' => $p->status,
                'is_active' => $p->is_active,
                'image' => asset($p->image),
                'url' => route('client.products.show', $p->id),
                'edit_url' => route('admin.products.edit', $p->id),
            ];
        };

        $allList = $allProducts->map($formatProduct)->values();
        $activeList = $allProducts->where('status', 'active')->where('stock', '>', 0)->map($formatProduct)->values();
        $inactiveList = $allProducts->where('status', 'inactive')->map($formatProduct)->values();
        $lowStockList = $allProducts->where('stock', '<=', 5)->map($formatProduct)->values();

        $kpiDetailData = [
            'all' => [
                'type' => 'all',
                'title' => 'Toàn Bộ Mẫu Trong Kho',
                'subtitle' => 'Danh sách toàn bộ các mẫu sản phẩm thời trang trong kho hàng BeeStyle',
                'badge' => 'TỔNG QUAN',
                'badge_class' => 'bg-warning text-dark',
                'icon' => 'fa-solid fa-shirt',
                'filter_url' => route('admin.products.index'),
                'metrics' => [
                    ['label' => 'Tổng Mẫu Sản Phẩm', 'value' => $allProducts->count() . ' mẫu', 'color' => 'text-primary'],
                    ['label' => 'Tổng Số Cái Tồn Kho', 'value' => number_format($allProducts->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Tổng Giá Trị Tồn Kho', 'value' => number_format($allProducts->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Tổng Đã Xuất Bán', 'value' => number_format($allProducts->sum('sold_count'), 0, ',', '.') . ' cái', 'color' => 'text-success'],
                ],
                'products' => $allList,
            ],
            'active' => [
                'type' => 'active',
                'title' => 'Sản Phẩm Đang Mở Bán Công Khai',
                'subtitle' => 'Các mẫu sản phẩm đang hiển thị và cho phép khách hàng đặt mua trên Website',
                'badge' => 'KINH DOANH',
                'badge_class' => 'bg-success text-white',
                'icon' => 'fa-solid fa-circle-check',
                'filter_url' => route('admin.products.index', ['status' => 'active']),
                'metrics' => [
                    ['label' => 'Số Mẫu Đang Bán', 'value' => $activeList->count() . ' mẫu', 'color' => 'text-success'],
                    ['label' => 'Tổng Cái Đang Mở Bán', 'value' => number_format($allProducts->where('status', 'active')->where('stock', '>', 0)->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Giá Trị Hàng Đang Bán', 'value' => number_format($allProducts->where('status', 'active')->where('stock', '>', 0)->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Giá Bán Trung Bình', 'value' => number_format(round($allProducts->where('status', 'active')->where('stock', '>', 0)->avg('price') ?: 0), 0, ',', '.') . '₫', 'color' => 'text-primary'],
                ],
                'products' => $activeList,
            ],
            'inactive' => [
                'type' => 'inactive',
                'title' => 'Sản Phẩm Đang Ẩn / Tạm Dừng',
                'subtitle' => 'Các mẫu sản phẩm đã tạm dừng kinh doanh và ẩn hoàn toàn khỏi Website',
                'badge' => 'TẠM DỪNG',
                'badge_class' => 'bg-secondary text-white',
                'icon' => 'fa-solid fa-eye-slash',
                'filter_url' => route('admin.products.index', ['status' => 'inactive']),
                'metrics' => [
                    ['label' => 'Số Mẫu Tạm Dừng', 'value' => $inactiveList->count() . ' mẫu', 'color' => 'text-secondary'],
                    ['label' => 'Số Cái Đang Lưu Kho', 'value' => number_format($allProducts->where('status', 'inactive')->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Giá Trị Hàng Tạm Dừng', 'value' => number_format($allProducts->where('status', 'inactive')->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Đã Từng Bán', 'value' => number_format($allProducts->where('status', 'inactive')->sum('sold_count'), 0, ',', '.') . ' cái', 'color' => 'text-muted'],
                ],
                'products' => $inactiveList,
            ],
            'low_stock' => [
                'type' => 'low_stock',
                'title' => 'Sản Phẩm Cảnh Báo Tồn Kho (≤ 5 Cái)',
                'subtitle' => 'Danh sách các mẫu sản phẩm đã hết hàng hoặc số lượng trong kho còn từ 1 đến 5 cái cần nhập thêm gấp',
                'badge' => 'CẢNH BÁO KHO',
                'badge_class' => 'bg-danger text-white',
                'icon' => 'fa-solid fa-triangle-exclamation',
                'filter_url' => route('admin.products.index', ['status' => 'out_of_stock']),
                'metrics' => [
                    ['label' => 'Tổng Mẫu Cảnh Báo', 'value' => $lowStockList->count() . ' mẫu', 'color' => 'text-danger'],
                    ['label' => 'Mẫu Đã Hết Sạch Kho', 'value' => $allProducts->where('stock', '<=', 0)->count() . ' mẫu', 'color' => 'text-danger'],
                    ['label' => 'Mẫu Sắp Hết (1-5 cái)', 'value' => $allProducts->where('stock', '>', 0)->where('stock', '<=', 5)->count() . ' mẫu', 'color' => 'text-warning'],
                    ['label' => 'Tổng Cái Còn Lại', 'value' => number_format($allProducts->where('stock', '<=', 5)->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                ],
                'products' => $lowStockList,
            ],
        ];

        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'categoryId', 
            'status', 
            'search',
            'totalProductsCount',
            'activeProductsCount',
            'inactiveProductsCount',
            'lowStockProductsCount',
            'kpiDetailData'
        ));
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

    /**
     * Chuyển đổi nhanh 1-Click trạng thái Kinh doanh <-> Tạm dừng của sản phẩm
     */
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = ($product->status === 'active') ? 'inactive' : 'active';
        $product->save();

        $statusText = ($product->status === 'active') ? 'ĐANG KINH DOANH (Mở bán công khai)' : 'TẠM DỪNG (Ẩn khỏi Website)';

        return back()->with('success', "Đã chuyển trạng thái sản phẩm '{$product->name}' sang {$statusText}!");
    }
}


