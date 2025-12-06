<?php

namespace App\Modules\Blog\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Services\BlogCategoryService;
use App\Modules\Blog\Requests\StoreBlogCategoryRequest;
use App\Modules\Blog\Requests\UpdateBlogCategoryRequest;

/**
 * ModuleName: Blog
 * Purpose: Admin controller for blog category management
 * 
 * @category Blog
 * @package  App\Modules\Blog\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-11-07
 */
class BlogCategoryController extends Controller
{
    protected BlogCategoryService $categoryService;

    public function __construct(BlogCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.view')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.blog.categories.index-livewire');
    }

    public function create()
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.create')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = $this->categoryService->getCategoriesForDropdown();
        return view('admin.blog.categories.create', compact('categories'));
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $category = $this->categoryService->createCategory($request->validated());

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'ক্যাটাগরি সফলভাবে তৈরি হয়েছে',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]);
        }

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে তৈরি হয়েছে');
    }

    public function edit($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $category = $this->categoryService->getCategory($id);
        $categories = $this->categoryService->getCategoriesForDropdown($id); // Exclude current category and its descendants

        return view('admin.blog.categories.edit', compact('category', 'categories'));
    }

    public function update(UpdateBlogCategoryRequest $request, $id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $this->categoryService->updateCategory($id, $request->validated());

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে আপডেট হয়েছে');
    }

    public function destroy($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-categories.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $this->categoryService->deleteCategory($id);

        return response()->json([
            'success' => true,
            'message' => 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে',
        ]);
    }
}
