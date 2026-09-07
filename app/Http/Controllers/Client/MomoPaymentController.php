<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MomoPaymentController extends Controller
{
    protected MomoService $momoService;

    public function __construct(MomoService $momoService)
    {
        $this->momoService = $momoService;
    }

    /**
     * API POST /api/payments/momo/create
     * Khởi tạo giao dịch MoMo Sandbox cho đơn hàng
     */
    public function create(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);

        $order = Order::where('order_code', $request->order_code)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng #' . $request->order_code,
            ], 404);
        }

        if (in_array(strtoupper((string)$order->payment_status), ['PAID', 'COMPLETED'])) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng này đã được thanh toán trước đó.',
                'order_code' => $order->order_code,
                'status' => 'PAID',
            ], 400);
        }

        if ($order->shipping_status === 'cancelled' || strtoupper((string)$order->payment_status) === 'CANCELLED') {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng này đã bị hủy.',
                'order_code' => $order->order_code,
                'status' => 'CANCELLED',
            ], 400);
        }

        // TÍNH TOÁN LẠI CHÍNH XÁC TỪ DATABASE SẢN PHẨM (KHÔNG TIN TƯỞNG FRONTEND)
        $order->loadMissing('items.product');
        $dbSubtotal = 0;
        foreach ($order->items as $item) {
            $prod = Product::find($item->product_id);
            $itemPrice = $prod ? (int)$prod->price : (int)$item->price;
            $dbSubtotal += $itemPrice * (int)$item->quantity;
        }
        $dbTotal = max(0, $dbSubtotal - (int)$order->discount_amount + (int)$order->shipping_fee);
        if ($dbTotal > 0 && $dbTotal !== (int)$order->total_amount) {
            $order->update(['total_amount' => $dbTotal, 'subtotal' => $dbSubtotal]);
        }

        $momoResult = $this->momoService->createPayment($order);

        if (!empty($momoResult['success']) && !empty($momoResult['payUrl'])) {
            return response()->json([
                'success' => true,
                'order_code' => $order->order_code,
                'orderId' => $momoResult['orderId'] ?? null,
                'deeplink' => $momoResult['deeplink'] ?? null,
                'payUrl' => $momoResult['payUrl'],
                'qrCodeUrl' => $momoResult['qrCodeUrl'] ?? null,
                'applink' => $momoResult['applink'] ?? null,
                'message' => 'Khởi tạo thanh toán MoMo thành công.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $momoResult['message'] ?? 'Không thể kết nối đến máy chủ MoMo Sandbox.',
            'resultCode' => $momoResult['resultCode'] ?? -1,
        ], 502);
    }

    /**
     * API POST /api/payments/momo/ipn
     * Webhook nhận kết quả thanh toán từ máy chủ MoMo (Nguồn xác nhận thanh toán chính)
     */
    public function ipn(Request $request)
    {
        $data = $request->all();
        Log::info('[MoMo IPN Webhook Received]', $data);

        // 1. Kiểm tra chữ ký số HMAC SHA-256 từ MoMo
        if (!$this->momoService->verifySignature($data)) {
            Log::warning('[MoMo IPN] Invalid Signature', $data);
            return response()->json(['resultCode' => 11007, 'message' => 'Invalid signature'], 400);
        }

        // 2. Trích xuất mã đơn hàng BeeStyle
        $orderCode = $this->momoService->extractOrderCode($data);
        $order = Order::with('items')->where('order_code', $orderCode)->first();

        if (!$order) {
            Log::warning("[MoMo IPN] Order Not Found: {$orderCode}", $data);
            return response()->json(['resultCode' => 11000, 'message' => 'Order not found'], 404);
        }

        // 3. Kiểm tra số tiền giao dịch khớp với đơn hàng
        $amount = (int)($data['amount'] ?? 0);
        if ($amount !== (int)round($order->total_amount)) {
            Log::error("[MoMo IPN] Amount Mismatch: Received {$amount}, expected {$order->total_amount}");
            return response()->json(['resultCode' => 11008, 'message' => 'Amount mismatch'], 400);
        }

        $resultCode = (int)($data['resultCode'] ?? -1);
        $transId = (string)($data['transId'] ?? '');

        // 4. Xử lý Idempotency: Nếu đơn đã được cập nhật PAID trước đó, trả về 204 ngay
        if (strtoupper((string)$order->payment_status) === 'PAID') {
            Log::info("[MoMo IPN] Order #{$orderCode} already marked as PAID. Skipping duplicate processing.");
            return response()->noContent();
        }

        DB::beginTransaction();
        try {
            if ($resultCode === 0) {
                // THANH TOÁN THÀNH CÔNG
                $order->update([
                    'payment_status' => 'PAID',
                    'momo_trans_id' => $transId,
                    'shipping_status' => 'processing',
                    'status_step' => 2,
                ]);

                DB::commit();

                // Gửi email hóa đơn sau khi commit thành công
                $this->sendOrderInvoiceEmail($order);
                Log::info("[MoMo IPN] Order #{$orderCode} successfully marked as PAID. TransId: {$transId}");
            } else {
                // GIAO DỊCH THẤT BẠI / KHÁCH HỦY / HẾT HẠN
                $newStatus = match ($resultCode) {
                    1006 => 'CANCELLED',
                    49 => 'EXPIRED',
                    default => 'PAYMENT_FAILED',
                };

                $order->update([
                    'payment_status' => $newStatus,
                    'momo_trans_id' => $transId ?: null,
                    'shipping_status' => in_array($newStatus, ['CANCELLED', 'EXPIRED']) ? 'cancelled' : $order->shipping_status,
                ]);

                // Hoàn lại kho nếu khách hủy hoặc hết hạn giao dịch
                if (in_array($newStatus, ['CANCELLED', 'EXPIRED'])) {
                    $this->restoreStock($order);
                }

                DB::commit();
                Log::warning("[MoMo IPN] Order #{$orderCode} failed with code {$resultCode}. Status: {$newStatus}");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[MoMo IPN Exception] " . $e->getMessage());
            return response()->json(['resultCode' => 99, 'message' => $e->getMessage()], 500);
        }

        return response()->noContent();
    }

    /**
     * GET /payment/momo/result
     * Trang hiển thị kết quả giao dịch sau khi khách hàng hoàn tất hoặc hủy trên MoMo
     */
    public function result(Request $request)
    {
        $data = $request->all();
        Log::info('[MoMo Browser Redirect Received]', $data);

        $orderCode = $this->momoService->extractOrderCode($data);
        $order = $orderCode ? Order::with(['items.product', 'user'])->where('order_code', $orderCode)->first() : null;

        $resultCode = (int)$request->input('resultCode', -1);
        $message = $request->input('message', 'Giao dịch chưa hoàn tất');
        $transId = $request->input('transId', '');

        // Kiểm tra chữ ký số nếu có signature
        $isSignatureValid = true;
        if (!empty($data['signature'])) {
            $isSignatureValid = $this->momoService->verifySignature($data);
        }

        // Nếu đơn hàng chưa cập nhật thành PAID nhưng resultCode == 0:
        // Thực hiện tra cứu trực tiếp máy chủ MoMo (Server-to-Server Query) để bảo vệ tính toàn vẹn
        if ($order && $resultCode === 0 && strtoupper((string)$order->payment_status) !== 'PAID') {
            $momoOrderId = $data['orderId'] ?? ($order->order_code . '_' . time());
            $queryResult = $this->momoService->queryTransaction($momoOrderId, $data['requestId'] ?? null);

            if (isset($queryResult['resultCode']) && (int)$queryResult['resultCode'] === 0) {
                // Xác thực MoMo Server đã ghi nhận thành công
                $order->update([
                    'payment_status' => 'PAID',
                    'momo_trans_id' => $queryResult['transId'] ?? $transId,
                    'shipping_status' => 'processing',
                    'status_step' => 2,
                ]);
                $this->sendOrderInvoiceEmail($order);
            }
        }

        // Nếu khách hàng hủy giao dịch và đơn chưa hủy
        if ($order && $resultCode !== 0 && strtoupper((string)$order->payment_status) === 'PENDING_PAYMENT') {
            $newStatus = match ($resultCode) {
                1006 => 'CANCELLED',
                49 => 'EXPIRED',
                default => 'PAYMENT_FAILED',
            };
            $order->update([
                'payment_status' => $newStatus,
                'shipping_status' => in_array($newStatus, ['CANCELLED', 'EXPIRED']) ? 'cancelled' : $order->shipping_status,
            ]);
            if (in_array($newStatus, ['CANCELLED', 'EXPIRED'])) {
                $this->restoreStock($order);
            }
        }

        return view('client.payment.momo_result', [
            'order' => $order,
            'resultCode' => $resultCode,
            'message' => $message,
            'transId' => $transId,
            'isSignatureValid' => $isSignatureValid,
        ]);
    }

    /**
     * Hoàn lại số lượng tồn kho sản phẩm và biến thể
     */
    protected function restoreStock(Order $order): void
    {
        try {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                Product::where('id', $item->product_id)->decrement('sold_count', $item->quantity);

                if (!empty($item->color) && !empty($item->size)) {
                    ProductVariant::where('product_id', $item->product_id)
                        ->where('color', $item->color)
                        ->where('size', $item->size)
                        ->increment('stock', $item->quantity);
                }
            }
        } catch (\Exception $e) {
            Log::error("[MoMo restoreStock Error] " . $e->getMessage());
        }
    }

    /**
     * Gửi email hóa đơn đơn hàng
     */
    protected function sendOrderInvoiceEmail(Order $order): void
    {
        if (empty($order->customer_email)) {
            return;
        }

        try {
            $order->loadMissing(['items.product', 'user']);
            Mail::send('emails.order_invoice', ['order' => $order], function ($message) use ($order) {
                $message->to($order->customer_email, $order->customer_name)
                    ->subject("【BeeStyle】Xác nhận Hóa Đơn Điện Tử Đơn Hàng #{$order->order_code}");
            });
        } catch (\Exception $e) {
            Log::warning("[MoMo sendOrderInvoiceEmail Error] " . $e->getMessage());
        }
    }
}