<div class="space-y-6">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Product Variations</h3>
            <p class="text-sm text-gray-600 mt-1">Generate variations based on attributes like size, color, etc.</p>
        </div>
        @if(!$showGenerator && count($generatedVariations) == 0)
        <button wire:click="toggleGenerator" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            + Generate Variations
        </button>
        @endif
    </div>

    {{-- Variation Generator --}}
    @if($showGenerator || count($variations) > 0)
    <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-200 pb-4">
            <h4 class="font-semibold text-gray-900">Variation Generator</h4>
            @if($showGenerator)
            <button wire:click="toggleGenerator" 
                    class="text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </button>
            @endif
        </div>

        {{-- Step 1: Select Attributes --}}
        @if(count($variations) == 0)
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold text-gray-700">Select Attributes</label>
                <button wire:click="addAttribute" 
                        class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    + Add Attribute
                </button>
            </div>

            @foreach($selectedAttributes as $index => $selected)
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex-1 space-y-3">
                    {{-- Attribute Selection --}}
                    <div>
                        <select wire:model.live="selectedAttributes.{{ $index }}.attribute_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Attribute</option>
                            @foreach($attributes as $attribute)
                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Values Selection --}}
                    @if(!empty($selectedAttributes[$index]['attribute_id']))
                    <div>
                        <label class="block text-xs text-gray-600 mb-2">Select Values:</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $selectedAttr = $attributes->find($selectedAttributes[$index]['attribute_id']);
                            @endphp
                            @if($selectedAttr)
                                @foreach($selectedAttr->values as $value)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           wire:model="selectedAttributes.{{ $index }}.value_ids" 
                                           value="{{ $value->id }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $value->value }}</span>
                                </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <button wire:click="removeAttribute({{ $index }})" 
                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endforeach

            @if(count($selectedAttributes) == 0)
            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <p class="text-gray-600">Click "Add Attribute" to start creating variations</p>
            </div>
            @endif

            {{-- Generate Button --}}
            @if(count($selectedAttributes) > 0)
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button wire:click="generateVariations" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Generate Variations
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- Step 2: Edit Generated Variations --}}
        @if(count($variations) > 0)
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h5 class="font-medium text-gray-900">Generated Variations ({{ count($variations) }})</h5>
                <button wire:click="$set('variations', [])" 
                        class="text-sm text-gray-600 hover:text-gray-800">
                    Start Over
                </button>
            </div>

            <div class="space-y-3">
                @foreach($variations as $index => $variation)
                <div class="border border-gray-200 rounded-lg p-4 {{ !$variation['enabled'] ? 'opacity-50' : '' }}">
                    <div class="flex items-start gap-4">
                        <input type="checkbox" 
                               wire:model="variations.{{ $index }}.enabled" 
                               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        
                        <div class="flex-1 grid grid-cols-5 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">Variation Name</label>
                                <input type="text" 
                                       wire:model="variations.{{ $index }}.name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                       placeholder="Variation name">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">SKU</label>
                                <input type="text" 
                                       wire:model="variations.{{ $index }}.sku" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                       placeholder="Auto">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Price ($)</label>
                                <input type="number" 
                                       wire:model="variations.{{ $index }}.price" 
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                       placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Stock</label>
                                <input type="number" 
                                       wire:model="variations.{{ $index }}.stock_quantity" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Save Variations --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button wire:click="$set('variations', [])" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="saveVariations" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Save Variations
                </button>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Existing Variations List --}}
    @if(count($generatedVariations) > 0)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900">Existing Variations ({{ count($generatedVariations) }})</h4>
            <button wire:click="toggleGenerator" 
                    class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                + Add More
            </button>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach($generatedVariations as $variation)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">{{ $variation['name'] }}</h5>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                            <span>SKU: <span class="font-mono">{{ $variation['sku'] }}</span></span>
                            <span>Price: <span class="font-semibold">${{ number_format($variation['price'], 2) }}</span></span>
                            <span>Stock: <span class="font-semibold">{{ $variation['stock_quantity'] }}</span></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button wire:click="deleteVariation({{ $variation['id'] }})" 
                                wire:confirm="Are you sure you want to delete this variation?"
                                class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Empty State --}}
    @if(!$showGenerator && count($variations) == 0 && count($generatedVariations) == 0)
    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Variations Yet</h3>
        <p class="text-gray-600 mb-4">Create product variations based on attributes like size, color, material, etc.</p>
        <button wire:click="toggleGenerator" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Generate Variations
        </button>
    </div>
    @endif
</div>
