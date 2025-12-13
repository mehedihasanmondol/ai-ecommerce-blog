<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Advertisement\Services\AdDisplayService;
use App\Modules\Advertisement\Services\AdTrackingService;

class AdvertisementApiController extends Controller
{
    protected $adDisplayService;
    protected $adTrackingService;

    public function __construct(
        AdDisplayService $adDisplayService,
        AdTrackingService $adTrackingService
    ) {
        $this->adDisplayService = $adDisplayService;
        $this->adTrackingService = $adTrackingService;
    }

    /**
     * Render ad for a specific slot
     */
    public function renderAd(Request $request)
    {
        $slotSlug = $request->get('slot');
        $categoryId = $request->get('category');

        if (!$slotSlug) {
            return response('', 204);
        }

        $ad = $this->adDisplayService->getAdForSlot($slotSlug, $categoryId);

        if (!$ad) {
            return response('', 204);
        }

        $campaign = $ad['campaign'];
        $creative = $ad['creative'];
        $adSlot = $ad['slot'];

        // Track impression
        $this->adTrackingService->trackImpression($campaign, $creative, $adSlot);

        // Render ad HTML
        $html = view('components.advertisement.ad-render', [
            'campaign' => $campaign,
            'creative' => $creative,
            'adSlot' => $adSlot,
            'adContent' => $this->adDisplayService->renderAd($creative, $adSlot)
        ])->render();

        return response($html)->header('Content-Type', 'text/html');
    }
}
