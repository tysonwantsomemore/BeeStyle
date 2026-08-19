<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng sản phẩm (products).
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('product_type')->default('variant'); // 'single': Đơn lẻ hoặc 'variant': Có nhiều biến thể
            $table->unsignedBigInteger('price'); // Giá bán (VNĐ)
            $table->unsignedBigInteger('original_price')->nullable(); // Giá gốc
            $table->unsignedInteger('discount_percent')->default(0);
            $table->unsignedInteger('stock')->default(0); // Tồn kho
            $table->unsignedInteger('sold_count')->default(0); // Đã bán
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->string('image'); // Ảnh đại diện chính
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('colors')->nullable(); // Mảng các màu: ['Đen', 'Trắng', 'Xanh Navy']
            $table->json('sizes')->nullable();  // Mảng các size: ['S', 'M', 'L', 'XL']
            $table->json('specifications')->nullable(); // Thông số kỹ thuật: { 'Chất liệu': 'Cotton 100%', 'Phom dáng': 'Slimfit', 'Xuất xứ': 'Việt Nam', ... }
            $table->boolean('is_new')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->string('status')->default('active'); // active: Đang bán, inactive: Ngừng bán, draft: Bản nháp
            $table->timestamps();
        });
    }

    /**
     * Xóa bảng khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
