<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\ShippingAddressRequest;
use App\Http\Requests\Bank\UpdateBankAccountRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\ConfirmContactChangeRequest;
use App\Http\Requests\Profile\RequestContactChangeRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Order; 
use App\Models\User;
use App\Models\UserAddress;
use App\Services\Address\AddressService;
use App\Services\Financial\BankAccountService;
use App\Services\Profile\ProfileService;
use App\Services\Security\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ProfileService $profileService;
    protected SecurityService $securityService;
    protected BankAccountService $bankService;
    protected AddressService $addressService;

    public function __construct(
        ProfileService $profileService,
        SecurityService $securityService,
        BankAccountService $bankService,
        AddressService $addressService
    ) {
        $this->profileService = $profileService;
        $this->securityService = $securityService;
        $this->bankService = $bankService;
        $this->addressService = $addressService;
    }

    /**
     * Display Customer Profile, Order History, Addresses, etc.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để xem thông tin tài khoản!');
        }

        $orders = Order::with(['items.product', 'returns'])->where('user_id', $user->id)->latest()->get();
        $returns = \App\Models\OrderReturn::with(['order.items.product', 'orderItem.product'])->where('user_id', $user->id)->latest()->get();
        $addresses = UserAddress::where('user_id', $user->id)->orderBy('is_default', 'desc')->latest()->get();
        $pendingReviewItems = method_exists($user, 'getPendingReviewItems') ? $user->getPendingReviewItems() : collect();

        return view('client.profile', compact('user', 'orders', 'addresses', 'pendingReviewItems', 'returns'));
    }

    /**
     * Update user profile information (Name, Phone, Email, Address, Avatar).
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->profileService->updateProfile(
            $user,
            $request->validated(),
            $request->file('avatar')
        );

        return back()->with('success', 'Chúc mừng bạn đã cập nhật hồ sơ cá nhân thành công!');
    }

    /**
     * BƯỚC 1: Yêu cầu đổi Email hoặc Số điện thoại (Gửi OTP)
     */
    public function requestContactChange(RequestContactChangeRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $result = $this->profileService->requestContactChange(
            $user,
            $request->input('type'),
            $request->input('new_value')
        );

        return back()->with('success', $result['message'])->with('pending_contact', $result);
    }

    /**
     * BƯỚC 2: Nhập mã OTP để xác nhận cập nhật Email hoặc Số điện thoại
     */
    public function confirmContactChange(ConfirmContactChangeRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->profileService->confirmContactChange(
            $user,
            $request->input('type'),
            $request->input('new_value'),
            $request->input('otp_code')
        );

        return back()->with('success', 'Đã cập nhật thông tin liên hệ mới thành công!');
    }

    /**
     * Update user password (bọc DB Transaction, revoke các sessions khác, gửi mail cảnh báo)
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->securityService->changePassword(
            $user,
            $request->input('current_password'),
            $request->input('new_password'),
            $request
        );

        return back()->with('success', 'Bạn đã đổi mật khẩu tài khoản thành công! Các phiên đăng nhập trên thiết bị khác đã được đăng xuất an toàn.');
    }

    /**
     * Cập nhật thông tin tài khoản ngân hàng (có kiểm tra Step-up Auth và Pending Payout Lock)
     */
    public function updateBank(UpdateBankAccountRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->bankService->updateBankAccount(
            $user,
            $request->validated(),
            $request->input('password'),
            $request->input('otp_code')
        );

        return back()->with('success', 'Cập nhật thông tin tài khoản ngân hàng nhận tiền hoàn thành công!');
    }

    /**
     * Thêm một địa chỉ nhận hàng mới vào sổ địa chỉ (có kiểm tra Cascade và Default Switch)
     */
    public function storeAddress(ShippingAddressRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->addressService->createAddress($user, $request->validated());

        return back()->with('success', 'Đã thêm địa chỉ nhận hàng mới vào sổ địa chỉ!');
    }

    /**
     * Xóa một địa chỉ nhận hàng khỏi sổ địa chỉ (tự động gán default cho địa chỉ còn lại gần nhất)
     */
    public function deleteAddress($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $address = $user->addresses()->findOrFail($id);
        $this->addressService->deleteAddress($address);

        return back()->with('success', 'Đã xóa địa chỉ khỏi sổ địa chỉ!');
    }

    /**
     * Cập nhật địa chỉ nhận hàng đã có
     */
    public function updateAddress(ShippingAddressRequest $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $address = $user->addresses()->findOrFail($id);
        $this->addressService->updateAddress($address, $request->validated());

        return back()->with('success', 'Đã cập nhật địa chỉ giao hàng thành công!');
    }

    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefaultAddress($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $address = $user->addresses()->findOrFail($id);
        $this->addressService->setDefaultAddress($address);

        return back()->with('success', 'Đã thiết lập địa chỉ nhận hàng mặc định!');
    }
}
