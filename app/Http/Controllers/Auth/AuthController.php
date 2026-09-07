<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
