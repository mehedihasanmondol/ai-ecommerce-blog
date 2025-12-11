<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedCategory;
use App\Models\SiteSetting;
use App\Modules\Blog\Models\BlogCategory;
use Illuminate\Http\Request;

/**
 * ModuleName: Admin Featured Categories
 * Purpose: Manage featured blog categories for newspaper homepage
 * 
 * Key Methods:
 * - index(): List all featured categories
 * - store(): Add category to featured list
 * - destroy(): Remove category from featured list
 * - reorder(): Update display order
 * - toggleStatus(): Enable/disable category
 * 
 * Dependencies:
 * - FeaturedCategory Model
 * - BlogCategory Model
 * 
 * @category Controllers
 * @package  App\Http\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-12-10
 * @updated  2025-12-10
 */
class FeaturedCategoryController extends Controller
{
    /**
     * Display featured categories management page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('featured-categories.view'), 403, 'You do not have permission to view featured categories.');

        $featuredCategories = FeaturedCategory::with('category')
            ->ordered()
            ->get();

        // Get section settings
        $sectionEnabled = SiteSetting::get('featured_categories_section_enabled', '1');
        $sectionTitle = SiteSetting::get('featured_categories_section_title', 'গুরুত্বপুর্ন বিভাগ');

        return view('admin.featured-categories.index', compact('featuredCategories', 'sectionEnabled', 'sectionTitle'));
    }

    /**
     * Add category to featured list
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('featured-categories.create'), 403, 'You do not have permission to add featured categories.');

        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id|unique:featured_categories,blog_category_id',
        ]);

        // Get the highest display order and add 1
        $maxOrder = FeaturedCategory::max('display_order') ?? 0;

        FeaturedCategory::create([
            'blog_category_id' => $request->blog_category_id,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.featured-categories.index')
            ->with('success', 'Category added to featured list successfully!');
    }

    /**
     * Remove category from featured list
     *
     * @param FeaturedCategory $featuredCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FeaturedCategory $featuredCategory)
    {
        abort_if(!auth()->user()->hasPermission('featured-categories.delete'), 403, 'You do not have permission to remove featured categories.');

        $featuredCategory->delete();

        return redirect()
            ->route('admin.featured-categories.index')
            ->with('success', 'Category removed from featured list successfully!');
    }

    /**
     * Update display order of featured categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('featured-categories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reorder featured categories.',
            ], 403);
        }

        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:featured_categories,id',
            'orders.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $order) {
            FeaturedCategory::where('id', $order['id'])
                ->update(['display_order' => $order['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Display order updated successfully!',
        ]);
    }

    /**
     * Toggle active status of featured category
     *
     * @param FeaturedCategory $featuredCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(FeaturedCategory $featuredCategory)
    {
        abort_if(!auth()->user()->hasPermission('featured-categories.edit'), 403, 'You do not have permission to toggle featured category status.');

        $featuredCategory->update([
            'is_active' => !$featuredCategory->is_active,
        ]);

        $status = $featuredCategory->is_active ? 'enabled' : 'disabled';

        return redirect()
            ->route('admin.featured-categories.index')
            ->with('success', "Featured category {$status} successfully!");
    }

    /**
     * Toggle section visibility on homepage
     */
    public function toggleSection(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('featured-categories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle section visibility.',
            ], 403);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'featured_categories_section_enabled'],
            ['value' => $request->enabled ? '1' : '0']
        );

        return response()->json(['success' => true]);
    }

    /**
     * Update section title
     */
    public function updateSectionTitle(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('featured-categories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update section title.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'featured_categories_section_title'],
            ['value' => $validated['title']]
        );

        return response()->json(['success' => true]);
    }
}
