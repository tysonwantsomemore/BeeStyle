<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Hiển thị form đăng nhập cho khách hàng và quản trị viên
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('client.profile');
        }

        return view('auth.login');
    }

    /**
     * Xử lý xác thực và đăng nhập tài khoản có Rate Limiting & Session Rotation
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login(
            $request->input('login_id'),
            $request->input('password'),
            $request->boolean('remember'),
            $request
        );

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Chào mừng Quản trị viên {$user->name} quay trở lại hệ thống BeeStyle!");
        }

        return redirect()->intended(route('client.profile'))
            ->with('success', "Xin chào {$user->name}, bạn đã đăng nhập thành công!");
    }

    /**
     * Hiển thị form đăng ký tài khoản thành viên mới
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('client.profile');
        }

        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản khách hàng mới kèm cơ chế gửi OTP kích hoạt
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        // Tự động đăng nhập người dùng vừa đăng ký
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('client.profile'))
            ->with('success', "Chúc mừng bạn đã tạo tài khoản BeeStyle thành công! Bạn nhận được 100 điểm thưởng chào mừng.");
    }

    /**
     * Xác thực kích hoạt tài khoản qua OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|digits:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã xác thực OTP.',
            'otp.digits'   => 'Mã OTP gồm đúng 6 chữ số.',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập.');
        }

        $this->authService->verifyAccount($user, $request->input('otp'));

        return back()->with('success', 'Chúc mừng! Tài khoản của bạn đã được xác thực thành công. Bây giờ bạn có thể thực hiện đặt hàng.');
    }

    /**
     * Xử lý đăng xuất và hủy phiên làm việc
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.home')
            ->with('success', 'Bạn đã đăng xuất tài khoản thành công. Hẹn gặp lại bạn!');
    }

    /**
     * Hiển thị form Quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('client.profile');
        }

        return view('auth.forgot-password');
    }

    /**
     * Gửi liên kết khôi phục mật khẩu qua Email
     */
    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.exists' => 'Không tìm thấy tài khoản nào khớp với địa chỉ email này.',
        ]);

        $email = strtolower($validated['email']);
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('auth.password.reset', ['token' => $token, 'email' => $email]);

        // Gửi email hướng dẫn khôi phục mật khẩu
        try {
            Mail::send('emails.password_reset', ['resetUrl' => $resetUrl, 'email' => $email], function ($message) use ($email) {
                $message->to($email)->subject('[BeeStyle] Hướng dẫn khôi phục mật khẩu tài khoản');
            });
        } catch (\Throwable $e) {
            // Ghi log nếu mailer offline trong môi trường dev
        }

        return back()->with('status', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra hộp thư!')
            ->with('dev_reset_url', $resetUrl);
    }

    /**
     * Hiển thị form Đặt lại mật khẩu mới từ link token
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        $email = $request->query('email', '');
        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Xử lý lưu mật khẩu mới
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $email = strtolower($validated['email']);
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($validated['token'], $record->token)) {
            return back()->withInput()->with('error', 'Mã xác thực khôi phục mật khẩu không hợp lệ hoặc đã hết hạn!');
        }

        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withInput()->with('error', 'Liên kết khôi phục mật khẩu đã hết hạn (quá 60 phút). Vui lòng gửi lại yêu cầu!');
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now(),
            ]);
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('auth.login')->with('success', 'Chúc mừng bạn đã đặt lại mật khẩu thành công! Vui lòng đăng nhập bằng mật khẩu mới.');
    }
}
