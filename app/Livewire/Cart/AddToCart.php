<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Product\Models\ProductVariant;

/**
 * ModuleName: Cart Management
 * Purpose: Handle add to cart functionality with quantity management
 * 
 * Key Methods:
 * - increment(): Increase quantity
 * - decrement(): Decrease quantity
 * - addToCart(): Add product to cart
 * - updateQuantity(): Update quantity value
 * 
 * Dependencies:
 * - Product Model
 * - ProductVariant Model
 * - Session (for cart storage)
 * 
 * @category Livewire
 * @package  Cart
 * @author   Windsurf AI
 * @created  2025-11-07
 */
class AddToCart extends Component
{
    public $product;
    public $defaultVariant;
    public $quantity = 1;
    public $selectedVariantId;
    public $maxQuantity;
    public $showSuccess = false;

    protected $listeners = ['variant-changed' => 'handleVariantChange'];

    /**
     * Mount component with product data
     */
    public function mount($product, $defaultVariant = null)
    {
        $this->product = $product;
        $this->defaultVariant = $defaultVariant;
        
        // Set initial variant
        if ($this->product->product_type === 'variable') {
            $this->selectedVariantId = $this->product->variants->first()->id ?? null;
        } else {
            $this->selectedVariantId = $this->defaultVariant->id ?? null;
        }
        
        $this->updateMaxQuantity();
    }

    /**
     * Handle variant change from variant selector
     */
    public function handleVariantChange($variantData)
    {
        if (isset($variantData['id'])) {
            $this->selectedVariantId = $variantData['id'];
            $this->updateMaxQuantity();
            
            // Reset quantity if it exceeds new max
            if ($this->quantity > $this->maxQuantity) {
                $this->quantity = $this->maxQuantity;
            }
        }
    }

    /**
     * Update maximum quantity based on selected variant
     */
    protected function updateMaxQuantity()
    {
        if ($this->selectedVariantId) {
            $variant = ProductVariant::find($this->selectedVariantId);
            $this->maxQuantity = $variant ? $variant->stock_quantity : 0;
        } else {
            $this->maxQuantity = 0;
        }
    }

    /**
     * Increment quantity
     */
    public function increment()
    {
        if ($this->quantity < $this->maxQuantity) {
            $this->quantity++;
        }
    }

    /**
     * Decrement quantity
     */
    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    /**
     * Update quantity directly
     */
    public function updateQuantity($value)
    {
        $value = (int) $value;
        
        if ($value < 1) {
            $this->quantity = 1;
        } elseif ($value > $this->maxQuantity) {
            $this->quantity = $this->maxQuantity;
        } else {
            $this->quantity = $value;
        }
    }

    /**
     * Add product to cart
     */
    public function addToCart()
    {
        // Validate variant selection for variable products
        if ($this->product->product_type === 'variable' && !$this->selectedVariantId) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Please select product options'
            ]);
            return;
        }

        // Validate stock availability
        $variant = ProductVariant::find($this->selectedVariantId);
        
        if (!$variant) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Product variant not found'
            ]);
            return;
        }

        if ($variant->stock_quantity < $this->quantity) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Insufficient stock available'
            ]);
            return;
        }

        // Get cart from session
        $cart = session()->get('cart', []);

        // Check if variant already in cart
        $cartKey = 'variant_' . $variant->id;
        
        if (isset($cart[$cartKey])) {
            // Update quantity
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            // Add new item
            $cart[$cartKey] = [
                'product_id' => $this->product->id,
                'variant_id' => $variant->id,
                'product_name' => $this->product->name,
                'variant_name' => $variant->variant_name,
                'sku' => $variant->sku,
                'price' => $variant->sale_price ?? $variant->price,
                'quantity' => $this->quantity,
                'image' => $this->product->images->first()->path ?? null,
            ];
        }

        // Save cart to session
        session()->put('cart', $cart);

        // Show success message
        $this->showSuccess = true;
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Product added to cart successfully!'
        ]);

        // Update cart count in header
        $this->dispatch('cart-updated', ['count' => count($cart)]);

        // Reset quantity
        $this->quantity = 1;

        // Hide success message after 3 seconds
        $this->dispatch('hide-success-after', ['delay' => 3000]);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
