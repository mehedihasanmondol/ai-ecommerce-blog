<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopVideo;
use App\Models\SiteSetting;
use App\Modules\Blog\Models\Post;
use Illuminate\Http\Request;

/**
 * ModuleName: Admin Top Videos
 * Purpose: Manage top video posts for newspaper homepage
 * 
 * Key Methods:
 * - index(): List all top videos
 * - store(): Add post to top videos
 * - destroy(): Remove post from top videos
 * - reorder(): Update display order
 * - toggleStatus(): Enable/disable video
 * 
 * Dependencies:
 * - TopVideo Model
 * - Post Model
 * 
 * @category Controllers
 * @package  App\Http\Controllers\Admin
 * @author   Admin
 * @created  2025-12-14
 * @updated  2025-12-14
 */
class TopVideoController extends Controller
{
    /**
     * Display top videos management page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('top-videos.view'), 403, 'You do not have permission to view top videos.');

        $topVideos = TopVideo::with('post')
            ->ordered()
            ->get();

        // Get section settings
        $sectionEnabled = SiteSetting::get('top_videos_section_enabled', '1');
        $sectionTitle = SiteSetting::get('top_videos_section_title', 'শীর্ষ ভিডিও');

        return view('admin.top-videos.index', compact('topVideos', 'sectionEnabled', 'sectionTitle'));
    }

    /**
     * Add post to top videos
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('top-videos.create'), 403, 'You do not have permission to add top videos.');

        $request->validate([
            'post_id' => 'required|exists:blog_posts,id|unique:top_videos,post_id',
        ]);

        // Verify the post has a video (youtube_url)
        $post = Post::find($request->post_id);
        if (!$post->youtube_url) {
            return redirect()
                ->back()
                ->with('error', 'Selected post does not have a video.');
        }

        // Get the highest display order and add 1
        $maxOrder = TopVideo::max('display_order') ?? 0;

        TopVideo::create([
            'post_id' => $request->post_id,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.top-videos.index')
            ->with('success', 'Video post added to top videos successfully!');
    }

    /**
     * Remove post from top videos
     *
     * @param TopVideo $topVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TopVideo $topVideo)
    {
        abort_if(!auth()->user()->hasPermission('top-videos.delete'), 403, 'You do not have permission to remove top videos.');

        $topVideo->delete();

        return redirect()
            ->route('admin.top-videos.index')
            ->with('success', 'Video post removed from top videos successfully!');
    }

    /**
     * Update display order of top videos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('top-videos.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reorder top videos.',
            ], 403);
        }

        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:top_videos,id',
            'orders.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $order) {
            TopVideo::where('id', $order['id'])
                ->update(['display_order' => $order['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Display order updated successfully!',
        ]);
    }

    /**
     * Toggle active status of top video
     *
     * @param TopVideo $topVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(TopVideo $topVideo)
    {
        abort_if(!auth()->user()->hasPermission('top-videos.edit'), 403, 'You do not have permission to toggle top video status.');

        $topVideo->update([
            'is_active' => !$topVideo->is_active,
        ]);

        $status = $topVideo->is_active ? 'enabled' : 'disabled';

        return redirect()
            ->route('admin.top-videos.index')
            ->with('success', "Top video {$status} successfully!");
    }

    /**
     * Toggle section visibility on homepage
     */
    public function toggleSection(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('top-videos.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle section visibility.',
            ], 403);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'top_videos_section_enabled'],
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
        if (!auth()->user()->hasPermission('top-videos.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update section title.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'top_videos_section_title'],
            ['value' => $validated['title']]
        );

        return response()->json(['success' => true]);
    }
}
