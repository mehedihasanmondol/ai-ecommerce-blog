<?php

namespace App\Modules\Advertisement\Repositories;

use App\Modules\Advertisement\Models\AdCampaign;
use Illuminate\Database\Eloquent\Collection;

/**
 * ModuleName: Advertisement
 * Purpose: Repository for ad campaign data access
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Repositories
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCampaignRepository
{
    /**
     * Get all campaigns
     * 
     * @return Collection
     */
    public function getAll(): Collection
    {
        return AdCampaign::with(['creator', 'categories', 'slots'])
            ->latest()
            ->get();
    }

    /**
     * Get active campaigns
     * 
     * @return Collection
     */
    public function getActive(): Collection
    {
        return AdCampaign::active()
            ->with(['activeCreatives', 'categories', 'slots'])
            ->byPriority()
            ->get();
    }

    /**
     * Find campaign by ID
     * 
     * @param int $id
     * @return AdCampaign|null
     */
    public function find(int $id): ?AdCampaign
    {
        return AdCampaign::with(['creator', 'categories', 'slots', 'creatives'])
            ->find($id);
    }

    /**
     * Get campaigns by status
     * 
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection
    {
        return AdCampaign::where('status', $status)
            ->with(['creator', 'categories', 'slots'])
            ->latest()
            ->get();
    }

    /**
     * Search campaigns
     * 
     * @param string $query
     * @return Collection
     */
    public function search(string $query): Collection
    {
        return AdCampaign::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with(['creator', 'categories', 'slots'])
            ->latest()
            ->get();
    }
}
