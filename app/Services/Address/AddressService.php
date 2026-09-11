<?php

namespace App\Services\Address;

use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Ward;
use App\Services\Administrative\AdministrativeService;
use Illuminate\Support\Facades\DB;

class AddressService
{
    protected AdministrativeService $adminService;

    public function __construct(AdministrativeService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Thêm địa chỉ mới vào sổ địa chỉ (có kiểm tra Cascade Consistency & Default Address Switch)
     */
    public function createAddress(User $user, array $validatedData): UserAddress
    {
        return DB::transaction(function () use ($user, $validatedData) {
            $provinceId = (int) ($validatedData['province_id'] ?? 0);
            $districtId = (int) ($validatedData['district_id'] ?? 0);
            $wardId = (int) ($validatedData['ward_id'] ?? 0);

            // 1. Kiểm tra tính nhất quán đơn vị hành chính 3 cấp
            $adminNames = $this->adminService->validateCascade($provinceId, $districtId, $wardId);

            // 2. Xử lý trạng thái địa chỉ mặc định
            $isDefault = !empty($validatedData['is_default']);
            if ($user->addresses()->count() === 0) {
                $isDefault = true;
            }

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            return $user->addresses()->create([
                'recipient_name' => trim(strip_tags($validatedData['receiver_name'] ?? $validatedData['recipient_name'])),
                'phone'          => preg_replace('/[\s\-\.\(\)]+/', '', $validatedData['receiver_phone'] ?? $validatedData['phone']),
                'province_id'    => $provinceId ?: null,
                'district_id'    => $districtId ?: null,
                'ward_id'        => $wardId ?: null,
                'city'           => $adminNames['province'],
                'district'       => $adminNames['district'],
                'ward'           => $adminNames['ward'],
                'address'        => trim(strip_tags($validatedData['detailed_address'] ?? $validatedData['address'])),
                'label'          => $validatedData['label'] ?? 'Nhà riêng',
                'is_default'     => $isDefault,
                'notes'          => isset($validatedData['notes']) ? trim(strip_tags($validatedData['notes'])) : null,
            ]);
        });
    }

    /**
     * Cập nhật địa chỉ nhận hàng
     */
    public function updateAddress(UserAddress $address, array $validatedData): UserAddress
    {
        return DB::transaction(function () use ($address, $validatedData) {
            $provinceId = (int) ($validatedData['province_id'] ?? $address->province_id ?? 0);
            $districtId = (int) ($validatedData['district_id'] ?? $address->district_id ?? 0);
            $wardId = (int) ($validatedData['ward_id'] ?? $address->ward_id ?? 0);

            $adminNames = $this->adminService->validateCascade($provinceId, $districtId, $wardId);

            $isDefault = !empty($validatedData['is_default']);
            if ($isDefault) {
                UserAddress::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->update([
                'recipient_name' => trim(strip_tags($validatedData['receiver_name'] ?? $validatedData['recipient_name'] ?? $address->recipient_name)),
                'phone'          => preg_replace('/[\s\-\.\(\)]+/', '', $validatedData['receiver_phone'] ?? $validatedData['phone'] ?? $address->phone),
                'province_id'    => $provinceId ?: $address->province_id,
                'district_id'    => $districtId ?: $address->district_id,
                'ward_id'        => $wardId ?: $address->ward_id,
                'city'           => $adminNames['province'],
                'district'       => $adminNames['district'],
                'ward'           => $adminNames['ward'],
                'address'        => trim(strip_tags($validatedData['detailed_address'] ?? $validatedData['address'] ?? $address->address)),
                'label'          => $validatedData['label'] ?? $address->label,
                'is_default'     => $isDefault ?: $address->is_default,
                'notes'          => isset($validatedData['notes']) ? trim(strip_tags($validatedData['notes'])) : $address->notes,
            ]);

            return $address->fresh();
        });
    }

    /**
     * Xóa địa chỉ và tự động chuyển địa chỉ mặc định sang địa chỉ gần nhất còn lại
     */
    public function deleteAddress(UserAddress $address): bool
    {
        return DB::transaction(function () use ($address) {
            $userId = $address->user_id;
            $wasDefault = $address->is_default;

            $address->delete();

            // Nếu vừa xóa địa chỉ mặc định, tự động gán địa chỉ còn lại gần nhất làm mặc định
            if ($wasDefault) {
                $nextDefault = UserAddress::where('user_id', $userId)
                    ->latest()
                    ->first();

                if ($nextDefault) {
                    $nextDefault->update(['is_default' => true]);
                }
            }

            return true;
        });
    }

    /**
     * Đặt một địa chỉ làm mặc định
     */
    public function setDefaultAddress(UserAddress $address): UserAddress
    {
        return DB::transaction(function () use ($address) {
            UserAddress::where('user_id', $address->user_id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
            return $address->fresh();
        });
    }

    /**
     * TẠO SNAPSHOT ĐỊA CHỈ CHO ĐƠN HÀNG (ADDRESS SNAPSHOTTING)
     * Sao chép toàn bộ thông tin địa chỉ dưới dạng text/JSON bất biến vào thời điểm chốt đơn,
     * ngăn chặn tuyệt đối tình trạng sai lệch dữ liệu giao hàng khi khách hàng sửa/xóa địa chỉ sau này.
     */
    public function createOrderAddressSnapshot(UserAddress|array $source): array
    {
        if ($source instanceof UserAddress) {
            $receiverName = $source->recipient_name;
            $receiverPhone = $source->phone;
            $detailedAddress = $source->address;
            $ward = $source->wardRelation?->name ?? $source->ward;
            $district = $source->districtRelation?->name ?? $source->district;
            $city = $source->province?->name ?? $source->city;
            $label = $source->label ?? 'Nhà riêng';
        } else {
            $receiverName = $source['receiver_name'] ?? $source['customer_name'] ?? $source['recipient_name'] ?? '';
            $receiverPhone = $source['receiver_phone'] ?? $source['customer_phone'] ?? $source['phone'] ?? '';
            $detailedAddress = $source['detailed_address'] ?? $source['shipping_address'] ?? $source['address'] ?? '';
            $ward = $source['ward'] ?? '';
            $district = $source['district'] ?? '';
            $city = $source['city'] ?? $source['province'] ?? 'Hồ Chí Minh';
            $label = $source['label'] ?? 'Nhà riêng';
        }

        $fullAddress = implode(', ', array_filter([$detailedAddress, $ward, $district, $city]));

        return [
            'receiver_name'    => trim($receiverName),
            'receiver_phone'   => trim($receiverPhone),
            'detailed_address' => trim($detailedAddress),
            'ward'             => trim($ward),
            'district'         => trim($district),
            'city'             => trim($city),
            'full_address'     => $fullAddress,
            'label'            => $label,
            'snapshot_at'      => now()->toIso8601String(),
        ];
    }
}
