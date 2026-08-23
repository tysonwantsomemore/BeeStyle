<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\BrandController as ClientBrandController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\OrderTrackingController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ReviewController;

use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DailyDealController as AdminDailyDealController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RevenueController as AdminRevenueController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserManagementController;



/*
|--------------------------------------------------------------------------
| AUTHENTICATION & SECURITY ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', fn() => redirect()->route('auth.login'))->name('login');
Route::get('/register', fn() => redirect()->route('auth.register'))->name('register');


Route::name('auth.')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| CLIENT / FRONTEND E-COMMERCE ROUTES
|--------------------------------------------------------------------------
*/
Route::name('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/uu-dai-trong-ngay', [ClientProductController::class, 'dailyDeals'])->name('daily-deals.index');
    Route::get('/san-pham', [ClientProductController::class, 'index'])->name('products.index');
    Route::get('/san-pham/api-quick-view/{id}', [ClientProductController::class, 'getQuickViewData'])->name('products.quickView');
    Route::get('/san-pham/{id}', [ClientProductController::class, 'show'])->name('products.show');

    
    // Brands
    Route::get('/thuong-hieu', [ClientBrandController::class, 'index'])->name('brands.index');
    Route::get('/thuong-hieu/{slug}', [ClientBrandController::class, 'show'])->name('brands.show');

    // Product Review (BẮT BUỘC ĐÃ MUA HÀNG VÀ ĐĂNG NHẬP)
    Route::get('/san-pham/{id}/danh-gia-chi-tiet', [ReviewController::class, 'getProductReviewsData'])->name('products.reviews.data');
    Route::post('/san-pham/{id}/danh-gia', [ReviewController::class, 'store'])->middleware('auth')->name('products.review');
    Route::post('/danh-dau-thong-bao-danh-gia', [ReviewController::class, 'dismissNotification'])->middleware('auth')->name('reviews.dismissNotification');


    // CÁC TÍNH NĂNG BẮT BUỘC ĐĂNG NHẬP: YÊU THÍCH, GIỎ HÀNG, THANH TOÁN, TRA CỨU ĐƠN HÀNG, TÀI KHOẢN
    Route::middleware('auth')->group(function () {
        // Wishlist Routes (Sản phẩm yêu thích)
        Route::get('/san-pham-yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/san-pham-yeu-thich/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/san-pham-yeu-thich/xoa/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::post('/san-pham-yeu-thich/xoa-tat-ca', [WishlistController::class, 'clear'])->name('wishlist.clear');

        // Cart Routes (Giỏ hàng & Thao tác)
        Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
        Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
        Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/gio-hang/xoa/{key}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/gio-hang/xoa-tat-ca', [CartController::class, 'clear'])->name('cart.clear');
        Route::post('/gio-hang/ma-giam-gia', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
        Route::delete('/gio-hang/xoa-ma', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
        
        // Checkout Routes (Thanh toán đơn hàng)
        Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
        
        // Order Tracking (Tra cứu đơn hàng)
        Route::get('/tra-cuu-don-hang', [OrderTrackingController::class, 'index'])->name('order-tracking');
        Route::post('/tra-cuu-don-hang/{code}/xac-nhan-thanh-toan', [OrderTrackingController::class, 'confirmTransfer'])->name('order-tracking.confirm-transfer');

        // Customer Account Profile & Settings
        Route::get('/tai-khoan', [ProfileController::class, 'index'])->name('profile');
        Route::put('/tai-khoan/cap-nhat', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/tai-khoan/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('/tai-khoan/ngan-hang', [ProfileController::class, 'updateBank'])->name('profile.bank');
        Route::post('/tai-khoan/dia-chi', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
        Route::put('/tai-khoan/dia-chi/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
        Route::delete('/tai-khoan/dia-chi/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
        Route::post('/tai-khoan/dia-chi/{id}/mac-dinh', [ProfileController::class, 'setDefaultAddress'])->name('profile.address.default');
    });

});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD & MANAGEMENT ROUTES (PROTECTED BY AUTH & ADMIN MIDDLEWARE)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/revenue-data', [DashboardController::class, 'getRevenueData'])->name('dashboard.revenueData');
    Route::get('/revenue/monthly', [AdminRevenueController::class, 'monthly'])->name('revenue.monthly');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // User account administration and role assignment
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');


    
    // Products Management
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Customers Management
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Reviews & Feedback Management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Daily Deals / Ưu Đãi Trong Ngày Management
    Route::get('/daily-deals', [AdminDailyDealController::class, 'index'])->name('daily-deals.index');
    Route::post('/daily-deals', [AdminDailyDealController::class, 'store'])->name('daily-deals.store');
    Route::put('/daily-deals/{id}', [AdminDailyDealController::class, 'update'])->name('daily-deals.update');
    Route::delete('/daily-deals/{id}', [AdminDailyDealController::class, 'destroy'])->name('daily-deals.destroy');
    Route::post('/daily-deals/{id}/toggle', [AdminDailyDealController::class, 'toggleStatus'])->name('daily-deals.toggle');
    Route::post('/daily-deals/{id}/renew', [AdminDailyDealController::class, 'renew'])->name('daily-deals.renew');

    // Coupons Management
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::put('/coupons/{id}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
});
