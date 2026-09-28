<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\DailyDeal;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Danh sách danh mục hoạt động kèm số lượng sản phẩm (động từ DB)
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->orderBy('sort_order', 'asc')
            ->get();

        // 2. Thương hiệu đối tác hoạt động (động từ DB)
        $brands = Brand::active()->orderBy('sort_order', 'asc')->take(8)->get();

        // 3. Ưu đãi trong ngày (Daily Deals / Flash Sale) - Ưu tiên các deal đang chạy trong khung giờ
        $runningDailyDeals = DailyDeal::with(['product.category', 'product.brand', 'product.variants', 'product.primaryImage'])
            ->whereHas('product', fn($q) => $q->where('status', 'active'))
            ->runningNow()
            ->latest('id')
            ->take(8)
            ->get();

        // Nếu chưa tới khung giờ hoặc hết giờ, lấy deal của hôm nay
        if ($runningDailyDeals->isEmpty()) {
            $runningDailyDeals = DailyDeal::with(['product.category', 'product.brand', 'product.variants', 'product.primaryImage'])
                ->whereHas('product', fn($q) => $q->where('status', 'active'))
                ->forToday()
                ->latest('id')
                ->take(8)
                ->get();
        }

        $isCurrentlyLive = $runningDailyDeals->isNotEmpty();
        $targetCountdown = now()->endOfDay()->toIso8601String();
        $currentSlotName = "Khung Giờ Vàng Hôm Nay";

        if ($isCurrentlyLive) {
            $earliestEnd = $runningDailyDeals->map(fn($d) => $d->getTargetEndDateTime())->min();
            $targetCountdown = $earliestEnd ? $earliestEnd->toIso8601String() : now()->endOfDay()->toIso8601String();
            $currentSlotName = $runningDailyDeals->first()->formatted_slot ?? 'Đặc Quyền Giờ Vàng';
        }

        // 4. MỤC SẢN PHẨM BÁN CHẠY: CÓ KHOẢNG THỜI GIAN RÕ RÀNG THEO YÊU CẦU
        // Chu kỳ thống kê mặc định là 30 ngày gần nhất (hoặc tùy chọn qua request)
        $bestSellerDays = (int) $request->query('best_seller_days', 30);
        if (!in_array($bestSellerDays, [7, 14, 30, 60, 90])) {
            $bestSellerDays = 30;
        }
        $bestSellerStartDate = now()->subDays($bestSellerDays)->startOfDay();
        $bestSellerEndDate = now();
        $bestSellerRangeFormatted = 'Từ ngày ' . $bestSellerStartDate->format('d/m/Y') . ' đến ngày ' . $bestSellerEndDate->format('d/m/Y');
        $bestSellerShortRange = $bestSellerStartDate->format('d/m') . ' - ' . $bestSellerEndDate->format('d/m/Y');

        // Thống kê doanh số bán thực tế từ bảng order_items của các đơn hàng không bị hủy
        $periodSales = OrderItem::selectRaw('product_id, SUM(quantity) as period_sold')
            ->whereHas('order', function($q) use ($bestSellerStartDate, $bestSellerEndDate) {
                $q->whereBetween('created_at', [$bestSellerStartDate, $bestSellerEndDate])
                  ->whereNotIn('shipping_status', ['cancelled']);
            })
            ->groupBy('product_id')
            ->pluck('period_sold', 'product_id');

        $periodProductIds = $periodSales->sortDesc()->keys()->toArray();

        if (!empty($periodProductIds)) {
            $cases = collect($periodProductIds)->map(fn($id, $idx) => "WHEN {$id} THEN {$idx}")->implode(' ');
            $bestSellers = Product::with(['category', 'brand', 'variants', 'primaryImage'])
                ->active()
                ->whereIn('id', $periodProductIds)
                ->orderByRaw("CASE id {$cases} ELSE " . count($periodProductIds) . " END")
                ->take(8)
                ->get();
        } else {
            $bestSellers = collect();
        }

        // Nếu số sản phẩm có đơn trong kỳ chưa đủ 8, bổ sung các sản phẩm best seller / sold_count cao nhất
        if ($bestSellers->count() < 8) {
            $excludeIds = $bestSellers->pluck('id')->toArray();
            $fillers = Product::with(['category', 'brand', 'variants', 'primaryImage'])
                ->active()
                ->whereNotIn('id', $excludeIds)
                ->orderByDesc('is_best_seller')
                ->orderByDesc('sold_count')
                ->take(8 - $bestSellers->count())
                ->get();
            $bestSellers = $bestSellers->concat($fillers);
        }

        // Gắn thông tin thời gian thống kê bán chạy và số lượng bán trong kỳ cho từng sản phẩm
        $bestSellers->each(function($p) use ($periodSales, $bestSellerRangeFormatted, $bestSellerShortRange, $bestSellerDays) {
            $p->period_sold = (int) ($periodSales[$p->id] ?? 0);
            $p->period_range_text = $bestSellerRangeFormatted;
            $p->period_short_range = $bestSellerShortRange;
            $p->period_days = $bestSellerDays;
        });

        // 5. Danh sách các nhóm sản phẩm khác
        $products = Product::with(['category', 'brand', 'variants', 'primaryImage'])->active()->latest()->take(8)->get();
        $featuredProducts = Product::with(['category', 'brand', 'variants', 'primaryImage'])->featured()->latest()->take(8)->get();
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $products;
        }

        $newArrivals = Product::with(['category', 'brand', 'variants', 'primaryImage'])->newArrivals()->latest()->take(8)->get();
        if ($newArrivals->isEmpty()) {
            $newArrivals = $products;
        }

        // 6. Tải sản phẩm theo từng danh mục động
        $categoryProducts = [];
        foreach ($categories as $cat) {
            $categoryProducts[$cat->id] = Product::with(['category', 'brand', 'variants', 'primaryImage'])
                ->active()
                ->where('category_id', $cat->id)
                ->latest()
                ->take(8)
                ->get();
        }

        // 7. Lookbook / Bộ sưu tập nổi bật chế tác (Lấy top 3 sản phẩm cao cấp / đánh giá cao nhất)
        $lookbookProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->active()
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->orderByDesc('price')
            ->take(3)
            ->get();
        if ($lookbookProducts->count() < 3) {
            $lookbookProducts = Product::with(['category', 'brand', 'primaryImage'])
                ->active()
                ->orderByDesc('price')
                ->take(3)
                ->get();
        }

        // 8. Mã giảm giá còn hạn & Mã tốt nhất (Động 100% từ bảng coupons)
        $coupons = Coupon::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function($q) {
                $q->where('total_limit', 0)
                  ->orWhereNull('total_limit')
                  ->orWhereColumn('used_count', '<', 'total_limit');
            })
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();
            
        $bestCoupon = $coupons->sortByDesc(function($c) {
            return $c->discount_type === 'percent' ? ($c->discount_value * 10000) : $c->discount_value;
        })->first();

        // 9. Đánh giá khách hàng được duyệt (Thực tế từ DB)
        $reviews = Review::where('status', 'approved')
            ->with(['product', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $totalApprovedReviews = Review::where('status', 'approved')->count();
        $averageRating = round(Review::where('status', 'approved')->avg('rating') ?: 5.0, 1);

        // 10. Số liệu tổng quan cửa hàng (Động từ DB)
        $totalActiveProducts = Product::active()->count();
        $totalBrands = $brands->count();

        return view('client.home', compact(
            'categories',
            'brands',
            'products',
            'featuredProducts',
            'bestSellers',
            'newArrivals',
            'categoryProducts',
            'lookbookProducts',
            'runningDailyDeals',
            'isCurrentlyLive',
            'targetCountdown',
            'currentSlotName',
            'bestSellerDays',
            'bestSellerStartDate',
            'bestSellerEndDate',
            'bestSellerRangeFormatted',
            'bestSellerShortRange',
            'coupons',
            'bestCoupon',
            'reviews',
            'totalApprovedReviews',
            'averageRating',
            'totalActiveProducts',
            'totalBrands'
        ));
    }
}