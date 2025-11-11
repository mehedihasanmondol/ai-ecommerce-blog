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
        Schema::table('user_addresses', function (Blueprint $table) {
            // Add name and email fields
            $table->string('name')->after('label');
            $table->string('email')->nullable()->after('phone');
            
            // Combine address fields into one
            $table->text('address')->after('email');
            
            // Drop old address fields
            $table->dropColumn([
                'address_line1',
                'address_line2',
                'city',
                'state',
                'postal_code',
                'country'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn(['name', 'email', 'address']);
            
            // Restore old address fields
            $table->string('address_line1')->after('label');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city', 100)->after('address_line2');
            $table->string('state', 100)->after('city');
            $table->string('postal_code', 20)->after('state');
            $table->string('country', 100)->default('Bangladesh')->after('postal_code');
        });
    }
};
