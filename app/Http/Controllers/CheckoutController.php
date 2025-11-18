<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\Delivery\Services\DeliveryService;
use App\Modules\Ecommerce\Order\Services\OrderService;
use App\Modules\User\Models\UserAddress;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * ModuleName: Checkout Management
 * Purpose: Handle checkout process with delivery integration
 * 
 * Key Methods:
 * - index(): Display checkout page with delivery options
 * - calculateShipping(): Calculate shipping cost based on zone and method
 * - placeOrder(): Process order with delivery information
 * 
 * Dependencies:
 * - DeliveryService: For delivery calculations
 * - OrderService: For order creation
 * 
 * @author AI Assistant
 * @date 2025-11-10
 */
class CheckoutController extends Controller
{
    public function __construct(
        protected DeliveryService $deliveryService,
        protected OrderService $orderService,
        protected CouponService $couponService
    ) {}

    /**
     * Display checkout page with delivery options
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        \Log::info('Checkout page accessed', ['cart_count' => count($cart)]);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Calculate cart totals
        $subtotal = 0;
        $totalWeight = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            
            // Calculate weight (assuming weight is in grams, convert to kg)
            if (isset($item['weight'])) {
                $totalWeight += ($item['weight'] / 1000) * $item['quantity'];
            }
        }

        // Get default shipping information and all saved addresses
        $defaultShipping = [];
        $savedAddresses = collect([]);
        $userProfile = null;
        
        if (Auth::check()) {
            // Get all saved addresses
            $savedAddresses = UserAddress::where('user_id', Auth::id())
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get user profile for fallback option
            $userProfile = Auth::user();
            
            // First, try to get default address from address manager
            $defaultAddress = $savedAddresses->where('is_default', true)->first();
            
            if ($defaultAddress) {
                // Use default address from address manager
                $defaultShipping = [
                    'name' => $defaultAddress->name,
                    'phone' => $defaultAddress->phone,
                    'email' => $defaultAddress->email ?? '',
                    'address' => $defaultAddress->address,
                ];
            } else {
                // Fallback to user profile info
                $defaultShipping = [
                    'name' => $userProfile->name,
                    'phone' => $userProfile->mobile ?? $userProfile->phone ?? '',
                    'email' => $userProfile->email,
                    'address' => $userProfile->address ?? '',
                ];
            }
        }

        return view('frontend.checkout.index', compact(
            'cart',
            'subtotal',
            'totalWeight',
            'defaultShipping',
            'savedAddresses',
            'userProfile'
        ));
    }

    /**
     * Calculate shipping cost based on zone and method
     */
    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:delivery_zones,id',
            'method_id' => 'required|exists:delivery_methods,id',
            'subtotal' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'item_count' => 'nullable|integer|min:1',
            'payment_method' => 'nullable|in:cod,online',
        ]);

        try {
            // Determine if COD payment
            $isCod = ($validated['payment_method'] ?? 'online') === 'cod';
            
            // Use detailed calculation to include COD fee
            $shippingData = $this->deliveryService->calculateShippingCostDetailed(
                $validated['zone_id'],
                $validated['method_id'],
                $validated['subtotal'],
                $validated['weight'] ?? 0,
                $validated['item_count'] ?? 1,
                $isCod
            );
            
            if (!$shippingData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $shippingData['message'] ?? 'Failed to calculate shipping',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingData['cost'],
                'breakdown' => $shippingData['breakdown'],
                'total' => $validated['subtotal'] + $shippingData['cost'],
                'is_free' => $shippingData['is_free'] ?? false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get delivery methods for a specific zone
     */
    public function getZoneMethods(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:delivery_zones,id',
        ]);

        $methods = $this->deliveryService->getMethodsByZone($validated['zone_id']);

        return response()->json([
            'success' => true,
            'methods' => $methods,
        ]);
    }

    /**
     * Place order with delivery information
     */
    public function placeOrder(Request $request)
    {
        \Log::info('Place order attempt', ['request_data' => $request->except(['_token'])]);
        
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_email' => 'nullable|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line_1' => 'required|string|max:255',
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'payment_method' => 'required|in:cod,online',
            'order_notes' => 'nullable|string|max:500',
        ], [
            'delivery_zone_id.required' => 'Please select a delivery zone',
            'delivery_zone_id.exists' => 'The selected delivery zone is invalid',
            'delivery_method_id.required' => 'Please select a delivery method',
            'delivery_method_id.exists' => 'The selected delivery method is invalid',
            'shipping_name.required' => 'Recipient name is required',
            'shipping_email.email' => 'Please enter a valid email address',
            'shipping_phone.required' => 'Phone number is required',
            'shipping_address_line_1.required' => 'Shipping address is required',
            'payment_method.required' => 'Please select a payment method',
        ]);

        $cart = Session::get('cart', []);
        
        \Log::info('Cart contents', ['cart' => $cart]);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Validate stock availability if restriction is enabled
        $restrictionEnabled = \App\Models\SiteSetting::get('enable_out_of_stock_restriction', '1') === '1';
        if ($restrictionEnabled) {
            foreach ($cart as $item) {
                if (isset($item['variant_id'])) {
                    $variant = \App\Modules\Ecommerce\Product\Models\ProductVariant::find($item['variant_id']);
                    if ($variant && !$variant->canAddToCart()) {
                        return back()
                            ->withInput()
                            ->with('error', "Product '{$item['product_name']}' is out of stock. Please remove it from your cart.");
                    }
                    
                    if ($variant && $variant->stock_quantity < $item['quantity']) {
                        return back()
                            ->withInput()
                            ->with('error', "Insufficient stock for '{$item['product_name']}'. Only {$variant->stock_quantity} available.");
                    }
                }
            }
        }

        try {
            // Calculate totals
            $subtotal = 0;
            $totalWeight = 0;
            $itemCount = 0;

            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $totalWeight += ($item['weight'] ?? 0) * $item['quantity'];
                $itemCount += $item['quantity'];
            }

            // Handle coupon discount
            $discountAmount = 0;
            $couponCode = null;
            $freeShipping = false;
            
            $appliedCoupon = Session::get('applied_coupon');
            if ($appliedCoupon) {
                $discountAmount = $appliedCoupon['discount_amount'] ?? 0;
                $couponCode = $appliedCoupon['code'] ?? null;
                $freeShipping = $appliedCoupon['free_shipping'] ?? false;
                
                // Record coupon usage
                if ($couponCode && Auth::check()) {
                    $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                    if ($coupon) {
                        // Will record usage after order is created
                    }
                }
            }

            // Determine if COD payment
            $isCod = $validated['payment_method'] === 'cod';
            
            // Calculate shipping cost with detailed breakdown (includes COD fee if applicable)
            // Apply free shipping from coupon
            if ($freeShipping) {
                $shippingCost = 0;
                $shippingBreakdown = [
                    'base_rate' => 0,
                    'handling_fee' => 0,
                    'insurance_fee' => 0,
                    'cod_fee' => 0,
                ];
            } else {
                $shippingData = $this->deliveryService->calculateShippingCostDetailed(
                    $validated['delivery_zone_id'],
                    $validated['delivery_method_id'],
                    $subtotal,
                    $totalWeight,
                    $itemCount,
                    $isCod
                );
                
                if (!$shippingData['success']) {
                    return back()
                        ->withInput()
                        ->with('error', $shippingData['message'] ?? 'Failed to calculate shipping cost');
                }
                
                $shippingCost = $shippingData['cost'];
                $shippingBreakdown = $shippingData['breakdown'];
            }

            // Parse name into first and last name
            $nameParts = explode(' ', $validated['shipping_name'], 2);
            $firstName = $validated['shipping_first_name'] ?? $nameParts[0] ?? '';
            $lastName = $validated['shipping_last_name'] ?? ($nameParts[1] ?? '');

            // Prepare order items for OrderService
            $orderItems = [];
            foreach ($cart as $item) {
                // If no variant_id, create a default variant object
                $variant = null;
                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = (object)[
                        'id' => $item['variant_id'],
                        'sku' => $item['sku'] ?? 'N/A',
                        'price' => $item['price'],
                        'attributeValues' => collect([]), // Empty collection
                    ];
                } else {
                    // Create a pseudo-variant for products without variants
                    $variant = (object)[
                        'id' => null, // Will be handled by OrderService
                        'sku' => $item['sku'] ?? 'N/A',
                        'price' => $item['price'],
                        'attributeValues' => collect([]),
                    ];
                }

                $orderItems[] = [
                    'product' => (object)[
                        'id' => $item['product_id'],
                        'name' => $item['product_name'],
                        'sku' => $item['sku'] ?? 'N/A',
                        'price' => $item['price'],
                    ],
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ];
            }

            // Shipping address
            $shippingAddress = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['shipping_email'],
                'phone' => $validated['shipping_phone'],
                'address_line_1' => $validated['shipping_address_line_1'],
                'address_line_2' => '',
                'city' => 'N/A', // Required field, use placeholder
                'state' => '',
                'postal_code' => '',
                'country' => 'BD',
            ];

            // Prepare order data for OrderService
            $orderData = [
                'user_id' => Auth::id(),
                'customer_name' => $validated['shipping_name'],
                'customer_email' => $validated['shipping_email'],
                'customer_phone' => $validated['shipping_phone'],
                'customer_notes' => $validated['order_notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'shipping_cost' => $shippingCost,
                'base_shipping_cost' => $shippingBreakdown['base_rate'] ?? 0,
                'handling_fee' => $shippingBreakdown['handling_fee'] ?? 0,
                'insurance_fee' => $shippingBreakdown['insurance_fee'] ?? 0,
                'cod_fee' => $shippingBreakdown['cod_fee'] ?? 0,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'items' => $orderItems,
                'billing_address' => $shippingAddress,
                'shipping_address' => $shippingAddress,
                // Delivery information
                'delivery_zone_id' => $validated['delivery_zone_id'],
                'delivery_method_id' => $validated['delivery_method_id'],
            ];

            // Create order
            $order = $this->orderService->createOrder($orderData);

            // Record coupon usage after order is created
            if ($couponCode && Auth::check()) {
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $this->couponService->recordUsage($coupon, Auth::id(), $discountAmount, $order->id);
                }
            }

            // Clear cart and coupon session
            Session::forget('cart');
            Session::forget('applied_coupon');

            // Redirect based on authentication
            if (Auth::check()) {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('success', 'Order placed successfully! Order number: ' . $order->order_number);
            } else {
                // For guest users, redirect to a thank you page or home
                return redirect()->route('home')
                    ->with('success', 'Order placed successfully! Order number: ' . $order->order_number . '. Check your email for order details.');
            }

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()
                ->withInput()
                ->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }
}
