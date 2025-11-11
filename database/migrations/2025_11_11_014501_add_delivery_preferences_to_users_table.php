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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_delivery_zone_id')->nullable()->after('remember_token')->constrained('delivery_zones')->onDelete('set null');
            $table->foreignId('default_delivery_method_id')->nullable()->after('default_delivery_zone_id')->constrained('delivery_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_delivery_zone_id']);
            $table->dropForeign(['default_delivery_method_id']);
            $table->dropColumn(['default_delivery_zone_id', 'default_delivery_method_id']);
        });
    }
};
