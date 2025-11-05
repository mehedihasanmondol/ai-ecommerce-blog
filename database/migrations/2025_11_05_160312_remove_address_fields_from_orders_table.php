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
        Schema::table('orders', function (Blueprint $table) {
            // Drop all address-related columns since we use order_addresses table
            $addressColumns = [
                'shipping_city',
                'shipping_state',
                'shipping_country',
                'shipping_postal_code',
                'billing_city',
                'billing_state',
                'billing_country',
                'billing_postal_code',
                'billing_first_name',
                'billing_last_name',
                'billing_phone',
                'billing_email',
                'billing_address_line_1',
                'billing_address_line_2',
                'shipping_first_name',
                'shipping_last_name',
                'shipping_phone',
                'shipping_email',
                'shipping_address_line_1',
                'shipping_address_line_2',
            ];
            
            foreach ($addressColumns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
