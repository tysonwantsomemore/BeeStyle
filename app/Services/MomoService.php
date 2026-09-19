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
     * Lấy các thông tin cấu hình MoMo
     */
    public function getConfig(): array
    {
        return [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
            'endpoint' => $this->endpoint,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl' => $this->ipnUrl,
        ];
    }

    /**
     * Tạo yêu cầu thanh toán MoMo ATM / Napas Sandbox (Gateway v2 API - payWithATM)
     * Chuẩn theo atm/atm_momo.php
     *
     * @param Order $order
     * @param string $requestType
     * @return array
     */
    public function createPayment(Order $order, string $requestType = 'payWithATM'): array
    {
        try {
            // Tạo unique orderId cho MoMo để tránh lỗi trùng lặp mã đơn khi khách thử thanh toán lại
            $orderId = $order->order_code . '_' . time();
            $requestId = (string) time();
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            $amount = $isDeposit ? (int) round($order->deposit_amount) : (int) round($order->total_amount);
            $orderInfo = $isDeposit
                ? "Thanh toan 50% tien coc don hang #" . $order->order_code . " tai BeeStyle"
                : "Thanh toan don hang #" . $order->order_code . " qua MoMo";
            $extraData = base64_encode(json_encode(['order_code' => $order->order_code, 'is_deposit' => $isDeposit]));

            // Chuỗi hash theo chuẩn atm/atm_momo.php
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
                'storeId' => 'MomoTestStore',
                'requestId' => $requestId,
                'amount' => (string) $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $this->redirectUrl,
                'ipnUrl' => $this->ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];

            Log::info("MoMo ATM Create Payment Request for Order #{$order->order_code}", [
                'endpoint' => $this->endpoint,
                'orderId' => $orderId,
                'amount' => $amount,
                'requestType' => $requestType
            ]);

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->post($this->endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("MoMo ATM Response for Order #{$order->order_code}", $data);

                if (isset($data['resultCode']) && (int)$data['resultCode'] === 0) {
                    return [
                        'success' => true,
                        'payUrl' => $data['payUrl'] ?? null,
                        'qrCodeUrl' => $data['qrCodeUrl'] ?? null,
                        'deeplink' => $data['deeplink'] ?? null,
                        'orderId' => $orderId,
                        'message' => $data['message'] ?? 'Khởi tạo thanh toán MoMo ATM thành công.',
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Lỗi khởi tạo MoMo ATM Gateway (Mã: ' . ($data['resultCode'] ?? 'unknown') . ')',
                    'resultCode' => $data['resultCode'] ?? -1,
                ];
            }

            Log::error("MoMo ATM HTTP Error: " . $response->body());
            return [
                'success' => false,
                'message' => 'Không thể kết nối máy chủ MoMo Sandbox (HTTP ' . $response->status() . ').',
            ];
        } catch (\Exception $e) {
            Log::error("MoMo ATM Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối cổng MoMo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Tạo chuỗi RawData tính Checksum theo chuẩn atm/result_atm.php & atm/ipn_momo.php
     *
     * @param array $data
     * @return string
     */
    public function getAtmCallbackRawHash(array $data): string
    {
        $partnerCode  = $data['partnerCode'] ?? $this->partnerCode;
        $accessKey    = $data['accessKey'] ?? $this->accessKey;
        $requestId    = $data['requestId'] ?? '';
        $amount       = $data['amount'] ?? '';
        $orderId      = $data['orderId'] ?? '';
        $orderInfo    = $data['orderInfo'] ?? '';
        $orderType    = $data['orderType'] ?? '';
        $transId      = $data['transId'] ?? '';
        $message      = $data['message'] ?? '';
        $localMessage = $data['localMessage'] ?? '';
        $responseTime = $data['responseTime'] ?? '';
        $errorCode    = $data['errorCode'] ?? ($data['resultCode'] ?? '');
        $payType      = $data['payType'] ?? '';
        $extraData    = $data['extraData'] ?? '';

        return "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&localMessage=" . $localMessage .
            "&responseTime=" . $responseTime .
            "&errorCode=" . $errorCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;
    }

    /**
     * Tính chữ ký đối tác Partner Signature từ chuỗi RawHash
     *
     * @param string $rawHash
     * @return string
     */
    public function getPartnerSignature(string $rawHash): string
    {
        return hash_hmac("sha256", $rawHash, $this->secretKey);
    }

    /**
     * Xác thực chữ ký số từ MoMo Callback hoặc IPN
     * Hỗ trợ đối soát cả 2 chuẩn: Chuẩn atm/result_atm.php và Chuẩn Alphabetical v2 Gateway
     *
     * @param array $data
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        if (empty($data['signature'])) {
            return false;
        }

        $m2signature = (string) $data['signature'];

        // 1. Kiểm tra theo chuẩn atm/result_atm.php & atm/ipn_momo.php
        $rawHashAtm = $this->getAtmCallbackRawHash($data);
        $partnerSignatureAtm = hash_hmac("sha256", $rawHashAtm, $this->secretKey);
        if (hash_equals($partnerSignatureAtm, $m2signature)) {
            return true;
        }

        // 2. Kiểm tra theo chuẩn MoMo Gateway v2 (nếu tham số dùng resultCode thay errorCode)
        $rawHashV2 = "accessKey=" . $this->accessKey .
            "&amount=" . ($data['amount'] ?? '') .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . ($data['message'] ?? '') .
            "&orderId=" . ($data['orderId'] ?? '') .
            "&orderInfo=" . ($data['orderInfo'] ?? '') .
            "&orderType=" . ($data['orderType'] ?? '') .
            "&partnerCode=" . ($data['partnerCode'] ?? $this->partnerCode) .
            "&payType=" . ($data['payType'] ?? '') .
            "&requestId=" . ($data['requestId'] ?? '') .
            "&responseTime=" . ($data['responseTime'] ?? '') .
            "&resultCode=" . ($data['resultCode'] ?? ($data['errorCode'] ?? '')) .
            "&transId=" . ($data['transId'] ?? '');

        $partnerSignatureV2 = hash_hmac("sha256", $rawHashV2, $this->secretKey);
        return hash_equals($partnerSignatureV2, $m2signature);
    }

    /**
     * Chuẩn bị dữ liệu Debugger kiểm thử theo atm/result_atm.php
     *
     * @param array $data
     * @return array
     */
    public function getAtmDebuggerData(array $data): array
    {
        $rawHash = $this->getAtmCallbackRawHash($data);
        $partnerSignature = $this->getPartnerSignature($rawHash);
        $m2signature = $data['signature'] ?? '';
        $isSignatureValid = !empty($m2signature) && hash_equals($partnerSignature, (string)$m2signature);

        if (!$isSignatureValid && !empty($m2signature)) {
            $isSignatureValid = $this->verifySignature($data);
        }

        return [
            'secretKey' => $this->secretKey,
            'rawHash' => $rawHash,
            'momoSignature' => $m2signature,
            'partnerSignature' => $partnerSignature,
            'isSignatureValid' => $isSignatureValid,
        ];
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
     * Chuẩn theo atm/query_transaction.php
     *
     * @param string $orderId
     * @param string|null $requestId
     * @return array
     */
    public function queryTransaction(string $orderId, ?string $requestId = null): array
    {
        try {
            $requestId = $requestId ?: (string) time();
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
                $jsonResult = $response->json();
                
                // Checksum response nếu cần
                $partnerSignature = null;
                $rawHashResponse = null;
                if (!empty($jsonResult['signature'])) {
                    $rawHashResponse = "partnerCode=" . ($jsonResult['partnerCode'] ?? '') .
                        "&accessKey=" . $this->accessKey .
                        "&requestId=" . ($jsonResult['requestId'] ?? '') .
                        "&orderId=" . ($jsonResult['orderId'] ?? '') .
                        "&errorCode=" . ($jsonResult['errorCode'] ?? ($jsonResult['resultCode'] ?? '')) .
                        "&transId=" . ($jsonResult['transId'] ?? '') .
                        "&amount=" . ($jsonResult['amount'] ?? '') .
                        "&message=" . ($jsonResult['message'] ?? '') .
                        "&localMessage=" . ($jsonResult['localMessage'] ?? '') .
                        "&requestType=" . ($jsonResult['requestType'] ?? '') .
                        "&payType=" . ($jsonResult['payType'] ?? '') .
                        "&extraData=" . ($jsonResult['extraData'] ?? '');

                    $partnerSignature = hash_hmac("sha256", $rawHashResponse, $this->secretKey);
                }

                return [
                    'success' => true,
                    'data' => $jsonResult,
                    'rawHash' => $rawHashResponse,
                    'partnerSignature' => $partnerSignature,
                    'momoSignature' => $jsonResult['signature'] ?? null,
                    'isPassChecksum' => !empty($jsonResult['signature']) && hash_equals((string)$partnerSignature, (string)$jsonResult['signature']),
                ];
            }

            return [
                'success' => false,
                'resultCode' => -1,
                'message' => 'Lỗi kết nối máy chủ MoMo: ' . $response->status(),
                'data' => $response->json() ?? ['body' => $response->body()]
            ];
        } catch (\Exception $e) {
            Log::error("MoMo queryTransaction Exception: " . $e->getMessage());
            return [
                'success' => false,
                'resultCode' => -1,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}