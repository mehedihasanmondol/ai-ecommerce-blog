<?php

namespace App\Modules\Blog\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Repositories\BlogCategoryRepository;
use App\Modules\Blog\Repositories\TagRepository;
use App\Modules\Blog\Services\PostService;
use App\Modules\Blog\Services\CommentService;
use Illuminate\Http\Request;

/**
 * ModuleName: Blog
 * Purpose: Frontend controller for public blog pages
 * 
 * @category Blog
 * @package  App\Modules\Blog\Controllers\Frontend
 * @author   AI Assistant
 * @created  2025-11-07
 */
class BlogController extends Controller
{
    protected PostService $postService;
    protected BlogCategoryRepository $categoryRepository;
    protected TagRepository $tagRepository;
    protected CommentService $commentService;

    public function __construct(
        PostService $postService,
        BlogCategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        CommentService $commentService
    ) {
        $this->postService = $postService;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->commentService = $commentService;
    }

    /**
     * Blog listing page
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $filter = $request->input('filter');
        $sort = $request->input('sort', 'latest');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('q');
        
        // Build query
        $query = \App\Modules\Blog\Models\Post::where('status', 'published')
            ->where('published_at', '<=', now());
        
        // Apply filters
        switch ($filter) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
                
            case 'articles':
                // Posts without YouTube video
                $query->where(function($q) {
                    $q->whereNull('youtube_url')
                      ->orWhere('youtube_url', '');
                });
                break;
                
            case 'videos':
                // Posts with YouTube video
                $query->whereNotNull('youtube_url')
                      ->where('youtube_url', '!=', '');
                break;
        }
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting (if not already sorted by filter)
        if ($filter !== 'popular') {
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('published_at', 'asc');
                    break;
                case 'popular':
                    $query->orderBy('views_count', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('published_at', 'desc');
                    break;
            }
        }
        
        // Paginate
        $posts = $query->with(['author', 'category', 'tags', 'tickMarks'])->paginate($perPage)->appends($request->query());
        
        $featuredPosts = $this->postService->getFeaturedPosts(3);
        $popularPosts = $this->postService->getPopularPosts(5);
        $categories = $this->categoryRepository->getRoots();
        $popularTags = $this->tagRepository->getPopular(10);

        return view('frontend.blog.index', compact(
            'posts',
            'featuredPosts',
            'popularPosts',
            'categories',
            'popularTags',
            'filter'
        ));
    }

    /**
     * Single post page
     */
    public function show($slug)
    {
        $post = $this->postService->getPostBySlug($slug);
        $post->load('author.authorProfile'); // Eager load author profile
        $relatedPosts = $post->relatedPosts(3);
        $popularPosts = $this->postService->getPopularPosts(5);
        $categories = $this->categoryRepository->getRoots();

        return view('frontend.blog.show', compact(
            'post',
            'relatedPosts',
            'popularPosts',
            'categories'
        ));
    }

    /**
     * Category archive page
     */
    public function category(Request $request, $slug)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        
        // Get sidebar categories: if current category has children, show them; otherwise show root categories
        if ($category->children()->where('is_active', true)->count() > 0) {
            // Show subcategories of current category
            $categories = $category->children()
                ->where('is_active', true)
                ->withCount(['posts' => function($query) {
                    $query->where('status', 'published');
                }])
                ->orderBy('sort_order')
                ->get();
        } else {
            // Show root categories
            $categories = $this->categoryRepository->getRoots();
        }
        
        // Get filter parameters
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest');
        $perPage = $request->input('per_page', 10);
        
        // Build query
        $query = $category->posts()->where('status', 'published');
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }
        
        // Paginate
        $posts = $query->with(['author', 'tags', 'tickMarks'])->paginate($perPage)->appends($request->query());

        return view('frontend.blog.category', compact('category', 'posts', 'categories'));
    }

    /**
     * Tag archive page
     */
    public function tag($slug)
    {
        $tag = $this->tagRepository->findBySlug($slug);
        $posts = $this->postService->getPostsByTag($tag->id, config('app.paginate', 10));
        $categories = $this->categoryRepository->getRoots();

        return view('frontend.blog.tag', compact('tag', 'posts', 'categories'));
    }

    /**
     * Search results page
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $sort = $request->input('sort', 'latest');
        $perPage = $request->input('per_page', 10);
        
        // Build query
        $postsQuery = \App\Modules\Blog\Models\Post::where('status', 'published')
            ->where('published_at', '<=', now());
        
        // Apply search
        if ($query) {
            $postsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $postsQuery->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $postsQuery->orderBy('views_count', 'desc');
                break;
            case 'title':
                $postsQuery->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $postsQuery->orderBy('published_at', 'desc');
                break;
        }
        
        // Paginate
        $posts = $postsQuery->with(['author', 'category', 'tags', 'tickMarks'])
            ->paginate($perPage)
            ->appends($request->query());
        
        $categories = $this->categoryRepository->getRoots();

        return view('frontend.blog.search', compact('query', 'posts', 'categories'));
    }

    /**
     * Store comment
     */
    public function storeComment(Request $request, $postId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_email' => 'required_without:user_id|email|max:255',
            'guest_website' => 'nullable|url|max:255',
        ]);

        $validated['blog_post_id'] = $postId;
        $comment = $this->commentService->createComment($validated);

        return back()->with('success', 'আপনার মন্তব্য সফলভাবে জমা হয়েছে। অনুমোদনের অপেক্ষায় রয়েছে।');
    }

    /**
     * Author profile page
     */
    public function author(Request $request, $slug)
    {
        // Find author profile by slug
        $authorProfile = \App\Models\AuthorProfile::where('slug', $slug)->firstOrFail();
        $author = $authorProfile->user;
        
        // Ensure author exists
        if (!$author) {
            abort(404, 'Author not found');
        }
        
        // Get sorting parameter
        $sort = $request->get('sort', 'newest');
        
        // Get published posts by this author with sorting
        $postsQuery = $author->publishedPosts()
            ->with(['category', 'tags']);
        
        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $postsQuery->oldest('published_at');
                break;
            case 'most_viewed':
                $postsQuery->orderBy('views_count', 'desc');
                break;
            case 'most_popular':
                // Most popular = combination of views and comments
                $postsQuery->withCount('comments')
                    ->orderByRaw('(views_count + comments_count * 10) DESC');
                break;
            case 'newest':
            default:
                $postsQuery->latest('published_at');
                break;
        }
        
        $posts = $postsQuery->paginate(config('app.paginate', 12))->appends(['sort' => $sort]);
        
        // Get author stats
        $totalPosts = $author->publishedPosts()->count();
        $totalViews = $author->publishedPosts()->sum('views_count');
        $totalComments = \App\Modules\Blog\Models\Comment::whereHas('post', function($query) use ($author) {
            $query->where('author_id', $author->id);
        })->where('status', 'approved')->count();
        
        $categories = $this->categoryRepository->getRoots();
        
        return view('frontend.blog.author', compact(
            'author',
            'posts',
            'totalPosts',
            'totalViews',
            'totalComments',
            'categories',
            'sort'
        ));
    }
}
