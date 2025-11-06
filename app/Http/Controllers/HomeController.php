<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Models\SaleOffer;
use App\Models\TrendingProduct;
use App\Models\BestSellerProduct;

/**
 * ModuleName: Home Controller
 * Purpose: Handle public homepage and landing pages
 * 
 * Key Methods:
 * - index(): Display homepage with featured products
 * 
 * Dependencies:
 * - Product Model
 * - Category Model
 * - Brand Model
 * 
 * @category Frontend
 * @package  Controllers
 * @author   Windsurf AI
 * @created  2025-01-06
 */
class HomeController extends Controller
{
    /**
     * Display the homepage
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get featured products (limit 12 for slider)
        $featuredProducts = Product::with(['variants', 'category', 'brand', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit(12)
            ->get();

        // Get new arrivals (latest 8 products)
        $newArrivals = Product::with(['variants', 'category', 'brand', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        // Get best sellers (products with most orders - placeholder for now)
        $bestSellers = Product::with(['variants', 'category', 'brand', 'images'])
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get all parent categories (for Shop by Category section)
        $featuredCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        // Get featured brands (limit 12)
        $featuredBrands = Brand::where('is_active', true)
            ->where('is_featured', true)
            ->limit(12)
            ->get();

        // Get sale offers products
        $saleOffers = SaleOffer::with(['product.variants', 'product.category', 'product.brand', 'product.images'])
            ->active()
            ->ordered()
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Get trending products
        $trendingProducts = TrendingProduct::with(['product.variants', 'product.category', 'product.brand', 'product.images'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Get best seller products
        $bestSellerProducts = BestSellerProduct::with(['product.variants', 'product.category', 'product.brand', 'product.images'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        return view('frontend.home.index', compact(
            'featuredProducts',
            'newArrivals',
            'bestSellers',
            'featuredCategories',
            'featuredBrands',
            'saleOffers',
            'trendingProducts',
            'bestSellerProducts'
        ));
    }

    /**
     * Display shop page with all products
     *
     * @return \Illuminate\View\View
     */
    public function shop()
    {
        $products = Product::with(['variants', 'category', 'brand', 'images'])
            ->where('is_active', true)
            ->paginate(24);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('frontend.shop.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Display about page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('frontend.pages.about');
    }

    /**
     * Display contact page
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('frontend.pages.contact');
    }
}
