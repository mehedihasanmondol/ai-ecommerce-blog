<?php

namespace App\Modules\Advertisement\Services;

use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Models\AdClick;
use App\Modules\Advertisement\Models\AdCreative;
use App\Modules\Advertisement\Models\AdImpression;
use App\Modules\Advertisement\Models\AdSlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * ModuleName: Advertisement
 * Purpose: Service for tracking ad impressions and clicks
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Services
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdTrackingService
{
    /**
     * Track an ad impression
     * 
     * @param AdCampaign $campaign
     * @param AdCreative $creative
     * @param AdSlot $slot
     * @return AdImpression|null
     */
    public function trackImpression(AdCampaign $campaign, AdCreative $creative, AdSlot $slot): ?AdImpression
    {
        // Check if we should track this impression
        if (!$this->shouldTrack()) {
            return null;
        }

        $visitorData = $this->getVisitorData();

        try {
            return AdImpression::create([
                'ad_campaign_id' => $campaign->id,
                'ad_creative_id' => $creative->id,
                'ad_slot_id' => $slot->id,
                'user_id' => Auth::id(),
                'ip_address' => $visitorData['ip'],
                'user_agent' => $visitorData['user_agent'],
                'referer' => $visitorData['referer'],
                'page_url' => $visitorData['page_url'],
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Failed to track ad impression: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Track an ad click
     * 
     * @param AdCampaign $campaign
     * @param AdCreative $creative
     * @param AdSlot $slot
     * @return AdClick|null
     */
    public function trackClick(AdCampaign $campaign, AdCreative $creative, AdSlot $slot): ?AdClick
    {
        // Check if we should track this click
        if (!$this->shouldTrack()) {
            return null;
        }

        $visitorData = $this->getVisitorData();

        try {
            return AdClick::create([
                'ad_campaign_id' => $campaign->id,
                'ad_creative_id' => $creative->id,
                'ad_slot_id' => $slot->id,
                'user_id' => Auth::id(),
                'ip_address' => $visitorData['ip'],
                'user_agent' => $visitorData['user_agent'],
                'referer' => $visitorData['referer'],
                'page_url' => $visitorData['page_url'],
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Failed to track ad click: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if we should track this request
     * Avoid tracking bots and invalid requests
     * 
     * @return bool
     */
    public function shouldTrack(): bool
    {
        $userAgent = request()->userAgent();

        // Don't track if no user agent
        if (empty($userAgent)) {
            return false;
        }

        // List of bot user agents to ignore
        $botPatterns = [
            'bot',
            'crawl',
            'spider',
            'slurp',
            'mediapartners',
            'googlebot',
            'bingbot',
            'yahoo',
            'baiduspider',
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get visitor data for tracking
     * 
     * @return array
     */
    public function getVisitorData(): array
    {
        return [
            'ip' => $this->getIpAddress(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'page_url' => request()->fullUrl(),
        ];
    }

    /**
     * Get visitor IP address
     * 
     * @return string|null
     */
    protected function getIpAddress(): ?string
    {
        // Check for IP from various headers (for proxy/load balancer scenarios)
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (isset($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // Handle comma-separated IPs (X-Forwarded-For can have multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return request()->ip();
    }

    /**
     * Track impression via AJAX (for lazy-loaded ads)
     * 
     * @param int $campaignId
     * @param int $creativeId
     * @param int $slotId
     * @return AdImpression|null
     */
    public function trackImpressionById(int $campaignId, int $creativeId, int $slotId): ?AdImpression
    {
        $campaign = AdCampaign::find($campaignId);
        $creative = AdCreative::find($creativeId);
        $slot = AdSlot::find($slotId);

        if (!$campaign || !$creative || !$slot) {
            return null;
        }

        return $this->trackImpression($campaign, $creative, $slot);
    }

    /**
     * Track click via redirect
     * 
     * @param int $campaignId
     * @param int $creativeId
     * @param int $slotId
     * @return AdClick|null
     */
    public function trackClickById(int $campaignId, int $creativeId, int $slotId): ?AdClick
    {
        $campaign = AdCampaign::find($campaignId);
        $creative = AdCreative::find($creativeId);
        $slot = AdSlot::find($slotId);

        if (!$campaign || !$creative || !$slot) {
            return null;
        }

        return $this->trackClick($campaign, $creative, $slot);
    }
}
