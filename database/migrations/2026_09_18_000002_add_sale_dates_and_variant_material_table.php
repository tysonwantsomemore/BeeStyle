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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sale_starts_at')) {
                $table->timestamp('sale_starts_at')->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('products', 'sale_ends_at')) {
                $table->timestamp('sale_ends_at')->nullable()->after('sale_starts_at');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'material')) {
                $table->string('material')->nullable()->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sale_starts_at')) {
                $table->dropColumn('sale_starts_at');
            }
            if (Schema::hasColumn('products', 'sale_ends_at')) {
                $table->dropColumn('sale_ends_at');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'material')) {
                $table->dropColumn('material');
            }
        });
    }
};
