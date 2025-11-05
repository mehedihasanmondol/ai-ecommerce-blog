@extends('layouts.admin')

@section('title', 'Create Order')

@section('content')
<div class="space-y-4" x-data="orderForm()" x-cloak>
    <!-- Sticky Header with Live Summary & Actions -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg sticky top-16 z-20">
        <div class="px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Left: Title & Back -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="p-2 hover:bg-white/20 rounded-lg transition-colors text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="text-white">
                        <h1 class="text-lg font-bold">Create New Order</h1>
                        <p class="text-sm text-blue-100">Quick order creation</p>
                    </div>
                </div>
                
                <!-- Center: Live Order Summary -->
                <div class="flex items-center space-x-6 text-white">
                    <div class="text-center px-4 border-r border-white/20">
                        <p class="text-xs text-blue-100">Items</p>
                        <p class="text-2xl font-bold" x-text="items.length">0</p>
                    </div>
                    <div class="text-center px-4 border-r border-white/20">
                        <p class="text-xs text-blue-100">Subtotal</p>
                        <p class="text-2xl font-bold" x-text="'৳' + calculateSubtotal().toFixed(2)">৳0.00</p>
                    </div>
                    <div class="text-center px-4">
                        <p class="text-xs text-blue-100">Total</p>
                        <p class="text-3xl font-bold" x-text="'৳' + calculateTotal().toFixed(2)">৳0.00</p>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.index') }}"
                       class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/30 rounded-lg transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" form="order-form"
                            class="px-6 py-2.5 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors shadow-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="order-form" action="{{ route('admin.orders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left Column: Order Items & Customer -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Order Items Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Order Items
                            </h3>
                            <button type="button" @click="addItem()" 
                                    class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </div>

                    <div class="p-4 space-y-3">
                        <!-- Column Headers (First Item Only) -->
                        <div class="grid grid-cols-12 gap-3 px-3 pb-2 border-b border-gray-200" x-show="items.length > 0">
                            <div class="col-span-5">
                                <label class="text-xs font-semibold text-gray-600 uppercase">Product <span class="text-red-500">*</span></label>
                            </div>
                            <div class="col-span-2 text-center">
                                <label class="text-xs font-semibold text-gray-600 uppercase">Quantity <span class="text-red-500">*</span></label>
                            </div>
                            <div class="col-span-2 text-center">
                                <label class="text-xs font-semibold text-gray-600 uppercase">Price <span class="text-red-500">*</span></label>
                            </div>
                            <div class="col-span-2 text-center">
                                <label class="text-xs font-semibold text-gray-600 uppercase">Subtotal</label>
                            </div>
                            <div class="col-span-1 text-center">
                                <label class="text-xs font-semibold text-gray-600 uppercase">Action</label>
                            </div>
                        </div>

                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-blue-300 transition-colors">
                                <div class="grid grid-cols-12 gap-3 items-center">
                                    <!-- Product Select -->
                                    <div class="col-span-5">
                                        <select :name="'items['+index+'][product_id]'" 
                                                @change="updateItemPrice($event, index)"
                                                required
                                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Select Product</option>
                                            @php
                                                $products = \App\Modules\Ecommerce\Product\Models\Product::orderBy('name')->get();
                                            @endphp
                                            @forelse($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}">
                                                    {{ $product->name }} - ৳{{ number_format($product->price ?? 0, 2) }}
                                                    @if($product->status !== 'active')
                                                        <span class="text-gray-500">({{ ucfirst($product->status ?? 'inactive') }})</span>
                                                    @endif
                                                </option>
                                            @empty
                                                <option value="" disabled>No products available - Please add products first</option>
                                            @endforelse
                                        </select>
                                        @if($products->isEmpty())
                                            <p class="text-xs text-red-500 mt-1">
                                                <a href="{{ route('admin.products.create') }}" class="underline">Add products</a> before creating orders
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-span-2">
                                        <input type="number" :name="'items['+index+'][quantity]'" 
                                               x-model.number="item.quantity" 
                                               min="1" required
                                               placeholder="Qty"
                                               class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-center">
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-2">
                                        <input type="number" :name="'items['+index+'][price]'" 
                                               x-model.number="item.price" 
                                               step="0.01" min="0" required
                                               placeholder="0.00"
                                               class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-center">
                                    </div>

                                    <!-- Subtotal Display -->
                                    <div class="col-span-2 text-center">
                                        <div class="bg-blue-50 px-3 py-2 rounded-lg">
                                            <span class="text-sm font-bold text-blue-600" 
                                                  x-text="'৳' + (item.quantity * item.price).toFixed(2)">৳0.00</span>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-span-1 text-center">
                                        <button type="button" @click="removeItem(index)" 
                                                x-show="items.length > 1"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Remove Item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <p class="text-sm">No items added yet. Click "Add Item" to start.</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Customer Details
                        </h3>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <!-- Quick Customer Select -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Quick Select Customer (Optional)</label>
                            <select name="user_id" 
                                    @change="fillCustomerData($event)"
                                    class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">-- New Customer --</option>
                                @php
                                    $users = \App\Models\User::orderBy('name')->limit(100)->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            data-name="{{ $user->name }}" 
                                            data-email="{{ $user->email }}" 
                                            data-phone="{{ $user->phone ?? '' }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @if($users->isEmpty())
                                <p class="text-xs text-gray-500 mt-1">No existing customers - Enter new customer details below</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" required x-model="customer.name"
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_phone" required x-model="customer.phone"
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="customer_email" required x-model="customer.email"
                                   class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Customer Notes</label>
                            <textarea name="customer_notes" rows="2"
                                      class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Address Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Shipping Address
                            </h3>
                            <label class="flex items-center text-sm">
                                <input type="checkbox" name="same_as_billing" value="1" checked
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-2">
                                <span class="text-gray-700">Same as billing</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_first_name" required
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_last_name" required
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_phone" required
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="billing_email"
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                            <input type="text" name="billing_address_line_1" required
                                   class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_city" required value="Dhaka"
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_postal_code" required
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                                <input type="text" name="billing_country" required value="Bangladesh"
                                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary & Payment -->
            <div class="space-y-4">
                
                <!-- Order Summary Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Order Summary
                        </h3>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <!-- Subtotal -->
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold" x-text="'৳' + calculateSubtotal().toFixed(2)">৳0.00</span>
                        </div>

                        <!-- Shipping -->
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <input type="number" name="shipping_cost" x-model.number="shipping" step="0.01" min="0" required
                                   class="w-24 text-right text-sm px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Discount -->
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Discount</span>
                            <input type="number" name="discount_amount" x-model.number="discount" step="0.01" min="0"
                                   class="w-24 text-right text-sm px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="'৳' + calculateTotal().toFixed(2)">৳0.00</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="pt-3 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                            <select name="payment_method" required
                                    class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="cod">Cash on Delivery</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Status <span class="text-red-500">*</span></label>
                            <select name="payment_status" required
                                    class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <!-- Admin Notes -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="2"
                                      class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="Internal notes..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function orderForm() {
    return {
        items: [{ product_id: '', quantity: 1, price: 0 }],
        shipping: 60,
        discount: 0,
        customer: {
            name: '',
            email: '',
            phone: ''
        },
        
        addItem() {
            this.items.push({ product_id: '', quantity: 1, price: 0 });
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        
        updateItemPrice(event, index) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            this.items[index].price = price;
        },
        
        fillCustomerData(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            if (selectedOption.value) {
                this.customer.name = selectedOption.dataset.name || '';
                this.customer.email = selectedOption.dataset.email || '';
                this.customer.phone = selectedOption.dataset.phone || '';
            }
        },
        
        calculateSubtotal() {
            return this.items.reduce((sum, item) => {
                return sum + (item.quantity * item.price);
            }, 0);
        },
        
        calculateTotal() {
            return this.calculateSubtotal() + this.shipping - this.discount;
        }
    }
}
</script>
@endpush
@endsection
