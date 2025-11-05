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
        Schema::table('order_items', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('order_items', 'variant_attributes')) {
                $table->json('variant_attributes')->nullable()->after('variant_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'variant_attributes')) {
                $table->dropColumn('variant_attributes');
            }
        });
    }
};
