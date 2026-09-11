<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBankCode implements ValidationRule
{
    /**
     * Danh sách các ngân hàng tại Việt Nam (Mã ngân hàng chuẩn Napas/VietQR & Tên giao dịch)
     */
    public const SUPPORTED_BANKS = [
        'VCB'    => 'Vietcombank - Ngân hàng TMCP Ngoại Thương Việt Nam',
        'CTG'    => 'VietinBank - Ngân hàng TMCP Công Thương Việt Nam',
        'BIDV'   => 'BIDV - Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
        'VBA'    => 'Agribank - Ngân hàng Nông nghiệp & Phát triển Nông thôn',
        'TCB'    => 'Techcombank - Ngân hàng TMCP Kỹ Thương Việt Nam',
        'MB'     => 'MBBank - Ngân hàng TMCP Quân Đội',
        'ACB'    => 'ACB - Ngân hàng TMCP Á Châu',
        'VPB'    => 'VPBank - Ngân hàng TMCP Việt Nam Thịnh Vượng',
        'TPB'    => 'TPBank - Ngân hàng TMCP Tiên Phong',
        'STB'    => 'Sacombank - Ngân hàng TMCP Sài Gòn Thương Tín',
        'HDB'    => 'HDBank - Ngân hàng TMCP Phát triển TP.HCM',
        'VIB'    => 'VIB - Ngân hàng TMCP Quốc tế Việt Nam',
        'MSB'    => 'MSB - Ngân hàng TMCP Hàng Hải Việt Nam',
        'OCB'    => 'OCB - Ngân hàng TMCP Phương Đông',
        'SHB'    => 'SHB - Ngân hàng TMCP Sài Gòn - Hà Nội',
        'LPB'    => 'LPBank - Ngân hàng TMCP Lộc Phát Việt Nam',
        'SSB'    => 'SeABank - Ngân hàng TMCP Đông Nam Á',
        'BAB'    => 'BacABank - Ngân hàng TMCP Bắc Á',
        'EIB'    => 'Eximbank - Ngân hàng TMCP Xuất Nhập Khẩu Việt Nam',
        'PVC'    => 'PVcomBank - Ngân hàng TMCP Đại Chúng Việt Nam',
        'KLB'    => 'Kienlongbank - Ngân hàng TMCP Kiên Long',
        'NAB'    => 'Nam A Bank - Ngân hàng TMCP Nam Á',
        'BVB'    => 'BaoVietBank - Ngân hàng TMCP Bảo Việt',
        'VBB'    => 'VietBank - Ngân hàng TMCP Việt Nam Thương Tín',
        'SCB'    => 'SCB - Ngân hàng TMCP Sài Gòn',
        'PGB'    => 'PGBank - Ngân hàng TMCP Thịnh vượng và Phát triển',
        'WOO'    => 'Woori Bank Việt Nam',
        'UOB'    => 'UOB - United Overseas Bank',
        'HSVN'   => 'HSBC Việt Nam',
        'SCVN'   => 'Standard Chartered Bank Việt Nam',
        'PBVN'   => 'Public Bank Việt Nam',
        'HLBVN'  => 'Hong Leong Bank Việt Nam',
        'CIMB'   => 'CIMB Bank Việt Nam',
        'CAKE'   => 'CAKE by VPBank (Ngân hàng số)',
        'TNEX'   => 'TNEX (Ngân hàng số MSB)',
        'TIMO'   => 'Timo (Ngân hàng số Bản Việt)',
    ];

    /**
     * Lấy toàn bộ danh sách ngân hàng được hỗ trợ.
     *
     * @return array<string, string>
     */
    public static function getBankList(): array
    {
        return self::SUPPORTED_BANKS;
    }

    /**
     * Lấy danh sách các mã ngân hàng hợp lệ.
     *
     * @return array<string>
     */
    public static function getValidCodes(): array
    {
        return array_keys(self::SUPPORTED_BANKS);
    }

    /**
     * Chạy quy tắc xác thực.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(':attribute phải là mã ngân hàng hợp lệ.');
            return;
        }

        $code = strtoupper(trim($value));

        if (!array_key_exists($code, self::SUPPORTED_BANKS)) {
            $fail(':attribute không nằm trong danh sách ngân hàng được hệ thống hỗ trợ.');
        }
    }
}
