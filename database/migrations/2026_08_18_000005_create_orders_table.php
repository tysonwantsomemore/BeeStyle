<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng đơn hàng (orders).
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // Mã đơn hàng định dạng BEE-YYYYMMDD-XXXX
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Thông tin người nhận hàng
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('notes')->nullable();

            // Hình thức thanh toán & Trạng thái giao hàng
            $table->string('payment_method')->default('cod'); // cod: Tiền mặt khi nhận hàng, vietqr: Chuyển khoản QR, momo: Ví MoMo, vnpay: Cổng VNPAY
            $table->string('payment_status')->default('unpaid'); // unpaid: Chưa thanh toán, paid: Đã thanh toán, refunded: Đã hoàn tiền
            $table->string('shipping_status')->default('pending'); // pending: Chờ xác nhận, confirmed: Đã xác nhận, processing: Đang đóng gói, shipping: Đang giao, delivered: Đã giao, completed: Hoàn tất, cancelled: Đã hủy
            $table->tinyInteger('status_step')->default(1); // Tiến trình 6 bước: 1: Chờ xác nhận, 2: Đã xác nhận, 3: Đang đóng gói, 4: Đang giao hàng, 5: Đã giao, 6: Hoàn tất, 0: Đã hủy

            // Chi tiết số tiền tài chính của đơn hàng
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('shipping_fee')->default(0);
            $table->unsignedBigInteger('total_amount');
            $table->string('coupon_code')->nullable();

            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Xóa bảng khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
