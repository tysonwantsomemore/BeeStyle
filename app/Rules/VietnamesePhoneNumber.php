<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VietnamesePhoneNumber implements ValidationRule
{
    /**
     * Regex kiểm tra số điện thoại Việt Nam chuẩn 10 chữ số:
     * - Bắt đầu bằng 03, 05, 07, 08, 09 (hoặc đầu số quốc tế +84 / 84)
     * - Theo sau là 8 chữ số
     * Ví dụ: 0912345678, 0387654321, +84987654321, 84901234567
     */
    public const PATTERN = '/^(?:\+?84|0)(?:3[2-9]|5[25689]|7[06-9]|8[1-9]|9[0-9])[0-9]{7}$/';

    /**
     * Chạy quy tắc xác thực.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) && !is_numeric($value)) {
            $fail(':attribute phải là chuỗi số điện thoại hợp lệ.');
            return;
        }

        // Xóa khoảng trắng, dấu gạch nối, dấu chấm nếu có
        $cleanPhone = preg_replace('/[\s\-\.\(\)]+/', '', (string) $value);

        if (!preg_match(self::PATTERN, $cleanPhone)) {
            $fail(':attribute không đúng định dạng số điện thoại Việt Nam (10 chữ số, bắt đầu bằng 03, 05, 07, 08, 09).');
        }
    }
}
