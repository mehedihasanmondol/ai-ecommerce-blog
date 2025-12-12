<?php

namespace App\Modules\Advertisement\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Models\AdClick;
use App\Modules\Advertisement\Models\AdImpression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ModuleName: Advertisement
 * Purpose: Admin controller for advertisement analytics
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdAnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Overview statistics
        $totalImpressions = AdImpression::dateRange($startDate, $endDate)->count();
        $totalClicks = AdClick::dateRange($startDate, $endDate)->count();
        $ctr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;

        // Today's stats
        $todayImpressions = AdImpression::today()->count();
        $todayClicks = AdClick::today()->count();

        // Campaign performance
        $campaignStats = AdCampaign::with(['impressions', 'clicks'])
            ->get()
            ->map(function ($campaign) use ($startDate, $endDate) {
                $impressions = $campaign->impressions()->dateRange($startDate, $endDate)->count();
                $clicks = $campaign->clicks()->dateRange($startDate, $endDate)->count();
                $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'status' => $campaign->status,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'ctr' => $ctr,
                ];
            })
            ->sortByDesc('impressions')
            ->values();

        // Daily trend data (last 30 days)
        $dailyTrend = DB::table('ad_impressions')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as impressions')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyClicks = DB::table('ad_clicks')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as clicks')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Merge impressions and clicks
        $chartData = $dailyTrend->map(function ($item) use ($dailyClicks) {
            return [
                'date' => $item->date,
                'impressions' => $item->impressions,
                'clicks' => $dailyClicks->get($item->date)->clicks ?? 0,
            ];
        });

        return view('admin.advertisements.analytics.index', compact(
            'totalImpressions',
            'totalClicks',
            'ctr',
            'todayImpressions',
            'todayClicks',
            'campaignStats',
            'chartData',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get campaign-specific analytics
     */
    public function campaign(Request $request, AdCampaign $campaign)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $impressions = $campaign->impressions()->dateRange($startDate, $endDate)->count();
        $clicks = $campaign->clicks()->dateRange($startDate, $endDate)->count();
        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

        // Creative performance
        $creativeStats = $campaign->creatives->map(function ($creative) use ($startDate, $endDate) {
            $impressions = $creative->impressions()->dateRange($startDate, $endDate)->count();
            $clicks = $creative->clicks()->dateRange($startDate, $endDate)->count();
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            return [
                'id' => $creative->id,
                'name' => $creative->name,
                'type' => $creative->type,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'ctr' => $ctr,
            ];
        })->sortByDesc('impressions')->values();

        // Slot performance
        $slotStats = $campaign->slots->map(function ($slot) use ($campaign, $startDate, $endDate) {
            $impressions = AdImpression::where('ad_campaign_id', $campaign->id)
                ->where('ad_slot_id', $slot->id)
                ->dateRange($startDate, $endDate)
                ->count();

            $clicks = AdClick::where('ad_campaign_id', $campaign->id)
                ->where('ad_slot_id', $slot->id)
                ->dateRange($startDate, $endDate)
                ->count();

            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            return [
                'id' => $slot->id,
                'name' => $slot->name,
                'location' => $slot->location,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'ctr' => $ctr,
            ];
        })->sortByDesc('impressions')->values();

        return view('admin.advertisements.analytics.campaign', compact(
            'campaign',
            'impressions',
            'clicks',
            'ctr',
            'creativeStats',
            'slotStats',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $format = $request->input('format', 'csv');

        $campaigns = AdCampaign::with(['impressions', 'clicks'])->get();

        $data = $campaigns->map(function ($campaign) use ($startDate, $endDate) {
            $impressions = $campaign->impressions()->dateRange($startDate, $endDate)->count();
            $clicks = $campaign->clicks()->dateRange($startDate, $endDate)->count();
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            return [
                'Campaign' => $campaign->name,
                'Status' => $campaign->status,
                'Start Date' => $campaign->start_date->format('Y-m-d'),
                'End Date' => $campaign->end_date ? $campaign->end_date->format('Y-m-d') : 'N/A',
                'Impressions' => $impressions,
                'Clicks' => $clicks,
                'CTR (%)' => $ctr,
            ];
        });

        if ($format === 'csv') {
            $filename = 'ad-analytics-' . now()->format('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, array_keys($data->first()));

                // Add data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format');
    }
}
