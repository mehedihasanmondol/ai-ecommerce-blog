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
            $table->string('reference_number')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('restrict');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer', 'return', 'damaged', 'lost'])->index();
            $table->integer('quantity');
            $table->integer('quantity_before')->comment('Stock before movement');
            $table->integer('quantity_after')->comment('Stock after movement');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            
            // Related references
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('transfer_to_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            
            // User tracking
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'warehouse_id']);
            $table->index(['type', 'created_at']);
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
