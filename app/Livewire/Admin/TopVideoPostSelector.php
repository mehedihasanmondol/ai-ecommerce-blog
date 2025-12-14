<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Modules\Blog\Models\Post;
use App\Models\TopVideo;

/**
 * ModuleName: Admin Top Videos
 * Purpose: Livewire component for searching and selecting video posts
 * 
 * Key Features:
 * - Real-time search for video posts
 * - Filter to show only posts with videos (youtube_url)
 * - Check for existing top videos
 * - Quick add functionality
 * 
 * @category Livewire
 * @package  App\Livewire\Admin
 * @author   Admin
 * @created  2025-12-14
 * @updated  2025-12-14
 */
class TopVideoPostSelector extends Component
{
    public $search = '';
    public $selectedPostId = null;

    /**
     * Search for video posts (posts with youtube_url)
     */
    public function searchPosts()
    {
        // Build query for video posts
        $query = Post::whereNotNull('youtube_url')
            ->where('youtube_url', '!=', '')
            ->where('status', 'published')
            ->whereDoesntHave('topVideo') // Exclude posts already in top videos
            ->with(['categories', 'media']);

        // Apply search filter if provided
        if (strlen($this->search) > 0) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Get latest 10 posts
        return $query->latest('published_at')
            ->limit(10)
            ->get()
            ->map(function ($post) {
                $exists = TopVideo::where('post_id', $post->id)->exists();
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'featured_image' => $post->featured_image,
                    'youtube_url' => $post->youtube_url,
                    'youtube_embed_url' => $post->youtube_embed_url,
                    'published_at' => $post->published_at?->format('M d, Y'),
                    'category' => $post->categories->first()?->name ?? 'Uncategorized',
                    'exists' => $exists,
                ];
            });
    }

    /**
     * Add post to top videos
     */
    public function addPost($postId)
    {
        // Check if already exists
        $exists = TopVideo::where('post_id', $postId)->exists();

        if ($exists) {
            session()->flash('error', 'This video is already in top videos.');
            return;
        }

        // Get the highest display order and add 1
        $maxOrder = TopVideo::max('display_order') ?? 0;

        TopVideo::create([
            'post_id' => $postId,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        $this->search = '';
        $this->dispatch('videoAdded');
        session()->flash('success', 'Video added to top videos successfully!');

        // Redirect to refresh the page
        return redirect()->route('admin.top-videos.index');
    }

    public function render()
    {
        $posts = $this->searchPosts();

        return view('livewire.admin.top-video-post-selector', [
            'posts' => $posts,
        ]);
    }
}
