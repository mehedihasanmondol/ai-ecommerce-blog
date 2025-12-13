<?php

namespace App\Modules\Advertisement\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Advertisement\Models\AdSlot;
use Illuminate\Http\Request;

/**
 * ModuleName: Advertisement
 * Purpose: Admin controller for managing ad slots
 * 
 * @category Advertisement
 * @package  App\Modules\Advertisement\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-12-12
 */
class AdSlotController extends Controller
{
    /**
     * Display listing of ad slots
     */
    public function index()
    {
        return view('admin.advertisements.slots.index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.advertisements.slots.create');
    }

    /**
     * Store new ad slot
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ad_slots,slug',
            'location' => 'required|in:header,footer,sidebar,inline,popup,native',
            'description' => 'nullable|string',
            'default_width' => 'nullable|integer|min:1',
            'default_height' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'lazy_load' => 'boolean',
        ]);

        try {
            $slot = AdSlot::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Ad slot created successfully!',
                'slot' => $slot,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ad slot: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update ad slot
     */
    public function update(Request $request, AdSlot $slot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ad_slots,slug,' . $slot->id,
            'location' => 'required|in:header,footer,sidebar,inline,popup,native',
            'description' => 'nullable|string',
            'default_width' => 'nullable|integer|min:1',
            'default_height' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'lazy_load' => 'boolean',
        ]);

        try {
            $slot->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Ad slot updated successfully!',
                'slot' => $slot,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ad slot: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete ad slot
     */
    public function destroy(AdSlot $slot)
    {
        try {
            $slot->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ad slot deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ad slot: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle slot status
     */
    public function toggleStatus(AdSlot $slot)
    {
        try {
            $slot->update(['is_active' => !$slot->is_active]);

            return response()->json([
                'success' => true,
                'is_active' => $slot->is_active,
                'message' => 'Ad slot status updated!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
