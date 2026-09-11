<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    /**
     * Chuẩn hóa dữ liệu trước khi xác thực:
     * - Trim khoảng trắng login_id và password.
     * - Nếu login_id chứa email, chuyển về chữ thường.
     * - Nếu login_id là số điện thoại, loại bỏ các ký tự phân cách như dấu gạch ngang, dấu chấm.
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if ($this->has('login_id')) {
            $loginId = trim((string) $this->input('login_id'));

            if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
                $loginId = strtolower($loginId);
            } else {
                // Làm sạch nếu người dùng nhập số điện thoại có dấu cách hoặc gạch nối
                $cleanedPhone = preg_replace('/[\s\-\.\(\)]+/', '', $loginId);
                if (preg_match('/^(?:\+?84|0)[0-9]{9}$/', $cleanedPhone)) {
                    $loginId = $cleanedPhone;
                }
            }

            $this->merge(['login_id' => $loginId]);
        }
    }

    /**
     * Quy tắc xác thực đăng nhập.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
            'remember' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Danh sách thông báo lỗi bằng tiếng Việt.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login_id.required' => 'Vui lòng nhập Email hoặc Số điện thoại để đăng nhập.',
            'login_id.string'   => 'Tên đăng nhập phải là chuỗi ký tự hợp lệ.',
            'login_id.max'      => 'Tên đăng nhập không được vượt quá :max ký tự.',

            'password.required' => 'Vui lòng nhập mật khẩu tài khoản của bạn.',
            'password.string'   => 'Mật khẩu phải là chuỗi ký tự.',
            'password.max'      => 'Mật khẩu không được vượt quá :max ký tự.',

            'remember.boolean'  => 'Trường ghi nhớ đăng nhập phải là giá trị đúng hoặc sai (boolean).',
        ];
    }

    /**
     * Xác định trường đăng nhập là 'email' hay 'phone'.
     *
     * @return string
     */
    public function getLoginFieldType(): string
    {
        $loginId = $this->input('login_id');
        return filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    }

    /**
     * Lấy mảng thông tin xác thực cho Auth::attempt()
     *
     * @return array<string, mixed>
     */
    public function getCredentials(): array
    {
        return [
            $this->getLoginFieldType() => $this->input('login_id'),
            'password'                 => $this->input('password'),
        ];
    }
}
