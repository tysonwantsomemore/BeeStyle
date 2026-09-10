<?php

namespace App\Services\Profile;

use App\Events\ContactVerificationRequestedEvent;
use App\Exceptions\InvalidOtpException;
use App\Models\User;
use App\Models\UserPendingContact;
use App\Services\Auth\OtpVerificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileService
{
    protected OtpVerificationService $otpService;

    public function __construct(OtpVerificationService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Cache key cho hồ sơ người dùng
     */
    public function getProfileCacheKey(int $userId): string
    {
        return "user_profile_{$userId}";
    }

    /**
     * Lấy thông tin hồ sơ người dùng có Cache
     */
    public function getCachedProfile(User $user): User
    {
        return Cache::remember($this->getProfileCacheKey($user->id), now()->addHours(6), function () use ($user) {
            return $user->fresh(['addresses', 'defaultAddress']);
        });
    }

    /**
     * Xóa cache hồ sơ người dùng
     */
    public function clearProfileCache(int $userId): void
    {
        Cache::forget($this->getProfileCacheKey($userId));
    }

    /**
     * Cập nhật thông tin cơ bản của hồ sơ (Tên, Giới tính, Ngày sinh, Địa chỉ, Avatar)
     */
    public function updateProfile(User $user, array $validatedData, ?UploadedFile $avatarFile = null): User
    {
        return DB::transaction(function () use ($user, $validatedData, $avatarFile) {
            $data = [];

            if (isset($validatedData['name'])) {
                $data['name'] = trim(strip_tags($validatedData['name']));
            }

            if (isset($validatedData['gender'])) {
                $data['gender'] = $validatedData['gender'];
            }

            if (isset($validatedData['dob'])) {
                $data['dob'] = $validatedData['dob'];
            }

            if (isset($validatedData['address'])) {
                $data['address'] = trim(strip_tags($validatedData['address']));
            }

            if (isset($validatedData['city'])) {
                $data['city'] = trim(strip_tags($validatedData['city']));
            }

            if (isset($validatedData['district'])) {
                $data['district'] = trim(strip_tags($validatedData['district']));
            }

            // Xử lý Upload Avatar và Storage Cleanup file cũ
            if ($avatarFile) {
                $data['avatar'] = $this->handleAvatarUpload($user, $avatarFile);
            }

            $user->update($data);

            // Xóa và làm mới Cache Redis
            $this->clearProfileCache($user->id);

            return $user->fresh();
        });
    }

    /**
     * Xử lý lưu avatar mới và xóa an toàn file avatar cũ trên disk
     */
    protected function handleAvatarUpload(User $user, UploadedFile $file): string
    {
        // Lưu file avatar mới
        $path = $file->store('avatars', 'public');
        $newAvatarUrl = '/storage/' . $path;

        // Xóa file avatar cũ nếu tồn tại trong thư mục storage và không phải avatar mặc định
        $oldAvatar = $user->avatar;
        if (!empty($oldAvatar) && str_starts_with($oldAvatar, '/storage/')) {
            $relativeOldPath = str_replace('/storage/', '', $oldAvatar);
            if (Storage::disk('public')->exists($relativeOldPath)) {
                Storage::disk('public')->delete($relativeOldPath);
                Log::info("[STORAGE CLEANUP] Đã xóa ảnh avatar cũ: {$relativeOldPath}");
            }
        }

        return $newAvatarUrl;
    }

    /**
     * BƯỚC 1 CỦA QUY TRÌNH 2 BƯỚC: Yêu cầu thay đổi Email hoặc Số điện thoại
     * Không cập nhật trực tiếp DB users; lưu vào bảng tạm user_pending_contacts và gửi OTP xác nhận.
     */
    public function requestContactChange(User $user, string $type, string $newValue): array
    {
        $type = strtolower(trim($type)); // 'email' hoặc 'phone'
        $newValue = trim($newValue);

        if ($type === 'email') {
            $newValue = strtolower($newValue);
        } else {
            $newValue = preg_replace('/[\s\-\.\(\)]+/', '', $newValue);
        }

        return DB::transaction(function () use ($user, $type, $newValue) {
            // Hủy các yêu cầu thay đổi cùng loại trước đó
            UserPendingContact::where('user_id', $user->id)
                ->where('type', $type)
                ->delete();

            $token = Str::random(64);

            $pending = UserPendingContact::create([
                'user_id'    => $user->id,
                'type'       => $type,
                'new_value'  => $newValue,
                'token'      => $token,
                'expires_at' => now()->addMinutes(15),
            ]);

            // Sinh mã OTP gửi đến Email/SĐT mới
            $otp = $this->otpService->generateOtp(
                $newValue,
                "change_{$type}",
                $user,
                ['pending_id' => $pending->id, 'new_value' => $newValue],
                10
            );

            // Bắn sự kiện gửi OTP
            event(new ContactVerificationRequestedEvent($user, $otp, $type, $newValue));

            return [
                'token'      => $token,
                'expires_at' => $pending->expires_at,
                'message'    => "Mã xác thực OTP đã được gửi tới {$newValue}. Vui lòng nhập mã để hoàn tất đổi thông tin.",
            ];
        });
    }

    /**
     * BƯỚC 2 CỦA QUY TRÌNH 2 BƯỚC: Xác nhận OTP để chính thức ghi đè Email / Số điện thoại vào User
     */
    public function confirmContactChange(User $user, string $type, string $newValue, string $otpCode): bool
    {
        $type = strtolower(trim($type));
        $newValue = trim($newValue);

        if ($type === 'email') {
            $newValue = strtolower($newValue);
        } else {
            $newValue = preg_replace('/[\s\-\.\(\)]+/', '', $newValue);
        }

        return DB::transaction(function () use ($user, $type, $newValue, $otpCode) {
            $pending = UserPendingContact::where('user_id', $user->id)
                ->where('type', $type)
                ->where('new_value', $newValue)
                ->latest()
                ->first();

            if (!$pending || $pending->isExpired()) {
                throw new InvalidOtpException('Yêu cầu đổi thông tin đã hết hạn hoặc không tồn tại.');
            }

            // Xác thực mã OTP
            $this->otpService->verifyOtp($newValue, "change_{$type}", $otpCode);

            // Ghi đè vào bảng users và đánh dấu đã xác thực
            if ($type === 'email') {
                $user->update([
                    'email'             => $newValue,
                    'email_verified_at' => now(),
                ]);
            } else {
                $user->update([
                    'phone'             => $newValue,
                    'phone_verified_at' => now(),
                ]);
            }

            // Xóa bản ghi tạm
            $pending->delete();

            // Xóa Cache Redis
            $this->clearProfileCache($user->id);

            return true;
        });
    }
}
