<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng mã giảm giá (coupons).
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Ví dụ: BEESTYLE50, FREESHIPMAX, VIPBEE15
            $table->string('title');
            $table->string('discount_type')->default('fixed'); // 'fixed': Giảm số tiền cố định, 'percent': Giảm theo %, 'shipping': Miễn phí vận chuyển
            $table->unsignedBigInteger('discount_value'); // Giá trị giảm: 50.000₫ hoặc 15 (%)
            $table->unsignedBigInteger('min_order_value')->default(0);
            $table->unsignedBigInteger('max_discount_value')->nullable();
            $table->unsignedInteger('total_limit')->default(1000);
            $table->unsignedInteger('used_count')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Xóa bảng khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
