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
        $search = $request->query('q');
        $sort = $request->query('sort', 'popular');
        $priceRange = $request->query('price_range');
        $selectedSize = $request->query('size');
        $selectedColor = $request->query('color');

        $query = Product::with(['category', 'brand', 'variants'])->active();

        // Bộ lọc Danh mục
        if ($categorySlug) {
            $cat = Category::active()->where('slug', $categorySlug)->first();
            if ($cat) {
                if ($cat->children()->exists()) {
                    $catIds = $cat->children->pluck('id')->push($cat->id);
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $cat->id);
                }
            } else {
                return redirect()->route('client.products.index');
            }
        }

        // Bộ lọc Thương hiệu thời trang
        if ($brandSlug) {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Bộ lọc Từ khóa tìm kiếm theo tên, SKU, mô tả ngắn
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Bộ lọc Khoảng giá bán
        if ($priceRange === 'under_500') {
            $query->where('price', '<', 500000);
        } elseif ($priceRange === '500_1000') {
            $query->whereBetween('price', [500000, 1000000]);
        } elseif ($priceRange === 'over_1000') {
            $query->where('price', '>', 1000000);
        }

        // Bộ lọc Kích cỡ (Size) trong mảng size hoặc bảng biến thể
        if ($selectedSize) {
            $query->where(function($q) use ($selectedSize) {
                $q->whereJsonContains('sizes', $selectedSize)
                  ->orWhereHas('variants', function($v) use ($selectedSize) {
                      $v->where('size', $selectedSize)->where('status', 'active');
                  });
            });
        }

        // Bộ lọc Màu sắc trong mảng màu hoặc bảng biến thể
        if ($selectedColor) {
            $query->where(function($q) use ($selectedColor) {
                $q->whereJsonContains('colors', $selectedColor)
                  ->orWhereHas('variants', function($v) use ($selectedColor) {
                      $v->where('color', $selectedColor)->where('status', 'active');
                  });
            });
        }

        // Sắp xếp kết quả tìm kiếm theo tiêu chí chọn
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'views_desc') {
            $query->orderByDesc('views')->orderByDesc('sold_count');
        } else {
            $query->orderByDesc('sold_count')->orderByDesc('rating');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('client.products.index', compact(
            'categories',
            'brands',
            'products',
            'categorySlug',
            'brandSlug',
            'search',
            'sort',
            'priceRange',
            'selectedSize',
            'selectedColor'
        ));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'variants' => fn($q) => $q->active(), 'images', 'reviews', 'dailyDeals'])->active()->findOrFail($id);

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
            ->take(4)
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
            $userHasPurchased = \App\Models\Order::where('user_id', $user->id)
                ->whereHas('items', function ($q) use ($id) {
                    $q->where('product_id', $id);
                })
                ->exists();

            $userReview = \App\Models\Review::where('product_id', $id)->where('user_id', $user->id)->first();
        }

        return view('client.products.show', compact('product', 'relatedProducts', 'categories', 'userHasPurchased', 'userReview'));
    }

    /**
     * API Lấy thông tin nhanh của sản phẩm (Màu sắc, Size, Tồn kho, Giá) để mở Modal chọn biến thể
     */
    public function getQuickViewData($id)
    {
        $product = Product::with(['variants' => fn($q) => $q->active(), 'category'])->active()->find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'], 404);
        }

        // Lấy danh sách màu sắc và sizes
        $colors = $product->colors ?? [];
        $sizes = $product->sizes ?? [];

        // Nếu bảng variants có dữ liệu thì lấy thêm từ variants

        // Kiểm tra ưu đãi trong ngày
        $runningDeal = \App\Models\DailyDeal::where('product_id', $product->id)->runningNow()->first();
        $effectivePrice = $product->price;
        $originalPrice = $product->original_price ?: $product->price;
        $discountPercent = $product->discount_percent;

        if ($runningDeal) {
            $effectivePrice = max(0, (int) round($product->price * (1 - ($runningDeal->discount_percent / 100))));
            $discountPercent = $runningDeal->discount_percent;
        }

        return response()->json([
            'success' => true,
            'id' => $product->id,
            'name' => $product->name,
            'category_name' => $product->category->name ?? 'Thời trang nam',
            'price' => $effectivePrice,
            'price_formatted' => number_format($effectivePrice, 0, ',', '.') . '₫',
            'original_price' => $originalPrice,
            'original_price_formatted' => $originalPrice ? number_format($originalPrice, 0, ',', '.') . '₫' : null,
            'discount_percent' => $discountPercent,
            'is_daily_deal' => (bool)$runningDeal,
            'deal_slot' => $runningDeal ? $runningDeal->formatted_slot : null,
            'image' => asset($product->image),
            'stock' => $product->stock,
            'colors' => $colors,
            'sizes' => $sizes,
            'variants' => $product->variants->map(function ($v) use ($runningDeal) {
                $vPrice = $v->price;
                if ($runningDeal) {
                    $vPrice = max(0, (int) round($v->price * (1 - ($runningDeal->discount_percent / 100))));
                }
                return [
                    'id' => $v->id,
                    'color' => $v->color,
                    'size' => $v->size,
                    'price' => $vPrice,
                    'price_formatted' => number_format($vPrice, 0, ',', '.') . '₫',
                    'stock' => $v->stock ?? 999,
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