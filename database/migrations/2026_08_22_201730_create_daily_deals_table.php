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
        Schema::create('daily_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->unsignedInteger('discount_percent')->comment('Mức giảm giá % (1 - 99)');
            $table->unsignedInteger('deal_price')->nullable()->comment('Giá sau khi giảm ưu đãi');
            $table->date('deal_date')->nullable()->comment('Ngày diễn ra khuyến mãi (null nếu lặp lại hàng ngày)');
            $table->time('start_time')->default('00:00:00')->comment('Thời gian bắt đầu trong ngày');
            $table->time('end_time')->default('23:59:59')->comment('Thời gian kết thúc trong ngày');
            $table->string('slot_name')->nullable()->comment('Tên khung giờ (ví dụ: 08:00 - 12:00)');
            $table->unsignedInteger('quantity_limit')->default(0)->comment('Giới hạn số lượng deal (0 = không giới hạn)');
            $table->unsignedInteger('sold_count')->default(0)->comment('Số lượng đã bán trong deal');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['deal_date', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_deals');
    }
};
