<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
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

        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'brands',
            'categoryId', 
            'brandId',
            'status', 
            'search'
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
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*' => 'nullable|string',
            'colors' => 'required|array|min:1',
            'colors.*' => 'required|string',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'required|string',
            'variant_stock' => 'nullable|array',
            'variant_price' => 'nullable|array',
            'variant_original_price' => 'nullable|array',
            'variant_material' => 'nullable|array',
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
            'sku.required' => 'Vui lòng nhập Mã SKU cho sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục thời trang.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng nhập kho.',
            'sku.unique' => 'Mã SKU này đã tồn tại trên hệ thống.',
            'colors.required' => 'Vui lòng chọn hoặc thêm ít nhất một Màu sắc cho sản phẩm.',
            'colors.min' => 'Vui lòng chọn hoặc thêm ít nhất một Màu sắc cho sản phẩm.',
            'sizes.required' => 'Vui lòng chọn hoặc thêm ít nhất một Kích thước (Size) cho sản phẩm.',
            'sizes.min' => 'Vui lòng chọn hoặc thêm ít nhất một Kích thước (Size) cho sản phẩm.',
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

        $sku = strtoupper(trim($request->input('sku')));

        $price = (float)$validated['price'];
        $originalPrice = isset($validated['original_price']) && is_numeric($validated['original_price']) ? (float)$validated['original_price'] : null;
        $discountPercent = 0;
        if ($originalPrice && $originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        $colors = $request->input('colors', []);
        $sizes = $request->input('sizes', []);

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

        $product = Product::create([
            'name' => $validated['name'],
            'sku' => $sku,
            'slug' => Str::slug($validated['name']) . '-' . strtolower($sku),
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'product_type' => 'variant',
            'price' => $price,
            'original_price' => $originalPrice,
            'sale_starts_at' => $request->filled('sale_starts_at') ? $request->input('sale_starts_at') : null,
            'sale_ends_at' => $request->filled('sale_ends_at') ? $request->input('sale_ends_at') : null,
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
        $variantPricesInput = $request->input('variant_price', []);
        $variantOriginalPricesInput = $request->input('variant_original_price', []);
        $variantMaterialsInput = $request->input('variant_material', []);

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

                $vPrice = (isset($variantPricesInput[$varSku]) && is_numeric($variantPricesInput[$varSku]) && (float)$variantPricesInput[$varSku] > 0)
                    ? (float)$variantPricesInput[$varSku]
                    : $product->price;

                $vOriginalPrice = (isset($variantOriginalPricesInput[$varSku]) && is_numeric($variantOriginalPricesInput[$varSku]) && (float)$variantOriginalPricesInput[$varSku] > 0)
                    ? (float)$variantOriginalPricesInput[$varSku]
                    : ($product->original_price ?: null);

                $vMaterial = isset($variantMaterialsInput[$varSku]) && trim($variantMaterialsInput[$varSku]) !== ''
                    ? trim($variantMaterialsInput[$varSku])
                    : ($specifications['Chất liệu'] ?? null);

                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $varSku,
                    'color' => $colorTrim,
                    'color_code' => $colorCode,
                    'size' => $sizeTrim,
                    'material' => $vMaterial,
                    'price' => $vPrice,
                    'original_price' => $vOriginalPrice,
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
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string',
            'variant_stock' => 'nullable|array',
            'variant_price' => 'nullable|array',
            'variant_material' => 'nullable|array',
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
        $originalPrice = isset($validated['original_price']) && is_numeric($validated['original_price']) ? (float)$validated['original_price'] : null;
        $discountPercent = 0;
        if ($originalPrice && $originalPrice > $price) {
            $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
        }

        $status = $validated['status'] ?? $product->status;
        $colors = $request->input('colors', $product->colors ?: ['Đen', 'Trắng']);
        $sizes = $request->input('sizes', $product->sizes ?: ['S', 'M', 'L', 'XL']);

        $oldPrice = (float) $product->price;
        $oldOriginalPrice = (float) ($product->original_price ?? 0);
        $syncAllVariants = $request->boolean('sync_variant_prices', false);
        $priceChanged = abs($price - $oldPrice) > 0.01;
        $originalPriceChanged = ($originalPrice != $oldOriginalPrice);

        $product->update([
            'name' => $validated['name'],
            'sku' => $sku,
            'slug' => Str::slug($validated['name']) . '-' . strtolower($sku),
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'price' => $price,
            'original_price' => $originalPrice,
            'sale_starts_at' => $request->filled('sale_starts_at') ? $request->input('sale_starts_at') : null,
            'sale_ends_at' => $request->filled('sale_ends_at') ? $request->input('sale_ends_at') : null,
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
        $variantStocksInput = $request->input('variant_stock', []);
        $variantPricesInput = $request->input('variant_price', []);
        $variantMaterialsInput = $request->input('variant_material', []);

        // 1. Cập nhật trực tiếp các biến thể hiện tại theo ID (từ bảng quản lý biến thể trang Edit)
        foreach ($product->variants as $variant) {
            $hasUpdate = false;
            $vData = [];
            if (isset($variantStocksInput[$variant->id]) && is_numeric($variantStocksInput[$variant->id])) {
                $vData['stock'] = max(0, (int)$variantStocksInput[$variant->id]);
                $hasUpdate = true;
            }

            // Xử lý đồng bộ giá bán & giá gốc cho biến thể
            if ($syncAllVariants) {
                // Admin yêu cầu đồng bộ toàn bộ biến thể theo giá chung
                $vData['price'] = $price;
                $vData['original_price'] = $originalPrice;
                $hasUpdate = true;
            } elseif ($priceChanged && isset($variantPricesInput[$variant->id]) && abs((float)$variantPricesInput[$variant->id] - $oldPrice) < 0.01) {
                // Giá sản phẩm cha thay đổi và biến thể này trước đó đang giữ giá cũ của cha -> tự động cập nhật sang giá cha mới
                $vData['price'] = $price;
                if ($originalPriceChanged) {
                    $vData['original_price'] = $originalPrice;
                }
                $hasUpdate = true;
            } elseif (isset($variantPricesInput[$variant->id]) && is_numeric($variantPricesInput[$variant->id]) && (float)$variantPricesInput[$variant->id] > 0) {
                // Admin chủ động nhập giá riêng cho biến thể này
                $vData['price'] = (float)$variantPricesInput[$variant->id];
                if ($originalPriceChanged && empty($variant->original_price)) {
                    $vData['original_price'] = $originalPrice;
                }
                $hasUpdate = true;
            } elseif ($priceChanged) {
                // Giá cha thay đổi mà không có input biến thể riêng lẻ -> đồng bộ theo giá cha
                $vData['price'] = $price;
                if ($originalPriceChanged) {
                    $vData['original_price'] = $originalPrice;
                }
                $hasUpdate = true;
            }

            if (isset($variantMaterialsInput[$variant->id])) {
                $vData['material'] = trim($variantMaterialsInput[$variant->id]) !== '' ? trim($variantMaterialsInput[$variant->id]) : null;
                $hasUpdate = true;
            }
            if ($hasUpdate) {
                $variant->update($vData);
            }
        }

        // 2. Đồng bộ các biến thể mới nếu quản trị viên tích thêm màu sắc / kích cỡ mới
        if (!empty($colors) && !empty($sizes)) {
            $variantStock = max(1, (int)floor($product->stock / (count($colors) * count($sizes))));
            foreach ($colors as $color) {
                $colorTrim = trim($color);
                foreach ($sizes as $size) {
                    $sizeTrim = trim($size);
                    $varSku = $product->sku . '-' . strtoupper(Str::slug($colorTrim)) . '-' . strtoupper(Str::slug($sizeTrim));

                    $variant = ProductVariant::where('product_id', $product->id)
                        ->where('color', $colorTrim)
                        ->where('size', $sizeTrim)
                        ->first();

                    if ($variant) {
                        // Kiểm tra nếu có cập nhật bằng mã SKU
                        $skuUpdate = [];
                        if (isset($variantStocksInput[$varSku]) && is_numeric($variantStocksInput[$varSku])) {
                            $skuUpdate['stock'] = max(0, (int)$variantStocksInput[$varSku]);
                        }
                        if (isset($variantPricesInput[$varSku]) && is_numeric($variantPricesInput[$varSku]) && (float)$variantPricesInput[$varSku] > 0) {
                            $skuUpdate['price'] = (float)$variantPricesInput[$varSku];
                        }
                        if (isset($variantMaterialsInput[$varSku])) {
                            $skuUpdate['material'] = trim($variantMaterialsInput[$varSku]) !== '' ? trim($variantMaterialsInput[$varSku]) : null;
                        }
                        if (!empty($skuUpdate)) {
                            $variant->update($skuUpdate);
                        }
                    } else {
                        // Tạo biến thể mới bổ sung
                        $vStock = (isset($variantStocksInput[$varSku]) && is_numeric($variantStocksInput[$varSku]))
                            ? max(0, (int)$variantStocksInput[$varSku])
                            : $variantStock;
                        $vPrice = (isset($variantPricesInput[$varSku]) && is_numeric($variantPricesInput[$varSku]) && (float)$variantPricesInput[$varSku] > 0)
                            ? (float)$variantPricesInput[$varSku]
                            : $product->price;
                        $vMaterial = isset($variantMaterialsInput[$varSku]) ? (trim($variantMaterialsInput[$varSku]) ?: null) : null;

                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $varSku,
                            'color' => $colorTrim,
                            'size' => $sizeTrim,
                            'material' => $vMaterial,
                            'price' => $vPrice,
                            'original_price' => $product->original_price,
                            'stock' => $vStock,
                            'image' => $imagePath,
                            'status' => 'active',
                        ]);
                    }
                }
            }
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

    /**
     * Lấy chi tiết lịch sử bán hàng và danh sách khách hàng đã mua sản phẩm
     */
    public function salesBuyers($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);

        // Lấy tất cả order items của sản phẩm kèm thông tin đơn hàng và tài khoản khách
        $orderItems = OrderItem::with(['order.user'])
            ->where('product_id', $product->id)
            ->whereHas('order')
            ->orderByDesc('id')
            ->get();

        // Danh sách đơn hàng hợp lệ (không tính đơn hủy)
        $validItems = $orderItems->filter(function ($item) {
            return $item->order && $item->order->shipping_status !== 'cancelled';
        });

        $totalSoldQty = (int) $validItems->sum('quantity');
        $totalRevenue = (int) $validItems->sum('subtotal');
        $distinctOrdersCount = $validItems->pluck('order_id')->unique()->count();
        
        $distinctBuyersCount = $validItems->map(function ($item) {
            $order = $item->order;
            if (!$order) return null;
            return $order->customer_phone ?: ($order->customer_email ?: $order->customer_name);
        })->filter()->unique()->count();

        // Danh sách khách hàng và chi tiết từng lần mua
        $buyers = $orderItems->map(function ($item) {
            $order = $item->order;
            
            $shippingStatus = $order ? $order->shipping_status : 'pending';
            $statusLabel = 'Chờ xử lý';
            $statusBadgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
            
            switch ($shippingStatus) {
                case 'completed':
                    $statusLabel = 'Hoàn tất';
                    $statusBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                    break;
                case 'delivered':
                    $statusLabel = 'Đã giao hàng';
                    $statusBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                    break;
                case 'shipping':
                    $statusLabel = 'Đang giao hàng';
                    $statusBadgeClass = 'bg-info-subtle text-info border-info-subtle';
                    break;
                case 'processing':
                    $statusLabel = 'Đang chuẩn bị hàng';
                    $statusBadgeClass = 'bg-primary-subtle text-primary border-primary-subtle';
                    break;
                case 'confirmed':
                    $statusLabel = 'Đã xác nhận';
                    $statusBadgeClass = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                    break;
                case 'cancelled':
                    $statusLabel = 'Đã hủy đơn';
                    $statusBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                    break;
            }

            return [
                'item_id' => $item->id,
                'order_id' => $order ? $order->id : null,
                'order_code' => $order ? $order->order_code : 'N/A',
                'order_url' => $order ? route('admin.orders.show', $order->id) : '#',
                'customer_name' => $order ? ($order->customer_name ?: 'Khách mua tại quầy / web') : 'Khách mua',
                'customer_phone' => $order ? ($order->customer_phone ?: 'Chưa có SĐT') : 'Chưa có SĐT',
                'customer_email' => $order ? ($order->customer_email ?: 'Chưa có email') : 'Chưa có email',
                'shipping_address' => $order ? ($order->shipping_address ?: 'Nhận tại cửa hàng') : '',
                'is_registered' => $order && $order->user_id ? true : false,
                'color' => $item->color ?: 'Tiêu chuẩn',
                'size' => $item->size ?: 'FreeSize',
                'quantity' => (int) $item->quantity,
                'price' => (int) $item->price,
                'price_formatted' => number_format((int) $item->price, 0, ',', '.') . '₫',
                'subtotal' => (int) $item->subtotal,
                'subtotal_formatted' => number_format((int) $item->subtotal, 0, ',', '.') . '₫',
                'shipping_status' => $shippingStatus,
                'status_label' => $statusLabel,
                'status_badge_class' => $statusBadgeClass,
                'payment_method' => $order ? ($order->payment_method_name ?? strtoupper($order->payment_method ?? 'COD')) : 'COD',
                'created_at' => $order && $order->created_at ? $order->created_at->format('d/m/Y H:i') : '',
                'created_at_human' => $order && $order->created_at ? $order->created_at->diffForHumans() : '',
            ];
        });

        $displaySoldQty = $totalSoldQty > 0 ? $totalSoldQty : (int)$product->sold_count;

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => (int) $product->price,
                'price_formatted' => number_format((int) $product->price, 0, ',', '.') . '₫',
                'image' => asset($product->image ?: 'assets/img/products/1.png'),
                'category_name' => $product->category->name ?? 'Thời trang nam',
                'stock' => (int) $product->stock,
                'total_sold_qty' => $displaySoldQty,
                'total_revenue' => $totalRevenue,
                'total_revenue_formatted' => number_format($totalRevenue, 0, ',', '.') . '₫',
                'orders_count' => $distinctOrdersCount,
                'buyers_count' => $distinctBuyersCount,
                'edit_url' => route('admin.products.edit', $product->id),
            ],
            'orders' => $buyers,
        ]);
    }
}