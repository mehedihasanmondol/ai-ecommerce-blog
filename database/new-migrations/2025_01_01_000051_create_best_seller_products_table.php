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
        Schema::create('best_seller_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('total_sales')->default(0); // Total units sold
            $table->decimal('total_revenue', 12, 2)->default(0); // Total revenue generated
            $table->date('period_start')->nullable(); // Period for which this is a best seller
            $table->date('period_end')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('sort_order');
            $table->index('is_active');
            $table->index('total_sales');
            $table->index('total_revenue');
            $table->index(['is_active', 'sort_order']);
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_seller_products');
    }
};
