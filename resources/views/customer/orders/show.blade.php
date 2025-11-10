@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders
            </a>
        </div>

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                        {{ ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('customer.orders.invoice', $order) }}" target="_blank"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Download Invoice
                    </a>
                </div>
            </div>

            <!-- Order Status Bar -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    @php
                        $statuses = ['pending', 'processing', 'confirmed', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $statuses);
                    @endphp
                    @foreach($statuses as $index => $status)
                        <div class="flex-1 text-center">
                            <div class="relative">
                                @if($index < count($statuses) - 1)
                                    <div class="absolute top-5 left-1/2 w-full h-0.5 {{ $index < $currentIndex ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                @endif
                                <div class="relative z-10 w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $index <= $currentIndex ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                    @if($index <= $currentIndex)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <span class="text-sm">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">{{ ucfirst($status) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->tracking_number)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Tracking Number:</span> {{ $order->tracking_number }}
                        @if($order->carrier)
                            <span class="ml-2">via {{ $order->carrier }}</span>
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $imageUrl = null;
                                            
                                            // Priority 1: Use stored product_image from order item
                                            if ($item->product_image) {
                                                $imageUrl = asset('storage/' . $item->product_image);
                                            }
                                            // Priority 2: Use variant image if available
                                            elseif ($item->variant && $item->variant->image) {
                                                $imageUrl = asset('storage/' . $item->variant->image);
                                            }
                                            // Priority 3: Use product's primary image
                                            elseif ($item->product && $item->product->images->where('is_primary', true)->first()) {
                                                $imageUrl = asset('storage/' . $item->product->images->where('is_primary', true)->first()->image_path);
                                            }
                                            // Priority 4: Use product's first image
                                            elseif ($item->product && $item->product->images->first()) {
                                                $imageUrl = asset('storage/' . $item->product->images->first()->image_path);
                                            }
                                        @endphp
                                        
                                        @if($imageUrl)
                                            <a href="{{ $item->product ? route('products.show', $item->product->slug) : '#' }}" 
                                               class="block">
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $item->product_name }}"
                                                     class="w-24 h-24 object-cover rounded-lg border border-gray-200 hover:border-blue-500 transition-colors"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>';">
                                            </a>
                                        @else
                                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Product Name with Link -->
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" 
                                               class="font-medium text-gray-900 hover:text-blue-600 transition-colors inline-block mb-1">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            <h3 class="font-medium text-gray-900 mb-1">{{ $item->product_name }}</h3>
                                        @endif
                                        
                                        <!-- Category and Brand -->
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            @if($item->product && $item->product->category)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    {{ $item->product->category->name }}
                                                </span>
                                            @endif
                                            
                                            @if($item->product && $item->product->brand)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-purple-50 text-purple-700 text-xs rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                    </svg>
                                                    {{ $item->product->brand->name }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- SKU -->
                                        @if($item->product_sku)
                                            <p class="text-xs text-gray-500 mb-1">
                                                <span class="font-medium">SKU:</span> 
                                                <span class="font-mono">{{ $item->product_sku }}</span>
                                            </p>
                                        @endif
                                        
                                        <!-- Variant Info -->
                                        @if($item->variant_name)
                                            <div class="mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                                    </svg>
                                                    {{ $item->variant_name }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Variant Attributes -->
                                        @if($item->variant_attributes && is_array($item->variant_attributes))
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @foreach($item->variant_attributes as $key => $value)
                                                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded border border-gray-200">
                                                        <span class="font-semibold">{{ ucfirst($key) }}:</span>
                                                        <span class="ml-1">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Quantity:</span> {{ $item->quantity }}
                                        </p>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">৳{{ number_format($item->total, 2) }}</p>
                                        <p class="text-sm text-gray-500">৳{{ number_format($item->price, 2) }} each</p>
                                        @if($item->discount_amount > 0)
                                            <p class="text-xs text-green-600 font-medium mt-1">
                                                Saved ৳{{ number_format($item->discount_amount, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Totals -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="text-gray-900">৳{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->shipping_cost > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Shipping</span>
                                        <span class="text-gray-900">৳{{ number_format($order->shipping_cost, 2) }}</span>
                                    </div>
                                @endif
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Discount</span>
                                        <span class="text-red-600">-৳{{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900">৳{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Shipping Address -->
                @if($order->shippingAddress)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                        <div class="text-sm text-gray-700">
                            <p class="font-medium">{{ $order->shippingAddress->full_name }}</p>
                            <p class="mt-1">{{ $order->shippingAddress->phone }}</p>
                            <p class="mt-2">{{ $order->shippingAddress->formatted_address }}</p>
                        </div>
                    </div>
                @endif

                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Status</p>
                            <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded-full bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($order->canBeCancelled())
                    <form id="cancel-customer-order-form" action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="button" 
                                onclick="window.dispatchEvent(new CustomEvent('confirm-modal', { 
                                    detail: { 
                                        title: 'Cancel Order', 
                                        message: 'Are you sure you want to cancel this order? This action cannot be undone.',
                                        onConfirm: () => document.getElementById('cancel-customer-order-form').submit()
                                    } 
                                }))"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Cancel Order
                        </button>
                    </form>
                @endif

                <!-- Need Help -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                    <p class="text-sm text-gray-600 mb-4">Contact our customer support for any questions about your order.</p>
                    <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
