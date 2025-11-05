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
            // Get all columns in the orders table
            $columns = Schema::getColumnListing('orders');
            
            // List of address-related keywords to search for
            $addressKeywords = [
                'shipping_', 'billing_', '_address', '_city', '_state', 
                '_country', '_postal', '_zip', '_phone', '_email',
                '_first_name', '_last_name', '_line_'
            ];
            
            // Find and drop any column containing address-related keywords
            foreach ($columns as $column) {
                foreach ($addressKeywords as $keyword) {
                    if (stripos($column, $keyword) !== false) {
                        // Skip if column is customer_email or customer_phone (these are needed)
                        if (in_array($column, ['customer_email', 'customer_phone'])) {
                            continue;
                        }
                        
                        if (Schema::hasColumn('orders', $column)) {
                            $table->dropColumn($column);
                        }
                        break;
                    }
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
