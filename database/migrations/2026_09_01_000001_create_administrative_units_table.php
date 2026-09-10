<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng dữ liệu Đơn vị hành chính Việt Nam (Tỉnh/Thành phố, Quận/Huyện, Phường/Xã)
     */
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Ví dụ: TP. Hồ Chí Minh, Hà Nội, Đà Nẵng
            $table->string('code', 20)->unique();// Ví dụ: HCM, HN, DNG, 79, 01
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('name');             // Ví dụ: Quận 1, Quận Bình Thạnh, Huyện Củ Chi
            $table->string('code', 20)->nullable();
            $table->timestamps();

            $table->index(['province_id', 'id']);
        });

        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->string('name');             // Ví dụ: Phường Bến Nghé, Phường 14
            $table->string('code', 20)->nullable();
            $table->timestamps();

            $table->index(['district_id', 'id']);
        });
    }

    /**
     * Hoàn tác migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
    }
};
