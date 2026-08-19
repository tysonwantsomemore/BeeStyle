<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // BEE-20260818-1001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('notes')->nullable();

            // Payment & Shipping
            $table->string('payment_method')->default('cod'); // cod, vietqr, momo, vnpay
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, refunded
            $table->string('shipping_status')->default('pending'); // pending, confirmed, processing, shipping, delivered, completed, cancelled
            $table->tinyInteger('status_step')->default(1); // 1: Chờ xác nhận, 2: Đã xác nhận, 3: Đang đóng gói, 4: Đang giao hàng, 5: Đã giao, 6: Hoàn tất, 0: Đã hủy

            // Financial amounts
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
