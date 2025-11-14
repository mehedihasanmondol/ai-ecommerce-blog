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
        Schema::create('new_arrival_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('arrival_date')->nullable(); // When the product arrived
            $table->date('featured_until')->nullable(); // Until when to show as new arrival
            $table->boolean('auto_remove')->default(true); // Auto remove after featured_until date
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('sort_order');
            $table->index('is_active');
            $table->index('arrival_date');
            $table->index('featured_until');
            $table->index(['is_active', 'sort_order']);
            $table->index(['featured_until', 'auto_remove']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_arrival_products');
    }
};
