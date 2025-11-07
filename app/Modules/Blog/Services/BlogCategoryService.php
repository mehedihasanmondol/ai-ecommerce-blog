<?php

namespace App\Modules\Blog\Services;

use App\Modules\Blog\Models\BlogCategory;
use App\Modules\Blog\Repositories\BlogCategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * ModuleName: Blog
 * Purpose: Business logic for blog category management
 * 
 * @category Blog
 * @package  App\Modules\Blog\Services
 * @author   AI Assistant
 * @created  2025-11-07
 */
class BlogCategoryService
{
    protected BlogCategoryRepository $categoryRepository;

    public function __construct(BlogCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function getActiveCategories(): Collection
    {
        return $this->categoryRepository->getActive();
    }

    public function getRootCategories(): Collection
    {
        return $this->categoryRepository->getRoots();
    }

    public function getCategory(int $id): ?BlogCategory
    {
        return $this->categoryRepository->find($id);
    }

    public function getCategoryBySlug(string $slug): ?BlogCategory
    {
        return $this->categoryRepository->findBySlug($slug);
    }

    public function createCategory(array $data): BlogCategory
    {
        DB::beginTransaction();
        try {
            // Handle image upload
            if (isset($data['image']) && $data['image']) {
                $data['image_path'] = $this->uploadCategoryImage($data['image']);
                unset($data['image']);
            }

            $category = $this->categoryRepository->create($data);

            // Log activity (TODO: Install spatie/laravel-activitylog package)
            // activity()
            //     ->performedOn($category)
            //     ->causedBy(auth()->user())
            //     ->log('Created blog category');

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCategory(int $id, array $data): BlogCategory
    {
        DB::beginTransaction();
        try {
            $category = $this->getCategory($id);

            // Handle image upload
            if (isset($data['image']) && $data['image']) {
                // Delete old image
                if ($category->image_path) {
                    Storage::disk('public')->delete($category->image_path);
                }
                $data['image_path'] = $this->uploadCategoryImage($data['image']);
                unset($data['image']);
            }

            $category = $this->categoryRepository->update($id, $data);

            // Log activity (TODO: Install spatie/laravel-activitylog package)
            // activity()
            //     ->performedOn($category)
            //     ->causedBy(auth()->user())
            //     ->log('Updated blog category');

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->getCategory($id);

        // Delete image
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        // Log activity (TODO: Install spatie/laravel-activitylog package)
        // activity()
        //     ->performedOn($category)
        //     ->causedBy(auth()->user())
        //     ->log('Deleted blog category');

        return $this->categoryRepository->delete($id);
    }

    protected function uploadCategoryImage($image): string
    {
        $filename = 'blog_category_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('blog/categories', $filename, 'public');
    }
}
