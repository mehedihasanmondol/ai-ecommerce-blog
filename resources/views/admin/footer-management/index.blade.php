@extends('layouts.admin')

@section('title', 'Footer Management')

@push('styles')
<style>
/* TinyMCE Custom Styling */
.tox-tinymce {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
}

.tox-toolbar {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
}

.tinymce-content {
    min-height: 150px;
}
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Footer Management</h1>
        <p class="text-gray-600 mt-1">Manage all footer content, links, and settings</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('settings')" id="tab-settings" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    <i class="fas fa-cog mr-2"></i>General Settings
                </button>
                <button onclick="showTab('links')" id="tab-links" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-link mr-2"></i>Footer Links
                </button>
                <button onclick="showTab('blog')" id="tab-blog" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-newspaper mr-2"></i>Blog Posts
                </button>
                <button onclick="showTab('social')" id="tab-social" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-share-alt mr-2"></i>Social Media
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- General Settings Tab -->
            <div id="content-settings" class="tab-content">
                <form action="{{ route('admin.footer-management.update-settings') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Newsletter Title</label>
                            <input type="text" name="newsletter_title" value="{{ $settings['general']->firstWhere('key', 'newsletter_title')->value ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Newsletter Description</label>
                            <textarea name="newsletter_description" id="newsletter-description-editor" class="tinymce-content">{{ $settings['general']->firstWhere('key', 'newsletter_description')->value ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value Guarantee Text</label>
                            <input type="text" name="value_guarantee" value="{{ $settings['general']->firstWhere('key', 'value_guarantee')->value ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rewards Program Text</label>
                            <input type="text" name="rewards_text" value="{{ $settings['general']->firstWhere('key', 'rewards_text')->value ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                            <textarea name="copyright_text" id="copyright-text-editor" class="tinymce-content">{{ $settings['legal']->firstWhere('key', 'copyright_text')->value ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Links Tab -->
            <div id="content-links" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach(['about' => 'About', 'company' => 'Company', 'resources' => 'Resources', 'customer_support' => 'Customer Support'] as $section => $title)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            @foreach($links[$section] ?? [] as $link)
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $link->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $link->url }}</p>
                                </div>
                                <form action="{{ route('admin.footer-management.delete-link', $link) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this link?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>

                        <form action="{{ route('admin.footer-management.store-link') }}" method="POST" class="space-y-2">
                            @csrf
                            <input type="hidden" name="section" value="{{ $section }}">
                            <input type="text" name="title" placeholder="Link Title" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <input type="text" name="url" placeholder="URL" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded">
                                <i class="fas fa-plus mr-1"></i>Add Link
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Blog Posts Tab -->
            <div id="content-blog" class="tab-content hidden">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Blog Post</h3>
                    <form action="{{ route('admin.footer-management.store-blog') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-lg">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="title" placeholder="Blog Post Title" required class="px-4 py-2 border border-gray-300 rounded-lg">
                            <input type="text" name="url" placeholder="URL" required class="px-4 py-2 border border-gray-300 rounded-lg">
                            <input type="file" name="image" accept="image/*" class="px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <button type="submit" class="mt-4 px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Add Blog Post
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($blogPosts as $post)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-32 object-cover">
                        @else
                        <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                        </div>
                        @endif
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $post->title }}</h4>
                            <p class="text-xs text-gray-500 mb-3">{{ $post->url }}</p>
                            <form action="{{ route('admin.footer-management.delete-blog', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this post?')">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Social Media Tab -->
            <div id="content-social" class="tab-content hidden">
                <form action="{{ route('admin.footer-management.update-settings') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(['facebook' => 'Facebook', 'twitter' => 'Twitter', 'youtube' => 'YouTube', 'pinterest' => 'Pinterest', 'instagram' => 'Instagram'] as $platform => $name)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-{{ $platform }} mr-2"></i>{{ $name }} URL
                            </label>
                            <input type="text" name="{{ $platform }}_url" value="{{ $settings['social']->firstWhere('key', $platform . '_url')->value ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="https://{{ $platform }}.com/yourpage">
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Save Social Links
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- TinyMCE CDN with API Key -->
<script src="https://cdn.tiny.cloud/1/8wacbe3zs5mntet5c9u50n4tenlqvgqm9bn1k6uctyqo3o7m/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Initialize TinyMCE for Newsletter Description
tinymce.init({
    selector: '#newsletter-description-editor',
    height: 200,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.4; }',
    branding: false,
    promotion: false,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});

// Initialize TinyMCE for Copyright Text
tinymce.init({
    selector: '#copyright-text-editor',
    height: 250,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.4; }',
    branding: false,
    promotion: false,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});

function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Form submission handler to sync TinyMCE content
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Sync all TinyMCE editors before form submission
            tinymce.triggerSave();
        });
    });
});
</script>
@endpush
@endsection
