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
        Schema::create('promotional_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            
            // Images
            $table->string('desktop_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('tablet_image')->nullable();
            
            // Link/Action
            $table->string('link_url')->nullable();
            $table->string('link_text')->default('Shop Now');
            $table->boolean('open_in_new_tab')->default(false);
            
            // Display settings
            $table->enum('position', ['hero', 'top', 'middle', 'bottom', 'sidebar'])->default('hero');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Scheduling
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            // Targeting
            $table->json('target_pages')->nullable(); // Which pages to show on
            $table->json('target_categories')->nullable(); // Show only for specific categories
            $table->json('target_user_types')->nullable(); // guest, registered, premium, etc.
            
            // Analytics
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('position');
            $table->index('sort_order');
            $table->index('is_active');
            $table->index(['starts_at', 'ends_at']);
            $table->index(['is_active', 'position', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_banners');
    }
};
