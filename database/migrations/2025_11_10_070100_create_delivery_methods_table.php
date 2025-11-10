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
        Schema::create('delivery_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Standard Delivery", "Express Delivery", "Same Day"
            $table->string('code')->unique(); // e.g., "standard", "express", "same-day"
            $table->text('description')->nullable();
            
            // Delivery time
            $table->string('estimated_days')->nullable(); // e.g., "3-5 days", "1-2 days", "Same day"
            $table->integer('min_days')->nullable(); // Minimum delivery days
            $table->integer('max_days')->nullable(); // Maximum delivery days
            
            // Carrier information
            $table->string('carrier_name')->nullable(); // e.g., "Pathao", "Sundarban", "SA Paribahan"
            $table->string('carrier_code')->nullable();
            $table->string('tracking_url')->nullable(); // URL template for tracking
            
            // Cost calculation type
            $table->enum('calculation_type', [
                'flat_rate',      // Fixed rate
                'weight_based',   // Based on weight
                'price_based',    // Based on order total
                'item_based',     // Based on number of items
                'free'            // Free shipping
            ])->default('flat_rate');
            
            // Free shipping conditions
            $table->decimal('free_shipping_threshold', 10, 2)->nullable(); // Free if order > this amount
            
            // Restrictions
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_order_amount', 10, 2)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable(); // in kg
            
            // Status and display
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_checkout')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Icon/Image
            $table->string('icon')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_methods');
    }
};
