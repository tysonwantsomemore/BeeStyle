<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseFormRequest;
use App\Rules\VietnamesePhoneNumber;
use Illuminate\Validation\Rule;

class RequestContactChangeRequest extends BaseFormRequest
{
    /**
     * Chuẩn hóa dữ liệu trước khi xác thực
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if ($this->has('type')) {
            $this->merge(['type' => strtolower(trim((string) $this->input('type')))]);
        }

        if ($this->has('new_value')) {
            $val = trim((string) $this->input('new_value'));
            if ($this->input('type') === 'email') {
                $this->merge(['new_value' => strtolower($val)]);
            } else {
                $this->merge(['new_value' => preg_replace('/[\s\-\.\(\)]+/', '', $val)]);
            }
        }
    }

    /**
     * Quy tắc xác thực yêu cầu thay đổi Email hoặc Số điện thoại (Bước 1)
     */
    public function rules(): array
    {
        $type = $this->input('type');
        $userId = $this->user()?->id;

        $rules = [
            'type' => [
                'required',
                'string',
                'in:email,phone',
            ],
            'new_value' => [
                'required',
                'string',
            ],
        ];

        if ($type === 'email') {
            $rules['new_value'][] = 'email:rfc,dns';
            $rules['new_value'][] = 'max:255';
            $rules['new_value'][] = Rule::unique('users', 'email')->ignore($userId);
        } elseif ($type === 'phone') {
            $rules['new_value'][] = new VietnamesePhoneNumber;
            $rules['new_value'][] = Rule::unique('users', 'phone')->ignore($userId);
        }

        return $rules;
    }

    /**
     * Thông báo lỗi tiếng Việt
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Vui lòng chọn loại thông tin cần thay đổi (Email hoặc Số điện thoại).',
            'type.in'       => 'Loại thông tin thay đổi không hợp lệ.',

            'new_value.required' => 'Vui lòng nhập Email hoặc Số điện thoại mới.',
            'new_value.email'    => 'Địa chỉ Email mới không đúng định dạng.',
            'new_value.unique'   => 'Email hoặc Số điện thoại này đã được sử dụng bởi tài khoản khác trong hệ thống.',
        ];
    }
}
