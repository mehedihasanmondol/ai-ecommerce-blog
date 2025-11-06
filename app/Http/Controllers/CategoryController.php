<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Product\Models\Product;
use Illuminate\Http\Request;

/**
 * Frontend Category Controller
 * Purpose: Handle public category browsing and product listing
 * 
 * @author AI Assistant
 * @date 2025-11-06
 */
class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index()
    {
        $categories = Category::with(['activeChildren'])
            ->parents()
            ->active()
            ->ordered()
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Display a specific category and its products
     */
    public function show(string $slug)
    {
        $category = Category::with(['activeChildren', 'parent'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get products in this category and its subcategories
        $categoryIds = $this->getCategoryIdsWithChildren($category);

        $products = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->active()
            ->with(['images', 'categories'])
            ->paginate(24);

        // Get breadcrumb
        $breadcrumb = $category->getBreadcrumb();

        return view('frontend.categories.show', compact('category', 'products', 'breadcrumb'));
    }

    /**
     * Get category IDs including all children recursively
     */
    protected function getCategoryIdsWithChildren(Category $category): array
    {
        $ids = [$category->id];

        foreach ($category->activeChildren as $child) {
            $ids[] = $child->id;
            
            // Get third level
            foreach ($child->activeChildren as $grandChild) {
                $ids[] = $grandChild->id;
            }
        }

        return $ids;
    }
}
