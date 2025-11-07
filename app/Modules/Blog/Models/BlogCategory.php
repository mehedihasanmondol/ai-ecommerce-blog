<?php

namespace App\Modules\Blog\Models;

use App\Traits\HasSeo;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ModuleName: Blog
 * Purpose: Represents a blog category with hierarchical structure
 * 
 * Key Methods:
 * - parent(): Get parent category
 * - children(): Get child categories
 * - posts(): Get posts in this category
 * - allPosts(): Get all posts including from child categories
 * - scopeActive(): Query only active categories
 * - scopeRoots(): Query only root categories
 * 
 * Dependencies:
 * - Post model
 * - HasSeo trait
 * - HasUniqueSlug trait
 * 
 * @category Blog
 * @package  App\Modules\Blog\Models
 * @author   AI Assistant
 * @created  2025-11-07
 * @updated  2025-11-07
 */
class BlogCategory extends Model
{
    use HasFactory, SoftDeletes, HasSeo, HasUniqueSlug;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image_path',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get posts in this category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'blog_category_id');
    }

    /**
     * Get published posts count
     */
    public function getPublishedPostsCountAttribute(): int
    {
        return $this->posts()->published()->count();
    }

    /**
     * Get all posts including from child categories
     */
    public function allPosts()
    {
        $categoryIds = $this->children->pluck('id')->push($this->id);
        return Post::whereIn('blog_category_id', $categoryIds);
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root categories (no parent)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the slug source field name
     */
    public function getSlugSourceField(): string
    {
        return 'name';
    }

    /**
     * Get tables to check for unique slug
     */
    public function getSlugUniqueTables(): array
    {
        return ['blog_categories'];
    }
}
