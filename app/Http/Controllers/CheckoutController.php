<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\Delivery\Services\DeliveryService;
use App\Modules\Ecommerce\Order\Services\OrderService;
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
        protected OrderService $orderService
    ) {}

    /**
     * Display checkout page with delivery options
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Get active delivery zones
        $deliveryZones = $this->deliveryService->getActiveZones();
        
        // Get active delivery methods
        $deliveryMethods = $this->deliveryService->getActiveMethods();

        // Get user's saved addresses if authenticated
        $savedAddresses = [];
        if (Auth::check()) {
            // TODO: Implement saved addresses feature
            $savedAddresses = [];
        }

        return view('frontend.checkout.index', compact(
            'cart',
            'subtotal',
            'deliveryZones',
            'deliveryMethods',
            'savedAddresses'
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
        ]);

        try {
            $shippingCost = $this->deliveryService->calculateShippingCost(
                $validated['zone_id'],
                $validated['method_id'],
                $validated['subtotal'],
                $validated['weight'] ?? 0,
                $validated['item_count'] ?? 1
            );

            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingCost,
                'total' => $validated['subtotal'] + $shippingCost,
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
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line_1' => 'required|string|max:255',
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'payment_method' => 'required|in:cod,online',
            'order_notes' => 'nullable|string|max:500',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
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

            // Calculate shipping cost
            $shippingCost = $this->deliveryService->calculateShippingCost(
                $validated['delivery_zone_id'],
                $validated['delivery_method_id'],
                $subtotal,
                $totalWeight,
                $itemCount
            );

            $total = $subtotal + $shippingCost;

            // Prepare order data
            $orderData = [
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'delivery_zone_id' => $validated['delivery_zone_id'],
                'delivery_method_id' => $validated['delivery_method_id'],
                'notes' => $validated['order_notes'] ?? null,
            ];

            // Parse name into first and last name
            $nameParts = explode(' ', $validated['shipping_name'], 2);
            $firstName = $validated['shipping_first_name'] ?? $nameParts[0] ?? '';
            $lastName = $validated['shipping_last_name'] ?? ($nameParts[1] ?? '');

            // Shipping address
            $shippingAddress = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['shipping_email'],
                'phone' => $validated['shipping_phone'],
                'address_line_1' => $validated['shipping_address_line_1'],
                'address_line_2' => null,
                'city' => null,
                'state' => null,
                'postal_code' => null,
                'country' => 'BD',
            ];

            // Order items
            $orderItems = [];
            foreach ($cart as $item) {
                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['sku'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ];
            }

            // Create order
            $order = $this->orderService->createOrder(
                $orderData,
                $orderItems,
                $shippingAddress,
                $shippingAddress // Use same address for billing
            );

            // Clear cart
            Session::forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully! Order number: ' . $order->order_number);

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to place order. Please try again.');
        }
    }
}
