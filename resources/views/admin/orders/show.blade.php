@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Success/Error Alerts -->
    @if (session()->has('success'))
        <div id="success-alert" class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <button onclick="this.closest('#success-alert').remove()" 
                            class="inline-flex text-green-500 hover:text-green-700 focus:outline-none transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div id="error-alert" class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <button onclick="this.closest('#error-alert').remove()" 
                            class="inline-flex text-red-500 hover:text-red-700 focus:outline-none transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Print Invoice
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Edit Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 last:border-0">
                                @if($item->product_image)
                                    <img src="{{ Storage::url($item->product_image) }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                                    @if($item->product_sku)
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                    @endif
                                    @if($item->variant_name)
                                        <p class="text-sm text-gray-600">{{ $item->variant_name }}</p>
                                    @endif
                                    {{-- Variant attributes hidden as per user request --}}
                                    {{-- @if($item->formatted_variant_attributes)
                                        <p class="text-sm text-gray-500">{{ $item->formatted_variant_attributes }}</p>
                                    @endif --}}
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">Quantity</p>
                                    <p class="font-medium text-gray-900">{{ $item->quantity }}</p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Price</p>
                                    <p class="font-medium text-gray-900">৳{{ number_format($item->price, 2) }}</p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Total</p>
                                    <p class="font-medium text-gray-900">৳{{ number_format($item->total, 2) }}</p>
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
                            @if($order->tax_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="text-gray-900">৳{{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                            @endif
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

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status History</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->statusHistories as $history)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">{{ $history->description }}</p>
                                        <span class="text-sm text-gray-500">{{ $history->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($history->notes)
                                        <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                                    @endif
                                    @if($history->user)
                                        <p class="text-xs text-gray-500 mt-1">By {{ $history->user->name }}</p>
                                    @endif
                                    @if($history->customer_notified)
                                        <p class="text-xs text-green-600 mt-1">✓ Customer notified</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-sm p-6 relative overflow-visible">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                
                <!-- Status Update Section -->
                <div class="mb-6">
                    @livewire('order.order-status-updater', ['order' => $order, 'availableStatuses' => $availableStatuses])
                </div>

                <!-- Additional Status Information -->
                <div class="space-y-3 pt-6 border-t border-gray-200">
                    <div>
                        <p class="text-sm text-gray-600">Payment Status</p>
                        <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded-full bg-{{ $order->payment_status_color }}-100 text-{{ $order->payment_status_color }}-800">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Payment Method</p>
                        <p class="font-medium mt-1">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="mb-2">
                            <p class="text-sm text-gray-600">Shipping Carrier</p>
                            @if($order->carrier)
                                <p class="font-medium mt-1 text-green-600">✓ {{ $order->carrier }}</p>
                            @else
                                <p class="font-medium mt-1 text-orange-600">⚠ Not Assigned</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tracking Number</p>
                            @if($order->tracking_number)
                                <p class="font-medium mt-1 font-mono text-blue-600">{{ $order->tracking_number }}</p>
                            @else
                                <p class="font-medium mt-1 text-gray-400">Not Available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium">{{ $order->customer_phone }}</p>
                    </div>
                    @if($order->customer_notes)
                        <div>
                            <p class="text-sm text-gray-600">Customer Notes</p>
                            <p class="text-sm text-gray-700 mt-1">{{ $order->customer_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

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

            <!-- Billing Address -->
            @if($order->billingAddress)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h3>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium">{{ $order->billingAddress->full_name }}</p>
                        <p class="mt-1">{{ $order->billingAddress->phone }}</p>
                        <p class="mt-2">{{ $order->billingAddress->formatted_address }}</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($order->canBeCancelled())
                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
