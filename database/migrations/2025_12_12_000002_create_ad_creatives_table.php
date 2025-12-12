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
        Schema::create('ad_creatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->onDelete('cascade');
            $table->string('name', 255);
            $table->enum('type', ['image', 'gif', 'video', 'html', 'script'])->default('image');
            $table->text('content')->nullable(); // For HTML/script content
            $table->string('image_path', 500)->nullable(); // Path to uploaded image/gif
            $table->string('video_url', 500)->nullable(); // YouTube/Vimeo URL or uploaded video path
            $table->enum('video_type', ['pre-roll', 'mid-roll', 'post-roll'])->nullable(); // For video ads
            $table->string('link_url', 500)->nullable(); // Click-through URL
            $table->enum('link_target', ['_blank', '_self'])->default('_blank');
            $table->integer('width')->unsigned()->nullable(); // Ad width in pixels
            $table->integer('height')->unsigned()->nullable(); // Ad height in pixels
            $table->string('alt_text', 255)->nullable(); // Alt text for images
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['ad_campaign_id']);
            $table->index(['type']);
            $table->index(['is_active']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_creatives');
    }
};
