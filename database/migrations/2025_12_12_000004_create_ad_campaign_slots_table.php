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
        Schema::create('ad_campaign_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->foreignId('ad_slot_id')->constrained('ad_slots')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate assignments
            $table->unique(['ad_campaign_id', 'ad_slot_id']);

            // Indexes
            $table->index(['ad_campaign_id']);
            $table->index(['ad_slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaign_slots');
    }
};
