<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display Customer Profile, Order History, Addresses, etc.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để xem thông tin tài khoản!');
        }

        $orders = Order::with(['items.product', 'returns'])->where('user_id', $user->id)->latest()->get();
        $returns = \App\Models\OrderReturn::with(['order.items.product'])->where('user_id', $user->id)->latest()->get();
        $addresses = UserAddress::where('user_id', $user->id)->orderBy('is_default', 'desc')->latest()->get();
        $pendingReviewItems = method_exists($user, 'getPendingReviewItems') ? $user->getPendingReviewItems() : collect();

        return view('client.profile', compact('user', 'orders', 'returns', 'addresses', 'pendingReviewItems'));
    }

    /**
     * Update user profile information (Name, Phone, Email, Address, Avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'gender' => 'nullable|string|in:Nam,Nữ,Khác',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký bởi tài khoản khác.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi tài khoản khác.',
            'avatar.image' => 'File tải lên phải là hình ảnh (jpg, png, webp).',
            'avatar.max' => 'Dung lượng ảnh đại diện tối đa là 3MB.',
        ]);

        $data = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => strtolower($validated['email']),
            'gender' => $validated['gender'] ?? $user->gender,
            'dob' => $validated['dob'] ?? $user->dob,
            'address' => $validated['address'] ?? $user->address,
            'city' => $validated['city'] ?? $user->city,
            'district' => $validated['district'] ?? $user->district,
        ];

        // Handle Avatar file upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $user->update($data);

        return back()->with('success', 'Chúc mừng bạn đã cập nhật hồ sơ cá nhân thành công!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại của bạn.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác. Vui lòng kiểm tra lại!']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'Bạn đã đổi mật khẩu tài khoản thành công!');
    }

    /**
     * Cập nhật thông tin tài khoản ngân hàng nhận tiền hoàn trả (nếu có)
     */
    public function updateBank(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:150',
            'bank_branch' => 'nullable|string|max:150',
        ], [
            'bank_name.required' => 'Vui lòng chọn hoặc nhập tên ngân hàng.',
            'bank_account_number.required' => 'Vui lòng nhập số tài khoản ngân hàng.',
            'bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản (viết hoa).',
        ]);

        $user->update([
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => trim($validated['bank_account_number']),
            'bank_account_name' => mb_strtoupper(trim($validated['bank_account_name']), 'UTF-8'),
            'bank_branch' => $validated['bank_branch'] ? trim($validated['bank_branch']) : null,
        ]);

        return back()->with('success', 'Cập nhật thông tin tài khoản ngân hàng nhận tiền hoàn thành công!');
    }

    /**
     * Thêm một địa chỉ nhận hàng mới vào sổ địa chỉ
     */
    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'label' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
            'notes' => 'nullable|string|max:255',
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận.',
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'city.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($user->addresses()->count() === 0) {
            $isDefault = true;
        }

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'ward' => $validated['ward'] ?? null,
            'address' => $validated['address'],
            'label' => $validated['label'] ?? 'Nhà riêng',
            'is_default' => $isDefault,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('client.profile', ['tab' => 'addresses'])->with('success', 'Đã thêm địa chỉ nhận hàng mới vào sổ địa chỉ!');
    }

    /**
     * Xóa một địa chỉ nhận hàng khỏi sổ địa chỉ
     */
    public function deleteAddress($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $address = $user->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $first = $user->addresses()->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return redirect()->route('client.profile', ['tab' => 'addresses'])->with('success', 'Đã xóa địa chỉ khỏi sổ địa chỉ!');
    }

    /**
     * Cập nhật địa chỉ nhận hàng đã có
     */
    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'label' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'ward' => $validated['ward'] ?? null,
            'address' => $validated['address'],
            'label' => $validated['label'] ?? 'Nhà riêng',
            'is_default' => $isDefault,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('client.profile', ['tab' => 'addresses'])->with('success', 'Đã cập nhật địa chỉ giao hàng thành công!');
    }

    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.login');
        }

        $user->addresses()->update(['is_default' => false]);
        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return redirect()->route('client.profile', ['tab' => 'addresses'])->with('success', 'Đã thiết lập địa chỉ nhận hàng mặc định!');
    }
}

