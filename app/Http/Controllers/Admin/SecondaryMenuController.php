<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecondaryMenuItem;
use Illuminate\Http\Request;

/**
 * SecondaryMenuController
 * Purpose: Manage secondary navigation menu items from admin panel
 * 
 * @author AI Assistant
 * @date 2025-11-06
 */
class SecondaryMenuController extends Controller
{
    /**
     * Display secondary menu settings
     */
    public function index()
    {
        $menuItems = SecondaryMenuItem::ordered()->get();
        
        return view('admin.secondary-menu.index', compact('menuItems'));
    }

    /**
     * Store a new menu item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'type' => 'required|in:link,dropdown',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['open_new_tab'] = $request->has('open_new_tab');

        SecondaryMenuItem::create($validated);

        return redirect()->route('admin.secondary-menu.index')
            ->with('success', 'Menu item created successfully!');
    }

    /**
     * Update menu item
     */
    public function update(Request $request, SecondaryMenuItem $secondaryMenu)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'type' => 'required|in:link,dropdown',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['open_new_tab'] = $request->has('open_new_tab');

        $secondaryMenu->update($validated);

        return redirect()->route('admin.secondary-menu.index')
            ->with('success', 'Menu item updated successfully!');
    }

    /**
     * Delete menu item
     */
    public function destroy(SecondaryMenuItem $secondaryMenu)
    {
        $secondaryMenu->delete();

        return redirect()->route('admin.secondary-menu.index')
            ->with('success', 'Menu item deleted successfully!');
    }

    /**
     * Reorder menu items
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            SecondaryMenuItem::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
