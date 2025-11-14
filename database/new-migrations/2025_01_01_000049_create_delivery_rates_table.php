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
            $table->decimal('base_rate', 10, 2)->default(0);
            $table->decimal('per_kg_rate', 10, 2)->nullable(); // For weight-based
            $table->decimal('per_item_rate', 10, 2)->nullable(); // For item-based
            $table->decimal('percentage_rate', 5, 2)->nullable(); // For price-based (e.g., 5%)
            
            // Conditions
            $table->decimal('min_weight', 8, 2)->nullable();
            $table->decimal('max_weight', 8, 2)->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->decimal('max_order_value', 10, 2)->nullable();
            $table->integer('min_items')->nullable();
            $table->integer('max_items')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['delivery_zone_id', 'delivery_method_id']);
            $table->index('is_active');
            $table->index(['effective_from', 'effective_until']);
            
            // Ensure unique zone-method combinations for active rates
            $table->unique(['delivery_zone_id', 'delivery_method_id']);
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
