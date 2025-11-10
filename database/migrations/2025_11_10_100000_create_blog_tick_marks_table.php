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
        Schema::create('blog_tick_marks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // e.g., "Verified", "Featured", "Hot"
            $table->string('slug', 100)->unique();
            $table->string('label', 100); // Display label
            $table->text('description')->nullable();
            $table->string('icon', 50)->default('check-circle'); // Icon name
            $table->string('color', 50)->default('blue'); // Tailwind color
            $table->string('bg_color', 50)->default('bg-blue-500'); // Background color class
            $table->string('text_color', 50)->default('text-white'); // Text color class
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System tick marks can't be deleted
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('blog_tick_marks');
    }
};
