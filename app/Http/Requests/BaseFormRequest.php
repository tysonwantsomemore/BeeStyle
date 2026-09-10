<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền thực hiện yêu cầu này hay không.
     * Mặc định cho phép tất cả các request, có thể override trong lớp con nếu cần phân quyền.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Chuẩn bị và làm sạch dữ liệu trước khi chạy validation.
     * Tự động trim khoảng trắng đầu/cuối của tất cả các chuỗi, làm sạch mảng lồng nhau.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        if (!empty($input)) {
            $this->replace($this->cleanInputs($input));
        }
    }

    /**
     * Đệ quy làm sạch khoảng trắng thừa trong mảng dữ liệu.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function cleanInputs(mixed $data): mixed
    {
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $value) {
                $cleaned[$key] = $this->cleanInputs($value);
            }
            return $cleaned;
        }

        if (is_string($data)) {
            // Trim khoảng trắng đầu và cuối chuỗi
            return trim($data);
        }

        return $data;
    }

    /**
     * Xử lý khi quá trình xác thực thất bại.
     * Trả về JSON chuẩn 422 nếu là API/AJAX Request, hoặc chuyển hướng kèm thông báo lỗi cho Web Request.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException|\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson() || $this->is('api/*') || $this->ajax()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu gửi lên không hợp lệ. Vui lòng kiểm tra lại các trường thông tin!',
                    'errors'  => $validator->errors(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Danh sách tên thuộc tính thân thiện hiển thị trong thông báo lỗi tiếng Việt.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'                  => 'Họ và tên',
            'login_id'              => 'Tên đăng nhập (Email/SĐT)',
            'email'                 => 'Địa chỉ Email',
            'phone'                 => 'Số điện thoại',
            'password'              => 'Mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu',
            'terms'                 => 'Điều khoản dịch vụ',
            'gender'                => 'Giới tính',
            'dob'                   => 'Ngày sinh',
            'avatar'                => 'Ảnh đại diện',
            'current_password'      => 'Mật khẩu hiện tại',
            'new_password'          => 'Mật khẩu mới',
            'confirm_new_password'  => 'Xác nhận mật khẩu mới',
            'bank_code'             => 'Ngân hàng',
            'account_number'        => 'Số tài khoản ngân hàng',
            'account_holder_name'   => 'Tên chủ tài khoản',
            'receiver_name'         => 'Họ tên người nhận',
            'receiver_phone'        => 'Số điện thoại người nhận',
            'province_id'           => 'Tỉnh/Thành phố',
            'district_id'           => 'Quận/Huyện',
            'ward_id'               => 'Phường/Xã',
            'detailed_address'      => 'Địa chỉ chi tiết',
            'is_default'            => 'Địa chỉ mặc định',
        ];
    }
}
