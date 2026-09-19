<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\BaseFormRequest;
use App\Rules\VietnamesePhoneNumber;

class ShippingAddressRequest extends BaseFormRequest
{
    /**
     * Chuẩn bị và làm sạch dữ liệu trước khi xác thực:
     * - Trim khoảng trắng họ tên người nhận và địa chỉ chi tiết.
     * - Làm sạch số điện thoại người nhận.
     * - Tự động đồng bộ các trường alias nếu form gửi recipient_name/phone/address.
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $mergeData = [];

        // Đồng bộ alias receiver_name <-> recipient_name
        if (!$this->has('receiver_name') && $this->has('recipient_name')) {
            $mergeData['receiver_name'] = $this->input('recipient_name');
        }

        // Đồng bộ alias receiver_phone <-> phone
        if (!$this->has('receiver_phone') && $this->has('phone')) {
            $mergeData['receiver_phone'] = $this->input('phone');
        }

        // Đồng bộ alias detailed_address <-> address
        if (!$this->has('detailed_address') && $this->has('address')) {
            $mergeData['detailed_address'] = $this->input('address');
        }

        // Đồng bộ alias city <-> province_name
        if (!$this->has('city') && $this->has('province_name')) {
            $mergeData['city'] = $this->input('province_name');
        } elseif (!$this->has('province_name') && $this->has('city')) {
            $mergeData['province_name'] = $this->input('city');
        }

        // Đồng bộ alias ward <-> ward_name
        if (!$this->has('ward') && $this->has('ward_name')) {
            $mergeData['ward'] = $this->input('ward_name');
        } elseif (!$this->has('ward_name') && $this->has('ward')) {
            $mergeData['ward_name'] = $this->input('ward');
        }

        // Đồng bộ alias district <-> district_name
        if (!$this->has('district') && $this->has('district_name')) {
            $mergeData['district'] = $this->input('district_name');
        } elseif (!$this->has('district_name') && $this->has('district')) {
            $mergeData['district_name'] = $this->input('district');
        }

        if ($this->has('receiver_name') || isset($mergeData['receiver_name'])) {
            $name = $mergeData['receiver_name'] ?? $this->input('receiver_name');
            $mergeData['receiver_name'] = preg_replace('/\s+/', ' ', trim((string) $name));
        }

        if ($this->has('receiver_phone') || isset($mergeData['receiver_phone'])) {
            $phone = $mergeData['receiver_phone'] ?? $this->input('receiver_phone');
            $mergeData['receiver_phone'] = preg_replace('/[\s\-\.\(\)]+/', '', (string) $phone);
        }

        if ($this->has('detailed_address') || isset($mergeData['detailed_address'])) {
            $addr = $mergeData['detailed_address'] ?? $this->input('detailed_address');
            $mergeData['detailed_address'] = preg_replace('/\s+/', ' ', trim((string) $addr));
        }

        if ($this->has('is_default')) {
            $mergeData['is_default'] = $this->boolean('is_default');
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Quy tắc xác thực địa chỉ giao hàng.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $provinceId = (int) $this->input('province_id');
        $districtId = (int) $this->input('district_id');
        $wardId = (int) $this->input('ward_id');

        return [
            'receiver_name' => [
                'required',
                'string',
                new \App\Rules\VietnamesePersonName(2, 50),
            ],
            'receiver_phone' => [
                'required',
                'string',
                new VietnamesePhoneNumber,
            ],
            'province_id' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'province_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'district_id' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'district_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'district' => [
                'nullable',
                'string',
                'max:100',
            ],
            'ward_id' => [
                'nullable',
                'numeric',
                'min:1',
                new \App\Rules\ValidAdministrativeCascade($provinceId, $districtId, $wardId),
            ],
            'ward_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'ward' => [
                'nullable',
                'string',
                'max:100',
            ],
            'detailed_address' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'is_default' => [
                'nullable',
                'boolean',
            ],
            'label' => [
                'nullable',
                'string',
                'max:50',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:255',
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
            'receiver_name.required' => 'Vui lòng nhập họ và tên người nhận hàng.',
            'receiver_name.string'   => 'Tên người nhận phải là chuỗi ký tự hợp lệ.',
            'receiver_name.min'      => 'Tên người nhận phải có ít nhất :min ký tự.',
            'receiver_name.max'      => 'Tên người nhận không được vượt quá :max ký tự.',

            'receiver_phone.required' => 'Vui lòng nhập số điện thoại người nhận hàng.',
            'receiver_phone.string'   => 'Số điện thoại người nhận phải là chuỗi ký tự.',
            'receiver_phone.regex'    => 'Số điện thoại người nhận phải là số điện thoại Việt Nam 10 chữ số (bắt đầu bằng 03, 05, 07, 08 hoặc 09).',

            'province_id.numeric'  => 'Mã Tỉnh / Thành phố phải là giá trị số hợp lệ.',
            'province_id.min'      => 'Mã Tỉnh / Thành phố không hợp lệ.',

            'district_id.numeric'  => 'Mã Quận / Huyện phải là giá trị số hợp lệ.',
            'district_id.min'      => 'Mã Quận / Huyện không hợp lệ.',

            'ward_id.numeric'  => 'Mã Phường / Xã phải là giá trị số hợp lệ.',
            'ward_id.min'      => 'Mã Phường / Xã không hợp lệ.',

            'detailed_address.required' => 'Vui lòng nhập địa chỉ chi tiết (số nhà, tên ngõ, tên đường...).',
            'detailed_address.string'   => 'Địa chỉ chi tiết phải là chuỗi ký tự.',
            'detailed_address.min'      => 'Địa chỉ chi tiết phải có độ dài tối thiểu từ :min ký tự.',
            'detailed_address.max'      => 'Địa chỉ chi tiết không được vượt quá :max ký tự.',

            'is_default.boolean' => 'Trường đặt làm địa chỉ mặc định phải là giá trị boolean (true/false).',

            'label.string' => 'Nhãn địa chỉ phải là chuỗi ký tự.',
            'label.max'    => 'Nhãn địa chỉ không được vượt quá :max ký tự.',

            'notes.string' => 'Ghi chú giao hàng phải là chuỗi ký tự.',
            'notes.max'    => 'Ghi chú giao hàng không được vượt quá :max ký tự.',
        ];
    }
}
