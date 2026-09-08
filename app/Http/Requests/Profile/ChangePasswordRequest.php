<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseFormRequest;
use App\Rules\MatchCurrentPassword;
use App\Rules\StrongPassword;

class ChangePasswordRequest extends BaseFormRequest
{
    /**
     * Quy tắc xác thực đổi mật khẩu tài khoản.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                new MatchCurrentPassword,
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                new StrongPassword(8),
                'different:current_password',
            ],
            'confirm_new_password' => [
                'required_without:new_password_confirmation',
                'nullable',
                'string',
                'same:new_password',
            ],
            'new_password_confirmation' => [
                'required_without:confirm_new_password',
                'nullable',
                'string',
                'same:new_password',
            ],
        ];
    }

    /**
     * Danh sách thông báo lỗi tùy chỉnh bằng tiếng Việt.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại của bạn.',
            'current_password.string'   => 'Mật khẩu hiện tại phải là chuỗi ký tự.',

            'new_password.required'  => 'Vui lòng nhập mật khẩu mới cần thiết lập.',
            'new_password.string'    => 'Mật khẩu mới phải là chuỗi ký tự.',
            'new_password.min'       => 'Mật khẩu mới phải có độ dài tối thiểu từ :min ký tự.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.',

            'confirm_new_password.required_without' => 'Vui lòng nhập lại mật khẩu mới để xác nhận.',
            'confirm_new_password.same'             => 'Xác nhận mật khẩu mới không khớp với mật khẩu mới đã nhập.',

            'new_password_confirmation.required_without' => 'Vui lòng nhập lại mật khẩu mới để xác nhận.',
            'new_password_confirmation.same'             => 'Xác nhận mật khẩu mới không khớp với mật khẩu mới đã nhập.',
        ];
    }
}
