<?php

namespace App\Modules\Advertisement\Services;

use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Repositories\AdCampaignRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * ModuleName: Advertisement
 * Purpose: Service for managing ad campaigns
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Services
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCampaignService
{
    protected $campaignRepository;

    public function __construct(AdCampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Create a new campaign
     * 
     * @param array $data
     * @return AdCampaign
     */
    public function createCampaign(array $data): AdCampaign
    {
        DB::beginTransaction();

        try {
            // Create campaign
            $campaign = AdCampaign::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'scheduled',
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'daily_impression_limit' => $data['daily_impression_limit'] ?? null,
                'total_impression_limit' => $data['total_impression_limit'] ?? null,
                'daily_click_limit' => $data['daily_click_limit'] ?? null,
                'total_click_limit' => $data['total_click_limit'] ?? null,
                'priority' => $data['priority'] ?? 5,
                'created_by' => Auth::id(),
            ]);

            // Note: Targeting (slots, categories) is now at creative level, not campaign level

            DB::commit();

            return $campaign;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing campaign
     * 
     * @param AdCampaign $campaign
     * @param array $data
     * @return AdCampaign
     */
    public function updateCampaign(AdCampaign $campaign, array $data): AdCampaign
    {
        DB::beginTransaction();

        try {
            // Update campaign
            $campaign->update([
                'name' => $data['name'] ?? $campaign->name,
                'description' => $data['description'] ?? $campaign->description,
                'status' => $data['status'] ?? $campaign->status,
                'start_date' => $data['start_date'] ?? $campaign->start_date,
                'end_date' => $data['end_date'] ?? $campaign->end_date,
                'daily_impression_limit' => $data['daily_impression_limit'] ?? $campaign->daily_impression_limit,
                'total_impression_limit' => $data['total_impression_limit'] ?? $campaign->total_impression_limit,
                'daily_click_limit' => $data['daily_click_limit'] ?? $campaign->daily_click_limit,
                'total_click_limit' => $data['total_click_limit'] ?? $campaign->total_click_limit,
                'priority' => $data['priority'] ?? $campaign->priority,
            ]);

            // Note: Targeting (slots, categories) is now at creative level, not campaign level

            DB::commit();

            return $campaign;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a campaign (soft delete)
     * 
     * @param AdCampaign $campaign
     * @return bool
     */
    public function deleteCampaign(AdCampaign $campaign): bool
    {
        return $campaign->delete();
    }

    /**
     * Toggle campaign status (active/paused)
     * 
     * @param AdCampaign $campaign
     * @return AdCampaign
     */
    public function toggleStatus(AdCampaign $campaign): AdCampaign
    {
        $newStatus = $campaign->status === 'active' ? 'paused' : 'active';
        $campaign->update(['status' => $newStatus]);

        return $campaign;
    }

    /**
     * Attach categories to campaign
     * 
     * @param AdCampaign $campaign
     * @param array $categoryIds
     * @return void
     */
    public function attachCategories(AdCampaign $campaign, array $categoryIds): void
    {
        $campaign->categories()->sync($categoryIds);
    }

    /**
     * Attach slots to campaign
     * 
     * @param AdCampaign $campaign
     * @param array $slotIds
     * @return void
     */
    public function attachSlots(AdCampaign $campaign, array $slotIds): void
    {
        $campaign->slots()->sync($slotIds);
    }

    /**
     * Get campaign analytics
     * 
     * @param AdCampaign $campaign
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getCampaignAnalytics(AdCampaign $campaign, ?string $startDate = null, ?string $endDate = null): array
    {
        $impressionsQuery = $campaign->impressions();
        $clicksQuery = $campaign->clicks();

        if ($startDate && $endDate) {
            $impressionsQuery->dateRange($startDate, $endDate);
            $clicksQuery->dateRange($startDate, $endDate);
        }

        $impressions = $impressionsQuery->count();
        $clicks = $clicksQuery->count();
        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

        return [
            'impressions' => $impressions,
            'clicks' => $clicks,
            'ctr' => $ctr,
            'daily_impressions' => $campaign->getTodayImpressions(),
            'daily_clicks' => $campaign->getTodayClicks(),
            'total_impressions' => $campaign->getTotalImpressions(),
            'total_clicks' => $campaign->getTotalClicks(),
        ];
    }
}
