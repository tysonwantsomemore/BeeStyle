<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Client\BrandController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\OrderTrackingController;
use App\Http\Controllers\Client\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;

/*
|--------------------------------------------------------------------------
| ĐƯỜNG DẪN XÁC THỰC & BẢO MẬT TÀI KHOẢN
|--------------------------------------------------------------------------
*/
Route::name('auth.')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ĐƯỜNG DẪN KHÁCH HÀNG / BÁN HÀNG (CLIENT FRONTEND)
|--------------------------------------------------------------------------
*/
Route::name('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/san-pham', [ClientProductController::class, 'index'])->name('products.index');
    Route::get('/san-pham/{id}', [ClientProductController::class, 'show'])->name('products.show');
    
    // Tuyến đường thương hiệu (Brands)
    Route::get('/thuong-hieu', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/thuong-hieu/{slug}', [BrandController::class, 'show'])->name('brands.show');

    // Tuyến đường giỏ hàng (Cart)
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
    Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
    Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/gio-hang/xoa/{key}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/gio-hang/xoa-tat-ca', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/gio-hang/ma-giam-gia', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::delete('/gio-hang/xoa-ma', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
    
    // Tuyến đường đặt hàng & thanh toán (Checkout)
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Tra cứu hành trình đơn hàng (Công khai)
    Route::get('/tra-cuu-don-hang', [OrderTrackingController::class, 'index'])->name('order-tracking');

    // Quản lý thông tin tài khoản cá nhân khách hàng (Yêu cầu đăng nhập)
    Route::middleware('auth')->group(function () {
        Route::get('/tai-khoan', [ProfileController::class, 'index'])->name('profile');
        Route::post('/tai-khoan/thong-tin', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/tai-khoan/doi-mat-khau', [ProfileController::class, 'changePassword'])->name('profile.password');
        Route::post('/tai-khoan/ngan-hang', [ProfileController::class, 'updateBank'])->name('profile.bank');
        Route::post('/tai-khoan/dia-chi', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
        Route::put('/tai-khoan/dia-chi/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
        Route::delete('/tai-khoan/dia-chi/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
        Route::post('/tai-khoan/dia-chi/{id}/mac-dinh', [ProfileController::class, 'setDefaultAddress'])->name('profile.address.default');
    });
});

/*
|--------------------------------------------------------------------------
| ĐƯỜNG DẪN TRANG QUẢN TRỊ ADMIN (YÊU CẦU ĐĂNG NHẬP & QUYỀN ADMIN)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý sản phẩm thời trang
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Quản lý danh mục sản phẩm
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::patch('/categories/{id}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Quản lý danh sách khách hàng
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');

    // Quản lý mã giảm giá (Coupons)
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
});
