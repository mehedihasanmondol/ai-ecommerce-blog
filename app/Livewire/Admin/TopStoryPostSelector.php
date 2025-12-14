<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Modules\Blog\Models\Post;
use App\Models\TopStory;

/**
 * ModuleName: Top Story Post Selector
 * Purpose: Search and select blog posts for top stories
 * 
 * Key Features:
 * - Real-time post search
 * - Filter by category
 * - Display post details
 * - Add to top stories
 * 
 * @category Livewire
 * @package  App\Livewire\Admin
 * @author   Admin
 * @created  2025-12-10
 */
class TopStoryPostSelector extends Component
{
    public $search = '';
    public $selectedCategory = '';

    protected $listeners = ['postAdded' => '$refresh'];

    public function selectPost($postId)
    {
        // Check if post already exists in top stories
        $exists = TopStory::where('post_id', $postId)->exists();

        if ($exists) {
            // Post already exists, just return without action
            return;
        }

        // Get the highest display order and add 1
        $maxOrder = TopStory::max('display_order') ?? 0;

        TopStory::create([
            'post_id' => $postId,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        $this->search = '';

        // Set success message and reload the page
        session()->flash('success', 'Post added to top stories successfully!');

        return redirect()->route('admin.top-stories.index');
    }

    public function getPostsProperty()
    {
        $query = Post::with(['category', 'author', 'media', 'categories'])
            ->where('status', 'published')
            ->whereDoesntHave('topStory');

        // If search is provided, filter by search term
        if (strlen($this->search) > 0) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search .  '%');
            });
        }

        // Apply category filter
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // If no search, show recent posts (default load)
        if (strlen($this->search) < 1) {
            return $query->latest('published_at')
                ->limit(10)
                ->get();
        }

        return $query->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.admin.top-story-post-selector', [
            'posts' => $this->posts,
        ]);
    }
}
