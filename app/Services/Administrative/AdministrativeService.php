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
        $province = Province::find($provinceId);
        $district = District::where('id', $districtId)->where('province_id', $provinceId)->first();
        $ward = Ward::where('id', $wardId)->where('district_id', $districtId)->first();

        // Nếu bảng database chưa được seed dữ liệu hoàn toàn, cho phép fallback theo ID nhưng kiểm tra tính toàn vẹn
        if ($province && !$district) {
            throw new AdministrativeMismatchException('Quận/Huyện đã chọn không thuộc Tỉnh/Thành phố đã chọn.');
        }

        if ($district && !$ward) {
            throw new AdministrativeMismatchException('Phường/Xã đã chọn không thuộc Quận/Huyện đã chọn.');
        }

        return [
            'province' => $province?->name ?? "Tỉnh #{$provinceId}",
            'district' => $district?->name ?? "Quận #{$districtId}",
            'ward'     => $ward?->name ?? "Phường #{$wardId}",
        ];
    }
}
