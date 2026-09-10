<?php

namespace App\Services\Financial;

use App\Exceptions\PendingPayoutLockException;
use App\Exceptions\StepUpAuthenticationException;
use App\Models\OrderReturn;
use App\Models\User;
use App\Services\Auth\OtpVerificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BankAccountService
{
    protected OtpVerificationService $otpService;

    public function __construct(OtpVerificationService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Kiểm tra xem người dùng có yêu cầu hoàn tiền/rút tiền đang chờ xử lý hay không
     */
    public function hasPendingPayouts(User $user): bool
    {
        return OrderReturn::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing', 'approved', 'received'])
            ->where('refund_method', 'bank')
            ->exists();
    }

    /**
     * Xác thực bảo mật bổ sung (Step-up Authentication) bằng Mật khẩu hoặc OTP
     *
     * @throws \App\Exceptions\StepUpAuthenticationException
     */
    public function verifyStepUpAuth(User $user, ?string $password = null, ?string $otp = null): void
    {
        // 1. Kiểm tra xác thực qua mật khẩu hiện tại
        if (!empty($password)) {
            if (Hash::check($password, $user->password)) {
                return;
            }
        }

        // 2. Kiểm tra xác thực qua mã OTP nếu có truyền
        if (!empty($otp)) {
            $contact = $user->email ?: $user->phone;
            try {
                $this->otpService->verifyOtp($contact, 'step_up_auth', $otp);
                return;
            } catch (\Throwable $e) {
                throw new StepUpAuthenticationException('Mã OTP xác thực thay đổi tài khoản ngân hàng không hợp lệ: ' . $e->getMessage());
            }
        }

        throw new StepUpAuthenticationException('Vui lòng nhập mật khẩu tài khoản hoặc mã OTP xác thực để thực hiện thay đổi thông tin ngân hàng.');
    }

    /**
     * Cập nhật thông tin tài khoản ngân hàng an toàn có kiểm tra Pending Payout Lock và Step-up Auth
     *
     * @throws \App\Exceptions\PendingPayoutLockException|\App\Exceptions\StepUpAuthenticationException
     */
    public function updateBankAccount(User $user, array $validatedData, ?string $confirmPassword = null, ?string $confirmOtp = null): User
    {
        // 1. Kiểm tra Pending Payout Lock: Chặn cập nhật nếu đang có giao dịch hoàn tiền chờ giải ngân
        if ($this->hasPendingPayouts($user)) {
            throw new PendingPayoutLockException(
                'Không thể cập nhật tài khoản ngân hàng do bạn đang có yêu cầu đổi trả / hoàn tiền đang trong quá trình xử lý. Vui lòng đợi giao dịch hoàn tất hoặc liên hệ CSKH!'
            );
        }

        // 2. Kiểm tra Step-up Authentication
        $this->verifyStepUpAuth($user, $confirmPassword, $confirmOtp);

        // 3. Thực thi cập nhật trong DB Transaction
        return DB::transaction(function () use ($user, $validatedData) {
            $bankCode = strtoupper(trim($validatedData['bank_code']));
            $accNumber = preg_replace('/\s+/', '', (string) $validatedData['account_number']);
            $accHolder = mb_strtoupper(preg_replace('/\s+/', ' ', trim($validatedData['account_holder_name'])), 'UTF-8');
            $branch = isset($validatedData['bank_branch']) ? trim($validatedData['bank_branch']) : null;

            $user->update([
                'bank_name'           => $bankCode,
                'bank_account_number' => $accNumber,
                'bank_account_name'   => $accHolder,
                'bank_branch'         => $branch,
            ]);

            return $user->fresh();
        });
    }
}
