<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Blog\Models\BlogCategory;

/**
 * ModuleName: Featured Categories
 * Purpose: Manage featured blog categories displayed on newspaper homepage
 * 
 * Key Features:
 * - Link blog categories to featured section
 * - Control display order
 * - Enable/disable categories
 * 
 * Relationships:
 * - belongsTo: BlogCategory
 * 
 * @category Models
 * @package  App\Models
 * @author   AI Assistant
 * @created  2025-12-10
 * @updated  2025-12-10
 */
class FeaturedCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the blog category associated with this featured category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Scope to get only active featured categories
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
