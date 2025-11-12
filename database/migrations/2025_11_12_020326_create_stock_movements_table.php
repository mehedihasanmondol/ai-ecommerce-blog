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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            // Core identification
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('restrict');
            
            // Movement details
            $table->enum('type', ['purchase', 'sale', 'return', 'adjustment', 'damaged', 'transfer'])->index();
            $table->integer('quantity')->comment('Positive for in, negative for out');
            $table->integer('quantity_before')->comment('Stock before movement');
            $table->integer('quantity_after')->comment('Stock after movement');
            
            // Financial tracking
            $table->decimal('cost_per_unit', 10, 2)->nullable()->comment('Unit cost at time of movement');
            $table->decimal('total_cost', 10, 2)->nullable()->comment('Total cost for this movement');
            
            // Reference tracking (polymorphic)
            $table->string('reference_type')->nullable()->comment('order, purchase_order, manual, etc.');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID of the referenced record');
            
            // Additional info
            $table->text('note')->nullable()->comment('Additional notes about the movement');
            
            // Related entities
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            
            // User tracking
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'product_variant_id']);
            $table->index(['warehouse_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['type', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
