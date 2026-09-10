<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseFormRequest;
use App\Rules\VietnamesePhoneNumber;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseFormRequest
{
    /**
     * Chuẩn bị và làm sạch dữ liệu trước khi xác thực:
     * - Trim khoảng trắng họ tên và chuẩn hóa các dấu cách thừa.
     * - Chuyển đổi email về chữ thường.
     * - Chuẩn hóa số điện thoại.
     * - Chuyển đổi giá trị gender tiếng Việt sang mã chuẩn hoặc ngược lại nếu cần.
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

        if ($this->has('gender')) {
            $gender = strtolower(trim((string) $this->input('gender')));
            $map = [
                'nam'   => 'male',
                'nữ'    => 'female',
                'nu'    => 'female',
                'khác'  => 'other',
                'khac'  => 'other',
            ];
            if (isset($map[$gender])) {
                $mergeData['gender'] = $map[$gender];
            }
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Quy tắc xác thực cập nhật hồ sơ cá nhân.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()?->id ?? $this->route('id') ?? $this->input('user_id');

        return [
            'name' => [
                'required',
                'string',
                new \App\Rules\VietnamesePersonName(2, 100),
            ],
            'email' => [
                'nullable',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => [
                'nullable',
                'string',
                new VietnamesePhoneNumber,
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'gender' => [
                'nullable',
                'string',
                'in:male,female,other,Nam,Nữ,Khác',
            ],
            'dob' => [
                'nullable',
                'date',
                'before:today',
                new \App\Rules\ValidAgeRange(10, 100),
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // Tối đa 2MB (2048 KB)
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'district' => [
                'nullable',
                'string',
                'max:100',
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
            'name.min'      => 'Họ và tên phải có tối thiểu :min ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá :max ký tự.',

            'email.required' => 'Vui lòng nhập địa chỉ Email liên lạc.',
            'email.string'   => 'Địa chỉ Email phải là chuỗi ký tự.',
            'email.email'    => 'Địa chỉ Email không đúng định dạng.',
            'email.max'      => 'Địa chỉ Email không được vượt quá :max ký tự.',
            'email.unique'   => 'Địa chỉ Email này đã được sử dụng bởi một tài khoản khác trong hệ thống.',

            'phone.required' => 'Vui lòng nhập số điện thoại liên lạc.',
            'phone.unique'   => 'Số điện thoại này đã được liên kết với một tài khoản khác.',

            'gender.in' => 'Giới tính không hợp lệ. Vui lòng chọn: Nam (male), Nữ (female) hoặc Khác (other).',

            'dob.date'   => 'Ngày sinh không đúng định dạng ngày tháng hợp lệ.',
            'dob.before' => 'Ngày sinh phải là một ngày trong quá khứ (trước ngày hôm nay).',

            'avatar.file'  => 'Ảnh đại diện tải lên phải là một tệp tin hợp lệ.',
            'avatar.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, WEBP.',
            'avatar.max'   => 'Dung lượng ảnh đại diện không được vượt quá 2MB (2048 KB).',

            'address.max'  => 'Địa chỉ không được vượt quá :max ký tự.',
            'city.max'     => 'Tên Tỉnh/Thành phố không được vượt quá :max ký tự.',
            'district.max' => 'Tên Quận/Huyện không được vượt quá :max ký tự.',
        ];
    }
}
