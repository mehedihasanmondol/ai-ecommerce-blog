<?php

namespace App\Modules\Advertisement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ModuleName: Advertisement
 * Purpose: Tracks ad clicks for analytics and CTR calculation
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Models
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdClick extends Model
{
    use HasFactory;

    protected $table = 'ad_clicks';

    // Only created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'ad_campaign_id',
        'ad_creative_id',
        'ad_slot_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referer',
        'page_url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the campaign this click belongs to
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    /**
     * Get the creative this click belongs to
     */
    public function creative(): BelongsTo
    {
        return $this->belongsTo(AdCreative::class, 'ad_creative_id');
    }

    /**
     * Get the slot this click belongs to
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(AdSlot::class, 'ad_slot_id');
    }

    /**
     * Get the user who clicked the ad (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get clicks for a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get today's clicks
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope to get this week's clicks
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope to get this month's clicks
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
