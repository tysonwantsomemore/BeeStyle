<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Danh sách tất cả các yêu cầu đổi trả & hoàn tiền (RMA Dashboard)
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = OrderReturn::with(['order.items.product', 'user', 'orderItem'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('return_code', 'LIKE', "%{$search}%")
                  ->orWhere('reason', 'LIKE', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_code', 'LIKE', "%{$search}%")
                         ->orWhere('customer_name', 'LIKE', "%{$search}%")
                         ->orWhere('customer_phone', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Đếm số lượng theo từng trạng thái
        $totalCount = OrderReturn::count();
        $pendingCount = OrderReturn::where('status', 'pending')->count();
        $approvedCount = OrderReturn::where('status', 'approved')->count();
        $receivedCount = OrderReturn::where('status', 'received')->count();
        $completedCount = OrderReturn::where('status', 'completed')->count();
        $rejectedCount = OrderReturn::where('status', 'rejected')->count();

        $returns = $query->paginate(10)->withQueryString();

        return view('admin.returns.index', compact(
            'returns',
            'status',
            'search',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'receivedCount',
            'completedCount',
            'rejectedCount'
        ));
    }

    /**
     * Chi tiết một yêu cầu đổi trả & thông tin đồng bộ đơn hàng
     */
    public function show($id)
    {
        $return = OrderReturn::with(['order.items.product', 'user.orders', 'orderItem'])->findOrFail($id);
        return view('admin.returns.show', compact('return'));
    }

    /**
     * Cập nhật trạng thái xử lý yêu cầu đổi trả (Approve, Receive, Complete, Reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $return = OrderReturn::with(['order.items', 'user'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,received,completed,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'rejected_reason' => 'nullable|string|max:500',
            'restock' => 'nullable|boolean',
            'refund_amount' => 'nullable|numeric|min:0',
            'bank_ref_code' => 'nullable|string|max:100',
            'exchange_carrier' => 'nullable|string|max:100',
            'exchange_tracking_code' => 'nullable|string|max:100',
            'received_condition' => 'nullable|string|max:255',
            'warehouse_instruction' => 'nullable|string|max:500',
        ]);

        $status = $validated['status'];
        $adminNotes = $validated['admin_notes'] ?? $return->admin_notes;
        $rejectedReason = $validated['rejected_reason'] ?? $return->rejected_reason;

        DB::transaction(function () use ($return, $status, $adminNotes, $rejectedReason, $request, $validated) {
            $data = [
                'status' => $status,
                'admin_notes' => $adminNotes,
            ];

            if (!empty($validated['refund_amount'])) {
                $data['refund_amount'] = (int) $validated['refund_amount'];
            }

            // Xử lý bước 2: Duyệt yêu cầu
            if ($status === 'approved') {
                if (!$return->approved_at) {
                    $data['approved_at'] = now();
                }
                if (!empty($validated['warehouse_instruction'])) {
                    $instruction = "[Hướng dẫn gửi hàng: " . trim($validated['warehouse_instruction']) . "]";
                    $data['admin_notes'] = $instruction . ($data['admin_notes'] ? " - " . $data['admin_notes'] : "");
                }
            } 
            // Xử lý bước 3: Kho nhận hàng
            elseif ($status === 'received') {
                if (!$return->received_at) {
                    $data['received_at'] = now();
                }
                if (!empty($validated['received_condition'])) {
                    $conditionLog = "[Kiểm định kho: " . trim($validated['received_condition']) . "]";
                    $data['admin_notes'] = $conditionLog . ($data['admin_notes'] ? " - " . $data['admin_notes'] : "");
                }
            } 
            // Xử lý bước 4: Hoàn tất & Hoàn tiền / Đổi hàng
            elseif ($status === 'completed') {
                $data['completed_at'] = now();

                // Ghi nhận mã giao dịch chuyển tiền hoặc mã vận đơn đổi hàng
                if (!empty($validated['bank_ref_code'])) {
                    $bankLog = "[Mã GD Hoàn Tiền: " . trim($validated['bank_ref_code']) . "]";
                    $data['admin_notes'] = $bankLog . ($data['admin_notes'] ? " - " . $data['admin_notes'] : "");
                }
                if (!empty($validated['exchange_carrier']) && !empty($validated['exchange_tracking_code'])) {
                    $exchangeLog = "[Đổi hàng - ĐVVC: " . trim($validated['exchange_carrier']) . " | Vận đơn: " . trim($validated['exchange_tracking_code']) . "]";
                    $data['admin_notes'] = $exchangeLog . ($data['admin_notes'] ? " - " . $data['admin_notes'] : "");
                }

                // 1. Tự động nhập kho lại sản phẩm trả hàng
                if ($request->boolean('restock', true) && in_array($return->type, ['return_refund', 'refund_only'])) {
                    if ($return->order_item_id && $return->orderItem) {
                        $item = $return->orderItem;
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                        Product::where('id', $item->product_id)->decrement('sold_count', $item->quantity);

                        if (!empty($item->color) && !empty($item->size)) {
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('color', $item->color)
                                ->where('size', $item->size)
                                ->increment('stock', $item->quantity);
                        }
                    } else {
                        // Hoàn trả toàn bộ đơn hàng
                        foreach ($return->order->items as $item) {
                            if ($item->product_id) {
                                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                                Product::where('id', $item->product_id)->decrement('sold_count', $item->quantity);

                                if (!empty($item->color) && !empty($item->size)) {
                                    ProductVariant::where('product_id', $item->product_id)
                                        ->where('color', $item->color)
                                        ->where('size', $item->size)
                                        ->increment('stock', $item->quantity);
                                }
                            }
                        }
                    }
                }

                // Trường hợp đổi size/màu: Nhập kho món cũ, trừ kho size mới và TỰ ĐỘNG TẠO ĐƠN ĐỔI MỚI (Replacement Order)
                if ($return->type === 'exchange') {
                    $origOrder = $return->order;
                    $item = $return->orderItem ?: ($origOrder ? $origOrder->items->first() : null);

                    if ($item && $origOrder) {
                        $newSize = $return->exchange_size ?: $item->size;
                        $newColor = $return->exchange_color ?: $item->color;

                        if ($request->boolean('restock', true)) {
                            // Nhập kho món cũ
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('color', $item->color)
                                ->where('size', $item->size)
                                ->increment('stock', $item->quantity);

                            // Trừ kho món mới
                            ProductVariant::where('product_id', $item->product_id)
                                ->where('size', $newSize)
                                ->decrement('stock', $item->quantity);
                        }

                        // Tự động tạo Đơn hàng Đổi mới (Replacement Order)
                        $newOrderCode = 'EXC-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
                        $carrier = $validated['exchange_carrier'] ?? 'Giao Hàng Nhanh (GHN)';
                        $trackingCode = $validated['exchange_tracking_code'] ?? ('EXC' . rand(100000, 999999));

                        $exchangeOrder = \App\Models\Order::create([
                            'order_code' => $newOrderCode,
                            'user_id' => $origOrder->user_id,
                            'customer_name' => $origOrder->customer_name,
                            'customer_phone' => $origOrder->customer_phone,
                            'customer_email' => $origOrder->customer_email,
                            'shipping_address' => $origOrder->shipping_address,
                            'city' => $origOrder->city,
                            'district' => $origOrder->district,
                            'notes' => "[ĐƠN ĐỔI HÀNG TỪ ĐƠN GỐC #{$origOrder->order_code}] - Phiếu RMA #{$return->return_code}",
                            'payment_method' => 'exchange',
                            'payment_status' => 'paid',
                            'shipping_status' => 'processing',
                            'status_step' => 3,
                            'subtotal' => 0,
                            'discount_amount' => 0,
                            'shipping_fee' => 0,
                            'total_amount' => 0,
                            'admin_notes' => "[Đổi hàng từ phiếu #{$return->return_code}] Đổi sang Size {$newSize} / Màu {$newColor} - [ĐVVC: {$carrier} | Vận đơn: {$trackingCode}]",
                        ]);

                        // Tạo sản phẩm trong đơn hàng đổi mới
                        \App\Models\OrderItem::create([
                            'order_id' => $exchangeOrder->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product_name . ' [ĐỔI SANG SIZE ' . $newSize . ']',
                            'price' => 0,
                            'quantity' => $item->quantity,
                            'subtotal' => 0,
                            'image' => $item->image,
                            'color' => $newColor,
                            'size' => $newSize,
                        ]);

                        $data['admin_notes'] = "[Tạo thành công đơn đổi mới #{$newOrderCode}] " . ($data['admin_notes'] ?? '');
                    }
                }

                // 2. Cập nhật trạng thái thanh toán của Order sang Refunded
                if ($return->type === 'return_refund' || $return->type === 'refund_only') {
                    $return->order->update(['payment_status' => 'refunded']);
                }
            } 
            // Xử lý từ chối
            elseif ($status === 'rejected') {
                $data['rejected_at'] = now();
                $data['rejected_reason'] = $rejectedReason;
            }

            $return->update($data);
        });

        return back()->with('success', "Trạng thái phiếu RMA #{$return->return_code} đã được cập nhật thành công ({$return->status_label})!");
    }
}
