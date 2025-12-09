<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Modules\Blog\Models\Post;
use App\Modules\Blog\Models\BlogCategory;
use App\Modules\Blog\Models\Tag;

/**
 * ModuleName: Blog Instant Search Component
 * Purpose: Provide instant search results for blog posts, categories, and tags
 * 
 * @category Livewire
 * @package  Blog
 * @created  2025-12-09
 */
class BlogInstantSearch extends Component
{
    #[Url(as: 'q', keep: true)]
    public $query = '';

    public $showResults = false;
    public $isLoading = false;

    protected $queryString = ['query' => ['as' => 'q', 'except' => '']];

    /**
     * Updated query - trigger search
     */
    public function updatedQuery()
    {
        $this->isLoading = true;

        if (strlen($this->query) >= 2) {
            $this->showResults = true;
        } else {
            $this->showResults = false;
        }

        $this->isLoading = false;
    }

    /**
     * Perform search and redirect to blog index page
     */
    public function search()
    {
        if (trim($this->query)) {
            return redirect()->route('blog.index', ['search' => trim($this->query)]);
        }
    }

    /**
     * Clear search
     */
    public function clearSearch()
    {
        $this->query = '';
        $this->showResults = false;
    }

    /**
     * Hide results
     */
    public function hideResults()
    {
        $this->showResults = false;
    }

    /**
     * Get search suggestions - blog posts
     */
    public function getPostSuggestionsProperty()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        return Post::with(['category', 'media'])
            ->where('status', 'published')
            ->where(function ($q) {
                $q->where('title', 'like', "%{$this->query}%")
                    ->orWhere('excerpt', 'like', "%{$this->query}%")
                    ->orWhere('content', 'like', "%{$this->query}%");
            })
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();
    }

    /**
     * Get category suggestions
     */
    public function getCategorySuggestionsProperty()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        return BlogCategory::where('is_active', true)
            ->where('name', 'like', "%{$this->query}%")
            ->withCount([
                'posts' => function ($q) {
                    $q->where('status', 'published');
                }
            ])
            ->having('posts_count', '>', 0)
            ->limit(4)
            ->get();
    }

    /**
     * Get tag suggestions
     */
    public function getTagSuggestionsProperty()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        return Tag::where('name', 'like', "%{$this->query}%")
            ->withCount([
                'posts' => function ($q) {
                    $q->where('status', 'published');
                }
            ])
            ->having('posts_count', '>', 0)
            ->limit(4)
            ->get();
    }

    /**
     * Get popular searches based on top categories
     */
    public function getPopularSearchesProperty()
    {
        return BlogCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount([
                'posts' => function ($q) {
                    $q->where('status', 'published');
                }
            ])
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(8)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Navigate to category
     */
    public function goToCategory($slug)
    {
        $this->hideResults();
        return redirect()->route('blog.category', $slug);
    }

    /**
     * Navigate to tag
     */
    public function goToTag($slug)
    {
        $this->hideResults();
        return redirect()->route('blog.tag', $slug);
    }

    /**
     * Navigate to post
     */
    public function goToPost($slug)
    {
        $this->hideResults();
        return redirect()->route('blog.show', $slug);
    }

    /**
     * Get search suggestions (autocomplete)
     */
    public function getSearchSuggestionsProperty()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        $suggestions = collect();

        // Post titles
        $postTitles = Post::where('status', 'published')
            ->where('title', 'like', "%{$this->query}%")
            ->limit(4)
            ->pluck('title');

        // Category names
        $categoryNames = BlogCategory::where('is_active', true)
            ->where('name', 'like', "%{$this->query}%")
            ->limit(2)
            ->pluck('name');

        // Tag names
        $tagNames = Tag::where('name', 'like', "%{$this->query}%")
            ->limit(2)
            ->pluck('name');

        return $suggestions
            ->concat($postTitles)
            ->concat($categoryNames)
            ->concat($tagNames)
            ->unique()
            ->take(8);
    }

    /**
     * Update search query with suggestion
     */
    public function updateQuery($term)
    {
        $this->query = $term;
        // Don't perform search, just update the input
        // Dispatch event to maintain focus
        $this->dispatch('maintain-focus');
    }

    /**
     * Search for popular term
     */
    public function searchPopular($term)
    {
        $this->query = $term;
        $this->search();
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.blog.blog-instant-search', [
            'postSuggestions' => $this->postSuggestions,
            'categorySuggestions' => $this->categorySuggestions,
            'tagSuggestions' => $this->tagSuggestions,
            'searchSuggestions' => $this->searchSuggestions,
            'popularSearches' => $this->popularSearches,
        ]);
    }
}
