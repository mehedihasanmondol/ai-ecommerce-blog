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
            // Delivery method and zone
            $table->foreignId('delivery_method_id')->nullable()->after('carrier')->constrained()->onDelete('set null');
            $table->foreignId('delivery_zone_id')->nullable()->after('delivery_method_id')->constrained()->onDelete('set null');
            
            // Delivery details
            $table->string('delivery_method_name')->nullable()->after('delivery_zone_id');
            $table->string('delivery_zone_name')->nullable()->after('delivery_method_name');
            $table->string('estimated_delivery')->nullable()->after('delivery_zone_name'); // e.g., "3-5 days"
            
            // Delivery charges breakdown
            $table->decimal('base_shipping_cost', 10, 2)->default(0)->after('estimated_delivery');
            $table->decimal('handling_fee', 10, 2)->default(0)->after('base_shipping_cost');
            $table->decimal('insurance_fee', 10, 2)->default(0)->after('handling_fee');
            $table->decimal('cod_fee', 10, 2)->default(0)->after('insurance_fee');
            
            // Delivery status
            $table->enum('delivery_status', [
                'pending',
                'processing',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'failed',
                'returned'
            ])->default('pending')->after('cod_fee');
            
            // Timestamps for delivery tracking
            $table->timestamp('picked_up_at')->nullable()->after('delivery_status');
            $table->timestamp('in_transit_at')->nullable()->after('picked_up_at');
            $table->timestamp('out_for_delivery_at')->nullable()->after('in_transit_at');
            
            // Indexes
            $table->index('delivery_method_id');
            $table->index('delivery_zone_id');
            $table->index('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_method_id']);
            $table->dropForeign(['delivery_zone_id']);
            $table->dropColumn([
                'delivery_method_id',
                'delivery_zone_id',
                'delivery_method_name',
                'delivery_zone_name',
                'estimated_delivery',
                'base_shipping_cost',
                'handling_fee',
                'insurance_fee',
                'cod_fee',
                'delivery_status',
                'picked_up_at',
                'in_transit_at',
                'out_for_delivery_at',
            ]);
        });
    }
};
