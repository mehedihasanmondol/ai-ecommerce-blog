<?php

namespace App\Modules\Blog\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Repositories\BlogCategoryRepository;
use App\Modules\Blog\Repositories\TagRepository;
use App\Modules\Blog\Services\PostService;
use App\Modules\Blog\Services\TickMarkService;
use App\Modules\Blog\Requests\StorePostRequest;
use App\Modules\Blog\Requests\UpdatePostRequest;
use Illuminate\Http\Request;

/**
 * ModuleName: Blog
 * Purpose: Admin controller for blog post management
 * 
 * @category Blog
 * @package  App\Modules\Blog\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-11-07
 */
class PostController extends Controller
{
    protected PostService $postService;
    protected BlogCategoryRepository $categoryRepository;
    protected TagRepository $tagRepository;
    protected TickMarkService $tickMarkService;

    public function __construct(
        PostService $postService,
        BlogCategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        TickMarkService $tickMarkService
    ) {
        $this->postService = $postService;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->tickMarkService = $tickMarkService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category_id', 'author_id', 'search', 'featured', 'date_from', 'date_to']);
        $posts = $this->postService->getAllPosts(config('app.paginate', 10), $filters);
        $counts = $this->postService->getPostsCountByStatus();

        return view('admin.blog.posts.index', compact('posts', 'counts'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->getActive();
        $tags = $this->tagRepository->all();

        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'পোস্ট সফলভাবে তৈরি হয়েছে');
    }

    public function show($id)
    {
        $post = $this->postService->getPost($id);
        return view('admin.blog.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = $this->postService->getPost($id);
        $categories = $this->categoryRepository->getActive();
        $tags = $this->tagRepository->all();

        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = $this->postService->updatePost($id, $request->validated());

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'পোস্ট সফলভাবে আপডেট হয়েছে');
    }

    public function destroy($id)
    {
        $this->postService->deletePost($id);

        return response()->json([
            'success' => true,
            'message' => 'পোস্ট সফলভাবে মুছে ফেলা হয়েছে',
        ]);
    }

    public function publish($id)
    {
        $this->postService->publishPost($id);

        return response()->json([
            'success' => true,
            'message' => 'পোস্ট প্রকাশিত হয়েছে',
        ]);
    }

    /**
     * Upload image for TinyMCE editor
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
            ]);

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store in public/storage/blog/images
                $path = $image->storeAs('blog/images', $filename, 'public');
                
                // Return JSON response for TinyMCE
                return response()->json([
                    'location' => asset('storage/' . $path)
                ]);
            }

            return response()->json([
                'error' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tick mark statistics
     */
    public function tickMarkStats()
    {
        $stats = $this->tickMarkService->getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Toggle verification status
     */
    public function toggleVerification($id)
    {
        try {
            $post = $this->tickMarkService->toggleVerification($id);
            
            return response()->json([
                'success' => true,
                'message' => $post->is_verified ? 'পোস্ট যাচাইকৃত হয়েছে' : 'যাচাইকরণ সরানো হয়েছে',
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle editor's choice status
     */
    public function toggleEditorChoice($id)
    {
        try {
            $post = $this->tickMarkService->toggleEditorChoice($id);
            
            return response()->json([
                'success' => true,
                'message' => $post->is_editor_choice ? 'সম্পাদকের পছন্দে যোগ করা হয়েছে' : 'সম্পাদকের পছন্দ থেকে সরানো হয়েছে',
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle trending status
     */
    public function toggleTrending($id)
    {
        try {
            $post = $this->tickMarkService->toggleTrending($id);
            
            return response()->json([
                'success' => true,
                'message' => $post->is_trending ? 'ট্রেন্ডিং হিসেবে চিহ্নিত করা হয়েছে' : 'ট্রেন্ডিং থেকে সরানো হয়েছে',
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle premium status
     */
    public function togglePremium($id)
    {
        try {
            $post = $this->tickMarkService->togglePremium($id);
            
            return response()->json([
                'success' => true,
                'message' => $post->is_premium ? 'প্রিমিয়াম হিসেবে চিহ্নিত করা হয়েছে' : 'প্রিমিয়াম থেকে সরানো হয়েছে',
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update tick marks
     */
    public function bulkUpdateTickMarks(Request $request)
    {
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:blog_posts,id',
            'tick_mark_type' => 'required|in:verified,editor_choice,trending,premium',
            'value' => 'required|boolean',
        ]);

        try {
            $affected = $this->tickMarkService->bulkUpdateTickMarks(
                $request->post_ids,
                $request->tick_mark_type,
                $request->value
            );
            
            return response()->json([
                'success' => true,
                'message' => "{$affected}টি পোস্ট আপডেট করা হয়েছে",
                'affected' => $affected,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
