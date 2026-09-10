<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MatchCurrentPassword implements ValidationRule
{
    protected ?string $customHashedPassword;

    public function __construct(?string $customHashedPassword = null)
    {
        $this->customHashedPassword = $customHashedPassword;
    }

    /**
     * Chạy quy tắc xác thực: Kiểm tra mật khẩu hiện tại có khớp với mật khẩu đang lưu trong cơ sở dữ liệu hay không.
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

        $hashedPassword = $this->customHashedPassword;

        if (!$hashedPassword) {
            $user = Auth::user();
            if (!$user || empty($user->password)) {
                $fail('Không tìm thấy thông tin tài khoản người dùng hoặc phiên đăng nhập đã hết hạn.');
                return;
            }
            $hashedPassword = $user->password;
        }

        if (!Hash::check($value, $hashedPassword)) {
            $fail('Mật khẩu hiện tại không chính xác. Vui lòng kiểm tra lại!');
        }
    }
}
