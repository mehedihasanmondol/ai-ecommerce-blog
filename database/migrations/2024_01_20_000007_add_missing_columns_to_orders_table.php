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
            // Check and add missing columns
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->unique()->after('id');
            }
            
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', [
                    'pending', 'processing', 'confirmed', 'shipped', 
                    'delivered', 'cancelled', 'refunded', 'failed'
                ])->default('pending')->after('user_id');
            }
            
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                    ->default('pending')->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('transaction_id');
            }
            
            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('subtotal');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0)->after('tax_amount');
            }
            
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_cost');
            }
            
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('discount_amount');
            }
            
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('total_amount');
            }
            
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->after('coupon_code');
            }
            
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->after('customer_name');
            }
            
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->after('customer_email');
            }
            
            if (!Schema::hasColumn('orders', 'customer_notes')) {
                $table->text('customer_notes')->nullable()->after('customer_phone');
            }
            
            if (!Schema::hasColumn('orders', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('customer_notes');
            }
            
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('admin_notes');
            }
            
            if (!Schema::hasColumn('orders', 'carrier')) {
                $table->string('carrier')->nullable()->after('tracking_number');
            }
            
            if (!Schema::hasColumn('orders', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('carrier');
            }
            
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('ip_address');
            }
            
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('paid_at');
            }
            
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            }
            
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            }
        });
        
        // Add indexes
        Schema::table('orders', function (Blueprint $table) {
            $indexes = Schema::getIndexes('orders');
            $indexNames = array_column($indexes, 'name');
            
            if (!in_array('orders_order_number_index', $indexNames)) {
                $table->index('order_number');
            }
            if (!in_array('orders_user_id_index', $indexNames)) {
                $table->index('user_id');
            }
            if (!in_array('orders_status_index', $indexNames)) {
                $table->index('status');
            }
            if (!in_array('orders_payment_status_index', $indexNames)) {
                $table->index('payment_status');
            }
            if (!in_array('orders_created_at_index', $indexNames)) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'order_number', 'status', 'payment_status', 'payment_method',
                'transaction_id', 'subtotal', 'tax_amount', 'shipping_cost',
                'discount_amount', 'total_amount', 'coupon_code', 'customer_name',
                'customer_email', 'customer_phone', 'customer_notes', 'admin_notes',
                'tracking_number', 'carrier', 'ip_address', 'paid_at',
                'shipped_at', 'delivered_at', 'cancelled_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
