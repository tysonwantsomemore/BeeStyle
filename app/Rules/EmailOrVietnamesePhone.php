<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrVietnamesePhone implements ValidationRule
{
    /**
     * Regex kiểm tra số điện thoại Việt Nam chuẩn 10 chữ số
     */
    public const PHONE_PATTERN = '/^(?:\+?84|0)(?:3[2-9]|5[25689]|7[06-9]|8[1-9]|9[0-9])[0-9]{7}$/';

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
            $fail(':attribute không hợp lệ.');
            return;
        }

        $input = trim($value);

        // Kiểm tra định dạng Email
        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL) !== false;

        // Kiểm tra định dạng SĐT Việt Nam
        $cleanPhone = preg_replace('/[\s\-\.\(\)]+/', '', $input);
        $isPhone = (bool) preg_match(self::PHONE_PATTERN, $cleanPhone);

        if (!$isEmail && !$isPhone) {
            $fail(':attribute phải là địa chỉ Email hợp lệ hoặc Số điện thoại Việt Nam 10 chữ số.');
        }
    }
}
