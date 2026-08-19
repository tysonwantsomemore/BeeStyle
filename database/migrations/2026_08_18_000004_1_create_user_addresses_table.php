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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('recipient_name');
            $table->string('phone');
            $table->string('city');            // Tỉnh / Thành phố
            $table->string('district');        // Quận / Huyện
            $table->string('ward')->nullable();// Phường / Xã
            $table->string('address');         // Địa chỉ chi tiết: số nhà, tên đường
            $table->string('label')->nullable()->default('Nhà riêng'); // Nhà riêng, Văn phòng
            $table->boolean('is_default')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
