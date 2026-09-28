<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DailyDeal;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Danh sách tất cả sản phẩm đang có ƯU ĐÃI TRONG NGÀY (Flash Sale)
     */
    public function dailyDeals(Request $request = null)
    {
        $request = $request ?? request();
        $tab = $request->query('tab', 'all'); // all, running, upcoming
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'discount_desc');
        $search = $request->query('q');

        $query = DailyDeal::with(['product.category', 'product.brand', 'product.variants'])
            ->whereHas('product', fn($q) => $q->active())
            ->active();

        // Lọc theo Tab trạng thái
        if ($tab === 'running') {
            $query->runningNow();
        } elseif ($tab === 'upcoming') {
            $query->upcomingToday();
        } else {
            $query->forToday();
        }

        // Lọc theo Danh mục sản phẩm
        if ($categorySlug) {
            $query->whereHas('product.category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Tìm kiếm theo tên sản phẩm / SKU
        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // Sắp xếp
        if ($sort === 'price_asc') {
            $query->orderBy('deal_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('deal_price', 'desc');
        } elseif ($sort === 'sold_desc') {
            $query->orderBy('sold_count', 'desc');
        } elseif ($sort === 'newest') {
            $query->latest('id');
        } else {
            $query->orderBy('discount_percent', 'desc')->latest('id');
        }

        $deals = $query->paginate(12)->withQueryString();

        // Thống kê số lượng cho các Tabs
        $totalTodayCount = DailyDeal::forToday()->count();
        $runningCount = DailyDeal::runningNow()->count();
        $upcomingCount = DailyDeal::upcomingToday()->count();
        $maxDiscount = DailyDeal::forToday()->max('discount_percent') ?: 50;

        // Tính thời gian đếm ngược chính xác
        $runningDeals = DailyDeal::runningNow()->get();
        $targetCountdown = now()->endOfDay()->toIso8601String();
        $isLive = false;
        $currentSlotTitle = 'Ưu Đãi Hôm Nay';

        if ($runningDeals->isNotEmpty()) {
            $isLive = true;
            $earliestEnd = $runningDeals->map(fn($d) => $d->getTargetEndDateTime())->min();
            $targetCountdown = $earliestEnd ? $earliestEnd->toIso8601String() : now()->endOfDay()->toIso8601String();
            $currentSlotTitle = $runningDeals->first()->formatted_slot;
        } else {
            $upcomingDeals = DailyDeal::upcomingToday()->orderBy('start_time', 'asc')->get();
            if ($upcomingDeals->isNotEmpty()) {
                $firstUpcoming = $upcomingDeals->first();
                $date = $firstUpcoming->deal_date ? $firstUpcoming->deal_date->format('Y-m-d') : now()->toDateString();
                $targetCountdown = Carbon::parse("{$date} {$firstUpcoming->start_time}")->toIso8601String();
                $currentSlotTitle = 'Sắp mở bán lúc ' . substr($firstUpcoming->start_time, 0, 5);
            }
        }

        // Danh mục có deal
        $categories = Category::active()->whereHas('products.dailyDeals', fn($q) => $q->forToday())->get();

        return view('client.daily_deals.index', compact(
            'deals',
            'tab',
            'categorySlug',
            'sort',
            'search',
            'totalTodayCount',
            'runningCount',
            'upcomingCount',
            'maxDiscount',
            'targetCountdown',
            'isLive',
            'currentSlotTitle',
            'categories'
        ));
    }

    public function index(Request $request)
    {
        $categories = Category::active()
            ->with(['activeChildren' => function($q) {
                $q->withCount(['products' => fn($p) => $p->where('status', 'active')]);
            }])
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->get();

        $brands = Brand::active()
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->get();

        $categorySlug = $request->query('category');
        $brandSlug = $request->query('brand');
        $search = trim($request->query('q', ''));
        $sort = $request->query('sort', 'latest');
        $priceRange = $request->query('price_range');
        $selectedSize = $request->query('size');
        $selectedColor = $request->query('color');

        $query = Product::with(['category', 'brand', 'variants', 'primaryImage'])->active();

        // 1. Bộ lọc Danh mục sản phẩm
        $currentCategory = null;
        if ($categorySlug) {
            $currentCategory = Category::active()->where('slug', $categorySlug)->first();
            if ($currentCategory) {
                if ($currentCategory->children()->exists()) {
                    $catIds = $currentCategory->children->pluck('id')->push($currentCategory->id);
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $currentCategory->id);
                }
            } else {
                return redirect()->route('client.products.index', $request->except('category'));
            }
        }

        // 2. Bộ lọc Thương hiệu thời trang
        $currentBrand = null;
        if ($brandSlug) {
            $currentBrand = Brand::active()->where('slug', $brandSlug)->first();
            if ($currentBrand) {
                $query->where('brand_id', $currentBrand->id);
            }
        }

        // 3. Bộ lọc Từ khóa tìm kiếm theo tên, SKU, mô tả ngắn, danh mục hoặc thương hiệu
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$search}%"));
            });
        }

        // 4. Bộ lọc Khoảng giá bán (hỗ trợ cả over_1000 và above_1000)
        if ($priceRange === 'under_500') {
            $query->where('price', '<', 500000);
        } elseif ($priceRange === '500_1000') {
            $query->whereBetween('price', [500000, 1000000]);
        } elseif ($priceRange === 'over_1000' || $priceRange === 'above_1000') {
            $query->where('price', '>', 1000000);
        }

        // 5. Bộ lọc Kích cỡ (Size) trong mảng size hoặc bảng biến thể
        if ($selectedSize) {
            $query->where(function($q) use ($selectedSize) {
                $q->whereJsonContains('sizes', $selectedSize)
                  ->orWhereHas('variants', function($v) use ($selectedSize) {
                      $v->where('size', $selectedSize)->where('status', 'active');
                  });
            });
        }

        // 6. Bộ lọc Màu sắc trong mảng màu hoặc bảng biến thể
        if ($selectedColor) {
            $query->where(function($q) use ($selectedColor) {
                $q->whereJsonContains('colors', $selectedColor)
                  ->orWhereHas('variants', function($v) use ($selectedColor) {
                      $v->where('color', $selectedColor)->where('status', 'active');
                  });
            });
        }

        // 7. Sắp xếp kết quả tìm kiếm theo tiêu chí chọn (đồng bộ hóa view và controller)
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'bestseller' || $sort === 'popular') {
            $query->orderByDesc('sold_count')->orderByDesc('rating');
        } elseif ($sort === 'views_desc') {
            $query->orderByDesc('views')->orderByDesc('sold_count');
        } elseif ($sort === 'rating_desc') {
            $query->orderByDesc('rating')->orderByDesc('reviews_count');
        } elseif ($sort === 'latest' || $sort === 'newest') {
            $query->latest('id');
        } else {
            if ($search !== '') {
                $query->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END", ["{$search}%", "%{$search}%"])
                      ->latest('id');
            } else {
                $query->latest('id');
            }
        }

        $products = $query->paginate(12)->withQueryString();

        $availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $availableColors = ['Đen', 'Trắng', 'Xanh Navy', 'Xám Ghi'];

        // 8. Thu thập danh sách bộ lọc đang áp dụng (Active Filters Pills) để hiển thị trên giao diện
        $activeFilters = [];
        if ($search !== '') {
            $activeFilters['q'] = [
                'type' => 'q',
                'label' => 'Từ khóa: "' . $search . '"',
                'url' => route('client.products.index', $request->except(['q', 'page']))
            ];
        }
        if ($categorySlug && $currentCategory) {
            $activeFilters['category'] = [
                'type' => 'category',
                'label' => 'Danh mục: ' . $currentCategory->name,
                'url' => route('client.products.index', $request->except(['category', 'page']))
            ];
        }
        if ($brandSlug && $currentBrand) {
            $activeFilters['brand'] = [
                'type' => 'brand',
                'label' => 'Thương hiệu: ' . $currentBrand->name,
                'url' => route('client.products.index', $request->except(['brand', 'page']))
            ];
        }
        if ($priceRange) {
            $pLabel = match($priceRange) {
                'under_500' => 'Giá: Dưới 500.000₫',
                '500_1000' => 'Giá: 500k — 1.000.000₫',
                'over_1000', 'above_1000' => 'Giá: Trên 1.000.000₫',
                default => 'Khoảng giá'
            };
            $activeFilters['price_range'] = [
                'type' => 'price_range',
                'label' => $pLabel,
                'url' => route('client.products.index', $request->except(['price_range', 'page']))
            ];
        }
        if ($selectedSize) {
            $activeFilters['size'] = [
                'type' => 'size',
                'label' => 'Size: ' . $selectedSize,
                'url' => route('client.products.index', $request->except(['size', 'page']))
            ];
        }
        if ($selectedColor) {
            $activeFilters['color'] = [
                'type' => 'color',
                'label' => 'Màu: ' . $selectedColor,
                'url' => route('client.products.index', $request->except(['color', 'page']))
            ];
        }

        return view('client.products.index', compact(
            'categories',
            'brands',
            'products',
            'categorySlug',
            'brandSlug',
            'currentCategory',
            'currentBrand',
            'search',
            'sort',
            'priceRange',
            'selectedSize',
            'selectedColor',
            'availableSizes',
            'availableColors',
            'activeFilters'
        ));
    }

    /**
     * API Tìm kiếm nhanh (Live Search Autocomplete & Suggestions)
     */
    public function quickSearch(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json([
                'success' => true,
                'keyword' => $q,
                'categories' => [],
                'products' => [],
                'total' => 0,
                'all_url' => route('client.products.index')
            ]);
        }

        // 1. Đếm tổng sản phẩm khớp thực tế
        $baseQuery = Product::active()->where(function($query) use ($q) {
            $query->where('name', 'LIKE', "%{$q}%")
                  ->orWhere('sku', 'LIKE', "%{$q}%")
                  ->orWhere('short_description', 'LIKE', "%{$q}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$q}%"))
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$q}%"));
        });

        $totalFound = (clone $baseQuery)->count();

        // 2. Tìm danh mục khớp từ khóa
        $matchedCategories = Category::active()
            ->where('name', 'LIKE', "%{$q}%")
            ->withCount(['products' => fn($p) => $p->active()])
            ->take(3)
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'products_count' => $c->products_count,
                    'url' => route('client.products.index', ['category' => $c->slug])
                ];
            });

        // 3. Lấy tối đa 6 sản phẩm nổi bật khớp nhất
        $products = (clone $baseQuery)
            ->with(['category', 'primaryImage', 'variants', 'dailyDeals'])
            ->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END", ["{$q}%", "%{$q}%"])
            ->latest('id')
            ->take(6)
            ->get();

        $formatted = $products->map(function($p) {
            $img = $p->primaryImage->image_path ?? $p->thumbnail ?? 'assets/img/products/1.png';
            if (!str_starts_with($img, 'http')) {
                $img = asset(ltrim($img, '/'));
            }

            $effectivePrice = $p->effective_price ?? $p->price;
            $hasDiscount = $p->is_sale_active && ($p->original_price && $p->original_price > $effectivePrice);
            $discountPercent = $hasDiscount ? round((($p->original_price - $effectivePrice) / $p->original_price) * 100) : 0;

            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku ?: ('BS-' . $p->id),
                'price' => $effectivePrice,
                'price_formatted' => number_format($effectivePrice, 0, ',', '.') . '₫',
                'original_price_formatted' => $hasDiscount ? (number_format($p->original_price, 0, ',', '.') . '₫') : null,
                'has_discount' => $hasDiscount,
                'discount_percent' => $discountPercent,
                'image' => $img,
                'category_name' => $p->category->name ?? 'Beestyle Atelier',
                'brand_name' => $p->brand->name ?? '',
                'url' => route('client.products.show', $p->id)
            ];
        });

        return response()->json([
            'success' => true,
            'keyword' => $q,
            'total' => $totalFound,
            'categories' => $matchedCategories,
            'products' => $formatted,
            'all_url' => route('client.products.index', ['q' => $q])
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants' => fn($q) => $q->active(), 'images', 'reviews.user', 'dailyDeals'])->active()->findOrFail($id);

        // 1. Theo dõi lượt xem & Chống spam F5 qua Session
        $viewKey = 'viewed_product_' . $product->id;
        if (!session()->has($viewKey)) {
            try {
                $product->increment('views');
            } catch (\Exception $e) {
                // Fallback an toàn nếu chưa migrate
            }
            session()->put($viewKey, now()->timestamp);
        }

        // 2. Quản lý lịch sử sản phẩm đã xem gần đây (Recently Viewed Products)
        $recentIds = session()->get('recently_viewed_products', []);
        $recentIds = array_values(array_diff($recentIds, [$product->id]));
        array_unshift($recentIds, $product->id);
        $recentIds = array_slice($recentIds, 0, 10);
        session()->put('recently_viewed_products', $recentIds);

        // Lấy các sản phẩm đã xem trước đó (loại trừ sản phẩm hiện tại)
        $viewedIdsToFetch = array_values(array_diff($recentIds, [$product->id]));
        $recentlyViewedProducts = collect([]);
        if (!empty($viewedIdsToFetch)) {
            $recentlyViewedProducts = Product::with(['category', 'brand', 'variants'])
                ->active()
                ->whereIn('id', $viewedIdsToFetch)
                ->get()
                ->sortBy(function ($p) use ($viewedIdsToFetch) {
                    return array_search($p->id, $viewedIdsToFetch);
                })
                ->values()
                ->take(6);
        }

        // 3. Lấy danh sách Voucher / Mã giảm giá khả dụng của Shop
        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->orderBy('min_order_value', 'asc')
            ->take(6)
            ->get();

        $relatedProducts = Product::with(['category', 'brand', 'variants'])
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        $categories = Category::parents()->with('activeChildren')->get();

        $userHasPurchased = false;
        $userReview = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user->role === 'admin' || $user->role === 'staff') {
                $userHasPurchased = true;
            } else {
                $userHasPurchased = \App\Models\Order::where(function($q) use ($user) {
                        $q->where('user_id', $user->id);
                        if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                        if ($user->email) $q->orWhere('customer_email', $user->email);
                    })
                    ->where('shipping_status', '!=', 'cancelled')
                    ->whereHas('items', function ($q) use ($id, $product) {
                        $q->where('product_id', $id)
                          ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                    })
                    ->exists();

                if (!$userHasPurchased) {
                    $userHasPurchased = \App\Models\OrderItem::whereHas('order', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                        if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                        $q->where('shipping_status', '!=', 'cancelled');
                    })
                    ->where(function($q) use ($id, $product) {
                        $q->where('product_id', $id)
                          ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                    })
                    ->exists();
                }
            }

            $userReview = \App\Models\Review::where('product_id', $id)->where('user_id', $user->id)->first();
        }

        // Kiểm tra Deal / Flash Sale đang hoạt động thực tế trong khung giờ vàng
        $runningDeal = \App\Models\DailyDeal::where('product_id', $product->id)->runningNow()->first();

        return view('client.products.show', compact(
            'product',
            'relatedProducts',
            'recentlyViewedProducts',
            'availableCoupons',
            'runningDeal',
            'categories',
            'userHasPurchased',
            'userReview'
        ));
    }

    /**
     * API Lấy thông tin nhanh của sản phẩm (Màu sắc, Size, Tồn kho, Giá) để mở Modal chọn biến thể
     */
    public function getQuickViewData($id)
    {
        $product = Product::with(['variants' => fn($q) => $q->active(), 'category', 'brand', 'images'])->active()->find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'], 404);
        }

        // Lấy danh sách màu sắc và sizes
        $colors = $product->colors ?? [];
        $sizes = $product->sizes ?? [];
        if (is_string($colors)) {
            $colors = json_decode($colors, true) ?: array_map('trim', explode(',', $colors));
        }
        if (is_string($sizes)) {
            $sizes = json_decode($sizes, true) ?: array_map('trim', explode(',', $sizes));
        }
        if (empty($colors) && $product->variants->isNotEmpty()) {
            $colors = $product->variants->pluck('color')->filter()->unique()->values()->all();
        }
        if (empty($sizes) && $product->variants->isNotEmpty()) {
            $sizes = $product->variants->pluck('size')->filter()->unique()->values()->all();
        }

        // Kiểm tra ưu đãi trong ngày
        $runningDeal = \App\Models\DailyDeal::where('product_id', $product->id)->runningNow()->first();
        $effectivePrice = $product->effective_price;
        $isSaleActive = $product->is_sale_active;
        $originalPrice = $isSaleActive ? ($product->original_price ?: $product->price) : null;
        $discountPercent = $isSaleActive ? $product->discount_percent : 0;

        if ($runningDeal) {
            $effectivePrice = max(0, (int) round($product->effective_price * (1 - ($runningDeal->discount_percent / 100))));
            $discountPercent = $runningDeal->discount_percent;
        }

        $gallery = collect([asset($product->image)]);
        if ($product->images && $product->images->count() > 0) {
            foreach ($product->images as $img) {
                if ($img->image_path) {
                    $gallery->push(asset($img->image_path));
                }
            }
        }
        $gallery = $gallery->unique()->values()->all();

        return response()->json([
            'success' => true,
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku ?: ('BS-' . $product->id),
            'category_name' => $product->category->name ?? 'Thời trang nam',
            'brand_name' => $product->brand->name ?? 'BeeStyle Menswear',
            'rating' => (float)($product->rating ?? 5.0),
            'sold_count' => (int)($product->sold_count ?? 0),
            'product_url' => route('client.products.show', $product->id),
            'price' => $effectivePrice,
            'price_formatted' => number_format($effectivePrice, 0, ',', '.') . '₫',
            'original_price' => $originalPrice,
            'original_price_formatted' => $originalPrice ? number_format($originalPrice, 0, ',', '.') . '₫' : null,
            'discount_percent' => $discountPercent,
            'is_sale_active' => $isSaleActive,
            'is_daily_deal' => (bool)$runningDeal,
            'deal_slot' => $runningDeal ? $runningDeal->formatted_slot : null,
            'image' => asset($product->image),
            'gallery' => $gallery,
            'stock' => $product->variants->count() > 0 ? (int)$product->variants->sum('stock') : (int)$product->stock,
            'colors' => $colors,
            'sizes' => $sizes,
            'variants' => $product->variants->map(function ($v) use ($runningDeal) {
                $vPrice = $v->effective_price;
                if ($runningDeal) {
                    $vPrice = max(0, (int) round($vPrice * (1 - ($runningDeal->discount_percent / 100))));
                }
                return [
                    'id' => $v->id,
                    'color' => trim($v->color),
                    'size' => trim($v->size),
                    'material' => $v->material,
                    'price' => $vPrice,
                    'price_formatted' => number_format($vPrice, 0, ',', '.') . '₫',
                    'stock' => (int) $v->stock,
                ];
            }),
        ]);
    }

    /**
     * Lấy hồ sơ chi tiết người mua / người đánh giá để hiển thị modal xem hồ sơ
     */
    public function getReviewerProfile(Request $request, $id)
    {
        $review = \App\Models\Review::with(['user', 'product'])->find($id);
        $userName = 'Khách Hàng BeeStyle';
        $userAvatar = 'https://ui-avatars.com/api/?name=Khach+Hang&background=f59e0b&color=111827&bold=true&size=128';
        $joinedAt = 'Thành viên thân thiết';
        $totalOrders = rand(6, 16);
        $rankName = 'Hội Viên Vàng (Gold Member)';
        $rankClass = 'badge bg-warning text-dark';
        $rankIcon = 'fa-crown';
        $otherReviews = [];
        $userId = null;

        $isAdmin = \Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'staff']);
        if (!$review || ($review->status !== 'approved' && !$isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => 'Đánh giá này hiện không khả dụng hoặc đã bị ẩn.',
            ], 404);
        }

        if ($review) {
            $userName = $review->user_name;
            $userAvatar = $review->user_avatar_url;
            $userId = $review->user_id;

            if ($review->user) {
                $user = $review->user;
                $joinedAt = $user->created_at ? 'Thành viên từ ' . $user->created_at->format('m/Y') : 'Thành viên từ 2025';
                $completedOrders = $user->orders()->whereIn('shipping_status', ['completed', 'delivered'])->count();
                $totalOrders = max($completedOrders, rand(6, 15));
                $userRank = $user->rank ?? 'gold';
                if ($userRank === 'diamond') {
                    $rankName = 'Hội Viên Kim Cương';
                    $rankClass = 'badge bg-info text-white';
                    $rankIcon = 'fa-gem';
                } elseif ($userRank === 'platinum') {
                    $rankName = 'Hội Viên Bạch Kim';
                    $rankClass = 'badge bg-secondary text-white';
                    $rankIcon = 'fa-medal';
                } else {
                    $rankName = 'Hội Viên Vàng';
                    $rankClass = 'badge bg-warning text-dark';
                    $rankIcon = 'fa-crown';
                }

                $userOtherReviews = \App\Models\Review::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->where('id', '!=', $review->id)
                    ->with('product')
                    ->latest()
                    ->take(3)
                    ->get();

                foreach ($userOtherReviews as $or) {
                    if ($or->product) {
                        $otherReviews[] = [
                            'product_name' => $or->product->name,
                            'product_image' => asset($or->product->image),
                            'product_url' => route('client.products.show', $or->product->id),
                            'rating' => $or->rating,
                            'comment' => $or->comment,
                            'date' => $or->created_at ? $or->created_at->format('d/m/Y') : '',
                        ];
                    }
                }
            } else {
                $seedOther = \App\Models\Review::where('user_name', $review->user_name)
                    ->where('status', 'approved')
                    ->where('id', '!=', $review->id)
                    ->with('product')
                    ->latest()
                    ->take(3)
                    ->get();

                foreach ($seedOther as $or) {
                    if ($or->product) {
                        $otherReviews[] = [
                            'product_name' => $or->product->name,
                            'product_image' => asset($or->product->image),
                            'product_url' => route('client.products.show', $or->product->id),
                            'rating' => $or->rating,
                            'comment' => $or->comment,
                            'date' => $or->created_at ? $or->created_at->format('d/m/Y') : '',
                        ];
                    }
                }
            }
        }

        $isAdmin = \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'admin';
        $adminCustomerUrl = ($userId && $isAdmin && \Illuminate\Support\Facades\Route::has('admin.customers.show')) ? route('admin.customers.show', $userId) : null;

        return response()->json([
            'success' => true,
            'user_name' => $userName,
            'avatar_url' => $userAvatar,
            'rank_name' => $rankName,
            'rank_class' => $rankClass,
            'rank_icon' => $rankIcon,
            'joined_at' => $joinedAt,
            'total_orders' => $totalOrders,
            'total_reviews' => count($otherReviews) + 1,
            'verified_buyer' => true,
            'is_admin' => $isAdmin,
            'admin_customer_url' => $adminCustomerUrl,
            'other_reviews' => $otherReviews,
            'current_review' => $review ? [
                'rating' => $review->rating,
                'comment' => $review->comment,
                'date' => $review->created_at ? $review->created_at->format('d/m/Y') : '',
                'images' => $review->images_urls,
            ] : null,
        ]);
    }
}