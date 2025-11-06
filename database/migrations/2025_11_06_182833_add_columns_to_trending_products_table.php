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
        Schema::table('trending_products', function (Blueprint $table) {
            $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->after('product_id')->default(0);
            $table->boolean('is_active')->after('sort_order')->default(true);
            
            $table->unique('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trending_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropUnique(['product_id']);
            $table->dropColumn(['product_id', 'sort_order', 'is_active']);
        });
    }
};
