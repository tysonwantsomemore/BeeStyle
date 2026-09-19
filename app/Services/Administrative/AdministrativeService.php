<?php

namespace App\Services\Administrative;

use App\Exceptions\AdministrativeMismatchException;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;

class AdministrativeService
{
    /**
     * Lấy danh sách toàn bộ Tỉnh/Thành phố
     */
    public function getProvinces(): Collection
    {
        return Province::orderBy('name')->get();
    }

    /**
     * Lấy danh sách Quận/Huyện theo Tỉnh/Thành phố
     */
    public function getDistrictsByProvince(int $provinceId): Collection
    {
        return District::where('province_id', $provinceId)->orderBy('name')->get();
    }

    /**
     * Lấy danh sách Phường/Xã theo Quận/Huyện
     */
    public function getWardsByDistrict(int $districtId): Collection
    {
        return Ward::where('district_id', $districtId)->orderBy('name')->get();
    }

    /**
     * Kiểm tra tính nhất quán theo cấp hành chính (Cascade Consistency Check)
     *
     * @throws \App\Exceptions\AdministrativeMismatchException
     */
    public function validateCascade(int $provinceId, int $districtId, int $wardId): array
    {
        $province = $provinceId > 0 ? Province::find($provinceId) : null;
        $district = ($districtId > 0 && $provinceId > 0)
            ? District::where('id', $districtId)->where('province_id', $provinceId)->first()
            : ($districtId > 0 ? District::find($districtId) : null);
        $ward = ($wardId > 0 && $districtId > 0)
            ? Ward::where('id', $wardId)->where('district_id', $districtId)->first()
            : ($wardId > 0 ? Ward::find($wardId) : null);

        // Kiểm tra tính toàn vẹn nếu có đủ cả 2 cấp
        if ($provinceId > 0 && $districtId > 0 && $province && !$district) {
            throw new AdministrativeMismatchException('Quận/Huyện đã chọn không thuộc Tỉnh/Thành phố đã chọn.');
        }

        if ($districtId > 0 && $wardId > 0 && $district && !$ward) {
            throw new AdministrativeMismatchException('Phường/Xã đã chọn không thuộc Quận/Huyện đã chọn.');
        }

        return [
            'province' => $province?->name ?? ($provinceId > 0 ? "Tỉnh #{$provinceId}" : ''),
            'district' => $district?->name ?? ($districtId > 0 ? "Quận #{$districtId}" : ''),
            'ward'     => $ward?->name ?? ($wardId > 0 ? "Phường #{$wardId}" : ''),
        ];
    }
}
