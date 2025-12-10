<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopStory;
use App\Models\SiteSetting;
use App\Modules\Blog\Models\Post;
use Illuminate\Http\Request;

/**
 * ModuleName: Admin Top Stories
 * Purpose: Manage top story posts for newspaper homepage
 * 
 * Key Methods:
 * - index(): List all top stories
 * - store(): Add post to top stories
 * - destroy(): Remove post from top stories
 * - reorder(): Update display order
 * - toggleStatus(): Enable/disable story
 * 
 * Dependencies:
 * - TopStory Model
 * - Post Model
 * 
 * @category Controllers
 * @package  App\Http\Controllers\Admin
 * @author   Admin
 * @created  2025-12-10
 * @updated  2025-12-10
 */
class TopStoryController extends Controller
{
    /**
     * Display top stories management page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('top-stories.view'), 403, 'You do not have permission to view top stories.');

        $topStories = TopStory::with('post')
            ->ordered()
            ->get();

        // Get section settings
        $sectionEnabled = SiteSetting::get('top_stories_section_enabled', '1');
        $sectionTitle = SiteSetting::get('top_stories_section_title', 'প্রধান খবর');

        return view('admin.top-stories.index', compact('topStories', 'sectionEnabled', 'sectionTitle'));
    }

    /**
     * Add post to top stories
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('top-stories.create'), 403, 'You do not have permission to add top stories.');

        $request->validate([
            'post_id' => 'required|exists:blog_posts,id|unique:top_stories,post_id',
        ]);

        // Get the highest display order and add 1
        $maxOrder = TopStory::max('display_order') ?? 0;

        TopStory::create([
            'post_id' => $request->post_id,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.top-stories.index')
            ->with('success', 'Post added to top stories successfully!');
    }

    /**
     * Remove post from top stories
     *
     * @param TopStory $topStory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TopStory $topStory)
    {
        abort_if(!auth()->user()->hasPermission('top-stories.delete'), 403, 'You do not have permission to remove top stories.');

        $topStory->delete();

        return redirect()
            ->route('admin.top-stories.index')
            ->with('success', 'Post removed from top stories successfully!');
    }

    /**
     * Update display order of top stories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('top-stories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reorder top stories.',
            ], 403);
        }

        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:top_stories,id',
            'orders.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $order) {
            TopStory::where('id', $order['id'])
                ->update(['display_order' => $order['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Display order updated successfully!',
        ]);
    }

    /**
     * Toggle active status of top story
     *
     * @param TopStory $topStory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(TopStory $topStory)
    {
        abort_if(!auth()->user()->hasPermission('top-stories.edit'), 403, 'You do not have permission to toggle top story status.');

        $topStory->update([
            'is_active' => !$topStory->is_active,
        ]);

        $status = $topStory->is_active ? 'enabled' : 'disabled';

        return redirect()
            ->route('admin.top-stories.index')
            ->with('success', "Top story {$status} successfully!");
    }

    /**
     * Toggle section visibility on homepage
     */
    public function toggleSection(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('top-stories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle section visibility.',
            ], 403);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'top_stories_section_enabled'],
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
        if (!auth()->user()->hasPermission('top-stories.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update section title.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'top_stories_section_title'],
            ['value' => $validated['title']]
        );

        return response()->json(['success' => true]);
    }
}
