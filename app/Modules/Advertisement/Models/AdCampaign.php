<?php

namespace App\Modules\Advertisement\Models;

use App\Models\User;
use App\Modules\Blog\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ModuleName: Advertisement
 * Purpose: Represents an advertising campaign with scheduling, targeting, and limits
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Models
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCampaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ad_campaigns';

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'daily_impression_limit',
        'total_impression_limit',
        'daily_click_limit',
        'total_click_limit',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_impression_limit' => 'integer',
        'total_impression_limit' => 'integer',
        'daily_click_limit' => 'integer',
        'total_click_limit' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Get the user who created the campaign
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the creatives for this campaign
     */
    public function creatives(): HasMany
    {
        return $this->hasMany(AdCreative::class, 'ad_campaign_id');
    }

    /**
     * Get only active creatives
     */
    public function activeCreatives(): HasMany
    {
        return $this->hasMany(AdCreative::class, 'ad_campaign_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get impressions for this campaign
     */
    public function impressions(): HasMany
    {
        return $this->hasMany(AdImpression::class, 'ad_campaign_id');
    }

    /**
     * Get clicks for this campaign
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(AdClick::class, 'ad_campaign_id');
    }

    /**
     * Get all unique ad slots targeted by this campaign's creatives
     */
    public function slots(): BelongsToMany
    {
        return $this->belongsToMany(AdSlot::class, 'ad_creative_slot', 'ad_creative_id', 'ad_slot_id')
            ->join('ad_creatives', 'ad_creative_slot.ad_creative_id', '=', 'ad_creatives.id')
            ->where('ad_creatives.ad_campaign_id', $this->id)
            ->distinct();
    }

    /**
     * Check if campaign is currently active
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        // Check if within date range
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if campaign has reached its limits
     */
    public function hasReachedLimit(): bool
    {
        // Check total impression limit
        if ($this->total_impression_limit) {
            $totalImpressions = $this->impressions()->count();
            if ($totalImpressions >= $this->total_impression_limit) {
                return true;
            }
        }

        // Check daily impression limit
        if ($this->daily_impression_limit) {
            $todayImpressions = $this->getTodayImpressions();
            if ($todayImpressions >= $this->daily_impression_limit) {
                return true;
            }
        }

        // Check total click limit
        if ($this->total_click_limit) {
            $totalClicks = $this->clicks()->count();
            if ($totalClicks >= $this->total_click_limit) {
                return true;
            }
        }

        // Check daily click limit
        if ($this->daily_click_limit) {
            $todayClicks = $this->getTodayClicks();
            if ($todayClicks >= $this->daily_click_limit) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get today's impression count
     */
    public function getTodayImpressions(): int
    {
        return $this->impressions()
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Get today's click count
     */
    public function getTodayClicks(): int
    {
        return $this->clicks()
            ->whereDate('created_at', today())
            ->count();
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
     * Scope to get only active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope to get scheduled campaigns
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('start_date', '>', now());
    }

    /**
     * Scope to order by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }
}
