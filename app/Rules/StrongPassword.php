<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    protected int $minLength;

    public function __construct(int $minLength = 8)
    {
        $this->minLength = $minLength;
    }

    /**
     * Chạy quy tắc xác thực độ phức tạp mật khẩu.
     * Mật khẩu phải đạt:
     * 1. Độ dài tối thiểu (mặc định: 8 ký tự)
     * 2. Có ít nhất 1 chữ cái in hoa (A-Z)
     * 3. Có ít nhất 1 chữ cái in thường (a-z)
     * 4. Có ít nhất 1 chữ số (0-9)
     * 5. Có ít nhất 1 ký tự đặc biệt (!@#$%^&*...)
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

        if (mb_strlen($value) < $this->minLength) {
            $fail(":attribute phải có ít nhất {$this->minLength} ký tự.");
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail(':attribute phải chứa ít nhất 1 chữ cái in hoa (A-Z).');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail(':attribute phải chứa ít nhất 1 chữ cái in thường (a-z).');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail(':attribute phải chứa ít nhất 1 chữ số (0-9).');
            return;
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\\\|,.<>\/?~`]/', $value)) {
            $fail(':attribute phải chứa ít nhất 1 ký tự đặc biệt (ví dụ: @, #, $, %, !, &, *...).');
        }
    }
}
