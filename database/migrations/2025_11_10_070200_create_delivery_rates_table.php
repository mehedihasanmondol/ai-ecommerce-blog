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
        Schema::create('delivery_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_method_id')->constrained()->onDelete('cascade');
            
            // Rate configuration
            $table->decimal('base_rate', 10, 2)->default(0); // Base shipping cost
            
            // Weight-based pricing (if calculation_type is weight_based)
            $table->decimal('weight_from', 10, 2)->nullable(); // in kg
            $table->decimal('weight_to', 10, 2)->nullable(); // in kg
            $table->decimal('rate_per_kg', 10, 2)->nullable();
            
            // Price-based pricing (if calculation_type is price_based)
            $table->decimal('price_from', 10, 2)->nullable();
            $table->decimal('price_to', 10, 2)->nullable();
            $table->decimal('rate_percentage', 5, 2)->nullable(); // Percentage of order total
            
            // Item-based pricing (if calculation_type is item_based)
            $table->integer('item_from')->nullable();
            $table->integer('item_to')->nullable();
            $table->decimal('rate_per_item', 10, 2)->nullable();
            
            // Additional charges
            $table->decimal('handling_fee', 10, 2)->default(0);
            $table->decimal('insurance_fee', 10, 2)->default(0);
            $table->decimal('cod_fee', 10, 2)->default(0); // Cash on Delivery fee
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['delivery_zone_id', 'delivery_method_id']);
            $table->index('is_active');
            
            // Unique constraint to prevent duplicate zone-method combinations
            $table->unique(['delivery_zone_id', 'delivery_method_id', 'weight_from', 'price_from', 'item_from'], 'unique_delivery_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_rates');
    }
};
