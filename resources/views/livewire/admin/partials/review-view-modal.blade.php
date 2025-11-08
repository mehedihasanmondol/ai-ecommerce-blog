{{-- View Review Modal --}}
@if($showViewModal && $selectedReview)
<div class="fixed inset-0 z-50 overflow-y-auto" 
     x-data="{ show: @entangle('showViewModal') }"
     x-show="show"
     x-cloak
     style="display: none;">
    
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 transition-all duration-300" 
         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
         @click="$wire.closeViewModal()"></div>
    
    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative rounded-lg shadow-2xl max-w-3xl w-full border border-gray-200 max-h-[90vh] overflow-y-auto"
             style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90">
            
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Review Details</h3>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $selectedReview->rating)
                                <i class="fas fa-star text-amber-400"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-sm font-semibold text-gray-700">{{ $selectedReview->rating }}/5</span>
                    </div>
                </div>
                <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- Body --}}
            <div class="px-6 py-4">

            {{-- Product Info --}}
            @if($selectedReview->product)
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Product</h4>
                <a href="{{ route('products.show', $selectedReview->product->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium">
                    {{ $selectedReview->product->name }}
                </a>
            </div>
            @endif

            {{-- Reviewer Info --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Reviewer Information</h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Name:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $selectedReview->user ? $selectedReview->user->name : ($selectedReview->reviewer_name ?? 'Guest') }}
                        </span>
                        @if($selectedReview->user)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1"></i> Registered
                            </span>
                        @endif
                    </div>
                    @if($selectedReview->reviewer_email)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="text-sm text-gray-900">{{ $selectedReview->reviewer_email }}</span>
                    </div>
                    @endif
                    @if($selectedReview->is_verified_purchase)
                    <div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Verified Purchase
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Review Content --}}
            <div class="mb-6">
                @if($selectedReview->title)
                <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $selectedReview->title }}</h4>
                @endif
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $selectedReview->review }}</p>
            </div>

            {{-- Review Images --}}
            @if($selectedReview->images && count($selectedReview->images) > 0)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Review Images</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($selectedReview->images as $image)
                    <div class="aspect-square rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $image) }}" alt="Review Image" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Helpful Votes --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Helpfulness</h4>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-thumbs-up text-green-600"></i>
                        <span class="text-sm text-gray-900">{{ $selectedReview->helpful_count }} helpful</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-thumbs-down text-red-600"></i>
                        <span class="text-sm text-gray-900">{{ $selectedReview->not_helpful_count }} not helpful</span>
                    </div>
                </div>
            </div>

            {{-- Status & Dates --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Status & Timeline</h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Status:</span>
                        @if($selectedReview->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                        @elseif($selectedReview->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Submitted:</span>
                        <span class="text-sm text-gray-900">{{ $selectedReview->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($selectedReview->approved_at)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Approved:</span>
                        <span class="text-sm text-gray-900">{{ $selectedReview->approved_at->format('M d, Y h:i A') }}</span>
                        @if($selectedReview->approver)
                            <span class="text-sm text-gray-600">by {{ $selectedReview->approver->name }}</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            </div>
            
            {{-- Footer Actions --}}
            @if($selectedReview->status == 'pending')
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex gap-3">
                    <button wire:click="approve({{ $selectedReview->id }})" 
                            wire:loading.attr="disabled"
                            wire:target="approve"
                            class="flex-1 px-4 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="approve">
                            <i class="fas fa-check mr-2"></i>Approve Review
                        </span>
                        <span wire:loading wire:target="approve" class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Approving...
                        </span>
                    </button>
                    <button wire:click="reject({{ $selectedReview->id }})" 
                            wire:loading.attr="disabled"
                            wire:target="reject"
                            class="flex-1 px-4 py-2 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="reject">
                            <i class="fas fa-times mr-2"></i>Reject Review
                        </span>
                        <span wire:loading wire:target="reject" class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Rejecting...
                        </span>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
