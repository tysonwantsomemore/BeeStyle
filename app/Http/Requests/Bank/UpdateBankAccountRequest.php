<?php

namespace App\Http\Requests\Bank;

use App\Http\Requests\BaseFormRequest;
use App\Rules\UppercaseNoAccent;
use App\Rules\ValidBankCode;

class UpdateBankAccountRequest extends BaseFormRequest
{
    /**
     * Chuẩn bị và làm sạch dữ liệu trước khi xác thực:
     * - Trim khoảng trắng.
     * - Chuyển bank_code sang chữ IN HOA.
     * - Loại bỏ khoảng trắng thừa trong số tài khoản ngân hàng.
     * - Chuẩn hóa tên chủ tài khoản: Chuyển chữ thường sang CHỮ HOA, chuẩn hóa khoảng cách giữa các từ.
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $mergeData = [];

        if ($this->has('bank_code')) {
            $mergeData['bank_code'] = strtoupper(trim((string) $this->input('bank_code')));
        }

        if ($this->has('account_number')) {
            $mergeData['account_number'] = preg_replace('/\s+/', '', (string) $this->input('account_number'));
        }

        if ($this->has('account_holder_name')) {
            // Trim và loại bỏ các dấu cách kép
            $holder = preg_replace('/\s+/', ' ', trim((string) $this->input('account_holder_name')));
            $mergeData['account_holder_name'] = mb_strtoupper($holder, 'UTF-8');
        }

        if ($this->has('bank_branch')) {
            $mergeData['bank_branch'] = preg_replace('/\s+/', ' ', trim((string) $this->input('bank_branch')));
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Quy tắc xác thực thông tin tài khoản ngân hàng.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_code' => [
                'required',
                'string',
                new ValidBankCode,
            ],
            'account_number' => [
                'required',
                'string',
                'regex:/^[0-9A-Za-z]{6,20}$/',
            ],
            'account_holder_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                new UppercaseNoAccent,
            ],
            'bank_branch' => [
                'nullable',
                'string',
                'max:150',
            ],
            'password' => [
                'required_without:otp_code',
                'nullable',
                'string',
            ],
            'otp_code' => [
                'required_without:password',
                'nullable',
                'string',
                'digits:6',
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
            'bank_code.required' => 'Vui lòng chọn ngân hàng thụ hưởng.',
            'bank_code.string'   => 'Mã ngân hàng không hợp lệ.',

            'account_number.required' => 'Vui lòng nhập số tài khoản ngân hàng.',
            'account_number.string'   => 'Số tài khoản phải là chuỗi ký tự hợp lệ.',
            'account_number.regex'    => 'Số tài khoản ngân hàng phải có từ 6 đến 20 ký tự (chỉ bao gồm chữ số hoặc chữ cái, không chứa khoảng trắng hay ký tự đặc biệt).',

            'account_holder_name.required' => 'Vui lòng nhập họ tên chủ tài khoản ngân hàng.',
            'account_holder_name.string'   => 'Tên chủ tài khoản phải là chuỗi ký tự.',
            'account_holder_name.min'      => 'Tên chủ tài khoản phải có độ dài tối thiểu từ :min ký tự.',
            'account_holder_name.max'      => 'Tên chủ tài khoản không được vượt quá :max ký tự.',

            'bank_branch.string' => 'Tên chi nhánh ngân hàng phải là chuỗi ký tự.',
            'bank_branch.max'    => 'Tên chi nhánh ngân hàng không được vượt quá :max ký tự.',

            'password.required_without' => 'Vui lòng nhập mật khẩu tài khoản hoặc mã OTP để xác thực thay đổi thông tin ngân hàng.',
            'otp_code.required_without' => 'Vui lòng nhập mã OTP hoặc mật khẩu tài khoản để xác thực thay đổi thông tin ngân hàng.',
            'otp_code.digits'           => 'Mã OTP xác thực phải gồm đúng 6 chữ số.',
        ];
    }
}
