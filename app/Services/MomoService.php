<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MomoService
{
    protected string $partnerCode;
    protected string $accessKey;
    protected string $secretKey;
    protected string $endpoint;
    protected string $redirectUrl;
    protected string $ipnUrl;

    public function __construct()
    {
        $this->partnerCode = config('momo.partner_code', env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'));
        $this->accessKey = config('momo.access_key', env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'));
        $this->secretKey = config('momo.secret_key', env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'));
        $this->endpoint = config('momo.api_endpoint', env('MOMO_API_ENDPOINT', env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create')));
        $this->redirectUrl = config('momo.redirect_url', env('MOMO_REDIRECT_URL', 'http://127.0.0.1:8000/payment/momo/result'));
        $this->ipnUrl = config('momo.ipn_url', env('MOMO_IPN_URL', 'http://127.0.0.1:8000/api/payments/momo/ipn'));
    }

    /**
     * Tạo yêu cầu thanh toán MoMo Sandbox (Gateway v2 API)
     *
     * @param Order $order
     * @return array
     */
    public function createPayment(Order $order): array
    {
        try {
            // Tạo unique orderId cho MoMo để tránh lỗi trùng lặp mã đơn khi khách thử thanh toán lại
            $orderId = $order->order_code . '_' . time();
            $requestId = (string) Str::uuid();
            $amount = (int) round($order->total_amount);
            $orderInfo = "Thanh toan don hang #" . $order->order_code . " tai BeeStyle";
            $extraData = base64_encode(json_encode(['order_code' => $order->order_code]));
            $requestType = "captureWallet";

            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $this->ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $this->redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $payload = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => 'BeeStyle Store',
                'storeId' => 'BeeStyleStore',
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $this->redirectUrl,
                'ipnUrl' => $this->ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];

            Log::info("MoMo Sandbox Create Payment Request for Order #{$order->order_code}", [
                'endpoint' => $this->endpoint,
                'orderId' => $orderId,
                'amount' => $amount
            ]);

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->post($this->endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("MoMo Sandbox Response for Order #{$order->order_code}", $data);

                if (isset($data['resultCode']) && (int)$data['resultCode'] === 0) {
                    return [
                        'success' => true,
                        'payUrl' => $data['payUrl'] ?? null,
                        'qrCodeUrl' => $data['qrCodeUrl'] ?? null,
                        'deeplink' => $data['deeplink'] ?? null,
                        'orderId' => $orderId,
                        'message' => $data['message'] ?? 'Thành công.',
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Lỗi khởi tạo MoMo gateway (Mã: ' . ($data['resultCode'] ?? 'unknown') . ')',
                    'resultCode' => $data['resultCode'] ?? -1,
                ];
            }

            Log::error("MoMo Sandbox HTTP Error: " . $response->body());
            return [
                'success' => false,
                'message' => 'Không thể kết nối máy chủ MoMo Sandbox (HTTP ' . $response->status() . ').',
            ];
        } catch (\Exception $e) {
            Log::error("MoMo Sandbox Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối cổng MoMo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực chữ ký số từ MoMo Callback hoặc IPN
     *
     * @param array $data
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        if (empty($data['signature'])) {
            return false;
        }

        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . ($data['amount'] ?? '') .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . ($data['message'] ?? '') .
            "&orderId=" . ($data['orderId'] ?? '') .
            "&orderInfo=" . ($data['orderInfo'] ?? '') .
            "&orderType=" . ($data['orderType'] ?? '') .
            "&partnerCode=" . ($data['partnerCode'] ?? '') .
            "&payType=" . ($data['payType'] ?? '') .
            "&requestId=" . ($data['requestId'] ?? '') .
            "&responseTime=" . ($data['responseTime'] ?? '') .
            "&resultCode=" . ($data['resultCode'] ?? '') .
            "&transId=" . ($data['transId'] ?? '');

        $calculatedSignature = hash_hmac("sha256", $rawHash, $this->secretKey);

        return hash_equals($calculatedSignature, (string)$data['signature']);
    }

    /**
     * Trích xuất mã đơn hàng BeeStyle từ orderId của MoMo hoặc extraData
     *
     * @param array $data
     * @return string|null
     */
    public function extractOrderCode(array $data): ?string
    {
        // Thử lấy từ extraData trước
        if (!empty($data['extraData'])) {
            $decoded = json_decode(base64_decode($data['extraData']), true);
            if (!empty($decoded['order_code'])) {
                return $decoded['order_code'];
            }
        }

        // Thử tách từ orderId dạng BEE-XXXXXXXX-XXXX_timestamp
        if (!empty($data['orderId'])) {
            $parts = explode('_', $data['orderId']);
            return $parts[0] ?? $data['orderId'];
        }

        return null;
    }

    /**
     * Tra cứu trạng thái giao dịch MoMo trực tiếp từ máy chủ MoMo
     *
     * @param string $orderId
     * @param string|null $requestId
     * @return array
     */
    public function queryTransaction(string $orderId, ?string $requestId = null): array
    {
        try {
            $requestId = $requestId ?: (string) Str::uuid();
            $endpoint = str_replace('/create', '/query', $this->endpoint);

            $rawHash = "accessKey=" . $this->accessKey .
                "&orderId=" . $orderId .
                "&partnerCode=" . $this->partnerCode .
                "&requestId=" . $requestId;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $payload = [
                'partnerCode' => $this->partnerCode,
                'requestId' => $requestId,
                'orderId' => $orderId,
                'signature' => $signature,
                'lang' => 'vi'
            ];

            $response = Http::withoutVerifying()->timeout(10)->post($endpoint, $payload);
            if ($response->successful()) {
                return $response->json();
            }

            return ['resultCode' => -1, 'message' => 'Lỗi kết nối máy chủ MoMo: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("MoMo queryTransaction Exception: " . $e->getMessage());
            return ['resultCode' => -1, 'message' => $e->getMessage()];
        }
    }
}