<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidOtpException;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtpVerificationService
{
    /**
     * Tạo mã OTP 6 chữ số an toàn và lưu vào cơ sở dữ liệu
     */
    public function generateOtp(string $contact, string $type, ?User $user = null, ?array $payload = null, int $ttlMinutes = 5): VerificationCode
    {
        $contact = trim(strtolower($contact));

        return DB::transaction(function () use ($contact, $type, $user, $payload, $ttlMinutes) {
            // Vô hiệu hóa các mã OTP cũ chưa dùng của contact và type này
            VerificationCode::where('contact', $contact)
                ->where('type', $type)
                ->where('is_used', false)
                ->update(['is_used' => true]);

            // Sinh mã OTP 6 chữ số ngẫu nhiên
            $code = (string) random_int(100000, 999999);
            $token = Str::random(64);

            return VerificationCode::create([
                'user_id'      => $user?->id,
                'contact'      => $contact,
                'type'         => $type,
                'code'         => $code,
                'token'        => $token,
                'attempts'     => 0,
                'max_attempts' => 5,
                'is_used'      => false,
                'expires_at'   => now()->addMinutes($ttlMinutes),
                'payload'      => $payload,
                'ip_address'   => request()->ip(),
            ]);
        });
    }

    /**
     * Xác thực mã OTP
     *
     * @throws \App\Exceptions\InvalidOtpException
     */
    public function verifyOtp(string $contact, string $type, string $inputCode): VerificationCode
    {
        $contact = trim(strtolower($contact));
        $inputCode = trim($inputCode);

        $otpRecord = VerificationCode::where('contact', $contact)
            ->where('type', $type)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            throw new InvalidOtpException('Không tìm thấy yêu cầu xác thực hoặc mã OTP đã được sử dụng.');
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->update(['is_used' => true]);
            throw new InvalidOtpException('Mã xác thực OTP đã hết hạn sử dụng. Vui lòng yêu cầu mã mới.');
        }

        if ($otpRecord->attempts >= $otpRecord->max_attempts) {
            $otpRecord->update(['is_used' => true]);
            throw new InvalidOtpException('Bạn đã nhập sai mã OTP quá 5 lần. Mã này đã bị vô hiệu hóa.');
        }

        // Tăng số lần thử
        $otpRecord->increment('attempts');

        if (!hash_equals((string) $otpRecord->code, $inputCode)) {
            $remaining = max(0, $otpRecord->max_attempts - $otpRecord->attempts);
            throw new InvalidOtpException("Mã OTP không chính xác. Bạn còn {$remaining} lần thử lại.");
        }

        // Đánh dấu đã sử dụng thành công
        $otpRecord->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return $otpRecord;
    }

    /**
     * Kích hoạt tài khoản người dùng qua mã OTP
     */
    public function activateUserAccount(User $user, string $code): bool
    {
        $contact = $user->email ?: $user->phone;
        $this->verifyOtp($contact, 'register_activation', $code);

        $updateData = ['status' => 'active'];

        if (!empty($user->email)) {
            $updateData['email_verified_at'] = now();
        }
        if (!empty($user->phone)) {
            $updateData['phone_verified_at'] = now();
        }

        $user->update($updateData);

        return true;
    }
}
