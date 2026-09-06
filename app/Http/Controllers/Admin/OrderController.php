<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng với Bộ Lọc Đa Tầng (Multi-Filter Toolbar) chuẩn TMĐT chuyên nghiệp
     */
    public function index(Request $request)
    {
        $query = $this->getFilteredOrdersQuery($request);
        $orders = $query->paginate(15)->withQueryString();

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
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts', 'filters', 'carriers'));
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

        $query = Order::with(['items.product', 'user'])->orderBy('id', 'desc');

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

        $orders = Order::with(['items.product', 'user'])
            ->whereIn('id', (array)$orderIds)
            ->orderBy('id', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        return view('admin.orders.bulk-print', compact('orders'));
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
            'action' => 'required|string|in:confirm,processing,shipping,delivered,completed,mark_paid,cancel',
        ]);

        $orderIds = $validated['order_ids'];
        $action = $validated['action'];
        $now = now();

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
                        if (in_array($order->shipping_status, ['pending', 'confirmed', 'processing'])) {
                            $updateData['shipping_status'] = 'shipping';
                            $updateData['status_step'] = 4;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            if (!$order->processing_at) $updateData['processing_at'] = $now;
                            $updateData['shipping_at'] = $order->shipping_at ?: $now;
                        }
                        break;

                    case 'delivered':
                        if (in_array($order->shipping_status, ['pending', 'confirmed', 'processing', 'shipping'])) {
                            $updateData['shipping_status'] = 'delivered';
                            $updateData['status_step'] = 5;
                            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
                            if (!$order->processing_at) $updateData['processing_at'] = $now;
                            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
                            $updateData['delivered_at'] = $order->delivered_at ?: $now;
                            if ($order->payment_method === 'cod') {
                                $updateData['payment_status'] = 'paid';
                                $updateData['paid_at'] = $order->paid_at ?: $now;
                            }
                            $updateData['review_notified'] = false;
                        }
                        break;

                    case 'completed':
                        if ($order->shipping_status !== 'cancelled') {
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
                            if ($order->shipping_status !== 'completed' && $order->user_id) {
                                $user = User::find($order->user_id);
                                if ($user) {
                                    $earnedPoints = (int)floor($order->total_amount / 10000);
                                    $user->increment('points', $earnedPoints);
                                    $user->increment('total_spent', $order->total_amount);
                                }
                            }
                        }
                        break;

                    case 'mark_paid':
                        if ($order->payment_status !== 'paid') {
                            $updateData['payment_status'] = 'paid';
                            $updateData['paid_at'] = $order->paid_at ?: $now;
                        }
                        break;

                    case 'cancel':
                        if ($order->shipping_status !== 'cancelled') {
                            $updateData['shipping_status'] = 'cancelled';
                            $updateData['status_step'] = 0;
                            $updateData['cancelled_at'] = $now;
                            $updateData['cancelled_by'] = 'admin';
                            $updateData['cancel_reason'] = 'Hủy hàng loạt bởi Quản trị viên';

                            // Hoàn kho cho sản phẩm
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

                            // Trừ lại điểm thưởng nếu đơn từng hoàn tất
                            if (in_array($order->shipping_status, ['completed', 'delivered']) && $order->user_id) {
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
            'mark_paid' => 'Đánh dấu đã thanh toán',
            'cancel' => 'Hủy đơn & hoàn kho',
        ];

        $actionName = $actionLabels[$action] ?? 'Cập nhật';

        return back()->with('success', "Thành công! Đã thực hiện thao tác '{$actionName}' đồng bộ cho {$count} đơn hàng cùng một lúc.");
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'returns'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'shipping_status' => 'required|string|in:pending,confirmed,processing,shipping,delivered,completed,cancelled',
            'payment_status' => 'nullable|string|in:unpaid,paid,refunded,pending,cancelled,expired',
            'shipping_carrier' => 'nullable|string|max:100',
            'tracking_code' => 'nullable|string|max:100',
            'admin_notes' => 'nullable|string|max:1000',
            'cancel_reason' => 'nullable|string|max:500',
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

        // Ghi nhận mốc thời gian (ngày & giờ cụ thể) cho từng bước khi xác nhận
        $stepStatus = $validated['shipping_status'];
        $now = now();

        if ($stepStatus === 'confirmed') {
            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
        } elseif ($stepStatus === 'processing') {
            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
            if (!$order->processing_at) $updateData['processing_at'] = $now;
        } elseif ($stepStatus === 'shipping') {
            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
            if (!$order->processing_at) $updateData['processing_at'] = $now;
            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
        } elseif ($stepStatus === 'delivered') {
            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
            if (!$order->processing_at) $updateData['processing_at'] = $now;
            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
            if (!$order->delivered_at) $updateData['delivered_at'] = $now;
        } elseif ($stepStatus === 'completed') {
            if (!$order->confirmed_at) $updateData['confirmed_at'] = $now;
            if (!$order->processing_at) $updateData['processing_at'] = $now;
            if (!$order->shipping_at) $updateData['shipping_at'] = $now;
            if (!$order->delivered_at) $updateData['delivered_at'] = $now;
            if (!$order->completed_at) $updateData['completed_at'] = $now;
        }

        if ($paymentStatus === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = $now;
        }

        // Khi đơn hàng được chuyển sang "Đã giao hàng" hoặc "Hoàn tất", kích hoạt thông báo mời khách hàng tự đánh giá
        if (in_array($validated['shipping_status'], ['delivered', 'completed']) && !in_array($order->shipping_status, ['delivered', 'completed'])) {
            $updateData['review_notified'] = false;
        }

        $order->update($updateData);

        return back()->with('success', "Trạng thái đơn hàng #{$order->order_code} đã được cập nhật thành công ({$order->status_label})! Dữ liệu đã đồng bộ theo thời gian thực.");
    }
}
