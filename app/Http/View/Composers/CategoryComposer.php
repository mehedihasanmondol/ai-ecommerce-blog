<?php

namespace App\Http\View\Composers;

use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Brand\Models\Brand;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

/**
 * CategoryComposer
 * Purpose: Provide category and brand data to views (especially for mega menu)
 * 
 * @author AI Assistant
 * @date 2025-11-06
 */
class CategoryComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $megaMenuCategories = $this->getMegaMenuCategories();
        $trendingBrands = $this->getTrendingBrands();
        
        $view->with([
            'megaMenuCategories' => $megaMenuCategories,
            'trendingBrands' => $trendingBrands,
        ]);
    }

    /**
     * Get categories for mega menu
     * Cached for performance
     */
    protected function getMegaMenuCategories()
    {
        return Cache::remember('mega_menu_categories', 3600, function () {
            return Category::with(['activeChildren' => function ($query) {
                    $query->with(['activeChildren' => function ($subQuery) {
                        $subQuery->orderBy('sort_order')->limit(8); // Limit third-level categories
                    }])
                    ->orderBy('sort_order')
                    ->limit(10); // Limit subcategories per parent
                }])
                ->parents()
                ->active()
                ->ordered()
                ->limit(8) // Limit to 8 main categories for mega menu
                ->get();
        });
    }

    /**
     * Get trending brands for mega menu
     * Cached for performance
     */
    protected function getTrendingBrands()
    {
        return Cache::remember('trending_brands', 3600, function () {
            return Brand::where('is_active', true)
                ->where('is_featured', true) // Assuming brands have is_featured column
                ->orderBy('sort_order')
                ->limit(6) // Show 6 trending brands
                ->get();
        });
    }
}
