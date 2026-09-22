<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserManagementController extends Controller
{
    /**
     * Danh sách tài khoản người dùng & phân quyền hệ thống kèm bộ lọc
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = $request->query('role');
        $status = $request->query('status');
        $security = $request->query('security');
        $sort = $request->query('sort', 'latest');

        $query = User::withCount('orders');

        // 1. Tìm kiếm theo từ khóa: Tên, Email, SĐT, ID
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $cleanId = preg_replace('/[^\d]/', '', $search);
                if (!empty($cleanId) && is_numeric($cleanId)) {
                    $q->orWhere('id', (int) $cleanId);
                }
                $q->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. Lọc theo vai trò (Role)
        if (in_array($role, ['admin', 'customer', 'shipper'])) {
            $query->where('role', $role);
        }

        // 3. Lọc theo trạng thái hoạt động (Status)
        if (in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        // 4. Lọc theo trạng thái bảo mật
        if ($security === 'verified') {
            $query->where(fn($q) => $q->whereNotNull('email_verified_at')->orWhereNotNull('phone_verified_at'));
        } elseif ($security === 'unverified') {
            $query->whereNull('email_verified_at')->whereNull('phone_verified_at');
        } elseif ($security === 'temp_locked') {
            $query->whereNotNull('locked_until')->where('locked_until', '>', now());
        }

        // 5. Sắp xếp danh sách
        match ($sort) {
            'oldest'      => $query->oldest(),
            'name_asc'    => $query->orderBy('name', 'asc'),
            'name_desc'   => $query->orderBy('name', 'desc'),
            'admin_first' => $query->orderByRaw("FIELD(role, 'admin', 'shipper', 'customer')")->latest(),
            default       => $query->latest(),
        };

        $users = $query->paginate(12)->withQueryString();

        // THỐNG KÊ TỔNG QUAN TÀI KHOẢN HỆ THỐNG
        $stats = [
            'total'        => User::count(),
            'admins'       => User::where('role', 'admin')->count(),
            'customers'    => User::where('role', 'customer')->count(),
            'shippers'     => User::where('role', 'shipper')->count(),
            'locked'       => User::where('status', 'banned')->count(),
            'verified'     => User::where(fn($q) => $q->whereNotNull('email_verified_at')->orWhereNotNull('phone_verified_at'))->count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'role', 'status', 'security', 'sort'));
    }

    /**
     * Tạo mới tài khoản Quản trị viên (Admin) hoặc Nhân viên/Người dùng
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'role'     => ['required', 'in:admin,customer,shipper'],
            'status'   => ['required', 'in:active,banned'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'     => 'Vui lòng nhập họ và tên.',
            'email.required'    => 'Vui lòng nhập địa chỉ email.',
            'email.email'       => 'Địa chỉ email không đúng định dạng.',
            'email.unique'      => 'Địa chỉ email này đã tồn tại trong hệ thống.',
            'phone.unique'      => 'Số điện thoại này đã được đăng ký cho tài khoản khác.',
            'role.required'     => 'Vui lòng chọn vai trò tài khoản.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có tối thiểu 6 ký tự.',
            'password.confirmed'=> 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name'              => trim($validated['name']),
            'email'             => strtolower(trim($validated['email'])),
            'phone'             => !empty($validated['phone']) ? preg_replace('/[\s\-\.\(\)]+/', '', trim($validated['phone'])) : null,
            'role'              => $validated['role'],
            'status'            => $validated['status'],
            'password'          => Hash::make($validated['password']),
            'avatar'            => '/assets/img/team/40x40/57.webp',
            'email_verified_at' => now(), // Mặc định tài khoản do admin tạo được xác thực ngay
            'points'            => 0,
            'total_spent'       => 0,
        ]);

        $roleLabel = match ($user->role) {
            'admin' => 'Quản trị viên',
            'shipper' => 'Bưu tá giao hàng',
            default => 'Khách hàng',
        };

        return back()->with('success', "Đã tạo tài khoản {$roleLabel} thành công cho {$user->name} ({$user->email}).");
    }

    /**
     * Cập nhật thông tin, vai trò và trạng thái tài khoản
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'phone'  => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'role'   => ['required', 'in:admin,customer,shipper'],
            'status' => ['required', 'in:active,banned'],
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'phone.unique'  => 'Số điện thoại này đã được sử dụng bởi người dùng khác.',
            'role.in'       => 'Vai trò tài khoản không hợp lệ.',
            'status.in'     => 'Trạng thái hoạt động không hợp lệ.',
        ]);

        // Kiểm tra an toàn cho tài khoản đang đăng nhập
        if ($user->is(Auth::user())) {
            if ($validated['role'] !== 'admin') {
                return back()->with('error', 'Bạn không thể tự hạ quyền Quản trị viên của chính mình.');
            }
            if ($validated['status'] === 'banned') {
                return back()->with('error', 'Bạn không thể tự khóa tài khoản đang đăng nhập.');
            }
        }

        // Đảm bảo hệ thống luôn có ít nhất 1 Quản trị viên
        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Hệ thống phải luôn có ít nhất một Quản trị viên cấp cao.');
        }

        $user->update([
            'name'   => trim($validated['name']),
            'phone'  => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'role'   => $validated['role'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Đã cập nhật thông tin và phân quyền thành công cho {$user->name}.");
    }

    /**
     * Đổi / Đặt lại mật khẩu tài khoản người dùng
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'new_password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'       => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        $user->update([
            'password'              => Hash::make($validated['new_password']),
            'password_changed_at'   => now(),
            'locked_until'          => null,
            'failed_login_attempts' => 0,
        ]);

        return back()->with('success', "Đã đặt lại mật khẩu mới cho tài khoản {$user->name} ({$user->email}) thành công.");
    }

    /**
     * Khóa hoặc Mở khóa tài khoản nhanh (Toggle Status)
     */
    public function toggleStatus(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }

        if ($user->role === 'admin' && $user->status === 'active' && User::where('role', 'admin')->where('status', 'active')->count() <= 1) {
            return back()->with('error', 'Không thể khóa Quản trị viên duy nhất đang hoạt động của hệ thống.');
        }

        $newStatus = ($user->status === 'banned') ? 'active' : 'banned';
        $user->update([
            'status'                => $newStatus,
            'locked_until'          => null,
            'failed_login_attempts' => 0,
        ]);

        $statusText = ($newStatus === 'active') ? 'mở khóa hoạt động' : 'khóa tạm thời';

        return back()->with('success', "Đã {$statusText} tài khoản của {$user->name} thành công.");
    }

    /**
     * Xóa tài khoản người dùng với các ràng buộc an toàn tuyệt đối
     */
    public function destroy(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản đang đăng nhập.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Không thể xóa Quản trị viên duy nhất của hệ thống.');
        }

        // Bảo vệ toàn vẹn dữ liệu đơn hàng và kế toán
        $ordersCount = $user->orders()->count();
        if ($ordersCount > 0) {
            return back()->with('error', "Không thể xóa tài khoản \"{$user->name}\" vì đã có {$ordersCount} đơn hàng phát sinh trong hệ thống. Để bảo đảm toàn vẹn dữ liệu kế toán và lịch sử giao dịch, bạn hãy dùng chức năng KHÓA TÀI KHOẢN thay vì xóa.");
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Đã xóa vĩnh viễn tài khoản người dùng \"{$userName}\" khỏi hệ thống.");
    }

    /**
     * Xuất danh sách tài khoản ra file CSV hỗ trợ Excel (UTF-8 BOM)
     */
    public function export(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::withCount('orders')->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (in_array($role, ['admin', 'customer', 'shipper'])) {
            $query->where('role', $role);
        }

        if (in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        $users = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="danh_sach_tai_khoan_beestyle_' . date('Y-m-d_His') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, [
                'ID',
                'Họ và Tên',
                'Email',
                'Số Điện Thoại',
                'Vai Trò',
                'Trạng Thái',
                'Số Đơn Hàng',
                'Xác Thực Email',
                'Xác Thực SĐT',
                'Ngày Đăng Ký'
            ]);

            foreach ($users as $u) {
                fputcsv($handle, [
                    $u->id,
                    $u->name,
                    $u->email,
                    $u->phone ?? '',
                    match ($u->role) {
                        'admin' => 'Quản trị viên',
                        'shipper' => 'Bưu tá giao hàng',
                        default => 'Khách hàng',
                    },
                    ($u->status === 'banned') ? 'Bị khóa' : 'Hoạt động',
                    $u->orders_count ?? 0,
                    $u->email_verified_at ? 'Đã xác thực' : 'Chưa',
                    $u->phone_verified_at ? 'Đã xác thực' : 'Chưa',
                    $u->created_at ? $u->created_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
