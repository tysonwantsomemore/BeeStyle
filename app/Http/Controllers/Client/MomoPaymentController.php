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
    /**
     * API POST /api/payments/momo/ipn
     * Webhook nhận kết quả thanh toán từ máy chủ MoMo (Nguồn xác nhận thanh toán chính)
     * Chuẩn theo atm/ipn_momo.php
     */
    public function ipn(Request $request)
    {
        $data = $request->all();
        Log::info('[MoMo IPN Webhook Received]', $data);

        // 1. Kiểm tra chữ ký số HMAC SHA-256 từ MoMo theo chuẩn atm/ipn_momo.php
        $isSignatureValid = $this->momoService->verifySignature($data);
        $debugger = $this->momoService->getAtmDebuggerData($data);

        if (!$isSignatureValid) {
            Log::warning('[MoMo IPN] Invalid Signature', $data);
            return response()->json([
                'resultCode' => 11007,
                'message' => 'ERROR! Fail checksum',
                'debugger' => $debugger
            ], 400);
        }

        // 2. Trích xuất mã đơn hàng BeeStyle
        $orderCode = $this->momoService->extractOrderCode($data);
        $order = Order::with('items')->where('order_code', $orderCode)->first();

        if (!$order) {
            Log::warning("[MoMo IPN] Order Not Found: {$orderCode}", $data);
            return response()->json([
                'resultCode' => 11000,
                'message' => 'Order not found',
                'debugger' => $debugger
            ], 404);
        }

        // 3. Kiểm tra số tiền giao dịch khớp với đơn hàng (hoặc tiền cọc nếu có)
        $amount = (int)($data['amount'] ?? 0);
        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        $expectedAmount = $isDeposit ? (int)round($order->deposit_amount) : (int)round($order->total_amount);

        if ($amount > 0 && abs($amount - $expectedAmount) > 100) {
            Log::error("[MoMo IPN] Amount Mismatch: Received {$amount}, expected {$expectedAmount}");
            return response()->json([
                'resultCode' => 11008,
                'message' => 'Amount mismatch',
                'debugger' => $debugger
            ], 400);
        }

        $resultCode = (int)($data['resultCode'] ?? ($data['errorCode'] ?? -1));
        $transId = (string)($data['transId'] ?? '');

        // 4. Xử lý Idempotency: Nếu đơn đã được cập nhật PAID trước đó, trả về 200 ngay
        if (in_array(strtoupper((string)$order->payment_status), ['PAID', 'COMPLETED', 'DEPOSIT_PAID'])) {
            Log::info("[MoMo IPN] Order #{$orderCode} already marked as PAID. Skipping duplicate processing.");
            return response()->json([
                'resultCode' => 0,
                'message' => 'Received payment result success (Order already paid)',
                'debugger' => $debugger
            ]);
        }

        DB::beginTransaction();
        try {
            if ($resultCode === 0) {
                // THANH TOÁN THÀNH CÔNG
                if ($isDeposit) {
                    $order->update([
                        'deposit_status' => 'paid',
                        'deposit_paid_at' => now(),
                        'payment_status' => 'deposit_paid',
                        'momo_trans_id' => $transId,
                        'shipping_status' => 'processing',
                        'status_step' => 3,
                        'confirmed_at' => $order->confirmed_at ?: now(),
                        'processing_at' => now(),
                    ]);
                } else {
                    $order->update([
                        'payment_status' => 'paid',
                        'momo_trans_id' => $transId,
                        'shipping_status' => 'processing',
                        'status_step' => 3,
                        'paid_at' => now(),
                        'confirmed_at' => $order->confirmed_at ?: now(),
                        'processing_at' => now(),
                    ]);
                }

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
            return response()->json([
                'resultCode' => 99,
                'message' => $e->getMessage(),
                'debugger' => $debugger
            ], 500);
        }

        return response()->json([
            'resultCode' => 0,
            'message' => 'Received payment result success',
            'debugger' => $debugger
        ]);
    }

    /**
     * GET /payment/momo/result
     * Trang hiển thị kết quả giao dịch sau khi khách hàng hoàn tất hoặc hủy trên MoMo
     * Chuẩn theo atm/result_atm.php
     */
    public function result(Request $request)
    {
        $data = $request->all();
        Log::info('[MoMo Browser Redirect Received]', $data);

        $momoConfig = $this->momoService->getConfig();

        // Trích xuất toàn bộ tham số chuẩn theo atm/result_atm.php
        $partnerCode  = $request->input('partnerCode', $momoConfig['partnerCode']);
        $accessKey    = $request->input('accessKey', $momoConfig['accessKey']);
        $orderId      = $request->input('orderId', '');
        $localMessage = $request->input('localMessage', '');
        $message      = $request->input('message', '');
        $transId      = $request->input('transId', '');
        $orderInfo    = $request->input('orderInfo', '');
        $amount       = $request->input('amount', '');
        $errorCode    = $request->input('errorCode', $request->input('resultCode', ''));
        $resultCode   = (int) ($request->input('resultCode', $request->input('errorCode', -1)));
        $responseTime = $request->input('responseTime', '');
        $requestId    = $request->input('requestId', '');
        $extraData    = $request->input('extraData', '');
        $payType      = $request->input('payType', 'napas');
        $orderType    = $request->input('orderType', 'momo_wallet');
        $m2signature  = $request->input('signature', '');

        // Trích xuất và tìm đơn hàng tương ứng
        $orderCode = $this->momoService->extractOrderCode($data);
        $order = $orderCode ? Order::with(['items.product', 'user'])->where('order_code', $orderCode)->first() : null;

        // Tính toán chuỗi Checksum và Chữ ký đối tác theo atm/result_atm.php
        $debugger = $this->momoService->getAtmDebuggerData($data);
        $isSignatureValid = $debugger['isSignatureValid'];

        $isSuccess = ($resultCode === 0 || $errorCode === '0') && $isSignatureValid;

        // Cập nhật trạng thái đơn hàng nếu hợp lệ và chưa cập nhật PAID
        if ($order && $isSuccess) {
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            if (strtoupper((string)$order->payment_status) !== 'PAID' && strtoupper((string)$order->payment_status) !== 'DEPOSIT_PAID') {
                if ($isDeposit) {
                    $order->update([
                        'deposit_status' => 'paid',
                        'deposit_paid_at' => now(),
                        'payment_status' => 'deposit_paid',
                        'momo_trans_id' => $transId,
                        'shipping_status' => 'processing',
                        'status_step' => 3,
                        'confirmed_at' => $order->confirmed_at ?: now(),
                        'processing_at' => now(),
                    ]);
                } else {
                    $order->update([
                        'payment_status' => 'paid',
                        'momo_trans_id' => $transId,
                        'shipping_status' => 'processing',
                        'status_step' => 3,
                        'paid_at' => now(),
                        'confirmed_at' => $order->confirmed_at ?: now(),
                        'processing_at' => now(),
                    ]);
                }
                $this->sendOrderInvoiceEmail($order);
            }
        } elseif ($order && !$isSuccess && in_array(strtoupper((string)$order->payment_status), ['PENDING_PAYMENT', 'UNPAID'])) {
            // Xử lý đơn hàng khi khách hủy hoặc giao dịch thất bại
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
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'orderId' => $orderId,
            'localMessage' => $localMessage,
            'message' => $message,
            'transId' => $transId,
            'orderInfo' => $orderInfo,
            'amount' => $amount,
            'errorCode' => $errorCode,
            'resultCode' => $resultCode,
            'responseTime' => $responseTime,
            'requestId' => $requestId,
            'extraData' => $extraData,
            'payType' => $payType,
            'orderType' => $orderType,
            'm2signature' => $m2signature,
            'isSignatureValid' => $isSignatureValid,
            'isSuccess' => $isSuccess,
            'debugger' => $debugger,
        ]);
    }

    /**
     * GET /payment/momo/query
     * Giao diện tra cứu trạng thái giao dịch MoMo trực tiếp (Chuẩn theo atm/query_transaction.php)
     */
    public function queryView(Request $request)
    {
        $momoConfig = $this->momoService->getConfig();
        $orderId = $request->input('orderId', '');

        return view('client.payment.momo_query', [
            'partnerCode' => $momoConfig['partnerCode'],
            'orderId' => $orderId,
            'response' => null,
            'debugger' => null,
        ]);
    }

    /**
     * POST /payment/momo/query
     * Thực hiện tra cứu trạng thái giao dịch MoMo trực tiếp (Chuẩn theo atm/query_transaction.php)
     */
    public function querySubmit(Request $request)
    {
        $request->validate([
            'orderId' => 'required|string',
        ]);

        $orderId = $request->input('orderId');
        $momoConfig = $this->momoService->getConfig();
        $queryResult = $this->momoService->queryTransaction($orderId);

        return view('client.payment.momo_query', [
            'partnerCode' => $momoConfig['partnerCode'],
            'orderId' => $orderId,
            'response' => json_encode($queryResult['data'] ?? $queryResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'queryResult' => $queryResult,
            'isPassChecksum' => $queryResult['isPassChecksum'] ?? false,
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