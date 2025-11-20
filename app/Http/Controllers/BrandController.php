<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Modules\Ecommerce\Product\Models\Product;
use Illuminate\Http\Request;

/**
 * Frontend Brand Controller
 * Purpose: Handle public brand browsing and product listing
 * 
 * @author AI Assistant
 * @date 2025-11-06
 */
class BrandController extends Controller
{
    /**
     * Display all brands
     */
    public function index(Request $request)
    {
        $query = Brand::where('is_active', true)->withCount('products');
        
        // Filter by letter if provided
        if ($request->has('letter')) {
            $letter = strtoupper($request->get('letter'));
            $query->where('name', 'LIKE', $letter . '%');
        }
        
        $brands = $query->orderBy('name')->paginate(24);
        
        // Get brands grouped by letter for A-Z navigation
        $brandsByLetter = Brand::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get()
            ->groupBy(function($brand) {
                return strtoupper(substr($brand->name, 0, 1));
            });

        return view('frontend.brands.index', compact('brands', 'brandsByLetter'));
    }

    /**
     * Display a specific brand and its products
     */
    public function show(string $slug)
    {
        $brand = Brand::where('slug', $slug)
            ->where('is_active', true)
            ->withCount('products')
            ->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->active()
            ->with(['images', 'categories'])
            ->paginate(24);

        // Get related brands (other active brands)
        $relatedBrands = Brand::where('is_active', true)
            ->where('id', '!=', $brand->id)
            ->withCount('products')
            ->orderBy('name')
            ->limit(8)
            ->get();
        
        // Prepare SEO data for brand page - use brand's SEO settings if exist, otherwise use defaults
        $seoData = [
            'title' => !empty($brand->meta_title) 
                ? $brand->meta_title 
                : $brand->name . ' Products | ' . \App\Models\SiteSetting::get('site_name', config('app.name')),
            
            'description' => !empty($brand->meta_description) 
                ? $brand->meta_description 
                : (!empty($brand->description) 
                    ? \Illuminate\Support\Str::limit(strip_tags($brand->description), 160)
                    : 'Shop ' . $brand->name . ' products. Discover quality products from ' . $brand->name),
            
            'keywords' => !empty($brand->meta_keywords) 
                ? $brand->meta_keywords 
                : $brand->name . ', ' . $brand->name . ' products, shop ' . $brand->name . ', ' . \App\Models\SiteSetting::get('meta_keywords', 'ecommerce, products'),
            
            'og_image' => !empty($brand->og_image)
                ? asset('storage/' . $brand->og_image)
                : ($brand->logo 
                    ? asset('storage/' . $brand->logo) 
                    : (\App\Models\SiteSetting::get('site_logo')
                        ? asset('storage/' . \App\Models\SiteSetting::get('site_logo'))
                        : asset('images/og-default.jpg'))),
            
            'og_type' => 'website',
            'canonical' => route('brands.show', $brand->slug),
        ];

        return view('frontend.brands.show', compact('brand', 'products', 'relatedBrands', 'seoData'));
    }
}
