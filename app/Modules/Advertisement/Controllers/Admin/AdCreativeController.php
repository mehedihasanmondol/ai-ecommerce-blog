<?php

namespace App\Modules\Advertisement\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Advertisement\Models\AdCampaign;
use App\Modules\Advertisement\Models\AdCreative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * ModuleName: Advertisement
 * Purpose: Admin controller for managing ad creatives
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdCreativeController extends Controller
{
    /**
     * Display creatives for a campaign
     */
    public function index(AdCampaign $campaign)
    {
        $campaign->load('creatives');
        return view('admin.advertisements.creatives.index', compact('campaign'));
    }

    /**
     * Store new creative
     */
    public function store(Request $request, AdCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:image,gif,video,html,script',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'video_url' => 'nullable|url',
            'video_type' => 'nullable|in:pre-roll,mid-roll,post-roll',
            'link_url' => 'nullable|url',
            'link_target' => 'required|in:_blank,_self',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'alt_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'slot_ids' => 'nullable|array',
            'slot_ids.*' => 'exists:ad_slots,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:blog_categories,id',
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('advertisements/creatives', 'public');
            }

            $creative = $campaign->creatives()->create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'content' => $validated['content'] ?? null,
                'image_path' => $imagePath,
                'video_url' => $validated['video_url'] ?? null,
                'video_type' => $validated['video_type'] ?? null,
                'link_url' => $validated['link_url'] ?? null,
                'link_target' => $validated['link_target'] ?? '_blank',
                'width' => $validated['width'] ?? null,
                'height' => $validated['height'] ?? null,
                'alt_text' => $validated['alt_text'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            // Sync ad slots (creative-level targeting)
            if (!empty($validated['slot_ids'])) {
                $creative->slots()->sync($validated['slot_ids']);
            }

            // Sync categories (creative-level targeting)
            if (!empty($validated['category_ids'])) {
                $creative->categories()->sync($validated['category_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Creative created successfully!',
                'creative' => $creative,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create creative: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update creative
     */
    public function update(Request $request, AdCreative $creative)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:image,gif,video,html,script',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url',
            'video_type' => 'nullable|in:pre-roll,mid-roll,post-roll',
            'link_url' => 'nullable|url',
            'link_target' => 'required|in:_blank,_self',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'alt_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($creative->image_path) {
                    Storage::disk('public')->delete($creative->image_path);
                }
                $validated['image_path'] = $request->file('image')->store('advertisements/creatives', 'public');
            }

            $creative->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Creative updated successfully!',
                'creative' => $creative,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update creative: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete creative
     */
    public function destroy(AdCreative $creative)
    {
        try {
            // Delete image file if exists
            if ($creative->image_path) {
                Storage::disk('public')->delete($creative->image_path);
            }

            $creative->delete();

            return response()->json([
                'success' => true,
                'message' => 'Creative deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete creative: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload image via AJAX
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        try {
            $path = $request->file('image')->store('advertisements/creatives', 'public');
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }
}
