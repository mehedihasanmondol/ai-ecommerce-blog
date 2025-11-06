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
        // Find product by slug
        $product = Product::with([
            'variants', 
            'images', 
            'category', 
            'brand',
            'attributes.values'
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Get related products (same category)
        $relatedProducts = Product::with(['variants', 'images', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('frontend.product.show', compact('product', 'relatedProducts'));
    }
}
