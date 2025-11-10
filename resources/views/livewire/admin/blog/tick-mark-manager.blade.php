<div class="inline-flex items-center gap-0.5">
    @if(!$post)
        <span class="text-xs text-gray-400">...</span>
    @else
    <!-- Active Tick Marks (Status Only - Not Clickable) -->
        @if($isVerified)
        <!-- Verified -->
        <span class="inline-flex items-center p-1 rounded bg-blue-500 text-white" title="Verified">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </span>
        @endif

        @if($isEditorChoice)
        <!-- Editor's Choice -->
        <span class="inline-flex items-center p-1 rounded bg-purple-500 text-white" title="Editor's Choice">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </span>
        @endif

        @if($isTrending)
        <!-- Trending -->
        <span class="inline-flex items-center p-1 rounded bg-red-500 text-white" title="Trending">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
            </svg>
        </span>
        @endif

        @if($isPremium)
        <!-- Premium -->
        <span class="inline-flex items-center p-1 rounded bg-yellow-500 text-white" title="Premium">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </span>
        @endif

        @if(!$isVerified && !$isEditorChoice && !$isTrending && !$isPremium)
        <span class="text-xs text-gray-400">-</span>
        @endif

    <!-- Verification Modal -->
    @if($showVerificationModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true" wire:click="closeVerificationModal"></div>
            
            <!-- Modal Content -->
            <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl max-w-lg w-full z-10">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-blue-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Verify Post
                            </h3>
                            <div class="mt-4">
                                <label for="verification-notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Verification Notes (Optional)
                                </label>
                                <textarea 
                                    wire:model="verificationNotes"
                                    id="verification-notes"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Add any notes about this verification..."
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        wire:click="saveVerification"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Verify Post
                    </button>
                    <button 
                        wire:click="closeVerificationModal"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Manage All Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl max-w-2xl w-full z-10">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">
                                Manage Tick Marks
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Verified -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            wire:model="isVerified"
                                            id="verified-checkbox" 
                                            type="checkbox" 
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        >
                                    </div>
                                    <div class="ml-3">
                                        <label for="verified-checkbox" class="font-medium text-gray-700">Verified</label>
                                        <p class="text-sm text-gray-500">Mark this post as verified and trustworthy</p>
                                        @if($post && $post->verified_at)
                                            <p class="text-xs text-gray-400 mt-1">
                                                Verified by {{ $post->verifier->name ?? 'Unknown' }} on {{ $post->verified_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Verification Notes -->
                                @if($isVerified)
                                <div class="ml-7">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Verification Notes
                                    </label>
                                    <textarea 
                                        wire:model="verificationNotes"
                                        id="notes"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                        placeholder="Add verification notes..."
                                    ></textarea>
                                </div>
                                @endif

                                <!-- Editor's Choice -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            wire:model="isEditorChoice"
                                            id="editor-choice-checkbox" 
                                            type="checkbox" 
                                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                        >
                                    </div>
                                    <div class="ml-3">
                                        <label for="editor-choice-checkbox" class="font-medium text-gray-700">Editor's Choice</label>
                                        <p class="text-sm text-gray-500">Feature this post as editor's choice</p>
                                    </div>
                                </div>

                                <!-- Trending -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            wire:model="isTrending"
                                            id="trending-checkbox" 
                                            type="checkbox" 
                                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                        >
                                    </div>
                                    <div class="ml-3">
                                        <label for="trending-checkbox" class="font-medium text-gray-700">Trending</label>
                                        <p class="text-sm text-gray-500">Mark this post as currently trending</p>
                                    </div>
                                </div>

                                <!-- Premium -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            wire:model="isPremium"
                                            id="premium-checkbox" 
                                            type="checkbox" 
                                            class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500"
                                        >
                                    </div>
                                    <div class="ml-3">
                                        <label for="premium-checkbox" class="font-medium text-gray-700">Premium</label>
                                        <p class="text-sm text-gray-500">Mark this post as premium content</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button 
                        wire:click="updateAllTickMarks"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm"
                    >
                        Save Changes
                    </button>
                    <button 
                        wire:click="clearAllTickMarks"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-red-700 bg-white border border-red-300 rounded-md shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm"
                    >
                        Clear All
                    </button>
                    <button 
                        wire:click="closeModal"
                        type="button" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
