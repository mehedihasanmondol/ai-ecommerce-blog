<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Ecommerce\Product\Models\Product;

/**
 * ModuleName: Cart Controller
 * Purpose: Handle cart page display and cart operations
 * 
 * @category Controllers
 * @package  App\Http\Controllers
 * @created  2025-11-09
 */
class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cartArray = session()->get('cart', []);
        $cart = collect($cartArray);
        
        // Calculate totals
        $itemsTotal = 0;
        $totalWeight = 0;
        $discounts = 0;
        
        foreach ($cart as $item) {
            $itemsTotal += $item['price'] * $item['quantity'];
            
            // Calculate discount if original price exists
            if (isset($item['original_price']) && $item['original_price'] > $item['price']) {
                $discounts += ($item['original_price'] - $item['price']) * $item['quantity'];
            }
        }
        
        // Get recommended products (similar to frequently purchased)
        $recommendedProducts = $this->getRecommendedProducts($cartArray);
        
        return view('frontend.cart.index', compact('cart', 'itemsTotal', 'discounts', 'totalWeight', 'recommendedProducts'));
    }
    
    /**
     * Get recommended products based on cart items
     */
    protected function getRecommendedProducts($cart)
    {
        if (empty($cart)) {
            // If cart is empty, show popular products
            return Product::with(['variants', 'images', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->limit(5)
                ->get()
                ->map(function($product) {
                    return $this->formatProductData($product);
                });
        }
        
        // Get product IDs from cart
        $cartProductIds = collect($cart)->pluck('product_id')->unique()->toArray();
        
        // Get category IDs from cart products
        $cartProducts = Product::whereIn('id', $cartProductIds)->get();
        $categoryIds = $cartProducts->pluck('category_id')->unique()->filter()->toArray();
        
        if (empty($categoryIds)) {
            return collect([]);
        }
        
        // Get related products from same categories
        return Product::with(['variants', 'images', 'brand'])
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $cartProductIds)
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->map(function($product) {
                return $this->formatProductData($product);
            });
    }
    
    /**
     * Format product data for display
     */
    protected function formatProductData($product)
    {
        $variant = $product->variants->first();
        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
        
        return [
            'id' => $product->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id ?? null,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand ? $product->brand->name : null,
            'price' => $variant->sale_price ?? $variant->price ?? 0,
            'original_price' => $variant->price ?? 0,
            'image' => $primaryImage ? $primaryImage->image_path : null,
            'rating' => $product->average_rating ?? 0,
            'reviews' => $product->review_count ?? 0,
            'sku' => $variant->sku ?? null,
        ];
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }
    
    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }
    
    /**
     * Remove multiple items from cart
     */
    public function removeMultiple(Request $request)
    {
        $request->validate([
            'cart_keys' => 'required|array',
            'cart_keys.*' => 'string',
        ]);
        
        $cart = session()->get('cart', []);
        
        foreach ($request->cart_keys as $key) {
            if (isset($cart[$key])) {
                unset($cart[$key]);
            }
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Items removed from cart'
        ]);
    }
}
