<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyDeal;
use App\Models\Product;
use Illuminate\Http\Request;

class DailyDealController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('q');
        $dateFilter = $request->query('date');

        $query = DailyDeal::with(['product.category', 'product.brand'])->latest('id');

        // Tìm kiếm theo tên sản phẩm hoặc mã SKU
        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo ngày
        if ($dateFilter) {
            if ($dateFilter === 'today') {
                $today = now()->toDateString();
                $query->where(function ($q) use ($today) {
                    $q->whereNull('deal_date')->orWhereDate('deal_date', $today);
                });
            } else {
                $query->whereDate('deal_date', $dateFilter);
            }
        }

        // Lọc theo trạng thái
        if ($statusFilter === 'running') {
            $query->runningNow();
        } elseif ($statusFilter === 'upcoming') {
            $query->upcomingToday();
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $deals = $query->paginate(10)->withQueryString();

        // Danh sách tất cả sản phẩm đang hoạt động để chọn trong modal
        $products = Product::active()
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'sku', 'price', 'original_price', 'image', 'stock']);

        // Thống kê nhanh
        $today = now()->toDateString();
        $totalDeals = DailyDeal::count();
        $runningDealsCount = DailyDeal::runningNow()->count();
        $todayDealsCount = DailyDeal::forToday()->count();
        $totalSoldInDeals = DailyDeal::sum('sold_count');

        return view('admin.daily_deals.index', compact(
            'deals',
            'products',
            'statusFilter',
            'search',
            'dateFilter',
            'totalDeals',
            'runningDealsCount',
            'todayDealsCount',
            'totalSoldInDeals'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percent' => 'required|integer|min:1|max:99',
            'deal_date' => 'nullable|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'title' => 'nullable|string|max:255',
            'slot_name' => 'nullable|string|max:100',
            'quantity_limit' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm cần áp dụng ưu đãi.',
            'product_id.exists' => 'Sản phẩm đã chọn không tồn tại.',
            'discount_percent.required' => 'Vui lòng nhập phần trăm khuyến mãi giảm giá.',
            'discount_percent.min' => 'Khuyến mãi tối thiểu là 1%.',
            'discount_percent.max' => 'Khuyến mãi tối đa là 99%.',
            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu trong ngày.',
            'end_time.required' => 'Vui lòng chọn thời gian kết thúc trong ngày.',
        ]);

        // Chuẩn hóa định dạng thời gian HH:MM:SS
        $startTime = strlen($validated['start_time']) == 5 ? $validated['start_time'] . ':00' : $validated['start_time'];
        $endTime = strlen($validated['end_time']) == 5 ? $validated['end_time'] . ':00' : $validated['end_time'];

        if ($endTime <= $startTime) {
            return back()->withInput()->with('error', 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu trong ngày.');
        }

        $product = Product::findOrFail($validated['product_id']);
        $dealPrice = max(0, (int) round($product->price * (1 - ($validated['discount_percent'] / 100))));

        $slotName = !empty($validated['slot_name']) 
            ? $validated['slot_name'] 
            : (substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5));

        $title = !empty($validated['title']) 
            ? $validated['title'] 
            : "Ưu đãi -{$validated['discount_percent']}%";

        DailyDeal::create([
            'product_id' => $product->id,
            'title' => $title,
            'discount_percent' => $validated['discount_percent'],
            'deal_price' => $dealPrice,
            'deal_date' => $validated['deal_date'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'slot_name' => $slotName,
            'quantity_limit' => $validated['quantity_limit'] ?? 0,
            'sold_count' => 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.daily-deals.index')
            ->with('success', "Đã thêm sản phẩm \"{$product->name}\" vào chương trình ƯU ĐÃI TRONG NGÀY với mức giảm {$validated['discount_percent']}% thành công!");
    }

    public function update(Request $request, $id)
    {
        $deal = DailyDeal::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percent' => 'required|integer|min:1|max:99',
            'deal_date' => 'nullable|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'title' => 'nullable|string|max:255',
            'slot_name' => 'nullable|string|max:100',
            'quantity_limit' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm cần áp dụng ưu đãi.',
            'discount_percent.required' => 'Vui lòng nhập phần trăm khuyến mãi giảm giá.',
            'discount_percent.min' => 'Khuyến mãi tối thiểu là 1%.',
            'discount_percent.max' => 'Khuyến mãi tối đa là 99%.',
            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu trong ngày.',
            'end_time.required' => 'Vui lòng chọn thời gian kết thúc trong ngày.',
        ]);

        $startTime = strlen($validated['start_time']) == 5 ? $validated['start_time'] . ':00' : $validated['start_time'];
        $endTime = strlen($validated['end_time']) == 5 ? $validated['end_time'] . ':00' : $validated['end_time'];

        if ($endTime <= $startTime) {
            return back()->withInput()->with('error', 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu trong ngày.');
        }

        $product = Product::findOrFail($validated['product_id']);
        $dealPrice = max(0, (int) round($product->price * (1 - ($validated['discount_percent'] / 100))));
        
        $slotName = !empty($validated['slot_name']) 
            ? $validated['slot_name'] 
            : (substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5));

        $title = !empty($validated['title']) 
            ? $validated['title'] 
            : ($deal->title ?: "Ưu đãi -{$validated['discount_percent']}%");

        $deal->update([
            'product_id' => $product->id,
            'title' => $title,
            'discount_percent' => $validated['discount_percent'],
            'deal_price' => $dealPrice,
            'deal_date' => $validated['deal_date'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'slot_name' => $slotName,
            'quantity_limit' => $validated['quantity_limit'] ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $deal->is_active,
        ]);

        return redirect()->route('admin.daily-deals.index')
            ->with('success', "Đã cập nhật cấu hình Ưu Đãi Trong Ngày cho \"{$product->name}\" thành công!");
    }

    public function destroy($id)
    {
        $deal = DailyDeal::findOrFail($id);
        $productName = $deal->product->name ?? 'sản phẩm';
        $deal->delete();

        return redirect()->route('admin.daily-deals.index')
            ->with('success', "Đã gỡ \"{$productName}\" khỏi danh sách Ưu Đãi Trong Ngày!");
    }

    public function toggleStatus($id)
    {
        $deal = DailyDeal::findOrFail($id);
        $deal->is_active = !$deal->is_active;
        $deal->save();

        $statusMsg = $deal->is_active ? 'kích hoạt' : 'tạm dừng';
        return redirect()->route('admin.daily-deals.index')
            ->with('success', "Đã {$statusMsg} chương trình ưu đãi của \"{$deal->product->name}\"!");
    }

    /**
     * Gia hạn thêm thời gian cho ưu đãi (khi đã hết giờ hoặc cần kéo dài thêm)
     */
    public function renew(Request $request, $id)
    {
        $deal = DailyDeal::findOrFail($id);

        $validated = $request->validate([
            'renew_type' => 'required|string|in:today_end,plus_hours,tomorrow,custom',
            'plus_hours' => 'nullable|integer|min:1|max:24',
            'custom_date' => 'nullable|date',
            'custom_start' => 'nullable|string',
            'custom_end' => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:1|max:99',
            'quantity_limit' => 'nullable|integer|min:0',
            'reset_sold' => 'nullable|boolean',
        ]);

        $now = now();
        $dealDate = $now->toDateString();
        $startTime = '00:00:00';
        $endTime = '23:59:59';
        $slotName = 'Cả ngày (00:00 - 23:59)';

        if ($validated['renew_type'] === 'today_end') {
            // Gia hạn đến hết 23:59 hôm nay
            $dealDate = $now->toDateString();
            $startTime = '00:00:00';
            $endTime = '23:59:59';
            $slotName = 'Cả ngày (00:00 - 23:59)';
        } elseif ($validated['renew_type'] === 'plus_hours') {
            // Gia hạn thêm X giờ tính từ bây giờ
            $plusH = (int)($validated['plus_hours'] ?? 2);
            $dealDate = $now->toDateString();
            $startTime = $now->format('H:i:s');
            $endTime = $now->copy()->addHours($plusH)->format('H:i:s');
            $slotName = 'Gia hạn (' . substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5) . ')';
        } elseif ($validated['renew_type'] === 'tomorrow') {
            // Gia hạn sang ngày mai
            $dealDate = $now->copy()->addDay()->toDateString();
            $startTime = '08:00:00';
            $endTime = '22:00:00';
            $slotName = 'Khung 08:00 - 22:00';
        } elseif ($validated['renew_type'] === 'custom') {
            $dealDate = !empty($validated['custom_date']) ? $validated['custom_date'] : null;
            $startTime = !empty($validated['custom_start']) ? (strlen($validated['custom_start']) == 5 ? $validated['custom_start'] . ':00' : $validated['custom_start']) : '08:00:00';
            $endTime = !empty($validated['custom_end']) ? (strlen($validated['custom_end']) == 5 ? $validated['custom_end'] . ':00' : $validated['custom_end']) : '22:00:00';
            $slotName = substr($startTime, 0, 5) . ' - ' . substr($endTime, 0, 5);
        }

        $discountPercent = !empty($validated['discount_percent']) ? (int)$validated['discount_percent'] : $deal->discount_percent;
        $product = $deal->product;
        $dealPrice = max(0, (int) round($product->price * (1 - ($discountPercent / 100))));

        $updateData = [
            'deal_date' => $dealDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'slot_name' => $slotName,
            'discount_percent' => $discountPercent,
            'deal_price' => $dealPrice,
            'is_active' => true,
        ];

        if (isset($validated['quantity_limit'])) {
            $updateData['quantity_limit'] = (int)$validated['quantity_limit'];
        }

        if ($request->has('reset_sold') && $request->reset_sold) {
            $updateData['sold_count'] = 0;
        }

        $deal->update($updateData);

        return redirect()->route('admin.daily-deals.index')
            ->with('success', "Đã gia hạn thành công ưu đãi cho sản phẩm \"{$deal->product->name}\"! Sản phẩm sẽ lập tức hiển thị trên trang khách hàng.");
    }
}
