<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Rules\StrongPassword;
use App\Rules\VietnamesePhoneNumber;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseFormRequest
{
    /**
     * Chuẩn bị và chuẩn hóa dữ liệu trước khi xác thực.
     * - Trim khoảng trắng, chuẩn hóa khoảng cách giữa các từ trong họ tên.
     * - Chuyển email về chữ thường.
     * - Loại bỏ khoảng trắng/dấu gạch nối trong số điện thoại.
     * - Tự động tách trường email_or_phone (nếu form sử dụng 1 ô nhập duy nhất).
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $mergeData = [];

        if ($this->has('name')) {
            $mergeData['name'] = preg_replace('/\s+/', ' ', trim((string) $this->input('name')));
        }

        if ($this->has('email')) {
            $mergeData['email'] = strtolower(trim((string) $this->input('email')));
        }

        if ($this->has('phone')) {
            $mergeData['phone'] = preg_replace('/[\s\-\.\(\)]+/', '', (string) $this->input('phone'));
        }

        // Hỗ trợ trường hợp form chỉ có 1 trường "email_or_phone"
        if ($this->has('email_or_phone') && !$this->has('email') && !$this->has('phone')) {
            $rawInput = trim((string) $this->input('email_or_phone'));
            if (filter_var($rawInput, FILTER_VALIDATE_EMAIL)) {
                $mergeData['email'] = strtolower($rawInput);
            } else {
                $mergeData['phone'] = preg_replace('/[\s\-\.\(\)]+/', '', $rawInput);
            }
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Quy tắc xác thực khi đăng ký tài khoản mới.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                new \App\Rules\VietnamesePersonName(2, 50),
            ],
            'email' => [
                'required_without:phone',
                'nullable',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                new VietnamesePhoneNumber,
                Rule::unique('users', 'phone'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                new StrongPassword(8),
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'same:password',
            ],
            'terms' => [
                'required',
                'accepted',
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
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'name.string'   => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.min'      => 'Họ và tên phải có độ dài tối thiểu từ :min ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá :max ký tự.',

            'email.required_without' => 'Vui lòng cung cấp địa chỉ Email hoặc Số điện thoại để tạo tài khoản.',
            'email.email'            => 'Địa chỉ Email không đúng định dạng chuẩn (ví dụ: user@example.com).',
            'email.max'              => 'Địa chỉ Email không được vượt quá :max ký tự.',
            'email.unique'           => 'Địa chỉ Email này đã được đăng ký trong hệ thống. Vui lòng đăng nhập hoặc sử dụng email khác.',

            'phone.required_without' => 'Vui lòng cung cấp Số điện thoại hoặc Địa chỉ Email để tạo tài khoản.',
            'phone.unique'           => 'Số điện thoại này đã được đăng ký trong hệ thống. Vui lòng đăng nhập hoặc sử dụng số khác.',

            'password.required'  => 'Vui lòng nhập mật khẩu bảo vệ tài khoản.',
            'password.string'    => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min'       => 'Mật khẩu phải có độ dài tối thiểu từ :min ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp với mật khẩu đã nhập.',

            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu để xác nhận.',
            'password_confirmation.same'     => 'Mật khẩu xác nhận không khớp với mật khẩu đã nhập.',

            'terms.required' => 'Bạn cần chấp nhận Điều khoản và Quy định của BeeStyle để tiếp tục.',
            'terms.accepted' => 'Bạn phải tích chọn đồng ý với Điều khoản dịch vụ & Chính sách bảo mật.',
        ];
    }
}
