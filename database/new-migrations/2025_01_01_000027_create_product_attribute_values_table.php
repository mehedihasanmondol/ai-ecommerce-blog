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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->string('value'); // Red, Large, Cotton
            $table->string('color_code')->nullable(); // #FF0000 for color type
            $table->string('image')->nullable(); // For image type
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('attribute_id');
            $table->index('sort_order');
            $table->index(['attribute_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
