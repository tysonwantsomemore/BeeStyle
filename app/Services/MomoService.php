<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

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
     * Gửi yêu cầu HTTP POST tới cổng MoMo qua cURL (theo chuẩn tài liệu tích hợp MoMo Payment)
     *
     * @param string $url
     * @param string $data (JSON string)
     * @return string
     */
    public function execPostRequest(string $url, string $data): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false) {
            Log::error("MoMo execPostRequest cURL Error: " . $curlError);
            return json_encode([
                'resultCode' => -1,
                'message' => 'Lỗi kết nối máy chủ MoMo: ' . $curlError,
            ]);
        }

        return (string) $result;
    }

    /**
     * Tạo yêu cầu thanh toán MoMo Payment Gateway (Sandbox v2 API)
     *
     * @param Order $order
     * @param string $requestType Mặc định 'payWithATM' (Cổng thanh toán MoMo ATM trực tuyến, không hiển thị mã quét QR)
     * @return array
     */
    public function createPayment(Order $order, string $requestType = 'payWithATM'): array
    {
        try {
            // Tạo unique orderId cho MoMo để tránh lỗi trùng lặp mã đơn khi khách hàng thử thanh toán lại
            $orderId = $order->order_code . '_' . time();
            $requestId = time() . '_' . rand(1000, 9999);

            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            $amount = $isDeposit ? (int) round($order->deposit_amount) : (int) round($order->total_amount);
            $amountStr = (string) $amount;

            $orderInfo = $isDeposit
                ? "Dat coc 50% don hang #" . $order->order_code . " qua MoMo Payment"
                : "Thanh toan don hang #" . $order->order_code . " qua MoMo Payment";

            $extraData = base64_encode(json_encode([
                'order_code' => $order->order_code,
                'is_deposit' => $isDeposit,
            ]));

            // Tạo chuỗi mã hóa HMAC-SHA256 theo chuẩn MoMo Gateway
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amountStr .
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
                'partnerName' => 'MoMo Demo',
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
                'orderExpireTime' => 15,
                'signature' => $signature,
            ];

            Log::info("MoMo Payment Create Request for Order #{$order->order_code}", [
                'endpoint' => $this->endpoint,
                'orderId' => $orderId,
                'amount' => $amount,
                'requestType' => $requestType,
            ]);

            $jsonString = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $response = $this->execPostRequest($this->endpoint, $jsonString);
            $data = json_decode($response, true);

            Log::info("MoMo Payment Response for Order #{$order->order_code}", $data ?: ['raw' => $response]);

            if (isset($data['resultCode']) && (int) $data['resultCode'] === 0) {
                return [
                    'success' => true,
                    'payUrl' => $data['payUrl'] ?? null,
                    'qrCodeUrl' => $data['qrCodeUrl'] ?? null,
                    'deeplink' => $data['deeplink'] ?? null,
                    'applink' => $data['applink'] ?? null,
                    'orderId' => $orderId,
                    'requestId' => $requestId,
                    'message' => $data['message'] ?? 'Thành công.',
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Lỗi khởi tạo MoMo Payment (Mã: ' . ($data['resultCode'] ?? 'unknown') . ')',
                'resultCode' => $data['resultCode'] ?? -1,
            ];
        } catch (\Throwable $e) {
            Log::error("MoMo Payment Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối cổng MoMo Payment: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực chữ ký số từ MoMo Callback hoặc IPN Webhook
     * Hỗ trợ kiểm tra linh hoạt cả 2 định dạng mã hóa chữ ký số của MoMo
     *
     * @param array $data
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        if (empty($data['signature'])) {
            return false;
        }

        $receivedSignature = (string) $data['signature'];

        // Cách 1: Chuẩn mã hóa Gateway V2 theo thứ tự bảng chữ cái (Alphabetical order)
        $rawHashAlphabetical = "accessKey=" . $this->accessKey .
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
            "&resultCode=" . ($data['resultCode'] ?? ($data['errorCode'] ?? '')) .
            "&transId=" . ($data['transId'] ?? '');

        $sigAlphabetical = hash_hmac("sha256", $rawHashAlphabetical, $this->secretKey);
        if (hash_equals($sigAlphabetical, $receivedSignature)) {
            return true;
        }

        // Cách 2: Chuẩn mã hóa tuần tự (Sequential checksum từ tài liệu mẫu)
        $rawHashSequential = "partnerCode=" . ($data['partnerCode'] ?? '') .
            "&accessKey=" . $this->accessKey .
            "&requestId=" . ($data['requestId'] ?? '') .
            "&amount=" . ($data['amount'] ?? '') .
            "&orderId=" . ($data['orderId'] ?? '') .
            "&orderInfo=" . ($data['orderInfo'] ?? '') .
            "&orderType=" . ($data['orderType'] ?? '') .
            "&transId=" . ($data['transId'] ?? '') .
            "&message=" . ($data['message'] ?? '') .
            "&localMessage=" . ($data['localMessage'] ?? '') .
            "&responseTime=" . ($data['responseTime'] ?? '') .
            "&errorCode=" . ($data['errorCode'] ?? ($data['resultCode'] ?? '')) .
            "&payType=" . ($data['payType'] ?? '') .
            "&extraData=" . ($data['extraData'] ?? '');

        $sigSequential = hash_hmac("sha256", $rawHashSequential, $this->secretKey);
        if (hash_equals($sigSequential, $receivedSignature)) {
            return true;
        }

        return false;
    }

    /**
     * Tra cứu trạng thái giao dịch MoMo trực tiếp từ máy chủ MoMo (Server-to-Server Query)
     *
     * @param string $orderId
     * @param string|null $requestId
     * @return array
     */
    public function queryTransaction(string $orderId, ?string $requestId = null): array
    {
        try {
            $endpoint = str_replace('/create', '/query', $this->endpoint);
            $requestId = $requestId ?: (time() . "_" . rand(1000, 9999));

            // Chữ ký truy vấn giao dịch theo tài liệu MoMo
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
                'lang' => 'vi',
            ];

            $response = $this->execPostRequest($endpoint, json_encode($payload));
            $json = json_decode($response, true);

            return is_array($json) ? $json : ['resultCode' => -1, 'message' => 'Phản hồi không hợp lệ'];
        } catch (\Throwable $e) {
            Log::error("MoMo queryTransaction Exception: " . $e->getMessage());
            return ['resultCode' => -1, 'message' => $e->getMessage()];
        }
    }

    /**
     * Trích xuất mã đơn hàng BeeStyle từ orderId của MoMo hoặc extraData
     *
     * @param array $data
     * @return string|null
     */
    public function extractOrderCode(array $data): ?string
    {
        // 1. Thử lấy từ extraData trước
        if (!empty($data['extraData'])) {
            $decoded = json_decode(base64_decode($data['extraData']), true);
            if (!empty($decoded['order_code'])) {
                return $decoded['order_code'];
            }
        }

        // 2. Thử tách từ orderId dạng BEE-XXXXXXXX-XXXX_timestamp
        if (!empty($data['orderId'])) {
            $parts = explode('_', $data['orderId']);
            return $parts[0] ?? $data['orderId'];
        }

        return null;
    }
}