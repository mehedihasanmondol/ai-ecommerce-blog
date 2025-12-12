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
        // Create new pivot tables for creative-level targeting

        // Ad Creative - Slot relationship
        Schema::create('ad_creative_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_creative_id');
            $table->unsignedBigInteger('ad_slot_id');
            $table->timestamps();

            $table->foreign('ad_creative_id')->references('id')->on('ad_creatives')->onDelete('cascade');
            $table->foreign('ad_slot_id')->references('id')->on('ad_slots')->onDelete('cascade');

            $table->primary(['ad_creative_id', 'ad_slot_id']);
        });

        // Ad Creative - Category relationship
        Schema::create('ad_creative_category', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_creative_id');
            $table->unsignedBigInteger('blog_category_id');
            $table->timestamps();

            $table->foreign('ad_creative_id')->references('id')->on('ad_creatives')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');

            $table->primary(['ad_creative_id', 'blog_category_id'], 'ad_creative_category_primary');
        });

        // Drop old campaign-level targeting pivot tables
        Schema::dropIfExists('ad_campaign_slot');
        Schema::dropIfExists('ad_campaign_category');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate campaign-level pivot tables
        Schema::create('ad_campaign_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_campaign_id');
            $table->unsignedBigInteger('ad_slot_id');
            $table->timestamps();

            $table->foreign('ad_campaign_id')->references('id')->on('ad_campaigns')->onDelete('cascade');
            $table->foreign('ad_slot_id')->references('id')->on('ad_slots')->onDelete('cascade');

            $table->primary(['ad_campaign_id', 'ad_slot_id']);
        });

        Schema::create('ad_campaign_category', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_campaign_id');
            $table->unsignedBigInteger('blog_category_id');
            $table->timestamps();

            $table->foreign('ad_campaign_id')->references('id')->on('ad_campaigns')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');

            $table->primary(['ad_campaign_id', 'blog_category_id'], 'ad_campaign_category_primary');
        });

        // Drop creative-level pivot tables
        Schema::dropIfExists('ad_creative_slot');
        Schema::dropIfExists('ad_creative_category');
    }
};
