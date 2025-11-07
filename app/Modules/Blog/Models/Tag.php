<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * ModuleName: Blog
 * Purpose: Represents a blog tag for categorizing posts
 * 
 * Key Methods:
 * - posts(): Get posts with this tag
 * - incrementPostsCount(): Increment posts count
 * - decrementPostsCount(): Decrement posts count
 * - scopePopular(): Query tags by popularity
 * 
 * Dependencies:
 * - Post model
 * 
 * @category Blog
 * @package  App\Modules\Blog\Models
 * @author   AI Assistant
 * @created  2025-11-07
 * @updated  2025-11-07
 */
class Tag extends Model
{
    use HasFactory;

    protected $table = 'blog_tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'posts_count',
    ];

    protected $casts = [
        'posts_count' => 'integer',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get posts with this tag
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'blog_post_tag', 'blog_tag_id', 'blog_post_id')
            ->withTimestamps();
    }

    /**
     * Increment posts count
     */
    public function incrementPostsCount(): void
    {
        $this->increment('posts_count');
    }

    /**
     * Decrement posts count
     */
    public function decrementPostsCount(): void
    {
        $this->decrement('posts_count');
    }

    /**
     * Scope to get popular tags
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->where('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit($limit);
    }

    /**
     * Scope to search tags
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
