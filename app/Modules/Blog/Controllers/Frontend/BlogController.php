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
    public function index()
    {
        $posts = $this->postService->getPublishedPosts(config('app.paginate', 10));
        $featuredPosts = $this->postService->getFeaturedPosts(3);
        $popularPosts = $this->postService->getPopularPosts(5);
        $categories = $this->categoryRepository->getRoots();
        $popularTags = $this->tagRepository->getPopular(10);

        return view('frontend.blog.index', compact(
            'posts',
            'featuredPosts',
            'popularPosts',
            'categories',
            'popularTags'
        ));
    }

    /**
     * Single post page
     */
    public function show($slug)
    {
        $post = $this->postService->getPostBySlug($slug);
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
        $posts = $query->with(['author', 'tags'])->paginate($perPage)->appends($request->query());

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
        $posts = $this->postService->searchPosts($query, config('app.paginate', 10));
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
}
