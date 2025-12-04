@extends('layouts.admin')

@section('title', 'Create Attribute')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Sticky Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.attributes.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Attributes
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Create Attribute</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.attributes.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" form="attribute-form"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Create Attribute
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.attributes.store') }}" method="POST" id="attribute-form" x-data="attributeForm()">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Attribute Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Attribute Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., Color, Size, Material"
                           required>
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (Optional)
                    </label>
                    <input type="text" 
                           name="slug" 
                           value="{{ old('slug') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                           placeholder="auto-generated">
                    @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" 
                            x-model="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                            required>
                        <option value="select">Dropdown Select</option>
                        <option value="color">Color Picker</option>
                        <option value="button">Button/Badge</option>
                    </select>
                    @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-6 pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_visible" 
                               value="1"
                               {{ old('is_visible') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Visible on product page</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_variation" 
                               value="1"
                               {{ old('is_variation', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Used for variations</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Attribute Values</h2>
                <button type="button" 
                        @click="addValue()"
                        class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus mr-1"></i> Add Value
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(value, index) in values" :key="index">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text" 
                                   :name="'values[' + index + '][value]'" 
                                   x-model="value.value"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Value name"
                                   required>
                        </div>
                        
                        <div x-show="type === 'color'" class="w-32">
                            <input type="color" 
                                   :name="'values[' + index + '][color_code]'" 
                                   x-model="value.color_code"
                                   class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer">
                        </div>

                        <button type="button" 
                                @click="removeValue(index)"
                                x-show="values.length > 1"
                                class="text-red-600 hover:text-red-800 px-3 py-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>
            </div>

            @error('values')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-4 mt-6">
            <a href="{{ route('admin.attributes.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i> Create Attribute
            </button>
        </div>
    </form>
</div>

<script>
function attributeForm() {
    return {
        type: 'select',
        values: [
            { value: '', color_code: '#000000' }
        ],
        addValue() {
            this.values.push({ value: '', color_code: '#000000' });
        },
        removeValue(index) {
            this.values.splice(index, 1);
        }
    }
}
</script>
@endsection
