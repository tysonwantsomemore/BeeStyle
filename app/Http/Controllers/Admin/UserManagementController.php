<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $role = $request->query('role');
        $status = $request->query('status');

        $users = User::withCount('orders')
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")))
            ->when(in_array($role, ['admin', 'customer']), fn ($query) => $query->where('role', $role))
            ->when(in_array($status, ['active', 'banned']), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'locked' => User::where('status', 'banned')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'role', 'status'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,customer'],
            'status' => ['required', 'in:active,banned'],
        ]);

        if ($user->is(auth()->user())) {
            return back()->with('error', 'Bạn không thể tự thay đổi quyền hoặc khóa tài khoản đang đăng nhập.');
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Hệ thống phải luôn có ít nhất một quản trị viên.');
        }

        $user->update($validated);

        return back()->with('success', "Đã cập nhật quyền và trạng thái cho {$user->name}.");
    }
}
