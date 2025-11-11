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
            $table->string('logo')->nullable(); // Gateway logo
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->json('credentials'); // Store API keys, secrets, etc.
            $table->json('settings')->nullable(); // Additional settings
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add payment_gateway_id to orders table
        if (!Schema::hasColumn('orders', 'payment_gateway_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_gateway_id')->nullable()->after('payment_method');
                $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('orders', 'transaction_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_gateway_id']);
            $table->dropColumn(['payment_gateway_id', 'transaction_id']);
        });
        
        Schema::dropIfExists('payment_gateways');
    }
};
