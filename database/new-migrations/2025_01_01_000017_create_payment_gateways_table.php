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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., bKash, Nagad, SSL Commerz
            $table->string('slug')->unique(); // e.g., bkash, nagad, sslcommerz
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            
            // Configuration
            $table->json('config')->nullable(); // Store API keys, endpoints, etc.
            $table->boolean('is_sandbox')->default(true);
            
            // Fees
            $table->decimal('fixed_fee', 8, 2)->default(0);
            $table->decimal('percentage_fee', 5, 2)->default(0); // e.g., 2.5%
            $table->decimal('min_amount', 10, 2)->nullable();
            $table->decimal('max_amount', 10, 2)->nullable();
            
            // Status
            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0);
            
            // Display
            $table->string('button_text')->default('Pay Now');
            $table->string('button_color', 7)->default('#007bff');
            
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
