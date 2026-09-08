<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseFormRequest;

class ConfirmContactChangeRequest extends BaseFormRequest
{
    /**
     * Quy tắc xác thực hoàn tất đổi Email/SĐT (Bước 2)
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'in:email,phone',
            ],
            'new_value' => [
                'required',
                'string',
            ],
            'otp_code' => [
                'required',
                'string',
                'digits:6',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'      => 'Vui lòng cung cấp loại thông tin xác thực.',
            'new_value.required' => 'Vui lòng cung cấp thông tin liên hệ mới.',
            'otp_code.required'  => 'Vui lòng nhập mã xác thực OTP.',
            'otp_code.digits'    => 'Mã xác thực OTP phải gồm đúng 6 chữ số.',
        ];
    }
}
