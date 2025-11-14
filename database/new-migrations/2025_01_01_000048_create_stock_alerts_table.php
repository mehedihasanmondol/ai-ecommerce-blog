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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            
            // Alert details
            $table->enum('alert_type', ['low_stock', 'out_of_stock', 'overstock']);
            $table->integer('current_stock');
            $table->integer('threshold_value');
            $table->enum('status', ['active', 'resolved', 'ignored'])->default('active');
            
            // Notification tracking
            $table->boolean('email_sent')->default(false);
            $table->boolean('sms_sent')->default(false);
            $table->timestamp('last_notified_at')->nullable();
            $table->integer('notification_count')->default(0);
            
            // Resolution
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_note')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'variant_id']);
            $table->index(['warehouse_id', 'alert_type']);
            $table->index(['status', 'created_at']);
            $table->index('alert_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
