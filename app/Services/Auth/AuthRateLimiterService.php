<?php

namespace App\Services\Auth;

use App\Exceptions\RateLimitExceededException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthRateLimiterService
{
    /**
     * Số lần đăng nhập sai tối đa trước khi khóa
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Thời gian khóa cơ sở (15 phút = 900 giây)
     */
    public const BASE_DECAY_SECONDS = 900;

    /**
     * Tạo throttle key duy nhất dựa trên tên đăng nhập và IP
     */
    public function throttleKey(string $identifier, ?string $ip = null): string
    {
        $cleanIdentifier = Str::lower(trim($identifier));
        $clientIp = $ip ?? request()->ip();
        return 'login_attempt:' . sha1($cleanIdentifier . '|' . $clientIp);
    }

    /**
     * Kiểm tra xem định danh hiện tại có đang bị khóa hay không
     *
     * @throws \App\Exceptions\RateLimitExceededException
     */
    public function ensureIsNotRateLimited(string $identifier, ?string $ip = null): void
    {
        $key = $this->throttleKey($identifier, $ip);
        $lockKey = $key . ':lock';

        if (Cache::has($lockKey)) {
            $remainingSeconds = (int) Cache::get($lockKey . ':remaining_ttl', self::BASE_DECAY_SECONDS);
            $minutes = ceil($remainingSeconds / 60);

            throw new RateLimitExceededException(
                "Bạn đã đăng nhập sai quá " . self::MAX_ATTEMPTS . " lần. Tài khoản tạm thời bị khóa trong {$minutes} phút để bảo vệ an toàn.",
                $remainingSeconds
            );
        }
    }

    /**
     * Ghi nhận một lần đăng nhập sai và kích hoạt khóa theo cấp số nhân nếu vượt ngưỡng
     */
    public function hit(string $identifier, ?string $ip = null): int
    {
        $key = $this->throttleKey($identifier, $ip);
        $lockCountKey = $key . ':lock_count';

        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes(15));

        if ($attempts >= self::MAX_ATTEMPTS) {
            // Tính toán thời gian khóa theo cấp số nhân:
            // Lần 1 vượt: 15 phút (900s)
            // Lần 2 vượt: 30 phút (1800s)
            // Lần 3 vượt: 60 phút (3600s)
            // Lần 4+ vượt: 120 phút (7200s)
            $lockCount = Cache::get($lockCountKey, 0) + 1;
            Cache::put($lockCountKey, $lockCount, now()->addHours(24));

            $multiplier = min(pow(2, $lockCount - 1), 8); // Tối đa x8 (120 phút)
            $decaySeconds = (int) (self::BASE_DECAY_SECONDS * $multiplier);

            $lockKey = $key . ':lock';
            Cache::put($lockKey, true, now()->addSeconds($decaySeconds));
            Cache::put($lockKey . ':remaining_ttl', $decaySeconds, now()->addSeconds($decaySeconds));

            // Reset bộ đếm số lần sai trong phiên để bắt đầu chu kỳ khóa mới
            Cache::forget($key);

            $minutes = ceil($decaySeconds / 60);
            throw new RateLimitExceededException(
                "Đăng nhập sai quá nhiều lần! Tài khoản của bạn đã bị khóa tạm thời trong {$minutes} phút.",
                $decaySeconds
            );
        }

        return $attempts;
    }

    /**
     * Xóa sạch các bộ đếm sau khi đăng nhập thành công
     */
    public function clear(string $identifier, ?string $ip = null): void
    {
        $key = $this->throttleKey($identifier, $ip);
        Cache::forget($key);
        Cache::forget($key . ':lock');
        Cache::forget($key . ':lock:remaining_ttl');
        Cache::forget($key . ':lock_count');
    }

    /**
     * Lấy số lượt thử còn lại
     */
    public function remainingAttempts(string $identifier, ?string $ip = null): int
    {
        $key = $this->throttleKey($identifier, $ip);
        $attempts = (int) Cache::get($key, 0);
        return max(0, self::MAX_ATTEMPTS - $attempts);
    }
}
