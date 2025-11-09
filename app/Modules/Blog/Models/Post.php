<?php

namespace App\Modules\Blog\Models;

use App\Models\User;
use App\Traits\HasSeo;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * ModuleName: Blog
 * Purpose: Represents a blog post with full CMS features
 * 
 * Key Methods:
 * - author(): Get post author
 * - category(): Get post category
 * - tags(): Get post tags
 * - comments(): Get post comments
 * - incrementViews(): Increment view count
 * - calculateReadingTime(): Calculate reading time
 * - isPublished(): Check if post is published
 * - scopePublished(): Query only published posts
 * - scopeFeatured(): Query only featured posts
 * 
 * Dependencies:
 * - User model
 * - BlogCategory model
 * - Tag model
 * - Comment model
 * - HasSeo trait
 * - HasUniqueSlug trait
 * 
 * @category Blog
 * @package  App\Modules\Blog\Models
 * @author   AI Assistant
 * @created  2025-11-07
 * @updated  2025-11-07
 */
class Post extends Model
{
    use HasFactory, SoftDeletes, HasSeo, HasUniqueSlug;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'author_id',
        'blog_category_id',
        'featured_image',
        'featured_image_alt',
        'youtube_url',
        'status',
        'published_at',
        'scheduled_at',
        'views_count',
        'reading_time',
        'is_featured',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
    ];

    protected $appends = ['reading_time_text'];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            // Calculate reading time
            if ($post->content) {
                $post->reading_time = $post->calculateReadingTime($post->content);
            }
        });

        static::updating(function ($post) {
            // Recalculate reading time if content changed
            if ($post->isDirty('content')) {
                $post->reading_time = $post->calculateReadingTime($post->content);
            }
        });
    }

    /**
     * Get the author of the post
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category of the post (legacy - kept for backward compatibility)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get the categories associated with the post (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'blog_post_id', 'blog_category_id')
            ->withTimestamps();
    }

    /**
     * Get the tags associated with the post
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id')
            ->withTimestamps();
    }

    /**
     * Get the comments for the post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_post_id');
    }

    /**
     * Get only approved comments
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_post_id')
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->latest();
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Calculate reading time based on content
     * Average reading speed: 200 words per minute
     */
    public function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);
        return max(1, $minutes); // Minimum 1 minute
    }

    /**
     * Get reading time as text
     */
    public function getReadingTimeTextAttribute(): string
    {
        if ($this->reading_time < 1) {
            return 'Less than a minute';
        }
        
        return $this->reading_time . ' min read';
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeVideoIdAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }

        // Extract video ID from various YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        
        if (preg_match($pattern, $this->youtube_url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $videoId = $this->youtube_video_id;
        
        if (!$videoId) {
            return null;
        }

        return "https://www.youtube.com/embed/{$videoId}";
    }

    /**
     * Check if post is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->isPast();
    }

    /**
     * Check if post is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && 
               $this->scheduled_at && 
               $this->scheduled_at->isFuture();
    }

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to get only featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get posts by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    /**
     * Scope to get posts by tag
     */
    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('blog_tags.id', $tagId);
        });
    }

    /**
     * Scope to get posts by author
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope to search posts
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get popular posts (by views)
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    /**
     * Scope to get recent posts
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->latest('published_at')->limit($limit);
    }

    /**
     * Get related posts based on category and tags
     */
    public function relatedPosts($limit = 3)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('blog_category_id', $this->blog_category_id)
                    ->orWhereHas('tags', function ($q) {
                        $q->whereIn('blog_tags.id', $this->tags->pluck('id'));
                    });
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get the slug source field name
     */
    public function getSlugSourceField(): string
    {
        return 'title';
    }

    /**
     * Get tables to check for unique slug
     */
    public function getSlugUniqueTables(): array
    {
        return ['blog_posts', 'products'];
    }
}
