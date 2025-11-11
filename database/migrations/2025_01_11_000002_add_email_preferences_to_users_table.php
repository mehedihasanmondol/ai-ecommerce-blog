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
            $table->boolean('email_order_updates')->default(true)->after('is_active');
            $table->boolean('email_promotions')->default(false)->after('email_order_updates');
            $table->boolean('email_newsletter')->default(false)->after('email_promotions');
            $table->boolean('email_recommendations')->default(false)->after('email_newsletter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_order_updates',
                'email_promotions',
                'email_newsletter',
                'email_recommendations',
            ]);
        });
    }
};
