<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Ecommerce\Product\Models\Product;

/**
 * ModuleName: Frontend Product Controller
 * Purpose: Handle public product display
 * 
 * Key Methods:
 * - show($slug): Display single product detail page
 * 
 * Dependencies:
 * - Product Model
 * 
 * @category Frontend
 * @package  Controllers
 * @author   Windsurf AI
 * @created  2025-11-06
 */
class ProductController extends Controller
{
    /**
     * Display the product detail page
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
    {
        // Find product by slug with all relationships
        $product = Product::with([
            'variants.attributeValues.attribute', 
            'images', 
            'category.parent', 
            'brand'
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Get default variant for simple products
        $defaultVariant = $product->variants->where('is_default', true)->first() 
                       ?? $product->variants->first();

        // Get related products (same category, limit 8)
        $relatedProducts = Product::with(['variants', 'images', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(8)
            ->get();

        // Get recently viewed products from session
        $recentlyViewed = $this->getRecentlyViewedProducts($product->id);

        // Get inspired by browsing products (based on browsing history)
        $inspiredByBrowsing = $this->getInspiredByBrowsing($product);

        // Track this product as recently viewed
        $this->trackRecentlyViewed($product->id);

        // Calculate average rating (placeholder - will implement when reviews are added)
        $averageRating = 0;
        $totalReviews = 0;

        return view('frontend.products.show', compact(
            'product', 
            'defaultVariant',
            'relatedProducts', 
            'recentlyViewed',
            'inspiredByBrowsing',
            'averageRating',
            'totalReviews'
        ));
    }

    /**
     * Track recently viewed products in session
     *
     * @param int $productId
     * @return void
     */
    protected function trackRecentlyViewed(int $productId): void
    {
        $recentlyViewed = session()->get('recently_viewed', []);
        
        // Remove if already exists
        $recentlyViewed = array_diff($recentlyViewed, [$productId]);
        
        // Add to beginning
        array_unshift($recentlyViewed, $productId);
        
        // Keep only last 10
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        
        session()->put('recently_viewed', $recentlyViewed);
    }

    /**
     * Get recently viewed products
     *
     * @param int $currentProductId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentlyViewedProducts(int $currentProductId)
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);
        
        // Remove current product from list
        $recentlyViewedIds = array_diff($recentlyViewedIds, [$currentProductId]);
        
        if (empty($recentlyViewedIds)) {
            return collect([]);
        }

        // Get products maintaining the order
        return Product::with(['variants', 'images', 'brand'])
            ->whereIn('id', $recentlyViewedIds)
            ->where('is_active', true)
            ->get()
            ->sortBy(function ($product) use ($recentlyViewedIds) {
                return array_search($product->id, $recentlyViewedIds);
            })
            ->take(6);
    }

    /**
     * Get inspired by browsing products based on user's browsing history
     *
     * @param Product $currentProduct
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getInspiredByBrowsing(Product $currentProduct)
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);
        
        // If no browsing history, return products from same category
        if (empty($recentlyViewedIds)) {
            return Product::with(['variants', 'images', 'brand'])
                ->where('category_id', $currentProduct->category_id)
                ->where('id', '!=', $currentProduct->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        // Get recently viewed products to analyze browsing patterns
        $recentlyViewedProducts = Product::whereIn('id', $recentlyViewedIds)
            ->where('is_active', true)
            ->get();

        // Collect category IDs and brand IDs from browsing history
        $categoryIds = $recentlyViewedProducts->pluck('category_id')->unique()->toArray();
        $brandIds = $recentlyViewedProducts->pluck('brand_id')->filter()->unique()->toArray();

        // Get products from browsed categories and brands
        $query = Product::with(['variants', 'images', 'brand'])
            ->where('id', '!=', $currentProduct->id)
            ->where('is_active', true);

        // Filter by categories or brands from browsing history
        $query->where(function ($q) use ($categoryIds, $brandIds) {
            if (!empty($categoryIds)) {
                $q->whereIn('category_id', $categoryIds);
            }
            if (!empty($brandIds)) {
                $q->orWhereIn('brand_id', $brandIds);
            }
        });

        // Exclude already viewed products
        $query->whereNotIn('id', $recentlyViewedIds);

        return $query->inRandomOrder()
            ->limit(10)
            ->get();
    }
}
