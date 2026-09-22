<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class VnpayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $url;
    protected string $apiUrl;
    protected string $returnUrl;
    protected string $version;
    protected string $locale;
    protected int $expireMinutes;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.tmn_code', env('VNP_TMN_CODE', 'NWCCIJDI'));
        $this->hashSecret = config('vnpay.hash_secret', env('VNP_HASH_SECRET', 'ZXPMMSHUMXCWBQOUSTJRBNFAWYIOJRQO'));
        $this->url = config('vnpay.url', env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'));
        $this->apiUrl = config('vnpay.api_url', env('VNP_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'));
        $this->returnUrl = config('vnpay.return_url', env('VNP_RETURN_URL', url('/thanh-toan/vnpay/callback')));
        $this->version = config('vnpay.version', '2.1.0');
        $this->locale = config('vnpay.locale', 'vn');
        $this->expireMinutes = (int) config('vnpay.expire_minutes', 15);
    }

    /**
     * Tạo URL thanh toán VNPAY Payment Gateway
     *
     * @param Order $order
     * @param string|null $bankCode Mã phương thức thanh toán (VNPAYQR, VNBANK, INTCARD...) nếu có
     * @param string|null $ipAddr
     * @return string
     */
    public function createPaymentUrl(Order $order, ?string $bankCode = null, ?string $ipAddr = null): string
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        $amount = $isDeposit ? (int) round($order->deposit_amount) : (int) round($order->total_amount);

        // Tạo mã giao dịch duy nhất cho VNPAY để tránh lỗi trùng lặp mã khi thanh toán lại
        $vnp_TxnRef = $order->order_code . '_' . time();
        $startTime = date('YmdHis');
        $expire = date('YmdHis', strtotime("+{$this->expireMinutes} minutes", strtotime($startTime)));

        $orderInfo = $isDeposit
            ? "Dat coc 50% don hang " . $order->order_code . " qua VNPAY"
            : "Thanh toan don hang " . $order->order_code . " qua VNPAY";

        $inputData = [
            'vnp_Version' => $this->version,
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $amount * 100, // VNPAY yêu cầu số tiền nhân 100
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $startTime,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddr ?: (request()->ip() ?: '127.0.0.1'),
            'vnp_Locale' => $this->locale,
            'vnp_OrderInfo' => $orderInfo,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_ExpireDate' => $expire,
        ];

        if (!empty($bankCode)) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        ksort($inputData);
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnp_Url = $this->url . '?' . $query;
        if (!empty($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        Log::info("VNPAY Payment URL Generated for Order #{$order->order_code}", [
            'txnRef' => $vnp_TxnRef,
            'amount' => $amount,
            'expire' => $expire,
        ]);

        return $vnp_Url;
    }

    /**
     * Xác thực chữ ký số HMAC-SHA512 trả về từ VNPAY
     *
     * @param array $data
     * @return bool
     */
    public function verifyResponse(array $data): bool
    {
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash']);
        unset($data['vnp_SecureHashType']);

        ksort($data);
        $i = 0;
        $hashData = '';
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . '=' . urlencode($value);
                    $i = 1;
                }
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        return hash_equals(strtolower($secureHash), strtolower($vnp_SecureHash));
    }

    /**
     * Trích xuất mã đơn hàng gốc từ vnp_TxnRef (VD: BEE-20260918-ABCD_1789658962 => BEE-20260918-ABCD)
     *
     * @param array|string $data
     * @return string|null
     */
    public function extractOrderCode(array|string $data): ?string
    {
        $txnRef = is_array($data) ? ($data['vnp_TxnRef'] ?? '') : $data;
        if (empty($txnRef)) {
            return null;
        }

        $parts = explode('_', $txnRef);
        return $parts[0] ?? null;
    }

    /**
     * Diễn giải chi tiết mã phản hồi từ VNPAY
     *
     * @param string|null $responseCode
     * @return string
     */
    public function getResponseMessage(?string $responseCode): string
    {
        return match ($responseCode) {
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, bất thường)',
            '09' => 'Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Đã hết hạn chờ thanh toán. Vui lòng thử lại',
            '12' => 'Thẻ/Tài khoản của khách hàng bị khóa',
            '13' => 'Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Khách hàng hủy giao dịch',
            '51' => 'Tài khoản của quý khách không đủ số dư để thực hiện giao dịch',
            '65' => 'Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Khách hàng nhập sai mật khẩu thanh toán quá số lần quy định',
            default => 'Giao dịch không thành công hoặc đã phát sinh lỗi (Mã lỗi: ' . ($responseCode ?: 'Không xác định') . ')',
        };
    }
}
