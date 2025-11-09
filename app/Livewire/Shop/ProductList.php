<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Brand\Models\Brand;

/**
 * ModuleName: Product List Component
 * Purpose: Display and filter products in shop page
 * 
 * @category Livewire
 * @package  Shop
 * @created  2025-11-09
 */
class ProductList extends Component
{
    use WithPagination;

    // URL query string parameters
    #[Url(as: 'q')]
    public $search = '';
    
    #[Url(as: 'cat', keep: true)]
    public $selectedCategories = [];
    
    #[Url(as: 'brand', keep: true)]
    public $selectedBrands = [];
    
    #[Url(as: 'min')]
    public $minPrice = '';
    
    #[Url(as: 'max')]
    public $maxPrice = '';
    
    #[Url(as: 'rating')]
    public $minRating = '';
    
    #[Url(as: 'stock')]
    public $inStock = false;
    
    #[Url(as: 'sale')]
    public $onSale = false;
    
    #[Url(as: 'sort')]
    public $sortBy = 'latest';
    
    #[Url(as: 'show')]
    public $perPage = 24;

    public $viewMode = 'grid';
    public $showFilters = false;

    /**
     * Reset pagination when filters change
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatingSelectedBrands()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingMinRating()
    {
        $this->resetPage();
    }

    public function updatingInStock()
    {
        $this->resetPage();
    }

    public function updatingOnSale()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset([
            'search',
            'selectedCategories',
            'selectedBrands',
            'minPrice',
            'maxPrice',
            'minRating',
            'inStock',
            'onSale'
        ]);
        $this->resetPage();
    }

    /**
     * Clear specific filter
     */
    public function clearFilter($filter)
    {
        $this->reset($filter);
        $this->resetPage();
    }

    /**
     * Toggle view mode
     */
    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    /**
     * Get filtered products
     */
    public function getProductsProperty()
    {
        $query = Product::with(['variants', 'category', 'brand', 'images'])
            ->where('is_active', true);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        // Category Filter
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Brand Filter
        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        // Price Range Filter
        if ($this->minPrice !== '' && $this->minPrice !== null) {
            $query->whereHas('variants', function($q) {
                $q->where('price', '>=', $this->minPrice);
            });
        }
        if ($this->maxPrice !== '' && $this->maxPrice !== null) {
            $query->whereHas('variants', function($q) {
                $q->where('price', '<=', $this->maxPrice);
            });
        }

        // Rating Filter
        if ($this->minRating) {
            $query->where('average_rating', '>=', $this->minRating);
        }

        // In Stock Filter
        if ($this->inStock) {
            $query->whereHas('variants', function($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }

        // On Sale Filter
        if ($this->onSale) {
            $query->whereHas('variants', function($q) {
                $q->whereNotNull('sale_price')
                  ->whereColumn('sale_price', '<', 'price');
            });
        }

        // Sorting
        switch ($this->sortBy) {
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Get categories with product counts
     */
    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get brands with product counts
     */
    public function getBrandsProperty()
    {
        return Brand::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get price range
     */
    public function getPriceRangeProperty()
    {
        return Product::where('products.is_active', true)
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->selectRaw('MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price')
            ->first();
    }

    /**
     * Check if any filters are active
     */
    public function hasActiveFilters()
    {
        return $this->search || 
               !empty($this->selectedCategories) || 
               !empty($this->selectedBrands) ||
               $this->minPrice ||
               $this->maxPrice ||
               $this->minRating ||
               $this->inStock ||
               $this->onSale;
    }

    /**
     * Add product to cart
     */
    #[On('add-to-cart')]
    public function addToCart($productId, $variantId, $quantity = 1)
    {
        $product = Product::with(['variants', 'images', 'brand'])->find($productId);
        
        if (!$product) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Product not found'
            ]);
            return;
        }
        
        $variant = $product->variants->where('id', $variantId)->first();
        
        if (!$variant) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Product variant not found'
            ]);
            return;
        }
        
        // Check stock
        if ($variant->stock_quantity < $quantity) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Insufficient stock'
            ]);
            return;
        }
        
        $cart = session()->get('cart', []);
        $cartKey = 'variant_' . $variant->id;
        
        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand ? $product->brand->name : null,
                'price' => $variant->sale_price ?? $variant->price,
                'original_price' => $variant->price,
                'quantity' => $quantity,
                'image' => $primaryImage ? $primaryImage->image_path : null,
                'sku' => $variant->sku,
                'stock_quantity' => $variant->stock_quantity,
            ];
        }
        
        session()->put('cart', $cart);
        
        $this->dispatch('cart-updated');
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Product added to cart!'
        ]);
    }

    /**
     * Toggle wishlist
     */
    #[On('toggle-wishlist')]
    public function toggleWishlist($productId, $variantId = null)
    {
        $wishlist = session()->get('wishlist', []);
        
        $key = 'product_' . $productId;
        if ($variantId) {
            $key = 'variant_' . $variantId;
        }
        
        if (isset($wishlist[$key])) {
            // Remove from wishlist
            unset($wishlist[$key]);
            session()->put('wishlist', $wishlist);
            
            $this->dispatch('wishlist-updated');
            $this->dispatch('show-toast', [
                'type' => 'info',
                'message' => 'Removed from wishlist'
            ]);
            return;
        }
        
        // Add to wishlist
        $product = Product::with(['variants', 'images', 'brand'])->find($productId);
        
        if (!$product) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Product not found'
            ]);
            return;
        }
        
        $variant = null;
        if ($variantId) {
            $variant = $product->variants->where('id', $variantId)->first();
        } else {
            $variant = $product->variants->first();
        }
        
        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
        
        $wishlist[$key] = [
            'product_id' => $product->id,
            'variant_id' => $variant->id ?? null,
            'product_name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand ? $product->brand->name : null,
            'price' => $variant->sale_price ?? $variant->price ?? 0,
            'original_price' => $variant->price ?? 0,
            'image' => $primaryImage ? $primaryImage->image_path : null,
            'sku' => $variant->sku ?? null,
            'added_at' => now()->toDateTimeString(),
        ];
        
        session()->put('wishlist', $wishlist);
        
        $this->dispatch('wishlist-updated');
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Added to wishlist!'
        ]);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.shop.product-list', [
            'products' => $this->products,
            'categories' => $this->categories,
            'brands' => $this->brands,
            'priceRange' => $this->priceRange,
        ])->extends('layouts.app')
          ->section('content');
    }
}
