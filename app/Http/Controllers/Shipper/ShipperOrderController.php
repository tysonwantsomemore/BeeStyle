<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipperOrderController extends Controller
{
    /**
     * Bảng danh sách đơn hàng được phân công riêng cho Bưu tá
     * (Bưu tá chỉ nhìn thấy các đơn hàng được gán cho chính tài khoản của mình)
     */
    public function index(Request $request)
    {
        // Tự động quét hoàn tất đơn hàng quá 7 ngày
        Order::autoCompleteEligibleDeliveredOrders();

        $user = Auth::user();
        $tab = $request->query('tab', 'shipping');
        $search = trim((string)$request->query('q', ''));

        // Query đơn hàng: Bưu tá xem đơn được gán (hoặc đơn chờ lấy hàng tại kho), Admin có thể xem toàn bộ hoặc lọc theo bưu tá
        $query = Order::with(['items.product', 'user']);

        if (!$user->isAdmin()) {
            if ($tab === 'pickup') {
                $query->where(function ($q) use ($user) {
                    $q->where('shipper_id', $user->id)
                      ->orWhereNull('shipper_id');
                });
            } else {
                $query->where('shipper_id', $user->id);
            }
        } elseif ($request->filled('shipper_id')) {
            $query->where('shipper_id', $request->query('shipper_id'));
        }

        // Lọc theo Tab trạng thái
        if ($tab === 'pickup') {
            $query->whereIn('shipping_status', ['confirmed', 'processing']);
        } elseif ($tab === 'shipping') {
            $query->where('shipping_status', 'shipping');
        } elseif ($tab === 'delivered') {
            $query->where('shipping_status', 'delivered');
        } elseif ($tab === 'completed') {
            $query->where('shipping_status', 'completed');
        } elseif ($tab === 'all') {
            // Xem tất cả
        }

        // Tìm kiếm theo mã đơn, người nhận, sđt, địa chỉ
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%")
                  ->orWhere('tracking_code', 'like', "%{$search}%");
            });
        }

        // Luôn hiển thị các đơn hàng mới nhất trên cùng
        $orders = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Thống kê nhanh cho Bưu tá
        $statQuery = Order::query();
        if (!$user->isAdmin()) {
            $statQuery->where('shipper_id', $user->id);
        } elseif ($request->filled('shipper_id')) {
            $statQuery->where('shipper_id', $request->query('shipper_id'));
        }

        $pickupStatQuery = Order::query();
        if (!$user->isAdmin()) {
            $pickupStatQuery->where(function ($q) use ($user) {
                $q->where('shipper_id', $user->id)
                  ->orWhereNull('shipper_id');
            });
        } elseif ($request->filled('shipper_id')) {
            $pickupStatQuery->where('shipper_id', $request->query('shipper_id'));
        }

        $pickupCount = $pickupStatQuery->whereIn('shipping_status', ['confirmed', 'processing'])->count();
        $deliveringCount = (clone $statQuery)->where('shipping_status', 'shipping')->count();
        $deliveredCount = (clone $statQuery)->where('shipping_status', 'delivered')->count();
        $deliveredTodayCount = (clone $statQuery)->where('shipping_status', 'delivered')
            ->whereDate('delivered_at', Carbon::today())
            ->count();

        // Tổng tiền COD cần thu hôm nay của các đơn đang giao (bao gồm đơn COD và đơn cọc 50% còn lại)
        $shippingOrders = (clone $statQuery)->where('shipping_status', 'shipping')->get();
        $codNeedToCollect = $shippingOrders->sum(function ($order) {
            if ($order->payment_status === 'paid') {
                return 0;
            }
            if ($order->payment_status === 'deposit_paid' || ($order->is_deposit_required && $order->deposit_status === 'paid')) {
                return $order->remaining_amount > 0 ? $order->remaining_amount : ($order->total_amount - $order->deposit_amount);
            }
            if ($order->payment_method === 'cod') {
                return $order->is_deposit_required ? ($order->remaining_amount ?: ($order->total_amount - $order->deposit_amount)) : $order->total_amount;
            }
            return 0;
        });

        // Tổng tiền COD đã thu thành công hôm nay
        $collectedOrders = (clone $statQuery)->whereIn('shipping_status', ['delivered', 'completed'])
            ->whereDate('delivered_at', Carbon::today())
            ->get();
        $codCollectedToday = $collectedOrders->sum(function ($order) {
            if ($order->is_deposit_required || $order->payment_status === 'deposit_paid') {
                return $order->total_amount - $order->deposit_amount;
            }
            if ($order->payment_method === 'cod') {
                return $order->total_amount;
            }
            return 0;
        });

        $shippersList = $user->isAdmin() ? User::where('role', 'shipper')->get() : collect();

        return view('shipper.orders.index', compact(
            'orders',
            'tab',
            'search',
            'pickupCount',
            'deliveringCount',
            'deliveredCount',
            'deliveredTodayCount',
            'codNeedToCollect',
            'codCollectedToday',
            'shippersList'
        ));
    }

    /**
     * Chi tiết đơn hàng bưu tá phụ trách
     */
    public function show($id)
    {
        // Tự động quét hoàn tất đơn hàng quá 7 ngày
        Order::autoCompleteEligibleDeliveredOrders();

        $user = Auth::user();
        $query = Order::with(['items.product', 'user']);

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('shipper_id', $user->id)
                  ->orWhereNull('shipper_id');
            });
        }

        $order = $query->findOrFail($id);

        return view('shipper.orders.show', compact('order'));
    }

    /**
     * Bưu tá tiếp nhận đơn hàng tại kho và bắt đầu đi giao
     */
    public function startDelivery(Request $request, $id)
    {
        $user = Auth::user();
        $query = Order::query();

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('shipper_id', $user->id)
                  ->orWhereNull('shipper_id');
            });
        }

        $order = $query->findOrFail($id);

        if (!in_array($order->shipping_status, ['confirmed', 'processing'])) {
            return back()->with('error', 'Chỉ các đơn hàng đang ở trạng thái Chuẩn Bị Hàng / Đang Đóng Gói mới có thể tiếp nhận giao!');
        }

        $now = now();
        $order->update([
            'shipping_status'  => 'shipping',
            'status_step'      => 4,
            'shipper_id'       => $user->id,
            'shipping_carrier' => $order->shipping_carrier ?: ('BeeStyle Express - ' . $user->name),
            'tracking_code'    => $order->tracking_code ?: ('BEE-' . strtoupper(\Illuminate\Support\Str::random(8))),
            'shipping_at'      => $order->shipping_at ?: $now,
            'confirmed_at'     => $order->confirmed_at ?: $now,
            'processing_at'    => $order->processing_at ?: $now,
        ]);

        return redirect()->route('shipper.orders.index', ['tab' => 'shipping'])
            ->with('success', "Đã tiếp nhận đơn hàng #{$order->order_code}! Bưu tá bắt đầu di chuyển phát hàng tới khách.");
    }

    /**
     * Bưu tá xác nhận ĐÃ GIAO HÀNG THÀNH CÔNG (BẮT BUỘC 1 ẢNH POD)
     */
    public function deliver(Request $request, $id)
    {
        $user = Auth::user();
        $query = Order::with('items');

        if (!$user->isAdmin()) {
            $query->where('shipper_id', $user->id);
        }

        $order = $query->findOrFail($id);

        if ($order->shipping_status !== 'shipping') {
            return back()->with('error', 'Chỉ các đơn hàng đang ở trạng thái Đang Giao Hàng mới có thể xác nhận đã giao!');
        }

        // BẮT BUỘC BƯU TÁ PHẢI CHỤP HOẶC TẢI LÊN 1 ẢNH BẰNG CHỨNG GIAO HÀNG (POD)
        $validated = $request->validate([
            'delivery_proof_file' => 'required_without:delivery_proof_image|nullable|file|image|max:10240',
            'delivery_proof_image' => 'nullable|string|max:255',
            'delivery_proof_note' => 'nullable|string|max:1000',
        ], [
            'delivery_proof_file.required_without' => 'BƯU TÁ BẮT BUỘC PHẢI CHỤP HOẶC TẢI LÊN 1 ẢNH BẰNG CHỨNG GIAO HÀNG (POD) để xác nhận đã giao!',
            'delivery_proof_file.image' => 'Tập tin tải lên phải là hình ảnh (JPG, PNG, WEBP).',
            'delivery_proof_file.max' => 'Dung lượng ảnh tối đa 10MB.',
        ]);

        $now = now();
        $proofPath = $order->delivery_proof_image;

        if ($request->hasFile('delivery_proof_file')) {
            $proofPath = $request->file('delivery_proof_file')->store('delivery_proofs', 'public');
        } elseif ($request->filled('delivery_proof_image')) {
            $proofPath = $request->input('delivery_proof_image');
        }

        if (empty($proofPath)) {
            return back()->with('error', 'Vui lòng cung cấp 1 hình ảnh bằng chứng giao hàng (chụp ảnh gói hàng đã giao hoặc khách nhận).');
        }

        $note = $request->input('delivery_proof_note') ?: 'Bưu tá ' . $user->name . ' đã trao kiện hàng tận tay khách hàng thành công.';

        $updateData = [
            'shipping_status'      => 'delivered',
            'status_step'          => 5,
            'delivered_at'         => $now,
            'delivery_proof_image' => $proofPath,
            'delivery_proof_note'  => $note,
            'delivery_proof_at'    => $now,
            'review_notified'      => false,
        ];

        // Nếu là COD hoặc đơn có cọc 50%, tự động đánh dấu đã thu đủ tiền mặt
        if ($order->payment_method === 'cod' || $order->is_deposit_required || $order->payment_status === 'deposit_paid') {
            $updateData['payment_status'] = 'paid';
            $updateData['remaining_amount'] = 0;
            $updateData['paid_at'] = $now;
        }

        $order->update($updateData);

        return redirect()->route('shipper.orders.index', ['tab' => 'shipping'])
            ->with('success', "Xác nhận thành công! Đơn hàng #{$order->order_code} đã giao thành công và lưu ảnh bằng chứng (POD).");
    }

    /**
     * Bưu tá báo cáo sự cố / Hẹn lại ngày giao
     */
    public function reportIssue(Request $request, $id)
    {
        $user = Auth::user();
        $query = Order::query();

        if (!$user->isAdmin()) {
            $query->where('shipper_id', $user->id);
        }

        $order = $query->findOrFail($id);

        $request->validate([
            'issue_type' => 'required|string',
            'issue_note' => 'required|string|max:500',
        ]);

        $noteText = "[BƯU TÁ BÁO CÁO: " . $request->input('issue_type') . " - " . now()->format('H:i d/m') . "] " . $request->input('issue_note');

        $order->update([
            'admin_notes' => $noteText . ($order->admin_notes ? " | " . $order->admin_notes : ""),
        ]);

        return back()->with('success', 'Đã lưu ghi chú báo cáo sự cố của đơn hàng.');
    }
}
