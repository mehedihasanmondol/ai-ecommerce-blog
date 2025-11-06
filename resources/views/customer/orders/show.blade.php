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
                                        @if($item->variant_name)
                                            <p class="text-sm text-gray-600">{{ $item->variant_name }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">৳{{ number_format($item->total, 2) }}</p>
                                        <p class="text-sm text-gray-500">৳{{ number_format($item->price, 2) }} each</p>
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
