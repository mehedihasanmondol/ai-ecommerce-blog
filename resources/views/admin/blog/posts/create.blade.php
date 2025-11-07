@extends('layouts.admin')

@section('title', 'Add New Post')

@push('styles')
<style>
/* TinyMCE Custom Styling */
.tox-tinymce {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
}
.tox .tox-toolbar {
    background: #f8f9fa !important;
}
.char-counter {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    font-size: 0.75rem;
    color: #64748b;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- WordPress-style Top Bar -->
    <div class="bg-white border-b border-gray-200 -mx-4 -mt-6 px-4 py-3 mb-6 sticky top-16 z-10 shadow-sm">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.blog.posts.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Posts
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-xl font-semibold text-gray-900">Add New Post</h1>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="previewPost()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Preview
                </button>
                <button type="button" onclick="saveDraft()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Save Draft
                </button>
                <button type="submit" form="post-form"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Publish
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">

        <form id="post-form" action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Auto-save indicator -->
            <div id="autosave-indicator" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Draft saved
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title - WordPress Style -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 pb-0">
                            <input type="text" 
                                   name="title" 
                                   id="post-title"
                                   value="{{ old('title') }}" 
                                   required
                                   class="w-full text-3xl font-bold border-none focus:outline-none focus:ring-0 placeholder-gray-400"
                                   placeholder="Add title"
                                   autocomplete="off">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Permalink -->
                        <div class="px-6 py-3 border-t border-gray-100">
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 mr-2">Permalink:</span>
                                <span class="text-blue-600">{{ url('/') }}/</span>
                                <input type="text" 
                                       name="slug" 
                                       id="post-slug"
                                       value="{{ old('slug') }}"
                                       class="border-none focus:outline-none focus:ring-0 text-blue-600 px-1 py-0 min-w-[200px]"
                                       placeholder="auto-generated">
                                <button type="button" 
                                        onclick="editSlug()" 
                                        class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Content Editor - TinyMCE -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Content *</label>
                        <textarea name="content" 
                                  id="tinymce-editor" 
                                  class="tinymce-content">{{ old('content') }}</textarea>
                        
                        <!-- Word Counter -->
                        <div class="char-counter" id="editor-stats">
                            <span id="word-count">0</span> words | 
                            <span id="char-count">0</span> characters
                        </div>
                        
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt - Collapsible -->
                    <div class="bg-white rounded-lg shadow" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50">
                            <span class="font-medium text-gray-900">Excerpt</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform" 
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition
                             class="px-6 pb-6 border-t border-gray-100">
                            <textarea name="excerpt" 
                                      rows="3"
                                      class="w-full mt-4 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="Write a short excerpt or leave empty to auto-generate...">{{ old('excerpt') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Excerpts are optional hand-crafted summaries of your content.</p>
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('meta_description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="keyword1, keyword2, keyword3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publish -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                            </div>

                            <div id="scheduled-date" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Date</label>
                                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-700">Featured Post</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-700">Allow Comments</label>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Categories -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Blog Categories</h3>
                            <button type="button" 
                                    onclick="openCategoryModal()"
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New
                            </button>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">Select one or more categories</p>

                        <!-- Category Checkboxes -->
                        <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3" id="category-list">
                            @forelse($categories as $category)
                                <label class="flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No categories available. Click "Add New" to create one.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                            <button type="button" 
                                    onclick="openTagModal()"
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New
                            </button>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">Select one or more tags</p>

                        <!-- Tag Checkboxes -->
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3" id="tag-list">
                            @forelse($tags as $tag)
                                <label class="flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer">
                                    <input type="checkbox" 
                                           name="tags[]" 
                                           value="{{ $tag->id }}"
                                           {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No tags available. Click "Add New" to create one.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>
                        
                        <!-- Image Preview -->
                        <div id="image-preview-container" class="hidden mb-4">
                            <div class="relative inline-block">
                                <img id="image-preview" src="" alt="Preview" 
                                     class="w-full max-w-md h-auto rounded-lg border-2 border-gray-200 shadow-sm">
                                <button type="button" 
                                        onclick="removeImagePreview()"
                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <input type="file" 
                               name="featured_image" 
                               id="featured-image-input"
                               accept="image/*"
                               onchange="previewFeaturedImage(event)"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-2 text-xs text-gray-500">Max size: 2MB (JPG, PNG, WebP)</p>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                            <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Image description for SEO">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-150">
                            Create Post
                        </button>
                        <a href="{{ route('admin.blog.posts.index') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg mt-2 transition duration-150">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add New Category Modal - Modern Style -->
<div id="categoryModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 9999;" x-data="{ show: false }">
    {{-- Background overlay with blur --}}
    <div class="fixed inset-0 transition-opacity" 
         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
         onclick="closeCategoryModal()"></div>
    
    {{-- Modal container --}}
    <div class="flex items-center justify-center min-h-screen p-4">
        {{-- Modal panel --}}
        <div class="relative rounded-lg shadow-xl max-w-lg w-full border border-gray-200"
             style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
            <div class="bg-white px-6 pt-5 pb-4 rounded-t-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Category</h3>
                    <button type="button" 
                            onclick="closeCategoryModal()"
                            class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                        <input type="text" 
                               id="modal-category-name"
                               onkeydown="if(event.key === 'Enter') { event.preventDefault(); createCategoryFromModal(); } else if(event.key === 'Escape') { closeCategoryModal(); }"
                               autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter category name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="modal-category-description"
                                  rows="3"
                                  onkeydown="if(event.key === 'Escape') { closeCategoryModal(); }"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Brief description (optional)"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-3 flex items-center justify-end gap-3 rounded-b-lg">
                <button type="button" 
                        onclick="closeCategoryModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" 
                        onclick="createCategoryFromModal()"
                        id="create-category-btn"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
                    <span id="create-btn-text">Create Category</span>
                    <span id="create-btn-loading" class="hidden">Creating...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add New Tag Modal - Modern Style -->
<div id="tagModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 9999;" x-data="{ show: false }">
    {{-- Background overlay with blur --}}
    <div class="fixed inset-0 transition-opacity" 
         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
         onclick="closeTagModal()"></div>
    
    {{-- Modal container --}}
    <div class="flex items-center justify-center min-h-screen p-4">
        {{-- Modal panel --}}
        <div class="relative rounded-lg shadow-xl max-w-lg w-full border border-gray-200"
             style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
            <div class="bg-white px-6 pt-5 pb-4 rounded-t-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Tag</h3>
                    <button type="button" 
                            onclick="closeTagModal()"
                            class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tag Name *</label>
                        <input type="text" 
                               id="modal-tag-name"
                               onkeydown="if(event.key === 'Enter') { event.preventDefault(); createTagFromModal(); } else if(event.key === 'Escape') { closeTagModal(); }"
                               autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Enter tag name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="modal-tag-description"
                                  rows="3"
                                  onkeydown="if(event.key === 'Escape') { closeTagModal(); }"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Brief description (optional)"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-3 flex items-center justify-end gap-3 rounded-b-lg">
                <button type="button" 
                        onclick="closeTagModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" 
                        onclick="createTagFromModal()"
                        id="create-tag-btn"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors">
                    <span id="create-tag-btn-text">Create Tag</span>
                    <span id="create-tag-btn-loading" class="hidden">Creating...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- TinyMCE CDN with API Key -->
<script src="https://cdn.tiny.cloud/1/8wacbe3zs5mntet5c9u50n4tenlqvgqm9bn1k6uctyqo3o7m/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Initialize TinyMCE
tinymce.init({
    selector: '#tinymce-editor',
    height: 500,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
        'emoticons', 'codesample', 'quickbars'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic underline strikethrough | forecolor backcolor | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | ' +
        'link image media table | ' +
        'codesample code | ' +
        'removeformat | help | fullscreen',
    toolbar_mode: 'sliding',
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
    contextmenu: 'link image table',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; font-size: 16px; line-height: 1.6; }',
    
    // Image upload settings
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.blog.upload-image') }}');
            
            // Add CSRF token
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            
            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };
            
            xhr.onload = function() {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                
                try {
                    const json = JSON.parse(xhr.responseText);
                    
                    if (!json || typeof json.location !== 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    
                    resolve(json.location);
                } catch (e) {
                    reject('Invalid response: ' + xhr.responseText);
                }
            };
            
            xhr.onerror = function () {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };
            
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            
            xhr.send(formData);
        });
    },
    automatic_uploads: true,
    images_reuse_filename: true,
    
    // Advanced features
    paste_data_images: true,
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
    
    // Content filtering
    valid_elements: '*[*]',
    extended_valid_elements: '*[*]',
    
    // Setup callback
    setup: function(editor) {
        editor.on('init', function() {
            updateWordCount();
        });
        
        editor.on('keyup change', function() {
            updateWordCount();
            autoSave();
        });
    }
});

// WordPress-style Editor Functions
const titleInput = document.getElementById('post-title');
const slugInput = document.getElementById('post-slug');
const wordCountEl = document.getElementById('word-count');
const charCountEl = document.getElementById('char-count');

// Auto-generate slug from title
titleInput.addEventListener('input', function() {
    if (!slugInput.dataset.manualEdit) {
        const slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        slugInput.value = slug;
    }
});

// Mark slug as manually edited
function editSlug() {
    slugInput.dataset.manualEdit = 'true';
    slugInput.focus();
    slugInput.select();
}

// Word and character counter for TinyMCE
function updateWordCount() {
    const editor = tinymce.get('tinymce-editor');
    if (editor) {
        const content = editor.getContent({format: 'text'});
        const words = content.trim().split(/\s+/).filter(w => w.length > 0).length;
        const chars = editor.getContent().length;
        wordCountEl.textContent = words;
        charCountEl.textContent = chars;
    }
}


// Auto-save draft
let autoSaveTimer;
function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        saveDraft(true);
    }, 30000); // 30 seconds
}

titleInput.addEventListener('input', autoSave);

// Save draft function
function saveDraft(isAutoSave = false) {
    const formData = new FormData(document.getElementById('post-form'));
    formData.set('status', 'draft');
    
    // Show indicator
    const indicator = document.getElementById('autosave-indicator');
    indicator.classList.remove('hidden');
    
    // Hide after 2 seconds
    setTimeout(() => {
        indicator.classList.add('hidden');
    }, 2000);
    
    console.log(isAutoSave ? 'Auto-saving draft...' : 'Saving draft...');
}

// Preview post with TinyMCE
function previewPost() {
    const title = titleInput.value;
    const editor = tinymce.get('tinymce-editor');
    const content = editor ? editor.getContent() : '';
    
    if (!title || !content) {
        alert('Please add a title and content first.');
        return;
    }
    
    // Open preview in new window
    const previewWindow = window.open('', 'preview', 'width=900,height=700');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
                    max-width: 800px; 
                    margin: 40px auto; 
                    padding: 20px;
                    line-height: 1.6;
                    color: #333;
                }
                h1 { font-size: 2.5em; margin-bottom: 20px; font-weight: 700; }
                h2 { font-size: 2em; margin-top: 30px; margin-bottom: 15px; }
                h3 { font-size: 1.5em; margin-top: 25px; margin-bottom: 12px; }
                img { max-width: 100%; height: auto; border-radius: 8px; }
                pre { 
                    background: #f5f5f5; 
                    padding: 15px; 
                    border-radius: 5px; 
                    overflow-x: auto;
                    border-left: 4px solid #3b82f6;
                }
                code { 
                    background: #f5f5f5; 
                    padding: 2px 6px; 
                    border-radius: 3px;
                    font-family: 'Courier New', monospace;
                }
                blockquote {
                    border-left: 4px solid #e2e8f0;
                    padding-left: 20px;
                    margin-left: 0;
                    color: #64748b;
                    font-style: italic;
                }
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 20px 0;
                }
                table td, table th {
                    border: 1px solid #e2e8f0;
                    padding: 12px;
                }
                table th {
                    background: #f8f9fa;
                    font-weight: 600;
                }
            </style>
        </head>
        <body>
            <h1>${title}</h1>
            <div>${content}</div>
        </body>
        </html>
    `);
}

// Status change handler
document.querySelector('select[name="status"]').addEventListener('change', function() {
    const scheduledDiv = document.getElementById('scheduled-date');
    scheduledDiv.style.display = this.value === 'scheduled' ? 'block' : 'none';
});

// Warn before leaving with unsaved changes
let formChanged = false;
document.getElementById('post-form').addEventListener('input', () => formChanged = true);
document.getElementById('post-form').addEventListener('submit', () => formChanged = false);

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Focus title on load
titleInput.focus();

// Form validation before submit
document.getElementById('post-form').addEventListener('submit', function(e) {
    const editor = tinymce.get('tinymce-editor');
    
    // Check if title is empty
    if (!titleInput.value.trim()) {
        e.preventDefault();
        alert('Please enter a post title.');
        titleInput.focus();
        return false;
    }
    
    // Check if content is empty
    if (editor) {
        const content = editor.getContent({format: 'text'}).trim();
        if (!content || content.length === 0) {
            e.preventDefault();
            alert('Please add some content to your post.');
            editor.focus();
            return false;
        }
    }
    
    // Form is valid, allow submission
    return true;
});

// Modal Functions
function openCategoryModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.remove('hidden');
    // Focus on input after modal opens
    setTimeout(() => {
        document.getElementById('modal-category-name').focus();
    }, 100);
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('modal-category-name').value = '';
    document.getElementById('modal-category-description').value = '';
    // Reset button state
    const createBtn = document.getElementById('create-category-btn');
    createBtn.disabled = false;
    document.getElementById('create-btn-text').classList.remove('hidden');
    document.getElementById('create-btn-loading').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('categoryModal');
        if (!modal.classList.contains('hidden')) {
            closeCategoryModal();
        }
    }
});

// Create Category from Modal
function createCategoryFromModal() {
    const name = document.getElementById('modal-category-name').value.trim();
    const description = document.getElementById('modal-category-description').value.trim();
    
    if (!name) {
        alert('Please enter a category name');
        return;
    }
    
    // Show loading state
    const createBtn = document.getElementById('create-category-btn');
    createBtn.disabled = true;
    document.getElementById('create-btn-text').classList.add('hidden');
    document.getElementById('create-btn-loading').classList.remove('hidden');
    
    // Send AJAX request to create category
    fetch('{{ route('admin.blog.categories.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            description: description,
            is_active: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new category to checkbox list
            const categoryList = document.getElementById('category-list');
            
            // Remove "no categories" message if exists
            const emptyMessage = categoryList.querySelector('p');
            if (emptyMessage) {
                emptyMessage.remove();
            }
            
            // Create new checkbox
            const label = document.createElement('label');
            label.className = 'flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer';
            label.innerHTML = `
                <input type="checkbox" 
                       name="categories[]" 
                       value="${data.category.id}"
                       checked
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">${data.category.name}</span>
            `;
            categoryList.appendChild(label);
            
            // Close modal
            closeCategoryModal();
            
            // Show success message
            showNotification('Category created successfully!', 'success');
        } else {
            alert(data.message || 'Failed to create category');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the category');
    })
    .finally(() => {
        createBtn.disabled = false;
        document.getElementById('create-btn-text').classList.remove('hidden');
        document.getElementById('create-btn-loading').classList.add('hidden');
    });
}

// Show notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white font-medium transform transition-all duration-300`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ============================================
// Featured Image Preview Functions
// ============================================

function previewFeaturedImage(event) {
    const file = event.target.files[0];
    
    if (file) {
        // Validate file size (2MB)
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            alert('File size exceeds 2MB. Please choose a smaller image.');
            event.target.value = ''; // Clear the input
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, WebP, or GIF).');
            event.target.value = ''; // Clear the input
            return;
        }
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');
            
            preview.src = e.target.result;
            container.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImagePreview() {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    const input = document.getElementById('featured-image-input');
    
    // Clear preview
    preview.src = '';
    container.classList.add('hidden');
    
    // Clear file input
    input.value = '';
}

// ============================================
// Tag Modal Functions
// ============================================

function openTagModal() {
    const modal = document.getElementById('tagModal');
    modal.classList.remove('hidden');
    // Focus on input after modal opens
    setTimeout(() => {
        document.getElementById('modal-tag-name').focus();
    }, 100);
}

function closeTagModal() {
    document.getElementById('tagModal').classList.add('hidden');
    document.getElementById('modal-tag-name').value = '';
    document.getElementById('modal-tag-description').value = '';
    // Reset button state
    const createBtn = document.getElementById('create-tag-btn');
    createBtn.disabled = false;
    document.getElementById('create-tag-btn-text').classList.remove('hidden');
    document.getElementById('create-tag-btn-loading').classList.add('hidden');
}

// Create Tag from Modal
function createTagFromModal() {
    const name = document.getElementById('modal-tag-name').value.trim();
    const description = document.getElementById('modal-tag-description').value.trim();
    
    if (!name) {
        alert('Please enter a tag name');
        return;
    }
    
    // Show loading state
    const createBtn = document.getElementById('create-tag-btn');
    createBtn.disabled = true;
    document.getElementById('create-tag-btn-text').classList.add('hidden');
    document.getElementById('create-tag-btn-loading').classList.remove('hidden');
    
    // Send AJAX request to create tag
    fetch('{{ route('admin.blog.tags.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new tag to checkbox list
            const tagList = document.getElementById('tag-list');
            
            // Remove "no tags" message if exists
            const emptyMessage = tagList.querySelector('p');
            if (emptyMessage) {
                emptyMessage.remove();
            }
            
            // Create new checkbox
            const label = document.createElement('label');
            label.className = 'flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer';
            label.innerHTML = `
                <input type="checkbox" 
                       name="tags[]" 
                       value="${data.tag.id}"
                       checked
                       class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <span class="ml-2 text-sm text-gray-700">${data.tag.name}</span>
            `;
            tagList.appendChild(label);
            
            // Close modal
            closeTagModal();
            
            // Show success message
            showNotification('Tag created successfully!', 'success');
        } else {
            alert(data.message || 'Failed to create tag');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the tag');
    })
    .finally(() => {
        createBtn.disabled = false;
        document.getElementById('create-tag-btn-text').classList.remove('hidden');
        document.getElementById('create-tag-btn-loading').classList.add('hidden');
    });
}
</script>
@endpush
@endsection
