@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-gray-50 min-h-screen py-8" x-data="checkoutPage()" @shipping-updated.window="shippingCost = $event.detail.shippingCost || 0">
    <div class="container mx-auto px-4">
        
        <!-- Error/Success Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-5 bg-red-50 border-2 border-red-300 text-red-800 rounded-lg shadow-md">
                <div class="flex items-start">
                    <svg class="w-6 h-6 mr-3 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2">⚠️ Please fix the following errors:</h3>
                        <ul class="space-y-2">
                            @foreach($errors->all() as $error)
                                <li class="flex items-start">
                                    <span class="text-red-600 mr-2">•</span>
                                    <span class="font-medium">{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="checkoutForm" method="POST" action="{{ route('checkout.place-order') }}" @submit="submitOrder">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Checkout Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Shipping Address
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" name="shipping_name" required
                                       value="{{ old('shipping_name', $defaultShipping['name'] ?? '') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Recipient name">
                                <!-- Hidden fields to match backend expectations -->
                                <input type="hidden" name="shipping_first_name" value="{{ old('shipping_first_name', Auth::user()->first_name ?? '') }}">
                                <input type="hidden" name="shipping_last_name" value="{{ old('shipping_last_name', Auth::user()->last_name ?? '') }}">
                                @error('shipping_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                    <input type="tel" name="shipping_phone" required
                                           value="{{ old('shipping_phone', $defaultShipping['phone'] ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Recipient phone">
                                    @error('shipping_phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="shipping_email"
                                           value="{{ old('shipping_email', $defaultShipping['email'] ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Recipient email (optional)">
                                    @error('shipping_email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                <input type="text" name="shipping_address_line_1" required
                                       value="{{ old('shipping_address_line_1', $defaultShipping['address'] ?? '') }}"
                                       placeholder="House/Flat, Street, Area"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('shipping_address_line_1')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Options -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @livewire('checkout.delivery-selector', ['cartTotal' => $subtotal, 'cartWeight' => $totalWeight, 'itemCount' => count($cart)])
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-2 p-2 border-2 rounded-md cursor-pointer transition-all"
                                   :class="paymentMethod === 'cod' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" required
                                       class="w-3.5 h-3.5 text-green-600 border-gray-300 focus:ring-green-500">
                                <div class="flex items-center justify-between flex-1">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Cash on Delivery</p>
                                        <p class="text-xs text-gray-500">Pay when you receive</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 p-2 border-2 border-gray-200 rounded-md opacity-50 cursor-not-allowed">
                                <input type="radio" name="payment_method" value="online" disabled
                                       class="w-3.5 h-3.5 text-gray-400 border-gray-300">
                                <div class="flex items-center justify-between flex-1">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Online Payment</p>
                                        <p class="text-xs text-gray-400">Coming soon</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Order Notes
                            <span class="ml-2 text-xs font-normal text-gray-500">(Optional)</span>
                        </h2>
                        <textarea name="order_notes" rows="3"
                                  placeholder="Any special instructions for delivery? (e.g., Call before delivery, Leave at door)"
                                  class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm">{{ old('order_notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                        
                        <!-- Cart Items -->
                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            @foreach($cart as $item)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/placeholder.png') }}" 
                                     alt="{{ $item['product_name'] }}"
                                     class="w-16 h-16 object-cover rounded">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-sm font-bold text-gray-900">৳{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            @if(session('applied_coupon'))
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Coupon ({{ session('applied_coupon.code') }})
                                </span>
                                <span class="font-medium text-green-600">-৳{{ number_format(session('applied_coupon.discount_amount', 0), 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                @if(session('applied_coupon.free_shipping'))
                                    <span class="font-medium text-green-600">FREE</span>
                                @else
                                    <span class="font-medium text-gray-900" x-text="shippingCost > 0 ? '৳' + shippingCost.toFixed(2) : 'Select method'"></span>
                                @endif
                            </div>

                            <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                                <span>Total</span>
                                <span x-text="'৳' + ({{ $subtotal }} - {{ session('applied_coupon.discount_amount', 0) }} + {{ session('applied_coupon.free_shipping') ? 0 : 'shippingCost' }}).toFixed(2)"></span>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="isProcessing"
                                class="w-full mt-6 px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                            <span x-show="!isProcessing">Place Order</span>
                            <span x-show="isProcessing">Processing...</span>
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-3">
                            By placing your order, you agree to our terms and conditions
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkoutPage() {
    return {
        selectedZone: '',
        selectedMethod: '',
        availableMethods: [],
        allMethods: [],
        shippingCost: {{ session('shipping_cost', 0) }},
        paymentMethod: 'cod',
        isProcessing: false,
        loadingMethods: false,

        onZoneChange() {
            this.selectedMethod = '';
            this.shippingCost = 0;
            this.availableMethods = [];
            
            if (this.selectedZone) {
                this.loadingMethods = true;
                
                // Filter methods by zone
                fetch(`/checkout/zone-methods?zone_id=${this.selectedZone}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.availableMethods = data.methods;
                        }
                        this.loadingMethods = false;
                    })
                    .catch(error => {
                        console.error('Error fetching methods:', error);
                        this.availableMethods = [];
                        this.loadingMethods = false;
                    });
            }
        },

        calculateShipping() {
            if (!this.selectedZone || !this.selectedMethod) {
                return;
            }

            const subtotal = {{ $subtotal }};
            const itemCount = {{ count($cart) }};

            fetch('/checkout/calculate-shipping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    zone_id: this.selectedZone,
                    method_id: this.selectedMethod,
                    subtotal: subtotal,
                    item_count: itemCount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.shippingCost = data.shipping_cost;
                }
            })
            .catch(error => {
                console.error('Error calculating shipping:', error);
            });
        },

        submitOrder(event) {
            // Set processing state
            this.isProcessing = true;
            
            // Allow form to submit normally - backend validation will handle errors
            return true;
        }
    }
}
</script>
@endsection
