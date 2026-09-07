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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('status_step');
            }
            if (!Schema::hasColumn('orders', 'processing_at')) {
                $table->timestamp('processing_at')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('orders', 'shipping_at')) {
                $table->timestamp('shipping_at')->nullable()->after('processing_at');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipping_at');
            }
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('delivered_at');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['confirmed_at', 'processing_at', 'shipping_at', 'delivered_at', 'completed_at', 'paid_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
