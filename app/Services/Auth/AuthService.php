<?php

namespace App\Services\Auth;

use App\Events\AccountRegisteredEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected AuthRateLimiterService $rateLimiter;
    protected OtpVerificationService $otpService;
    protected SessionManagerService $sessionManager;

    public function __construct(
        AuthRateLimiterService $rateLimiter,
        OtpVerificationService $otpService,
        SessionManagerService $sessionManager
    ) {
        $this->rateLimiter = $rateLimiter;
        $this->otpService = $otpService;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Xử lý đăng ký tài khoản mới kèm khởi tạo mã OTP kích hoạt
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $email = !empty($data['email']) ? strtolower(trim($data['email'])) : null;
            $phone = !empty($data['phone']) ? preg_replace('/[\s\-\.\(\)]+/', '', trim($data['phone'])) : null;

            $user = User::create([
                'name'        => trim($data['name']),
                'email'       => $email,
                'phone'       => $phone,
                'address'     => $data['address'] ?? null,
                'city'        => $data['city'] ?? 'Hồ Chí Minh',
                'role'        => 'customer',
                'rank'        => 'Thành viên Mới',
                'points'      => 100, // Điểm chào mừng
                'total_spent' => 0,
                'status'      => 'active', // hoặc 'unverified' nếu kích hoạt luồng OTP
                'avatar'      => '/assets/img/team/40x40/58.webp',
                'password'    => Hash::make($data['password']),
            ]);

            // Sinh mã OTP kích hoạt tài khoản
            $primaryContact = $email ?: $phone;
            $otp = null;
            if ($primaryContact) {
                $otp = $this->otpService->generateOtp($primaryContact, 'register_activation', $user);
            }

            // Bắn sự kiện đăng ký tài khoản thành công
            event(new AccountRegisteredEvent($user, $otp));

            return $user;
        });
    }

    /**
     * Xử lý đăng nhập an toàn có tích hợp Rate Limiting và Session Rotation
     *
     * @throws \App\Exceptions\RateLimitExceededException|\Illuminate\Validation\ValidationException
     */
    public function login(string $loginId, string $password, bool $remember, Request $request): User
    {
        $cleanId = trim($loginId);
        $fieldType = filter_var($cleanId, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // 1. Kiểm tra Rate Limiting trước khi thực hiện xác thực
        $this->rateLimiter->ensureIsNotRateLimited($cleanId, $request->ip());

        // 2. Thử xác thực tài khoản
        if (!Auth::attempt([$fieldType => $cleanId, 'password' => $password], $remember)) {
            $this->rateLimiter->hit($cleanId, $request->ip());
            $remaining = $this->rateLimiter->remainingAttempts($cleanId, $request->ip());

            throw ValidationException::withMessages([
                'login_id' => ["Thông tin đăng nhập hoặc mật khẩu không chính xác. Bạn còn {$remaining} lần thử lại trước khi tài khoản bị khóa tạm thời."],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // 3. Kiểm tra trạng thái tài khoản
        if ($user->status === 'banned') {
            Auth::logout();
            throw ValidationException::withMessages([
                'login_id' => ['Tài khoản của bạn đã bị khóa do vi phạm chính sách của BeeStyle. Vui lòng liên hệ CSKH!'],
            ]);
        }

        // 4. Xóa bộ đếm Rate Limiting khi đăng nhập thành công
        $this->rateLimiter->clear($cleanId, $request->ip());

        // 5. Xoay vòng Session ID & CSRF Token chống tấn công Session Fixation
        $this->sessionManager->rotateSession($request);

        return $user;
    }

    /**
     * Xác thực OTP kích hoạt tài khoản
     */
    public function verifyAccount(User $user, string $otpCode): bool
    {
        return $this->otpService->activateUserAccount($user, $otpCode);
    }
}
