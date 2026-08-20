<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
     * Xử lý xác thực và đăng nhập tài khoản
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'login_id.required' => 'Vui lòng nhập Email hoặc Số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loginId = trim($credentials['login_id']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Kiểm tra thông tin đăng nhập là định dạng Email hay Số điện thoại
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$fieldType => $loginId, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'banned') {
                Auth::logout();
                return back()->with('error', 'Tài khoản của bạn đã bị tạm khóa do vi phạm chính sách của BeeStyle.');
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', "Chào mừng Quản trị viên {$user->name} quay trở lại hệ thống BeeStyle!");
            }

            return redirect()->intended(route('client.profile'))
                ->with('success', "Xin chào {$user->name}, bạn đã đăng nhập thành công!");
        }

        return back()->withInput($request->only('login_id', 'remember'))
            ->with('error', 'Email/Số điện thoại hoặc mật khẩu không chính xác. Vui lòng thử lại!');
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
     * Xử lý đăng ký tài khoản khách hàng mới
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'accepted',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký tài khoản.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký tài khoản.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'terms.accepted' => 'Bạn cần đồng ý với Điều khoản và Chính sách của BeeStyle.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? 'Hồ Chí Minh',
            'role' => 'customer',
            'rank' => 'Thành viên Mới',
            'points' => 100, // Điểm thưởng chào mừng thành viên mới
            'total_spent' => 0,
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/58.webp',
            'password' => Hash::make($validated['password']),
        ]);

        // Tự động đăng nhập người dùng vừa đăng ký
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('client.profile'))
            ->with('success', "Chúc mừng bạn đã tạo tài khoản BeeStyle thành công! Bạn nhận được 100 điểm thưởng chào mừng.");
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
