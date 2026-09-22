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

        // Query đơn hàng: Bưu tá chỉ xem đơn của chính mình, Admin có thể xem toàn bộ
        $query = Order::with(['items.product', 'user']);

        if (!$user->isAdmin()) {
            $query->where('shipper_id', $user->id);
        } elseif ($request->filled('shipper_id')) {
            $query->where('shipper_id', $request->query('shipper_id'));
        }

        // Lọc theo Tab trạng thái
        if ($tab === 'shipping') {
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

        $orders = $query->latest('shipping_at')->paginate(10)->withQueryString();

        // Thống kê nhanh cho Bưu tá
        $statQuery = Order::query();
        if (!$user->isAdmin()) {
            $statQuery->where('shipper_id', $user->id);
        }

        $deliveringCount = (clone $statQuery)->where('shipping_status', 'shipping')->count();
        $deliveredTodayCount = (clone $statQuery)->where('shipping_status', 'delivered')
            ->whereDate('delivered_at', Carbon::today())
            ->count();

        // Tổng tiền COD cần thu hôm nay của các đơn đang giao
        $codNeedToCollect = (clone $statQuery)->where('shipping_status', 'shipping')
            ->where('payment_method', 'cod')
            ->where('payment_status', '!=', 'paid')
            ->sum('total_amount');

        // Tổng tiền COD đã thu thành công hôm nay
        $codCollectedToday = (clone $statQuery)->whereIn('shipping_status', ['delivered', 'completed'])
            ->where('payment_method', 'cod')
            ->whereDate('delivered_at', Carbon::today())
            ->sum('total_amount');

        $shippersList = $user->isAdmin() ? User::where('role', 'shipper')->get() : collect();

        return view('shipper.orders.index', compact(
            'orders',
            'tab',
            'search',
            'deliveringCount',
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
        $user = Auth::user();
        $query = Order::with(['items.product', 'user']);

        if (!$user->isAdmin()) {
            $query->where('shipper_id', $user->id);
        }

        $order = $query->findOrFail($id);

        return view('shipper.orders.show', compact('order'));
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

        // Nếu là COD, tự động đánh dấu đã thu đủ tiền
        if ($order->payment_method === 'cod') {
            $updateData['payment_status'] = 'paid';
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
