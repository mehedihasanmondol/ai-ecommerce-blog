<?php

namespace App\Modules\Advertisement\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Models\AdSlot;
use App\Modules\Advertisement\Repositories\AdCampaignRepository;
use App\Modules\Advertisement\Services\AdCampaignService;
use App\Modules\Blog\Models\BlogCategory;
use Illuminate\Http\Request;

/**
 * ModuleName: Advertisement
 * Purpose: Admin controller for managing ad campaigns
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCampaignController extends Controller
{
    protected $campaignService;
    protected $campaignRepository;

    public function __construct(
        AdCampaignService $campaignService,
        AdCampaignRepository $campaignRepository
    ) {
        $this->campaignService = $campaignService;
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Display listing of campaigns
     */
    public function index(Request $request)
    {
        return view('admin.advertisements.campaigns.index');
    }

    /**
     * Show create campaign form
     */
    public function create()
    {
        // No longer need categories and slots for campaign creation
        // Targeting is now at creative level
        return view('admin.advertisements.campaigns.create');
    }

    /**
     * Store new campaign
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,paused,completed,scheduled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'daily_impression_limit' => 'nullable|integer|min:0',
            'total_impression_limit' => 'nullable|integer|min:0',
            'daily_click_limit' => 'nullable|integer|min:0',
            'total_click_limit' => 'nullable|integer|min:0',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        try {
            $campaign = $this->campaignService->createCampaign($validated);

            return redirect()
                ->route('admin.advertisements.campaigns.edit', $campaign)
                ->with('success', 'Campaign created successfully! Now add creatives to your campaign.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create campaign: ' . $e->getMessage());
        }
    }

    /**
     * Show edit campaign form
     */
    public function edit(AdCampaign $campaign)
    {
        $campaign->load(['creatives.slots', 'creatives.categories']);
        // Categories and slots are now for creative modal, not campaign
        $categories = BlogCategory::active()->orderBy('name')->get();
        $slots = AdSlot::active()->orderBy('name')->get();

        return view('admin.advertisements.campaigns.edit', compact('campaign', 'categories', 'slots'));
    }

    /**
     * Update campaign
     */
    public function update(Request $request, AdCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,paused,completed,scheduled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'daily_impression_limit' => 'nullable|integer|min:0',
            'total_impression_limit' => 'nullable|integer|min:0',
            'daily_click_limit' => 'nullable|integer|min:0',
            'total_click_limit' => 'nullable|integer|min:0',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        try {
            $campaign = $this->campaignService->updateCampaign($campaign, $validated);

            return redirect()
                ->route('admin.advertisements.campaigns.edit', $campaign)
                ->with('success', 'Campaign updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update campaign: ' . $e->getMessage());
        }
    }

    /**
     * Delete campaign
     */
    public function destroy(AdCampaign $campaign)
    {
        try {
            $this->campaignService->deleteCampaign($campaign);

            return redirect()
                ->route('admin.advertisements.campaigns.index')
                ->with('success', 'Campaign deleted successfully!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete campaign: ' . $e->getMessage());
        }
    }

    /**
     * Toggle campaign status
     */
    public function toggleStatus(AdCampaign $campaign)
    {
        try {
            $campaign = $this->campaignService->toggleStatus($campaign);

            return response()->json([
                'success' => true,
                'status' => $campaign->status,
                'message' => 'Campaign status updated to: ' . $campaign->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
