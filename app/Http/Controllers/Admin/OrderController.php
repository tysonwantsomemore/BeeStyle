<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng với Bộ Lọc Đa Tầng (Multi-Filter Toolbar) chuẩn TMĐT chuyên nghiệp
     */
    public function index(Request $request)
    {
        // Tự động quét và hoàn tất các đơn hàng đã giao quá 7 ngày không có khiếu nại
        Order::autoCompleteEligibleDeliveredOrders();

        $query = $this->getFilteredOrdersQuery($request);
        $orders = $query->paginate(5)->withQueryString();

        // Thống kê số lượng đơn hàng theo từng trạng thái để làm các tab lọc nhanh
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('shipping_status', 'pending')->count(),
            'confirmed' => Order::where('shipping_status', 'confirmed')->count(),
            'processing' => Order::where('shipping_status', 'processing')->count(),
            'shipping' => Order::where('shipping_status', 'shipping')->count(),
            'delivered' => Order::where('shipping_status', 'delivered')->count(),
            'completed' => Order::where('shipping_status', 'completed')->count(),
            'cancelled' => Order::where('shipping_status', 'cancelled')->count(),
        ];

        // Lấy danh sách các đối tác vận chuyển thực tế có trong hệ thống
        $carriers = [
            'Giao Hàng Tiết Kiệm (GHTK)',
            'Giao Hàng Nhanh (GHN)',
            'Viettel Post',
            'J&T Express',
            'Ninja Van',
            'Shipper Nội Bộ BeeStyle',
        ];

        // Danh sách bưu tá giao hàng (Shipper)
        $shippers = User::where('role', 'shipper')->get();

        $filters = [
            'status' => $request->query('status', ''),
            'q' => $request->query('q', ''),
            'payment_method' => $request->query('payment_method', ''),
            'payment_status' => $request->query('payment_status', ''),
            'date_preset' => $request->query('date_preset', ''),
            'date_from' => $request->query('date_from', ''),
            'date_to' => $request->query('date_to', ''),
            'carrier' => $request->query('carrier', ''),
            'amount_range' => $request->query('amount_range', ''),
            'shipper_id' => $request->query('shipper_id', ''),
            'print_status' => $request->query('print_status', ''),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts', 'filters', 'carriers', 'shippers'));
    }

    /**
     * Báo Cáo & Thống Kê Đơn Hàng Tách Biệt (Order & Fulfillment Analytics Dashboard)
     */
    public function statistics(Request $request)
    {
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('shipping_status', 'pending')->count(),
            'confirmed' => Order::where('shipping_status', 'confirmed')->count(),
            'processing' => Order::where('shipping_status', 'processing')->count(),
            'shipping' => Order::where('shipping_status', 'shipping')->count(),
            'delivered' => Order::where('shipping_status', 'delivered')->count(),
            'completed' => Order::where('shipping_status', 'completed')->count(),
            'cancelled' => Order::where('shipping_status', 'cancelled')->count(),
        ];

        $totalRevenue = Order::where(function ($q) {
            $q->whereIn('shipping_status', ['delivered', 'completed'])
              ->orWhere('payment_status', 'paid');
        })->where('shipping_status', '!=', 'cancelled')->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('shipping_status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereIn('shipping_status', ['delivered', 'completed'])
                  ->orWhere('payment_status', 'paid');
            })->sum('total_amount');

        $thisMonthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('shipping_status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereIn('shipping_status', ['delivered', 'completed'])
                  ->orWhere('payment_status', 'paid');
            })->sum('total_amount');

        $carrierStats = Order::whereNotNull('shipping_carrier')
            ->where('shipping_carrier', '!=', '')
            ->select('shipping_carrier', DB::raw('count(*) as total'))
            ->groupBy('shipping_carrier')
            ->orderByDesc('total')
            ->get();

        $paymentStats = Order::select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as amount'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $recentOrders = Order::with(['items.product', 'user'])
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('admin.orders.statistics', compact(
            'statusCounts',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'thisMonthOrders',
            'thisMonthRevenue',
            'carrierStats',
            'paymentStats',
            'recentOrders'
        ));
    }

    /**
     * Query Builder dùng chung cho cả trang danh sách và chức năng Xuất Excel/CSV
     */
    private function getFilteredOrdersQuery(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');
        $paymentMethod = $request->query('payment_method');
        $paymentStatus = $request->query('payment_status');
        $datePreset = $request->query('date_preset');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $carrier = $request->query('carrier');
        $amountRange = $request->query('amount_range');
        $shipperId = $request->query('shipper_id');
        $printStatus = $request->query('print_status');

        $query = Order::with(['items.product', 'user', 'shipper'])->orderBy('id', 'desc');

        if ($shipperId) {
            $query->where('shipper_id', $shipperId);
        }

        if ($printStatus === 'printed') {
            $query->whereNotNull('printed_at');
        } elseif ($printStatus === 'unprinted') {
            $query->whereNull('printed_at');
        }

        if ($status) {
            $query->where('shipping_status', $status);
        }

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        if ($paymentStatus) {
            $query->where('payment_status', strtolower($paymentStatus));
        }

        if ($carrier) {
            $query->where('shipping_carrier', 'LIKE', "%{$carrier}%");
        }

        // Lọc theo Khoảng thời gian đặt hàng
        if ($datePreset) {
            switch ($datePreset) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case '7days':
                    $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
                    break;
                case '30days':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay());
                    break;
                case 'this_month':
                    $query->whereYear('created_at', Carbon::now()->year)
                          ->whereMonth('created_at', Carbon::now()->month);
                    break;
            }
        } elseif ($dateFrom || $dateTo) {
            if ($dateFrom) {
                $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            }
            if ($dateTo) {
                $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
            }
        }

        // Lọc theo Khoảng giá trị đơn hàng
        if ($amountRange) {
            switch ($amountRange) {
                case 'under_500k':
                    $query->where('total_amount', '<', 500000);
                    break;
                case '500k_1m':
                    $query->whereBetween('total_amount', [500000, 1000000]);
                    break;
                case 'over_1m':
                    $query->where('total_amount', '>', 1000000);
                    break;
            }
        }

        // Tìm kiếm đa năng: Mã đơn, Mã vận đơn, Tên KH, SĐT, Email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('tracking_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    /**
     * Xuất danh sách đơn hàng ra file CSV chuẩn UTF-8 (Tương thích 100% Microsoft Excel & Google Sheets)
     */
    public function export(Request $request)
    {
        $query = $this->getFilteredOrdersQuery($request);
        $orders = $query->get();

        $filename = 'BeeStyle_DonHang_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens Vietnamese characters with accents correctly
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Header Row
            fputcsv($handle, [
                'ID',
                'Mã Đơn Hàng',
                'Thời Gian Đặt Hàng',
                'Tên Khách Hàng',
                'Số Điện Thoại',
                'Email',
                'Địa Chỉ Nhận Hàng',
                'Tỉnh / Thành Phố',
                'Kênh Thanh Toán',
                'Trạng Thái Thanh Toán',
                'Trạng Thái Vận Chuyển',
                'Đơn Vị Vận Chuyển',
                'Mã Vận Đơn',
                'Số Lượng Mẫu',
                'Tổng Số Sản Phẩm',
                'Tạm Tính (₫)',
                'Giảm Giá Voucher (₫)',
                'Mã Voucher',
                'Phí Vận Chuyển (₫)',
                'Tổng Tiền Thu Khách (₫)',
                'Ghi Chú Khách',
                'Ghi Chú Nội Bộ',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->order_code,
                    $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : '',
                    $order->customer_name,
                    $order->customer_phone,
                    $order->customer_email,
                    $order->shipping_address,
                    $order->city,
                    $order->payment_method_name,
                    $order->payment_status_label,
                    $order->status_label,
                    $order->shipping_carrier ?: 'Chưa gán',
                    $order->tracking_code ?: 'Chưa có',
                    $order->items->count(),
                    $order->items->sum('quantity'),
                    $order->subtotal,
                    $order->discount_amount,
                    $order->coupon_code,
                    $order->shipping_fee,
                    $order->total_amount,
                    $order->notes,
                    $order->admin_notes,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * In Phiếu Đóng Gói Hàng Loạt (Bulk Packing Slips)
     * Đánh dấu thời gian đã in phiếu & tự động chuyển đơn sang Đang Đóng Gói (processing)
     */
    public function bulkPrint(Request $request)
    {
        $orderIds = $request->input('order_ids', $request->input('selected_orders'));
        if (is_string($orderIds)) {
            $orderIds = array_filter(explode(',', $orderIds));
        }

        if (empty($orderIds)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng để in phiếu đóng gói.');
        }

        $orders = Order::with(['items.product', 'user', 'shipper'])
            ->whereIn('id', (array)$orderIds)
            ->orderBy('id', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        $now = now();
        foreach ($orders as $order) {
            $updateData = [
                'printed_at'  => $now,
                'print_count' => ($order->print_count ?? 0) + 1,
            ];

            // Khi in phiếu, đơn ở confirmed hoặc pending sẽ tự động chuyển sang Đang Đóng Gói
            if (in_array($order->shipping_status, ['pending', 'confirmed'])) {
                $updateData['shipping_status'] = 'processing';
                $updateData['status_step'] = 3;
                if (!$order->confirmed_at) {
                    $updateData['confirmed_at'] = $now;
                }
                $updateData['processing_at'] = $order->processing_at ?: $now;
            }

            $order->update($updateData);
        }

        return view('admin.orders.bulk-print', compact('orders'));
    }

    /**
     * In Phiếu Đóng Gói cho từng đơn hàng đơn lẻ & Tự động chuyển sang Đang Đóng Gói
     */
    public function printSlip($id)
    {
        $order = Order::with(['items.product', 'user', 'shipper'])->findOrFail($id);
        $now = now();

        $updateData = [
            'printed_at'  => $now,
            'print_count' => ($order->print_count ?? 0) + 1,
        ];

        // Khi in phiếu, tự động chuyển đơn sang Đang Đóng Gói
        if (in_array($order->shipping_status, ['pending', 'confirmed'])) {
            $updateData['shipping_status'] = 'processing';
            $updateData['status_step'] = 3;
            if (!$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
            $updateData['processing_at'] = $order->processing_at ?: $now;
        }

        $order->update($updateData);

        $orders = collect([$order]);
        return view('admin.orders.bulk-print', compact('orders'));
    }

    /**
     * Bàn giao đơn hàng từ Kho Đóng Gói cho Bưu Tá (BẮT BUỘC 1 ẢNH KIỆN HÀNG XUẤT KHO)
     */
    public function handoverShipper(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->shipping_status, ['confirmed', 'processing'])) {
            return back()->with('error', 'Chỉ các đơn hàng đang ở trạng thái Đã Xác Nhận hoặc Đang Đóng Gói mới có thể bàn giao cho bưu tá!');
        }

        $validated = $request->validate([
            'shipper_id' => 'required|exists:users,id',
            'shipping_carrier' => 'nullable|string|max:100',
            'tracking_code' => 'nullable|string|max:100',
            'handover_image_file' => 'required_without:handover_image|nullable|file|image|max:10240',
            'handover_image' => 'nullable|string|max:255',
        ], [
            'shipper_id.required' => 'Vui lòng chọn bưu tá phụ trách giao đơn hàng này.',
            'shipper_id.exists' => 'Bưu tá đã chọn không tồn tại trong hệ thống.',
            'handover_image_file.required_without' => 'BẮT BUỘC phải chụp hoặc tải lên 1 ảnh kiện hàng bàn giao xuất kho cho bưu tá!',
            'handover_image_file.image' => 'Tập tin tải lên phải là hình ảnh (JPG, PNG, WEBP).',
            'handover_image_file.max' => 'Dung lượng ảnh tối đa 10MB.',
        ]);

        $now = now();
        $handoverPath = $order->handover_image;

        if ($request->hasFile('handover_image_file')) {
            $handoverPath = $request->file('handover_image_file')->store('handover_proofs', 'public');
        } elseif ($request->filled('handover_image')) {
            $handoverPath = $request->input('handover_image');
        }

        if (empty($handoverPath)) {
            $handoverPath = 'assets/img/delivery-proofs/sample_pod_1.jpg';
        }

        $shipper = User::find($validated['shipper_id']);
        $carrier = $validated['shipping_carrier'] ?: ($shipper ? 'BeeStyle Express - ' . $shipper->name : 'BeeStyle Express');
        $tracking = $validated['tracking_code'] ?: 'BEE-' . strtoupper(Str::random(8));

        $order->update([
            'shipping_status'  => 'shipping',
            'status_step'      => 4,
            'shipper_id'       => $validated['shipper_id'],
            'shipping_carrier' => $carrier,
            'tracking_code'    => $tracking,
            'handover_image'   => $handoverPath,
            'shipping_at'      => $order->shipping_at ?: $now,
            'confirmed_at'     => $order->confirmed_at ?: $now,
            'processing_at'    => $order->processing_at ?: $now,
        ]);

        return back()->with('success', "Đã bàn giao đơn hàng #{$order->order_code} cho Bưu tá {$shipper->name} (Mã VĐ: {$tracking})! Đơn đã chuyển sang trạng thái ĐANG GIAO HÀNG.");
    }

    /**
     * Đóng gói xong -> Tự động chuyển sang bưu tá vận chuyển
     * Tự động điều phối bưu tá hợp lý, sinh mã vận đơn và chuyển đơn sang trạng thái Đang Giao Hàng (shipping)
     */
    public function finishPacking(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->shipping_status, ['confirmed', 'processing'])) {
            return back()->with('error', 'Chỉ các đơn hàng đang ở trạng thái Đã Xác Nhận hoặc Đang Đóng Gói mới có thể hoàn tất đóng gói và chuyển sang bưu tá!');
        }

        $now = now();

        // 1. Xác định Bưu tá nhận đơn (nếu chưa gán)
        $shipper = null;
        if ($order->shipper_id) {
            $shipper = User::find($order->shipper_id);
        }

        if (!$shipper) {
            // Tự động tìm bưu tá active có ít đơn đang giao nhất để chia đều công việc
            $shippers = User::where('role', 'shipper')->get();
            if ($shippers->isNotEmpty()) {
                $shipper = $shippers->sortBy(function ($shp) {
                    return Order::where('shipper_id', $shp->id)->where('shipping_status', 'shipping')->count();
                })->first();
            }
        }

        $shipperId = $shipper ? $shipper->id : null;
        $shipperName = $shipper ? $shipper->name : 'BeeStyle Courier';

        // 2. Tự động sinh mã vận đơn nếu chưa có
        $tracking = $order->tracking_code ?: ('BEE-' . strtoupper(Str::random(8)));
        $carrier = $order->shipping_carrier ?: ('BeeStyle Express - ' . $shipperName);

        // 3. Cập nhật đơn hàng sang Bước 4: Đang Giao Hàng
        $order->update([
            'shipping_status'  => 'shipping',
            'status_step'      => 4,
            'shipper_id'       => $shipperId,
            'shipping_carrier' => $carrier,
            'tracking_code'    => $tracking,
            'handover_image'   => $order->handover_image ?: 'assets/img/delivery-proofs/sample_pod_1.jpg',
            'shipping_at'      => $order->shipping_at ?: $now,
            'confirmed_at'     => $order->confirmed_at ?: $now,
            'processing_at'    => $order->processing_at ?: $now,
        ]);

        return back()->with('success', "Đóng gói hoàn tất! Đơn hàng #{$order->order_code} đã tự động chuyển giao cho Bưu tá {$shipperName} (Mã VĐ: {$tracking}). Đơn đã hiển thị trên Cổng Bưu Tá để bưu tá đi phát hàng.");
    }

    /**
     * Tự động xác nhận tất cả các đơn hàng đang ở trạng thái Chờ Xác Nhận (pending)
     */
    public function confirmAllPending(Request $request)
    {
        $now = now();
        $pendingOrders = Order::where('shipping_status', 'pending')->get();

        if ($pendingOrders->isEmpty()) {
            return back()->with('info', 'Hiện tại không có đơn hàng nào đang ở trạng thái chờ xác nhận.');
        }

        $count = 0;
        DB::beginTransaction();
        try {
            foreach ($pendingOrders as $order) {
                $order->update([
                    'shipping_status' => 'confirmed',
                    'status_step' => 2,
                    'confirmed_at' => $order->confirmed_at ?: $now,
                ]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi tự động xác nhận đơn hàng: ' . $e->getMessage());
        }

        return back()->with('success', "Thành công! Đã tự động xác nhận đồng bộ {$count} đơn hàng chờ duyệt cùng lúc.");
    }

    /**
     * Thao tác hàng loạt (Bulk Actions) trên nhiều đơn hàng được chọn
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id',
            'action' => 'required|string|in:confirm,processing,shipping,delivered,completed',
        ]);

        $orderIds = $validated['order_ids'];
        $action = $validated['action'];
        $now = now();

        if (in_array($action, ['mark_paid', 'cancel'])) {
            return back()->with('error', 'Thu tiền và Hủy đơn chỉ được phép thực hiện trên từng đơn hàng riêng biệt để đảm bảo an toàn tài chính và quản lý kho.');
        }

        $orders = Order::with('items')->whereIn('id', $orderIds)->get();
        if ($orders->isEmpty()) {
            return back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng để thực hiện.');
        }

        $count = 0;
        DB::beginTransaction();
        try {
            foreach ($orders as $order) {
                $updateData = [];

                switch ($action) {
                    case 'confirm':
                        if ($order->shipping_status === 'pending') {
                            $updateData['shipping_status'] = 'confirmed';
                            $updateData['status_step'] = 2;
                            $updateData['confirmed_at'] = $order->confirmed_at ?: $now;
                        }
                        break;

                    case 'processing':
                        if (in_array($order->shipping_status, ['pending', 'confirmed'])) {
                            $updateData['shipping_status'] = 'processing';
                            $updateData['status_step'] = 3;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            $updateData['processing_at'] = $order->processing_at ?: $now;
                        }
                        break;

                    case 'shipping':
                        if ($order->shipping_status === 'processing') {
                            $updateData['shipping_status'] = 'shipping';
                            $updateData['status_step'] = 4;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            if (!$order->processing_at) $updateData['processing_at'] = $now;
                            $updateData['shipping_at'] = $order->shipping_at ?: $now;

                            if (empty($order->shipper_id)) {
                                $shippers = User::where('role', 'shipper')->get();
                                if ($shippers->isNotEmpty()) {
                                    $autoShipper = $shippers->random();
                                    $updateData['shipper_id'] = $autoShipper->id;
                                    if (empty($order->shipping_carrier)) {
                                        $updateData['shipping_carrier'] = 'BeeStyle Express - ' . $autoShipper->name;
                                    }
                                }
                            }
                            if (empty($order->tracking_code)) {
                                $updateData['tracking_code'] = 'BEE-' . strtoupper(Str::random(8));
                            }
                            if (empty($order->handover_image)) {
                                $updateData['handover_image'] = 'assets/img/delivery-proofs/sample_pod_1.jpg';
                            }
                        }
                        break;

                    case 'delivered':
                        if ($order->shipping_status === 'shipping') {
                            $updateData['shipping_status'] = 'delivered';
                            $updateData['status_step'] = 5;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            if (!$order->processing_at) $updateData['processing_at'] = $now;
                            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
                            $updateData['delivered_at'] = $order->delivered_at ?: $now;
                            if (empty($order->delivery_proof_image)) {
                                $updateData['delivery_proof_image'] = 'assets/img/delivery-proofs/sample_pod_1.jpg';
                                $updateData['delivery_proof_at'] = $now;
                            }
                            if (empty($order->delivery_proof_note)) {
                                $updateData['delivery_proof_note'] = 'Bưu tá xác nhận đã chuyển kiện hàng thành công tới khách hàng.';
                            }
                            if ($order->payment_method === 'cod') {
                                $updateData['payment_status'] = 'paid';
                                $updateData['paid_at'] = $order->paid_at ?: $now;
                            }
                            $updateData['review_notified'] = false;
                        }
                        break;

                    case 'completed':
                        if ($order->shipping_status === 'delivered') {
                            $updateData['shipping_status'] = 'completed';
                            $updateData['status_step'] = 6;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            if (!$order->processing_at) $updateData['processing_at'] = $now;
                            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
                            if (!$order->delivered_at) $updateData['delivered_at'] = $now;
                            $updateData['completed_at'] = $order->completed_at ?: $now;
                            $updateData['payment_status'] = 'paid';
                            $updateData['paid_at'] = $order->paid_at ?: $now;
                            $updateData['review_notified'] = false;

                            // Cộng điểm thưởng và tổng chi tiêu nếu đơn chưa hoàn tất trước đó
                            if ($order->user_id) {
                                $user = User::find($order->user_id);
                                if ($user) {
                                    $earnedPoints = (int)floor($order->total_amount / 10000);
                                    $user->increment('points', $earnedPoints);
                                    $user->increment('total_spent', $order->total_amount);
                                }
                            }
                        }
                        break;
                }

                if (!empty($updateData)) {
                    $order->update($updateData);
                    $count++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi thực hiện thao tác hàng loạt: ' . $e->getMessage());
        }

        $actionLabels = [
            'confirm' => 'Xác nhận đơn hàng (Bước 2)',
            'processing' => 'Chuyển kho đóng gói (Bước 3)',
            'shipping' => 'Bàn giao bưu tá vận chuyển (Bước 4)',
            'delivered' => 'Giao hàng thành công (Bước 5)',
            'completed' => 'Hoàn tất đơn hàng (Bước 6)',
        ];

        $actionName = $actionLabels[$action] ?? 'Cập nhật';

        if ($count === 0) {
            return back()->with('warning', "Không có đơn hàng nào phù hợp ở bước này để thực hiện '{$actionName}' (các đơn đã chọn có thể đã được xử lý bước này trước đó).");
        }

        return back()->with('success', "Thành công! Đã thực hiện thao tác '{$actionName}' đồng bộ cho {$count} đơn hàng hợp lệ.");
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'returns', 'shipper'])->findOrFail($id);
        $shippers = User::where('role', 'shipper')->get();
        return view('admin.orders.show', compact('order', 'shippers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'shipping_status' => 'required|string|in:pending,confirmed,processing,shipping,delivered,completed,cancelled',
            'payment_status' => 'nullable|string|in:unpaid,paid,refunded,pending,cancelled,expired',
            'shipping_carrier' => 'nullable|string|max:100',
            'tracking_code' => 'nullable|string|max:100',
            'shipper_id' => 'nullable|exists:users,id',
            'handover_image_file' => 'nullable|file|image|max:10240',
            'handover_image' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:1000',
            'cancel_reason' => 'nullable|string|max:500',
            'delivery_proof_file' => 'nullable|file|image|max:10240',
            'delivery_proof_image' => 'nullable|string|max:255',
            'delivery_proof_note' => 'nullable|string|max:1000',
            'confirmed_at' => 'nullable|date',
            'processing_at' => 'nullable|date',
            'shipping_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'reset_steps' => 'nullable|boolean',
        ]);

        $stepMap = [
            'pending' => 1,
            'confirmed' => 2,
            'processing' => 3,
            'shipping' => 4,
            'delivered' => 5,
            'completed' => 6,
            'cancelled' => 0,
        ];

        $previousShippingStatus = $order->shipping_status;
        $newShippingStatus = $validated['shipping_status'];
        $paymentStatus = $validated['payment_status'] ?? $order->payment_status;

        // KIỂM TRA QUY TẮC CHUYỂN TRẠNG THÁI (STATE MACHINE ENFORCEMENT)
        if ($newShippingStatus !== $previousShippingStatus) {
            if (!$order->canTransitionTo($newShippingStatus)) {
                $statusLabels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'processing' => 'Đang đóng gói',
                    'shipping' => 'Đang giao hàng',
                    'delivered' => 'Đã giao hàng',
                    'completed' => 'Hoàn tất',
                    'cancelled' => 'Đã hủy',
                ];
                $currentLabel = $statusLabels[$previousShippingStatus] ?? $previousShippingStatus;
                $targetLabel = $statusLabels[$newShippingStatus] ?? $newShippingStatus;
                
                if (in_array($previousShippingStatus, ['delivered', 'completed'], true)) {
                    return back()->with('error', "Không thể chuyển ngược đơn hàng từ trạng thái '{$currentLabel}' về '{$targetLabel}'. Hàng đã được giao đến tay khách hàng! Nếu khách hàng có yêu cầu đổi trả hoặc hoàn tiền, vui lòng sử dụng chức năng Phiếu Đổi Trả (RMA).");
                }
                
                if ($previousShippingStatus === 'cancelled') {
                    return back()->with('error', "Đơn hàng đã ở trạng thái 'Đã hủy' và không thể thay đổi trạng thái.");
                }

                return back()->with('error', "Không thể chuyển trạng thái đơn hàng từ '{$currentLabel}' sang '{$targetLabel}' theo quy trình vận hành TMĐT chuẩn.");
            }
        }

        $cancelledBy = $order->cancelled_by;
        $cancelledAt = $order->cancelled_at;
        $cancelReason = $order->cancel_reason;

        // Nếu đơn hàng bị hủy, hoàn trả lại số lượng tồn kho cho các sản phẩm & phân loại biến thể
        if ($newShippingStatus === 'cancelled' && $previousShippingStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    $prod = Product::find($item->product_id);
                    if ($prod && $prod->sold_count >= $item->quantity) {
                        $prod->decrement('sold_count', $item->quantity);
                    }

                    if (!empty($item->color) && !empty($item->size)) {
                        ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            // Hoàn lại lượt sử dụng mã giảm giá (Voucher)
            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)->first();
                if ($coupon && $coupon->used_count > 0) {
                    $coupon->decrement('used_count');
                }
            }

            // Nếu đơn hàng từng hoàn tất và bị hủy, trừ lại điểm thưởng và tổng chi tiêu đã tích lũy
            if (in_array($previousShippingStatus, ['completed', 'delivered']) && $order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    $earnedPoints = (int)floor($order->total_amount / 10000);
                    if ($user->points >= $earnedPoints) {
                        $user->decrement('points', $earnedPoints);
                    }
                    if ($user->total_spent >= $order->total_amount) {
                        $user->decrement('total_spent', $order->total_amount);
                    }
                }
            }

            // Xử lý hoàn tiền tự động nếu đơn đã thanh toán online (VNPAY/MoMo/Chuyển khoản)
            if ($order->payment_status === 'paid' && (!isset($validated['payment_status']) || $validated['payment_status'] === 'paid')) {
                $paymentStatus = 'refunded';
            }

            $cancelledBy = 'admin';
            $cancelledAt = now();
            $cancelReason = $request->input('cancel_reason', 'Hủy bởi Quản trị viên BeeStyle');
        } elseif ($previousShippingStatus === 'cancelled' && $newShippingStatus !== 'cancelled') {
            // Nếu kích hoạt lại đơn hàng đã hủy, trừ lại kho
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                    Product::where('id', $item->product_id)->increment('sold_count', $item->quantity);

                    if (!empty($item->color) && !empty($item->size)) {
                        ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->decrement('stock', $item->quantity);
                    }
                }
            }
            $cancelledBy = null;
            $cancelledAt = null;
            $cancelReason = null;
        }

        // Tích lũy điểm thưởng & tổng chi tiêu khi đơn hàng hoàn tất
        if ($newShippingStatus === 'completed' && $previousShippingStatus !== 'completed' && $order->user_id) {
            $user = User::find($order->user_id);
            if ($user) {
                $earnedPoints = (int)floor($order->total_amount / 10000);
                $user->increment('points', $earnedPoints);
                $user->increment('total_spent', $order->total_amount);
            }
        }

        // Cập nhật thông tin Đơn vị vận chuyển & Mã vận đơn vào các cột chuẩn
        $shippingCarrier = $validated['shipping_carrier'] ?? $order->shipping_carrier;
        $trackingCode = $validated['tracking_code'] ?? $order->tracking_code;
        $adminNotes = $validated['admin_notes'] ?? $order->admin_notes;

        if (!empty($shippingCarrier) && !empty($trackingCode)) {
            $carrierInfo = "[ĐVVC: {$shippingCarrier} | Vận đơn: {$trackingCode}]";
            if (!str_contains((string)$adminNotes, $trackingCode)) {
                $adminNotes = $carrierInfo . ($adminNotes ? " - " . $adminNotes : "");
            }
        }

        $updateData = [
            'shipping_status' => $validated['shipping_status'],
            'payment_status' => $paymentStatus,
            'shipping_carrier' => $shippingCarrier,
            'tracking_code' => $trackingCode,
            'status_step' => $stepMap[$validated['shipping_status']] ?? 1,
            'admin_notes' => $adminNotes,
            'cancelled_by' => $cancelledBy,
            'cancelled_at' => $cancelledAt,
            'cancel_reason' => $cancelReason,
        ];

        if ($request->filled('shipper_id')) {
            $updateData['shipper_id'] = $request->input('shipper_id');
        }
        if ($request->hasFile('handover_image_file')) {
            $updateData['handover_image'] = $request->file('handover_image_file')->store('handover_proofs', 'public');
        } elseif ($request->filled('handover_image')) {
            $updateData['handover_image'] = $request->input('handover_image');
        }

        // Ghi nhận mốc thời gian (ngày & giờ cụ thể) cho từng bước khi xác nhận
        $stepStatus = $validated['shipping_status'];
        $now = now();

        // Hỗ trợ gán mốc thời gian tùy chỉnh nếu quản trị viên nhập từ giao diện
        if ($request->filled('confirmed_at')) {
            $updateData['confirmed_at'] = Carbon::parse($request->input('confirmed_at'));
        }
        if ($request->filled('processing_at')) {
            $updateData['processing_at'] = Carbon::parse($request->input('processing_at'));
        }
        if ($request->filled('shipping_at')) {
            $updateData['shipping_at'] = Carbon::parse($request->input('shipping_at'));
        }
        if ($request->filled('delivered_at')) {
            $updateData['delivered_at'] = Carbon::parse($request->input('delivered_at'));
        }
        if ($request->filled('completed_at')) {
            $updateData['completed_at'] = Carbon::parse($request->input('completed_at'));
        }

        // Tự động gán mốc thời gian tuần tự theo bước nếu chưa có
        if ($stepStatus === 'pending') {
            if ($request->boolean('reset_steps')) {
                $updateData['confirmed_at'] = null;
                $updateData['processing_at'] = null;
                $updateData['shipping_at'] = null;
                $updateData['delivered_at'] = null;
                $updateData['completed_at'] = null;
                $updateData['delivery_proof_at'] = null;
            }
        } elseif ($stepStatus === 'confirmed') {
            if (empty($updateData['confirmed_at']) && !$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
        } elseif ($stepStatus === 'processing') {
            if (empty($updateData['confirmed_at']) && !$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
            if (empty($updateData['processing_at']) && !$order->processing_at) {
                $updateData['processing_at'] = $now;
            }
        } elseif ($stepStatus === 'shipping') {
            if (empty($updateData['confirmed_at']) && !$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
            if (empty($updateData['processing_at']) && !$order->processing_at) {
                $updateData['processing_at'] = $now;
            }
            if (empty($updateData['shipping_at']) && !$order->shipping_at) {
                $updateData['shipping_at'] = $now;
            }
            // Tự động gán bưu tá nếu chưa có
            if (empty($updateData['shipper_id']) && empty($order->shipper_id)) {
                $shippers = User::where('role', 'shipper')->get();
                if ($shippers->isNotEmpty()) {
                    $autoShipper = $shippers->sortBy(function ($shp) {
                        return Order::where('shipper_id', $shp->id)->where('shipping_status', 'shipping')->count();
                    })->first();
                    if ($autoShipper) {
                        $updateData['shipper_id'] = $autoShipper->id;
                        if (empty($updateData['shipping_carrier']) && empty($order->shipping_carrier)) {
                            $updateData['shipping_carrier'] = 'BeeStyle Express - ' . $autoShipper->name;
                        }
                    }
                }
            }
            if (empty($updateData['tracking_code']) && empty($order->tracking_code)) {
                $updateData['tracking_code'] = 'BEE-' . strtoupper(Str::random(8));
            }
            if (empty($updateData['handover_image']) && empty($order->handover_image)) {
                $updateData['handover_image'] = 'assets/img/delivery-proofs/sample_pod_1.jpg';
            }
        } elseif ($stepStatus === 'delivered') {
            if (empty($updateData['confirmed_at']) && !$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
            if (empty($updateData['processing_at']) && !$order->processing_at) {
                $updateData['processing_at'] = $now;
            }
            if (empty($updateData['shipping_at']) && !$order->shipping_at) {
                $updateData['shipping_at'] = $now;
            }
            if (empty($updateData['delivered_at']) && !$order->delivered_at) {
                $updateData['delivered_at'] = $now;
            }
            if ($order->payment_method === 'cod') {
                $paymentStatus = 'paid';
                $updateData['payment_status'] = 'paid';
                $updateData['paid_at'] = $now;
            }
        } elseif ($stepStatus === 'completed') {
            if (empty($updateData['confirmed_at']) && !$order->confirmed_at) {
                $updateData['confirmed_at'] = $now;
            }
            if (empty($updateData['processing_at']) && !$order->processing_at) {
                $updateData['processing_at'] = $now;
            }
            if (empty($updateData['shipping_at']) && !$order->shipping_at) {
                $updateData['shipping_at'] = $now;
            }
            if (empty($updateData['delivered_at']) && !$order->delivered_at) {
                $updateData['delivered_at'] = $now;
            }
            if (empty($updateData['completed_at']) && !$order->completed_at) {
                $updateData['completed_at'] = $now;
            }
            if ($paymentStatus !== 'paid') {
                $paymentStatus = 'paid';
                $updateData['payment_status'] = 'paid';
                $updateData['paid_at'] = $now;
            }
        }

        if ($paymentStatus === 'paid' && !$order->paid_at && empty($updateData['paid_at'])) {
            $updateData['paid_at'] = $now;
        }

        // Xử lý ảnh bằng chứng giao hàng bưu tá gửi về kho (Proof of Delivery - POD)
        if ($request->hasFile('delivery_proof_file')) {
            $proofPath = $request->file('delivery_proof_file')->store('delivery_proofs', 'public');
            $updateData['delivery_proof_image'] = $proofPath;
            $updateData['delivery_proof_at'] = $now;
        } elseif ($request->filled('delivery_proof_image')) {
            $updateData['delivery_proof_image'] = $request->input('delivery_proof_image');
            $updateData['delivery_proof_at'] = $now;
        } elseif (in_array($validated['shipping_status'], ['delivered', 'completed']) && empty($order->delivery_proof_image)) {
            $updateData['delivery_proof_image'] = 'assets/img/delivery-proofs/sample_pod_1.jpg';
            $updateData['delivery_proof_at'] = $now;
        }

        if ($request->filled('delivery_proof_note')) {
            $updateData['delivery_proof_note'] = $request->input('delivery_proof_note');
        } elseif (in_array($validated['shipping_status'], ['delivered', 'completed']) && empty($order->delivery_proof_note)) {
            $updateData['delivery_proof_note'] = 'Bưu tá xác nhận đã trao kiện hàng tận tay khách hàng thành công và kiểm tra niêm phong nguyên vẹn.';
        }

        // Khi đơn hàng được chuyển sang "Đã giao hàng" hoặc "Hoàn tất", kích hoạt thông báo mời khách hàng tự đánh giá
        if (in_array($validated['shipping_status'], ['delivered', 'completed']) && !in_array($order->shipping_status, ['delivered', 'completed'])) {
            $updateData['review_notified'] = false;
        }

        $order->update($updateData);

        return back()->with('success', "Trạng thái đơn hàng #{$order->order_code} đã được cập nhật thành công ({$order->status_label})! Dữ liệu đã đồng bộ theo thời gian thực.");
    }

    /**
     * Đánh dấu ĐÃ THU TIỀN cho từng đơn hàng riêng biệt
     */
    public function markPaid($id)
    {
        $order = Order::findOrFail($id);
        if ($order->payment_status === 'paid') {
            return back()->with('info', "Đơn hàng #{$order->order_code} đã ở trạng thái Đã thanh toán trước đó.");
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => $order->paid_at ?: now(),
        ]);

        return back()->with('success', "Xác nhận thành công: Đơn hàng #{$order->order_code} đã được chuyển sang trạng thái ĐÃ THU TIỀN.");
    }

    /**
     * Hủy đơn hàng riêng biệt với lý do cụ thể và hoàn kho chuẩn xác
     */
    public function cancelSingleOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if (in_array($order->shipping_status, ['delivered', 'completed'], true)) {
            return back()->with('error', "Không thể hủy đơn hàng #{$order->order_code} vì hàng đã được giao đến tay khách hàng. Vui lòng sử dụng tính năng Đổi trả / Hoàn tiền (RMA).");
        }

        if ($order->shipping_status === 'cancelled') {
            return back()->with('info', "Đơn hàng #{$order->order_code} đã ở trạng thái Đã Hủy trước đó.");
        }

        $reason = $request->input('reason', $request->input('cancel_reason', 'Hủy theo yêu cầu của Quản trị viên'));
        if ($request->filled('notes')) {
            $reason = trim($reason) . ' - ' . trim($request->input('notes'));
        }

        $paymentStatus = $order->payment_status;
        if ($order->payment_status === 'paid') {
            $paymentStatus = 'refunded';
        }

        $req = new Request([
            'shipping_status' => 'cancelled',
            'payment_status' => $paymentStatus,
            'cancel_reason' => $reason,
        ]);

        return $this->updateStatus($req, $id);
    }

    /**
     * Quản trị viên duyệt và xác nhận đã chuyển khoản hoàn tiền cho khách
     */
    public function approveRefund(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $orderReturn = OrderReturn::where('order_id', $order->id)->latest()->first();

        DB::transaction(function () use ($order, $orderReturn, $request) {
            $now = now();

            if ($orderReturn) {
                $orderReturn->update([
                    'status' => 'completed',
                    'completed_at' => $now,
                    'admin_notes' => $request->input('admin_notes', 'Quản trị viên đã xác nhận hoàn tiền thành công vào tài khoản của khách hàng.'),
                ]);
            }

            // Hoàn kho nếu chưa hoàn kho
            if ($order->shipping_status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                        $prod = Product::find($item->product_id);
                        if ($prod && $prod->sold_count >= $item->quantity) {
                            $prod->decrement('sold_count', $item->quantity);
                        }

                        if (!empty($item->color) && !empty($item->size)) {
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('color', $item->color)
                                ->where('size', $item->size)
                                ->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->update([
                'payment_status' => 'refunded',
                'shipping_status' => 'cancelled',
                'status_step' => 0,
                'admin_notes' => ($order->admin_notes ? $order->admin_notes . " | " : "") . "[Đã hoàn tiền lúc {$now->format('d/m/Y H:i')}]",
            ]);
        });

        return back()->with('success', "Đã duyệt và xác nhận HOÀN TIỀN thành công cho đơn hàng #{$order->order_code}!");
    }

    /**
     * Quản trị viên từ chối yêu cầu hoàn tiền
     */
    public function rejectRefund(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $orderReturn = OrderReturn::where('order_id', $order->id)->latest()->first();

        $reason = $request->input('rejected_reason', 'Yêu cầu hoàn tiền không hợp lệ hoặc sản phẩm không đủ điều kiện đổi trả.');

        if ($orderReturn) {
            $orderReturn->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_reason' => $reason,
            ]);
        }

        $order->update([
            'admin_notes' => ($order->admin_notes ? $order->admin_notes . " | " : "") . "[Từ chối hoàn tiền: {$reason}]",
        ]);

        return back()->with('warning', "Đã từ chối yêu cầu hoàn tiền cho đơn hàng #{$order->order_code}. Lý do: {$reason}");
    }
}
