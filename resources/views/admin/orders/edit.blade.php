@extends('layouts.admin')

@section('title', 'Edit Order - ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-1">Update order information</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Customer Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Name
                        </label>
                        <input type="text" name="customer_name" id="customer_name"
                               value="{{ old('customer_name', $order->customer_name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Email
                        </label>
                        <input type="email" name="customer_email" id="customer_email"
                               value="{{ old('customer_email', $order->customer_email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Phone
                        </label>
                        <input type="text" name="customer_phone" id="customer_phone"
                               value="{{ old('customer_phone', $order->customer_phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method
                        </label>
                        <select name="payment_method" id="payment_method"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="cod" {{ $order->payment_method === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="bkash" {{ $order->payment_method === 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ $order->payment_method === 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="rocket" {{ $order->payment_method === 'rocket' ? 'selected' : '' }}>Rocket</option>
                            <option value="card" {{ $order->payment_method === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="bank_transfer" {{ $order->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status
                        </label>
                        <select name="payment_status" id="payment_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('payment_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Tracking Number
                        </label>
                        <input type="text" name="tracking_number" id="tracking_number"
                               value="{{ old('tracking_number', $order->tracking_number) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter tracking number">
                        @error('tracking_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="carrier" class="block text-sm font-medium text-gray-700 mb-2">
                            Carrier
                        </label>
                        <input type="text" name="carrier" id="carrier"
                               value="{{ old('carrier', $order->carrier) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Pathao, Sundarban">
                        @error('carrier') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Shipping Cost
                        </label>
                        <input type="number" step="0.01" name="shipping_cost" id="shipping_cost"
                               value="{{ old('shipping_cost', $order->shipping_cost) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('shipping_cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Amount
                        </label>
                        <input type="number" step="0.01" name="discount_amount" id="discount_amount"
                               value="{{ old('discount_amount', $order->discount_amount) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('discount_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div>
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Admin Notes
                </label>
                <textarea name="admin_notes" id="admin_notes" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Internal notes (not visible to customer)">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                @error('admin_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.orders.show', $order) }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
