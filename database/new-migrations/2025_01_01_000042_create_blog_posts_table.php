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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            
            // Author
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            
            // Category
            $table->foreignId('blog_category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
            
            // Images
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            
            // Status & Publishing
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Engagement
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedInteger('reading_time')->default(0); // in minutes
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('slug');
            $table->index('author_id');
            $table->index('blog_category_id');
            $table->index('status');
            $table->index('published_at');
            $table->index('is_featured');
            $table->index('views_count');
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
