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
        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->foreignId('ad_creative_id')->constrained('ad_creatives')->onDelete('cascade');
            $table->foreignId('ad_slot_id')->constrained('ad_slots')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->timestamp('created_at')->useCurrent(); // Only created_at, no updated_at

            // Indexes for fast analytics queries
            $table->index(['ad_campaign_id', 'created_at']);
            $table->index(['ad_creative_id', 'created_at']);
            $table->index(['ad_slot_id', 'created_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_impressions');
    }
};
