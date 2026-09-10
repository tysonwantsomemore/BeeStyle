<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UppercaseNoAccent implements ValidationRule
{
    /**
     * Regex chỉ cho phép chữ cái in hoa không dấu tiếng Việt (A-Z) và khoảng trắng.
     * Cấm chữ thường, cấm ký tự có dấu tiếng Việt (Á, À, Ả, Đ, Ê, Ô, Ư...), cấm số và ký tự đặc biệt.
     */
    public const PATTERN = '/^[A-Z\s]+$/';

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
            $fail(':attribute phải là chuỗi ký tự.');
            return;
        }

        $trimmed = trim($value);

        if (empty($trimmed)) {
            $fail(':attribute không được để trống.');
            return;
        }

        // Kiểm tra xem có chứa chữ cái thường không
        if (preg_match('/[a-z]/', $trimmed)) {
            $fail(':attribute phải được viết in hoa toàn bộ (ví dụ: NGUYEN VAN A).');
            return;
        }

        // Kiểm tra xem có ký tự có dấu tiếng Việt hoặc ký tự đặc biệt không
        if (!preg_match(self::PATTERN, $trimmed)) {
            $fail(':attribute phải là chữ in hoa KHÔNG DẤU, không chứa số hoặc ký tự đặc biệt (ví dụ: NGUYEN VAN A).');
        }
    }
}
