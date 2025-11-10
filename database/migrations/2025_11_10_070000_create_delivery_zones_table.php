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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Dhaka City", "Outside Dhaka", "International"
            $table->string('code')->unique(); // e.g., "dhaka-city", "outside-dhaka"
            $table->text('description')->nullable();
            
            // Geographic coverage
            $table->json('countries')->nullable(); // ["BD", "IN", "US"]
            $table->json('states')->nullable(); // ["Dhaka", "Chittagong"]
            $table->json('cities')->nullable(); // ["Dhaka", "Gazipur"]
            $table->json('postal_codes')->nullable(); // ["1000", "1200"]
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
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
        Schema::dropIfExists('delivery_zones');
    }
};
