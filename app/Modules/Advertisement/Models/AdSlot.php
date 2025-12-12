<?php

namespace App\Modules\Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ModuleName: Advertisement
 * Purpose: Represents a predefined ad slot location on the site
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Models
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdSlot extends Model
{
    use HasFactory;

    protected $table = 'ad_slots';

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'default_width',
        'default_height',
        'is_active',
        'lazy_load',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'lazy_load' => 'boolean',
        'default_width' => 'integer',
        'default_height' => 'integer',
    ];

    /**
     * Get the campaigns assigned to this slot
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(AdCampaign::class, 'ad_campaign_slots', 'ad_slot_id', 'ad_campaign_id')
            ->withTimestamps();
    }

    /**
     * Get active campaigns for this slot
     */
    public function activeCampaigns(): BelongsToMany
    {
        return $this->campaigns()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Get impressions for this slot
     */
    public function impressions(): HasMany
    {
        return $this->hasMany(AdImpression::class, 'ad_slot_id');
    }

    /**
     * Get clicks for this slot
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(AdClick::class, 'ad_slot_id');
    }

    /**
     * Get total impressions count
     */
    public function getTotalImpressions(): int
    {
        return $this->impressions()->count();
    }

    /**
     * Get total clicks count
     */
    public function getTotalClicks(): int
    {
        return $this->clicks()->count();
    }

    /**
     * Calculate click-through rate (CTR)
     */
    public function getCTR(): float
    {
        $impressions = $this->getTotalImpressions();

        if ($impressions === 0) {
            return 0.0;
        }

        $clicks = $this->getTotalClicks();

        return round(($clicks / $impressions) * 100, 2);
    }

    /**
     * Scope to get only active slots
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get slots by location
     */
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope to find by slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
