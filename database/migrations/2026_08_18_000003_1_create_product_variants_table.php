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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('color')->nullable();       // Ví dụ: Đen Sang Trọng, Trắng Tinh Khôi, Xanh Navy
            $table->string('color_code')->nullable();  // Mã màu HEX: #000000, #ffffff, #1e3a8a
            $table->string('size')->nullable();        // Size: S, M, L, XL, XXL, 39, 40, 41, 42
            $table->unsignedBigInteger('price');       // Đơn giá bán của biến thể
            $table->unsignedBigInteger('original_price')->nullable();
            $table->unsignedInteger('stock')->default(0); // Số lượng tồn kho của biến thể
            $table->string('image')->nullable();       // Hình ảnh theo màu
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
