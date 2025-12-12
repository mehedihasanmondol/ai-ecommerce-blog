<?php

namespace App\Modules\Advertisement\Services;

use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Models\AdCreative;
use App\Modules\Advertisement\Models\AdSlot;
use Illuminate\Support\Collection;

/**
 * ModuleName: Advertisement
 * Purpose: Service for displaying ads on the frontend with rotation and targeting
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Services
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdDisplayService
{
    /**
     * Get an ad to display for a specific slot
     * 
     * @param string $slotSlug The slug of the ad slot
     * @param int|null $categoryId Optional category ID for targeting
     * @return array|null Array with campaign, creative, and slot, or null if no ad available
     */
    public function getAdForSlot(string $slotSlug, ?int $categoryId = null): ?array
    {
        // Get the ad slot
        $slot = AdSlot::active()->bySlug($slotSlug)->first();

        if (!$slot) {
            return null;
        }

        // Query active creatives that target this slot
        // Targeting is now at creative level, not campaign level
        $creativesQuery = AdCreative::query()
            ->where('is_active', true)
            ->whereHas('campaign', function ($query) {
                $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                    });
            })
            ->whereHas('slots', function ($query) use ($slot) {
                $query->where('ad_slots.id', $slot->id);
            })
            ->with(['campaign']);

        // Filter by category if provided
        if ($categoryId) {
            $creativesQuery->where(function ($query) use ($categoryId) {
                // Either creative has no category targeting (show everywhere)
                $query->whereDoesntHave('categories')
                    // OR creative targets this specific category
                    ->orWhereHas('categories', function ($q) use ($categoryId) {
                        $q->where('blog_categories.id', $categoryId);
                    });
            });
        } else {
            // No category context - show creatives with no category targeting
            // (creatives with category targeting will only show on those specific category pages)
            $creativesQuery->whereDoesntHave('categories');
        }

        $creatives = $creativesQuery->get();

        if ($creatives->isEmpty()) {
            return null;
        }

        // Filter creatives whose campaigns haven't reached limits
        $eligibleCreatives = $creatives->filter(function ($creative) {
            return !$creative->campaign->hasReachedLimit();
        });

        if ($eligibleCreatives->isEmpty()) {
            return null;
        }

        // Select creative based on campaign priority (weighted random)
        $creative = $this->selectCreativeByPriority($eligibleCreatives);

        if (!$creative) {
            return null;
        }

        return [
            'campaign' => $creative->campaign,
            'creative' => $creative,
            'slot' => $slot,
        ];
    }

    /**
     * Filter campaigns by category targeting
     * 
     * @param Collection $campaigns
     * @param int|null $categoryId
     * @return Collection
     */
    protected function filterByTargeting(Collection $campaigns, ?int $categoryId): Collection
    {
        return $campaigns->filter(function ($campaign) use ($categoryId) {
            return $this->checkTargeting($campaign, $categoryId);
        });
    }

    /**
     * Check if campaign targeting matches current category
     * 
     * @param AdCampaign $campaign
     * @param int|null $categoryId
     * @return bool
     */
    public function checkTargeting(AdCampaign $campaign, ?int $categoryId): bool
    {
        // If campaign has no category targeting, show everywhere
        if ($campaign->categories->isEmpty()) {
            return true;
        }

        // If no category context, don't show targeted ads
        if (!$categoryId) {
            return false;
        }

        // Check if current category is in campaign's targeted categories
        return $campaign->categories->contains('id', $categoryId);
    }

    /**
     * Select campaign based on priority (weighted random selection)
     * 
     * @param Collection $campaigns
     * @return AdCampaign|null
     */
    protected function selectCampaignByPriority(Collection $campaigns): ?AdCampaign
    {
        if ($campaigns->isEmpty()) {
            return null;
        }

        // Calculate total priority weight
        $totalWeight = $campaigns->sum('priority');

        if ($totalWeight === 0) {
            // If all priorities are 0, select randomly
            return $campaigns->random();
        }

        // Generate random number between 1 and total weight
        $random = rand(1, $totalWeight);

        // Select campaign based on weighted random
        $currentWeight = 0;
        foreach ($campaigns as $campaign) {
            $currentWeight += $campaign->priority;
            if ($random <= $currentWeight) {
                return $campaign;
            }
        }

        // Fallback to first campaign
        return $campaigns->first();
    }

    /**
     * Select creative based on campaign priority (weighted random selection)
     * 
     * @param Collection $creatives Collection of AdCreative models
     * @return AdCreative|null
     */
    protected function selectCreativeByPriority(Collection $creatives): ?AdCreative
    {
        if ($creatives->isEmpty()) {
            return null;
        }

        // Group creatives by campaign priority
        $grouped = $creatives->groupBy(function ($creative) {
            return $creative->campaign->priority;
        });

        // Calculate total priority weight
        $totalWeight = $creatives->sum(function ($creative) {
            return $creative->campaign->priority;
        });

        if ($totalWeight === 0) {
            // If all priorities are 0, select randomly
            return $creatives->random();
        }

        // Generate random number between 1 and total weight
        $random = rand(1, $totalWeight);

        // Select creative based on weighted random
        $currentWeight = 0;
        foreach ($creatives as $creative) {
            $currentWeight += $creative->campaign->priority;
            if ($random <= $currentWeight) {
                return $creative;
            }
        }

        // Fallback to first creative
        return $creatives->first();
    }

    /**
     * Select a creative from the campaign (rotation)
     * 
     * @param AdCampaign $campaign
     * @return AdCreative|null
     */
    public function selectCreative(AdCampaign $campaign): ?AdCreative
    {
        $creatives = $campaign->activeCreatives;

        if ($creatives->isEmpty()) {
            return null;
        }

        // Simple rotation: random selection
        // You could implement more sophisticated rotation (round-robin, least-shown, etc.)
        return $creatives->random();
    }

    /**
     * Render ad HTML
     * 
     * @param AdCreative $creative
     * @param AdSlot $slot
     * @return string
     */
    public function renderAd(AdCreative $creative, AdSlot $slot): string
    {
        return $creative->render($slot);
    }

    /**
     * Get popup/interstitial ad if available
     * 
     * @param int|null $categoryId
     * @return array|null
     */
    public function getPopupAd(?int $categoryId = null): ?array
    {
        return $this->getAdForSlot('popup-interstitial', $categoryId);
    }

    /**
     * Get native in-feed ads
     * 
     * @param int $count Number of native ads to get
     * @param int|null $categoryId
     * @return Collection
     */
    public function getNativeAds(int $count = 3, ?int $categoryId = null): Collection
    {
        $nativeAds = collect();

        for ($i = 0; $i < $count; $i++) {
            $ad = $this->getAdForSlot('native-feed', $categoryId);
            if ($ad) {
                $nativeAds->push($ad);
            }
        }

        return $nativeAds;
    }

    /**
     * Check if campaign has reached its limits
     * 
     * @param AdCampaign $campaign
     * @return bool
     */
    public function checkLimits(AdCampaign $campaign): bool
    {
        return !$campaign->hasReachedLimit();
    }
}
