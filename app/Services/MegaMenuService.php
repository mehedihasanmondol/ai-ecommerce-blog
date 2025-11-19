<?php

namespace App\Services;

use App\Models\HomepageSetting;
use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Order\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * MegaMenuService
 * Purpose: Calculate trending brands dynamically per category based on sales data
 * 
 * @author AI Assistant
 * @date 2025-11-19
 */
class MegaMenuService
{
    /**
     * Get trending brands for a specific category based on sales
     * 
     * @param int $categoryId
     * @param int|null $limit
     * @param int|null $days
     * @return \Illuminate\Support\Collection
     */
    public function getTrendingBrandsByCategory(int $categoryId, ?int $limit = null, ?int $days = null)
    {
        // Check if dynamic trending brands feature is enabled
        $isEnabled = HomepageSetting::get('mega_menu_trending_brands_enabled', true);
        $isDynamic = HomepageSetting::get('mega_menu_trending_brands_dynamic', true);
        
        if (!$isEnabled) {
            return collect();
        }
        
        // If dynamic is disabled, fall back to featured brands
        if (!$isDynamic) {
            return $this->getFallbackTrendingBrands($limit);
        }
        
        // Get settings
        $limit = $limit ?? (int) HomepageSetting::get('mega_menu_trending_brands_limit', 6);
        $days = $days ?? (int) HomepageSetting::get('mega_menu_trending_brands_days', 30);
        
        // Cache key for this category
        $cacheKey = "trending_brands_category_{$categoryId}_{$limit}_{$days}";
        
        return Cache::remember($cacheKey, 3600, function () use ($categoryId, $limit, $days) {
            // Get all descendant category IDs (including current category)
            $categoryIds = $this->getCategoryWithDescendants($categoryId);
            
            // First, get brand IDs with sales totals using subquery
            $brandSales = DB::table('order_items')
                ->select('products.brand_id', DB::raw('SUM(order_items.quantity) as total_sales'))
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->whereIn('products.category_id', $categoryIds)
                ->where('orders.status', '!=', 'cancelled')
                ->where('orders.status', '!=', 'failed')
                ->where('orders.created_at', '>=', now()->subDays($days))
                ->whereNull('products.deleted_at')
                ->groupBy('products.brand_id')
                ->orderByDesc('total_sales')
                ->limit($limit)
                ->pluck('brand_id');
            
            // If no sales data, fall back to featured brands
            if ($brandSales->isEmpty()) {
                return $this->getFallbackTrendingBrands($limit);
            }
            
            // Fetch full brand records maintaining order
            $trendingBrands = Brand::whereIn('id', $brandSales)
                ->where('is_active', true)
                ->get()
                ->sortBy(function ($brand) use ($brandSales) {
                    return array_search($brand->id, $brandSales->toArray());
                })
                ->values();
            
            return $trendingBrands;
        });
    }
    
    /**
     * Get all trending brands (not category-specific)
     * Used for global sections like "Brands A-Z"
     * 
     * @param int|null $limit
     * @param int|null $days
     * @return \Illuminate\Support\Collection
     */
    public function getGlobalTrendingBrands(?int $limit = null, ?int $days = null)
    {
        // Check if feature is enabled
        $isEnabled = HomepageSetting::get('mega_menu_trending_brands_enabled', true);
        $isDynamic = HomepageSetting::get('mega_menu_trending_brands_dynamic', true);
        
        if (!$isEnabled) {
            return collect();
        }
        
        // If dynamic is disabled, fall back to featured brands
        if (!$isDynamic) {
            return $this->getFallbackTrendingBrands($limit);
        }
        
        // Get settings
        $limit = $limit ?? (int) HomepageSetting::get('mega_menu_trending_brands_limit', 6);
        $days = $days ?? (int) HomepageSetting::get('mega_menu_trending_brands_days', 30);
        
        // Cache key
        $cacheKey = "trending_brands_global_{$limit}_{$days}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit, $days) {
            // First, get brand IDs with sales totals using subquery
            $brandSales = DB::table('order_items')
                ->select('products.brand_id', DB::raw('SUM(order_items.quantity) as total_sales'))
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('orders.status', '!=', 'cancelled')
                ->where('orders.status', '!=', 'failed')
                ->where('orders.created_at', '>=', now()->subDays($days))
                ->whereNull('products.deleted_at')
                ->groupBy('products.brand_id')
                ->orderByDesc('total_sales')
                ->limit($limit)
                ->pluck('brand_id');
            
            // If no sales data, fall back to featured brands
            if ($brandSales->isEmpty()) {
                return $this->getFallbackTrendingBrands($limit);
            }
            
            // Fetch full brand records maintaining order
            $trendingBrands = Brand::whereIn('id', $brandSales)
                ->where('is_active', true)
                ->get()
                ->sortBy(function ($brand) use ($brandSales) {
                    return array_search($brand->id, $brandSales->toArray());
                })
                ->values();
            
            return $trendingBrands;
        });
    }
    
    /**
     * Get category and all its descendants
     * 
     * @param int $categoryId
     * @return array
     */
    protected function getCategoryWithDescendants(int $categoryId): array
    {
        $categoryIds = [$categoryId];
        
        // Get all child categories
        $childCategories = Category::where('parent_id', $categoryId)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();
        
        $categoryIds = array_merge($categoryIds, $childCategories);
        
        // Get grandchildren categories
        if (!empty($childCategories)) {
            $grandchildCategories = Category::whereIn('parent_id', $childCategories)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
            
            $categoryIds = array_merge($categoryIds, $grandchildCategories);
        }
        
        return $categoryIds;
    }
    
    /**
     * Fallback to featured brands when no sales data available
     * 
     * @param int|null $limit
     * @return \Illuminate\Support\Collection
     */
    protected function getFallbackTrendingBrands(?int $limit = null)
    {
        $limit = $limit ?? (int) HomepageSetting::get('mega_menu_trending_brands_limit', 6);
        
        return Brand::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Clear trending brands cache
     * Call this when orders are created/updated
     * 
     * @return void
     */
    public function clearTrendingBrandsCache(): void
    {
        // Clear all trending brands cache
        Cache::forget('trending_brands_global_*');
        
        // Clear category-specific caches
        $categories = Category::pluck('id');
        foreach ($categories as $categoryId) {
            Cache::forget("trending_brands_category_{$categoryId}_*");
        }
    }
}
