<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Blog\Models\Post;

/**
 * ModuleName: Top Videos
 * Purpose: Manage top video posts displayed on newspaper homepage
 * 
 * Key Features:
 * - Link blog posts (with videos) to top videos section
 * - Control display order
 * - Enable/disable videos
 * 
 * Relationships:
 * - belongsTo: Post (Blog Post with video)
 * 
 * @category Models
 * @package  App\Models
 * @author   Admin
 * @created  2025-12-14
 * @updated  2025-12-14
 */
class TopVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the blog post associated with this top video
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope to get only active top videos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
