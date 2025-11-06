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
        $query = Brand::where('is_active', true);
        
        // Filter by letter if provided
        if ($request->has('letter')) {
            $letter = strtoupper($request->get('letter'));
            $query->where('name', 'LIKE', $letter . '%');
        }
        
        $brands = $query->orderBy('name')->paginate(24);
        
        // Get brands grouped by letter for A-Z navigation
        $brandsByLetter = Brand::where('is_active', true)
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
            ->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->active()
            ->with(['images', 'categories'])
            ->paginate(24);

        return view('frontend.brands.show', compact('brand', 'products'));
    }
}
