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
        Schema::create('ad_campaign_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->foreignId('blog_category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate targeting
            $table->unique(['ad_campaign_id', 'blog_category_id'], 'ad_campaign_category_unique');

            // Indexes
            $table->index(['ad_campaign_id']);
            $table->index(['blog_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaign_categories');
    }
};
