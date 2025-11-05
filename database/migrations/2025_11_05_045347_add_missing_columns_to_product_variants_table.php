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
        Schema::table('product_variants', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('product_variants', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('name');
            }
            if (!Schema::hasColumn('product_variants', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('sale_price');
            }
            if (!Schema::hasColumn('product_variants', 'low_stock_alert')) {
                $table->integer('low_stock_alert')->default(5)->after('stock_quantity');
            }
            if (!Schema::hasColumn('product_variants', 'manage_stock')) {
                $table->boolean('manage_stock')->default(true)->after('low_stock_alert');
            }
            if (!Schema::hasColumn('product_variants', 'stock_status')) {
                $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])
                    ->default('in_stock')->after('manage_stock');
            }
            if (!Schema::hasColumn('product_variants', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('stock_status');
            }
            if (!Schema::hasColumn('product_variants', 'length')) {
                $table->decimal('length', 8, 2)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('product_variants', 'width')) {
                $table->decimal('width', 8, 2)->nullable()->after('length');
            }
            if (!Schema::hasColumn('product_variants', 'height')) {
                $table->decimal('height', 8, 2)->nullable()->after('width');
            }
            if (!Schema::hasColumn('product_variants', 'shipping_class')) {
                $table->string('shipping_class')->nullable()->after('height');
            }
            
            // Remove attributes column if exists (we use separate table)
            if (Schema::hasColumn('product_variants', 'attributes')) {
                $table->dropColumn('attributes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            //
        });
    }
};
