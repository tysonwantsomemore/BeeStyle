<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cập nhật các cột quan hệ hành chính cho UserAddress và cột Snapshot địa chỉ cho bảng Orders
     */
    public function up(): void
    {
        // 1. Cập nhật bảng user_addresses
        Schema::table('user_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('user_addresses', 'province_id')) {
                $table->foreignId('province_id')->nullable()->after('phone')->constrained('provinces')->nullOnDelete();
            }
            if (!Schema::hasColumn('user_addresses', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('province_id')->constrained('districts')->nullOnDelete();
            }
            if (!Schema::hasColumn('user_addresses', 'ward_id')) {
                $table->foreignId('ward_id')->nullable()->after('district_id')->constrained('wards')->nullOnDelete();
            }
        });

        // 2. Cập nhật bảng orders để hỗ trợ Address Snapshotting toàn diện
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'ward')) {
                $table->string('ward')->nullable()->after('district');
            }
            if (!Schema::hasColumn('orders', 'shipping_address_snapshot')) {
                $table->json('shipping_address_snapshot')->nullable()->after('notes');
            }
        });
    }

    /**
     * Hoàn tác migration.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ward', 'shipping_address_snapshot']);
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['province_id', 'district_id', 'ward_id']);
        });
    }
};
