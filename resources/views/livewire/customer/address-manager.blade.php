<div>
    <!-- Add Address Button -->
    <div class="mb-6 flex justify-end">
        <button wire:click="openModal" 
                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Address
        </button>
    </div>

    <!-- Addresses Grid -->
    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $address)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow relative">
                    <!-- Default Badge -->
                    @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Default
                            </span>
                        </div>
                    @endif

                    <div class="p-6">
                        <!-- Address Label -->
                        <div class="flex items-start mb-4">
                            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $address->label }}</h3>
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div class="space-y-2 mb-4">
                            <p class="text-sm text-gray-700">{{ $address->address_line1 }}</p>
                            @if($address->address_line2)
                                <p class="text-sm text-gray-700">{{ $address->address_line2 }}</p>
                            @endif
                            <p class="text-sm text-gray-700">
                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                            </p>
                            <p class="text-sm text-gray-700">{{ $address->country }}</p>
                            <p class="text-sm text-gray-600 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $address->phone }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2 pt-4 border-t border-gray-200">
                            @if(!$address->is_default)
                                <button wire:click="setAsDefault({{ $address->id }})" 
                                        class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    Set Default
                                </button>
                            @endif
                            <button wire:click="edit({{ $address->id }})" 
                                    class="flex-1 px-3 py-2 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                                Edit
                            </button>
                            <button wire:click="delete({{ $address->id }})" 
                                    onclick="return confirm('Are you sure you want to delete this address?')"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Addresses Added</h3>
            <p class="text-gray-600 mb-6">Add your first address to make checkout faster</p>
            <button wire:click="openModal" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Address
            </button>
        </div>
    @endif

    <!-- Address Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
                 wire:click="closeModal"></div>

            <!-- Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Modal panel -->
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full z-[10000]">
                    <div class="bg-white">
                        <!-- Modal Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $editMode ? 'Edit Address' : 'Add New Address' }}
                                </h3>
                                <button wire:click="closeModal" 
                                        class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                            <form wire:submit.prevent="save" class="space-y-4">
                                <!-- Label -->
                                <div>
                                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">
                                        Address Label <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           wire:model="label" 
                                           id="label" 
                                           placeholder="e.g., Home, Office, Apartment"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('label') border-red-500 @enderror">
                                    @error('label')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address Line 1 -->
                                <div>
                                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                                        Address Line 1 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           wire:model="address_line1" 
                                           id="address_line1" 
                                           placeholder="House/Flat No, Street Name"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address_line1') border-red-500 @enderror">
                                    @error('address_line1')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address Line 2 -->
                                <div>
                                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                                        Address Line 2 (Optional)
                                    </label>
                                    <input type="text" 
                                           wire:model="address_line2" 
                                           id="address_line2" 
                                           placeholder="Apartment, Building, Floor"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- City -->
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               wire:model="city" 
                                               id="city" 
                                               placeholder="Dhaka"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                                        @error('city')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- State -->
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                                            State/Division <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               wire:model="state" 
                                               id="state" 
                                               placeholder="Dhaka Division"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('state') border-red-500 @enderror">
                                        @error('state')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Postal Code -->
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                            Postal Code <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               wire:model="postal_code" 
                                               id="postal_code" 
                                               placeholder="1200"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                                        @error('postal_code')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Country -->
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                            Country <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               wire:model="country" 
                                               id="country" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror">
                                        @error('country')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           wire:model="phone" 
                                           id="phone" 
                                           placeholder="+880 1XX-XXX-XXXX"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Set as Default -->
                                <div>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               wire:model="is_default" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Set as default address</span>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                            <button wire:click="closeModal" 
                                    type="button"
                                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button wire:click="save" 
                                    type="button"
                                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                {{ $editMode ? 'Update Address' : 'Add Address' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
