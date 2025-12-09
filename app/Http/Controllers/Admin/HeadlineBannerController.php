<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeadlineBannerSettings;
use Illuminate\Http\Request;

/**
 * ModuleName: Headline Banner Management
 * Purpose: Admin controller for managing breaking news banner settings
 * 
 * @category Controllers
 * @package  App\Http\Controllers\Admin
 * @created  2025-12-09
 */
class HeadlineBannerController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = HeadlineBannerSettings::getSettings();

        // Ensure default values are set if they're null
        if (!$settings->bg_color) {
            $settings->bg_color = '#E31E24';
        }
        if (!$settings->text_color) {
            $settings->text_color = '#FFFFFF';
        }
        if (!$settings->label_bg_color) {
            $settings->label_bg_color = '#C41E3A';
        }

        return view('admin.headline-banner.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'label' => 'required|string|max:255',
            'news_text' => 'required|string',
            'bg_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'label_bg_color' => 'required|string|max:7',
            'scroll_speed' => 'required|integer|min:5|max:200',
            'auto_scroll' => 'boolean',
            'link_url' => 'nullable|url|max:255',
        ]);

        // Handle checkboxes - set to false if not present
        $validated['enabled'] = $request->has('enabled') ? true : false;
        $validated['auto_scroll'] = $request->has('auto_scroll') ? true : false;

        $settings = HeadlineBannerSettings::first();

        if ($settings) {
            $settings->update($validated);
        } else {
            HeadlineBannerSettings::create($validated);
        }

        return redirect()
            ->route('admin.blog.headline-banner.index');
    }

    /**
     * Quick toggle enable/disable
     */
    public function toggle(Request $request)
    {
        $settings = HeadlineBannerSettings::first();

        if ($settings) {
            $settings->update(['enabled' => !$settings->enabled]);

            return response()->json([
                'success' => true,
                'enabled' => $settings->enabled,
                'message' => $settings->enabled ? 'ব্যানার সক্রিয় করা হয়েছে' : 'ব্যানার নিষ্ক্রিয় করা হয়েছে',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'সেটিংস পাওয়া যায়নি',
        ], 404);
    }
}
