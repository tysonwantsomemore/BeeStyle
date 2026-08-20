<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng đánh giá sản phẩm (reviews).
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment');
            $table->string('status')->default('approved'); // approved: Đã duyệt, pending: Chờ duyệt, hidden: Ẩn
            $table->timestamps();
        });
    }

    /**
     * Xóa bảng khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
