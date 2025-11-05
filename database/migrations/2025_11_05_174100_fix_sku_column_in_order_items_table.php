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
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the duplicate 'sku' column since we have 'product_sku'
            if (Schema::hasColumn('order_items', 'sku')) {
                $table->dropColumn('sku');
            }
            
            // Also drop 'image' and 'attributes' if they exist (we have product_image and variant_attributes)
            if (Schema::hasColumn('order_items', 'image')) {
                $table->dropColumn('image');
            }
            
            if (Schema::hasColumn('order_items', 'attributes')) {
                $table->dropColumn('attributes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Re-add columns if needed
            if (!Schema::hasColumn('order_items', 'sku')) {
                $table->string('sku')->nullable();
            }
            
            if (!Schema::hasColumn('order_items', 'image')) {
                $table->string('image')->nullable();
            }
            
            if (!Schema::hasColumn('order_items', 'attributes')) {
                $table->json('attributes')->nullable();
            }
        });
    }
};
