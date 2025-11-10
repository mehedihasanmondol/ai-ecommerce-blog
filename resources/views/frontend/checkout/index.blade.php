@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-gray-50 min-h-screen py-8" x-data="checkoutPage()">
    <div class="container mx-auto px-4">
        <form @submit.prevent="submitOrder" method="POST" action="{{ route('checkout.place-order') }}">
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
                                       value="{{ old('shipping_name', Auth::user()->name ?? '') }}"
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
                                           value="{{ old('shipping_phone', Auth::user()->phone ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Recipient phone">
                                    @error('shipping_phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="shipping_email" required
                                           value="{{ old('shipping_email', Auth::user()->email ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Recipient email">
                                    @error('shipping_email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                <input type="text" name="shipping_address_line_1" required
                                       value="{{ old('shipping_address_line_1') }}"
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
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Delivery Options
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Delivery Zone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Zone *</label>
                                <select name="delivery_zone_id" required
                                        x-model="selectedZone"
                                        @change="onZoneChange()"
                                        class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="">Select your area</option>
                                    @foreach($deliveryZones as $zone)
                                        <option value="{{ $zone->id }}">
                                            {{ $zone->name }}@if($zone->description) - {{ $zone->description }}@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_zone_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Delivery Method -->
                            <div x-show="selectedZone">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Method *</label>
                                
                                <!-- Loading state -->
                                <div x-show="loadingMethods" class="text-center py-2">
                                    <div class="inline-flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="animate-spin h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading methods...
                                    </div>
                                </div>

                                <!-- Methods list - Ultra Compact Design -->
                                <div class="space-y-1.5" x-show="!loadingMethods && availableMethods.length > 0">
                                    <template x-for="method in availableMethods" :key="method.id">
                                        <label class="flex items-center gap-2 p-2 border-2 rounded-md cursor-pointer transition-all"
                                               :class="selectedMethod == method.id ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                                            <input type="radio" 
                                                   name="delivery_method_id" 
                                                   :value="method.id"
                                                   x-model="selectedMethod"
                                                   @change="calculateShipping()"
                                                   required
                                                   class="w-3.5 h-3.5 text-green-600 border-gray-300 focus:ring-green-500">
                                            <div class="flex items-center justify-between flex-1 min-w-0">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="method.name"></p>
                                                    <p class="text-xs text-gray-500 truncate" x-text="(method.delivery_time || 'Standard') + (method.carrier_name ? ' • ' + method.carrier_name : '')"></p>
                                                </div>
                                                <div class="text-right ml-3 flex-shrink-0">
                                                    <p class="text-sm font-bold whitespace-nowrap" 
                                                       :class="method.base_rate > 0 ? 'text-gray-900' : 'text-green-600'" 
                                                       x-text="method.base_rate > 0 ? '৳' + method.base_rate : 'FREE'"></p>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>

                                <!-- No methods available -->
                                <div x-show="!loadingMethods && availableMethods.length === 0" class="text-center py-4 bg-gray-50 rounded-md border border-dashed border-gray-300">
                                    <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-xs text-gray-600 mt-1.5">No delivery methods available</p>
                                </div>

                                @error('delivery_method_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-gray-900" x-text="shippingCost > 0 ? '৳' + shippingCost.toFixed(2) : 'Select method'"></span>
                            </div>

                            <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                                <span>Total</span>
                                <span x-text="'৳' + ({{ $subtotal }} + shippingCost).toFixed(2)"></span>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="!selectedZone || !selectedMethod || isProcessing"
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
        shippingCost: 0,
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

        submitOrder() {
            this.isProcessing = true;
        }
    }
}
</script>
@endsection
