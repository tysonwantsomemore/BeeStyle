<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Product::with(['category', 'brand', 'variants'])->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
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
        $brands = Brand::where('is_active', true)->get();

        // Thống kê nhanh chỉ số kho hàng
        $totalProductsCount = Product::count();
        $activeProductsCount = Product::where('status', 'active')->where('stock', '>', 0)->count();
        $inactiveProductsCount = Product::where('status', 'inactive')->count();
        $lowStockProductsCount = Product::where('stock', '<=', 5)->count();

        // Dữ liệu chi tiết cho 4 nhóm KPI khi bấm vào từng thẻ
        $allProducts = Product::with(['category', 'brand'])->get();

        $formatProduct = function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'category' => $p->category->name ?? 'Thời trang nam',
                'brand' => $p->brand->name ?? null,
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
            'brands',
            'categoryId', 
            'brandId',
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
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string',
            'variant_stock' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'nullable|string|in:active,inactive',
            'save_action' => 'nullable|string|in:save_index,save_new',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục thời trang.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'sku.unique' => 'Mã SKU này đã tồn tại trên hệ thống.',
            'image.image' => 'File ảnh đại diện không đúng định dạng hình ảnh.',
        ]);

        // Xử lý ảnh đại diện chính
        $imagePath = '/assets/img/products/polo_01.jpg';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $imagePath = trim($request->input('image_url'), " \t\n\r\0\x0B'\"");
        }

        $sku = $request->filled('sku') 
            ? strtoupper(trim($request->input('sku'))) 
            : ('BS-' . strtoupper(Str::random(6)));

        $price = (float)$validated['price'];
        $originalPrice = (isset($validated['original_price']) && (float)$validated['original_price'] > 0) ? (float)$validated['original_price'] : $price;
        $discountPercent = 0;
        if ($originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        $colors = $request->input('colors', ['Đen', 'Trắng']);
        if (empty($colors)) {
            $colors = ['Đen', 'Trắng'];
        }
        $sizes = $request->input('sizes', ['S', 'M', 'L', 'XL']);
        if (empty($sizes)) {
            $sizes = ['S', 'M', 'L', 'XL'];
        }

        $stock = (int)$validated['stock'];
        $status = $validated['status'] ?? 'active';

        // Lọc thông số kỹ thuật (specifications)
        $rawSpecs = $request->input('specifications', []);
        $specifications = [];
        if (is_array($rawSpecs)) {
            foreach ($rawSpecs as $k => $v) {
                if (!empty(trim((string)$v))) {
                    $specifications[trim($k)] = trim((string)$v);
                }
            }
        }
        if (empty($specifications)) {
            $specifications = [
                'Phom dáng' => 'Regular fit / Slimfit tôn dáng',
                'Chất liệu' => 'Cotton Compact cao cấp / Thoáng khí',
                'Xuất xứ' => 'Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)',
                'Bảo hành' => 'Đổi size miễn phí trong 30 ngày',
            ];
        }

        $product = Product::create([
            'name' => $validated['name'],
            'sku' => $sku,
            'slug' => Str::slug($validated['name']) . '-' . strtolower($sku),
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'product_type' => 'variant',
            'price' => $price,
            'original_price' => $originalPrice,
            'discount_percent' => $discountPercent,
            'stock' => $stock,
            'sold_count' => 0,
            'views' => 0,
            'rating' => 5.0,
            'reviews_count' => 0,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'specifications' => $specifications,
            'colors' => array_values($colors),
            'sizes' => array_values($sizes),
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_new' => $request->boolean('is_new', true),
            'status' => $status,
            'is_active' => ($status === 'active'),
            'image' => $imagePath,
        ]);

        // Lưu ảnh chính vào bảng product_images
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
            'sort_order' => 1,
        ]);

        // Lưu gallery ảnh phụ nếu có
        if ($request->hasFile('gallery_images')) {
            $sortOrder = 2;
            foreach ($request->file('gallery_images') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gPath = $gFile->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => '/storage/' . $gPath,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        // Bảng màu hex mã hoá chân thực cho các swatches hiển thị trên website
        $colorMap = [
            'Đen' => '#111827',
            'Trắng' => '#FFFFFF',
            'Xanh Navy' => '#1E3A8A',
            'Xám Tro' => '#6B7280',
            'Xám Ghi' => '#9CA3AF',
            'Beige' => '#E5D9C5',
            'Be sữa' => '#F5EBE0',
            'Nâu Cafe' => '#78350F',
            'Nâu' => '#593B2B',
            'Xanh Rêu' => '#365314',
            'Xanh Mint' => '#6EE7B7',
            'Đỏ Đô' => '#881337',
            'Rượu Vang' => '#4A0E17',
            'Vàng Cát' => '#FDE047',
            'Hồng Pastel' => '#F472B6',
            'Cam Đất' => '#C2410C',
        ];

        // Tự động tạo các biến thể tương ứng cho từng cặp (Màu, Size)
        $totalVariants = max(1, count($colors) * count($sizes));
        $baseStock = (int)floor($stock / $totalVariants);
        $remainder = $stock % $totalVariants;
        $variantStocksInput = $request->input('variant_stock', []);

        foreach ($colors as $color) {
            $colorTrim = trim($color);
            $colorCode = $colorMap[$colorTrim] ?? '#1F2937';
            foreach ($sizes as $size) {
                $sizeTrim = trim($size);
                $varSku = $product->sku . '-' . strtoupper(Str::slug($colorTrim)) . '-' . strtoupper(Str::slug($sizeTrim));
                
                $vStock = $baseStock;
                if (isset($variantStocksInput[$varSku]) && is_numeric($variantStocksInput[$varSku])) {
                    $vStock = max(0, (int)$variantStocksInput[$varSku]);
                } elseif ($remainder > 0) {
                    $vStock += 1;
                    $remainder--;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $varSku,
                    'color' => $colorTrim,
                    'color_code' => $colorCode,
                    'size' => $sizeTrim,
                    'price' => $product->price,
                    'original_price' => $product->original_price,
                    'stock' => $vStock,
                    'image' => $imagePath,
                    'status' => 'active',
                ]);
            }
        }

        // Đồng bộ chuẩn xác tổng tồn kho sản phẩm từ tổng tồn kho các biến thể
        $product->syncStockFromVariants();

        $saveAction = $request->input('save_action', 'save_index');
        $msg = 'Thêm mới sản phẩm "' . $product->name . '" (SKU: ' . $product->sku . ') với ' . $product->variants()->count() . ' biến thể thành công!';

        if ($saveAction === 'save_new') {
            return redirect()->route('admin.products.create')->with('success', $msg . ' Bạn có thể tiếp tục thêm sản phẩm tiếp theo.');
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants', 'images', 'reviews.user'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'images'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string',
            'is_featured' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'nullable|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'delete_gallery_ids' => 'nullable|array',
            'delete_gallery_ids.*' => 'exists:product_images,id',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục thời trang.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'sku.unique' => 'Mã SKU này đã tồn tại trên hệ thống.',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif ($request->filled('image_url') && $request->input('image_url') !== $product->image) {
            $imagePath = trim($request->input('image_url'), " \t\n\r\0\x0B'\"");
        }

        $sku = $request->filled('sku') 
            ? strtoupper(trim($request->input('sku'))) 
            : $product->sku;

        $price = (float)$validated['price'];
        $originalPrice = (isset($validated['original_price']) && (float)$validated['original_price'] > 0) ? (float)$validated['original_price'] : $price;
        $discountPercent = 0;
        if ($originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        $status = $validated['status'] ?? $product->status;
        $colors = $request->input('colors', $product->colors ?: ['Đen', 'Trắng']);
        $sizes = $request->input('sizes', $product->sizes ?: ['S', 'M', 'L', 'XL']);

        $product->update([
            'name' => $validated['name'],
            'sku' => $sku,
            'slug' => Str::slug($validated['name']) . '-' . strtolower($sku),
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'price' => $price,
            'original_price' => $originalPrice,
            'discount_percent' => $discountPercent,
            'stock' => (int)$validated['stock'],
            'short_description' => $validated['short_description'] ?? $product->short_description,
            'description' => $validated['description'] ?? $product->description,
            'colors' => $colors,
            'sizes' => $sizes,
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_new' => $request->boolean('is_new'),
            'status' => $status,
            'is_active' => ($status === 'active'),
            'image' => $imagePath,
        ]);

        // Xóa ảnh phụ được chọn
        if ($request->filled('delete_gallery_ids')) {
            ProductImage::whereIn('id', $request->delete_gallery_ids)
                ->where('product_id', $product->id)
                ->delete();
        }

        // Tải thêm ảnh phụ mới
        if ($request->hasFile('gallery_images')) {
            $maxSort = ProductImage::where('product_id', $product->id)->max('sort_order') ?: 1;
            foreach ($request->file('gallery_images') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $maxSort++;
                    $gPath = $gFile->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => '/storage/' . $gPath,
                        'sort_order' => $maxSort,
                    ]);
                }
            }
        }

        // Bổ sung hoặc cập nhật các biến thể theo màu & size
        if (!empty($colors) && !empty($sizes)) {
            $colorMap = [
                'Đen' => '#111827',
                'Trắng' => '#FFFFFF',
                'Xanh Navy' => '#1E3A8A',
                'Xám Tro' => '#6B7280',
                'Xám Ghi' => '#9CA3AF',
                'Beige' => '#E5D9C5',
                'Be sữa' => '#F5EBE0',
                'Nâu Cafe' => '#78350F',
                'Nâu' => '#593B2B',
                'Xanh Rêu' => '#365314',
                'Xanh Mint' => '#6EE7B7',
                'Đỏ Đô' => '#881337',
                'Vàng Cát' => '#FDE047',
            ];

            $variantStocksInput = $request->input('variant_stock', []);
            $totalVariants = max(1, count($colors) * count($sizes));
            $baseStockPerVar = max(0, (int)floor($product->stock / $totalVariants));
            $remainder = $product->stock % $totalVariants;

            $validVariantIds = [];

            foreach ($colors as $color) {
                $colorTrim = trim($color);
                $colorCode = $colorMap[$colorTrim] ?? '#1F2937';

                foreach ($sizes as $size) {
                    $sizeTrim = trim($size);
                    $varSku = $product->sku . '-' . strtoupper(Str::slug($colorTrim)) . '-' . strtoupper(Str::slug($sizeTrim));
                    
                    $vStock = $baseStockPerVar;
                    if (isset($variantStocksInput[$varSku]) && is_numeric($variantStocksInput[$varSku])) {
                        $vStock = max(0, (int)$variantStocksInput[$varSku]);
                    } elseif ($remainder > 0) {
                        $vStock += 1;
                        $remainder--;
                    }

                    $variant = ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'color' => $colorTrim,
                            'size' => $sizeTrim,
                        ],
                        [
                            'sku' => $varSku,
                            'color_code' => $colorCode,
                            'price' => $product->price,
                            'original_price' => $product->original_price,
                            'stock' => $vStock,
                            'image' => $imagePath,
                            'status' => 'active',
                        ]
                    );

                    $validVariantIds[] = $variant->id;
                }
            }

            // Vô hiệu hóa các biến thể cũ không còn nằm trong danh sách màu & size được chọn
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $validVariantIds)
                ->update(['status' => 'inactive']);
        }

        // Đồng bộ tổng tồn kho sản phẩm từ các biến thể
        $product->syncStockFromVariants();

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thông tin sản phẩm và biến thể thành công!');
    }

    public function destroy($id)
    {
        try {
            $product = Product::with(['images', 'variants'])->findOrFail($id);

            // Dọn dẹp tệp ảnh đại diện vật lý nếu được upload lưu trong public storage
            if (!empty($product->image) && str_contains($product->image, 'storage/')) {
                $relPath = preg_replace('/^\/?storage\//', '', $product->image);
                if (Storage::disk('public')->exists($relPath)) {
                    Storage::disk('public')->delete($relPath);
                }
            }

            // Dọn dẹp các tệp ảnh gallery trong public storage
            foreach ($product->images as $img) {
                if (!empty($img->image_path) && str_contains($img->image_path, 'storage/')) {
                    $relGalleryPath = preg_replace('/^\/?storage\//', '', $img->image_path);
                    if (Storage::disk('public')->exists($relGalleryPath)) {
                        Storage::disk('public')->delete($relGalleryPath);
                    }
                }
            }

            $productName = $product->name;
            $productSku = $product->sku;

            // Xóa sản phẩm: Cơ chế cascade foreign key của DB tự động xóa variants, gallery, reviews
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', "Đã xóa vĩnh viễn sản phẩm \"{$productName}\" (#{$productSku}) và các biến thể liên quan!");
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $newStatus = ($product->status === 'active') ? 'inactive' : 'active';
        $product->update([
            'status' => $newStatus,
            'is_active' => ($newStatus === 'active')
        ]);

        return back()->with('success', "Đã thay đổi trạng thái sản phẩm #{$product->sku} sang " . ($newStatus === 'active' ? 'Đang bán' : 'Tạm dừng'));
    }
}