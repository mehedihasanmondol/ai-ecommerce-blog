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
            // Add product_type column if it doesn't exist
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->enum('product_type', ['simple', 'grouped', 'affiliate', 'variable'])
                    ->default('simple')
                    ->after('brand_id');
            }
            
            // Add affiliate fields
            if (!Schema::hasColumn('products', 'external_url')) {
                $table->string('external_url')->nullable()->after('product_type');
            }
            if (!Schema::hasColumn('products', 'button_text')) {
                $table->string('button_text')->nullable()->after('external_url');
            }
            
            // Remove variant-specific columns from products table (they'll be in variants table)
            $columnsToRemove = ['sku', 'price', 'sale_price', 'cost_price', 'stock_quantity', 
                               'low_stock_threshold', 'stock_status', 'weight', 'dimensions', 
                               'color', 'size', 'material', 'featured_image', 'gallery_images'];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
