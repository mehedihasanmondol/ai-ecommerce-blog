<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Order Status</h3>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="updateStatus" class="space-y-4">
        <!-- Status Selection -->
        <div>
            <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">
                New Status <span class="text-red-500">*</span>
            </label>
            <select wire:model="newStatus" id="newStatus" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Select Status</option>
                @foreach($availableStatuses as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @error('newStatus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Tracking Information (show when status is shipped) -->
        @if($newStatus === 'shipped')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="trackingNumber" class="block text-sm font-medium text-gray-700 mb-2">
                        Tracking Number
                    </label>
                    <input type="text" wire:model="trackingNumber" id="trackingNumber"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter tracking number">
                    @error('trackingNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="carrier" class="block text-sm font-medium text-gray-700 mb-2">
                        Carrier
                    </label>
                    <input type="text" wire:model="carrier" id="carrier"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Pathao, Sundarban">
                    @error('carrier') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        <!-- Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Notes (Optional)
            </label>
            <textarea wire:model="notes" id="notes" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Add any notes about this status change..."></textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Notify Customer -->
        <div class="flex items-center">
            <input type="checkbox" wire:model="notifyCustomer" id="notifyCustomer"
                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="notifyCustomer" class="ml-2 text-sm text-gray-700">
                Notify customer via SMS/Email
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Update Status</span>
                <span wire:loading>Updating...</span>
            </button>
        </div>
    </form>
</div>
