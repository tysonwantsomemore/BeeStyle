<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VietnamesePersonName implements ValidationRule
{
    protected int $min;
    protected int $max;

    public function __construct(int $min = 2, int $max = 50)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Regex cho phép chữ cái tiếng Việt (có dấu hoặc không dấu) và khoảng trắng.
     * Chặn toàn bộ ký tự đặc biệt, mã HTML, dấu ngoặc nhọn, script, emoji...
     */
    public const PATTERN = '/^[\p{L}\s]+$/u';

    /**
     * Chạy quy tắc xác thực họ và tên.
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

        // Làm sạch và chống XSS
        $sanitized = trim(strip_tags($value));
        $length = mb_strlen($sanitized, 'UTF-8');

        if ($length < $this->min) {
            $fail(":attribute phải có độ dài tối thiểu từ {$this->min} ký tự.");
            return;
        }

        if ($length > $this->max) {
            $fail(":attribute không được vượt quá {$this->max} ký tự.");
            return;
        }

        if (!preg_match(self::PATTERN, $sanitized)) {
            $fail(':attribute chỉ được chứa chữ cái tiếng Việt và khoảng trắng, không được chứa số hoặc ký tự đặc biệt.');
        }
    }
}
