<?php

namespace App\Modules\Advertisement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Advertisement\Services\AdTrackingService;
use Illuminate\Http\Request;

/**
 * ModuleName: Advertisement
 * Purpose: Frontend controller for ad tracking
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Controllers
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdTrackingController extends Controller
{
    protected $trackingService;

    public function __construct(AdTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    /**
     * Track ad impression via AJAX
     */
    public function trackImpression(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:ad_campaigns,id',
            'creative_id' => 'required|exists:ad_creatives,id',
            'slot_id' => 'required|exists:ad_slots,id',
        ]);

        $impression = $this->trackingService->trackImpressionById(
            $validated['campaign_id'],
            $validated['creative_id'],
            $validated['slot_id']
        );

        return response()->json([
            'success' => (bool) $impression,
            'message' => $impression ? 'Impression tracked' : 'Tracking skipped',
        ]);
    }

    /**
     * Track ad click and redirect
     */
    public function trackClick(Request $request, int $campaignId, int $creativeId, int $slotId)
    {
        // Track the click
        $this->trackingService->trackClickById($campaignId, $creativeId, $slotId);

        // Get the creative to find the link URL
        $creative = \App\Modules\Advertisement\Models\AdCreative::find($creativeId);

        if ($creative && $creative->link_url) {
            return redirect($creative->link_url);
        }

        return redirect('/');
    }
}
