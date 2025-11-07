<?php

namespace App\Modules\Blog\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Repositories\BlogCategoryRepository;
use App\Modules\Blog\Repositories\TagRepository;
use App\Modules\Blog\Services\PostService;
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

    public function __construct(
        PostService $postService,
        BlogCategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->postService = $postService;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category_id', 'author_id', 'search']);
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
}
