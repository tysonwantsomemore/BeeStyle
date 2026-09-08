<?php

namespace App\Rules;

use App\Models\District;
use App\Models\Ward;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdministrativeCascade implements ValidationRule
{
    protected ?int $provinceId;
    protected ?int $districtId;
    protected ?int $wardId;

    public function __construct(?int $provinceId = null, ?int $districtId = null, ?int $wardId = null)
    {
        $this->provinceId = $provinceId;
        $this->districtId = $districtId;
        $this->wardId = $wardId;
    }

    /**
     * Chạy quy tắc xác thực tính nhất quán của đơn vị hành chính 3 cấp (Tỉnh -> Huyện -> Xã)
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Kiểm tra District có thuộc Province không
        if ($this->provinceId && $this->districtId) {
            $districtExists = District::where('id', $this->districtId)
                ->where('province_id', $this->provinceId)
                ->exists();

            if (!$districtExists) {
                $fail('Quận/Huyện đã chọn không thuộc Tỉnh/Thành phố này.');
                return;
            }
        }

        // 2. Kiểm tra Ward có thuộc District không
        if ($this->districtId && $this->wardId) {
            $wardExists = Ward::where('id', $this->wardId)
                ->where('district_id', $this->districtId)
                ->exists();

            if (!$wardExists) {
                $fail('Phường/Xã đã chọn không thuộc Quận/Huyện này.');
            }
        }
    }
}
