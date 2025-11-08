@extends('layouts.admin')

@section('title', 'Edit Product')

@push('styles')
<style>
/* TinyMCE Custom Styling */
.tox-tinymce {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
@endpush

@section('content')
    @livewire('admin.product.product-form', ['product' => $product])
@endsection

@push('scripts')
<!-- TinyMCE CDN with API Key -->
<script src="https://cdn.tiny.cloud/1/8wacbe3zs5mntet5c9u50n4tenlqvgqm9bn1k6uctyqo3o7m/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
let tinyMCERetryCount = 0;
const maxRetries = 50; // Try for 5 seconds max

function initTinyMCE() {
    // Check if element exists
    const element = document.getElementById('product-description-editor');
    
    if (!element) {
        tinyMCERetryCount++;
        if (tinyMCERetryCount < maxRetries) {
            setTimeout(initTinyMCE, 100);
        } else {
            console.log('TinyMCE: Element not found after max retries. It may be on a different step.');
        }
        return;
    }
    
    // Reset retry count
    tinyMCERetryCount = 0;
    
    // Remove existing instance if any
    if (tinymce.get('product-description-editor')) {
        tinymce.get('product-description-editor').remove();
    }
    
    console.log('Initializing TinyMCE...');
    
    // Initialize TinyMCE for Product Description
    tinymce.init({
        selector: '#product-description-editor',
        height: 400,
        menubar: false, // Hide menubar for cleaner interface
        plugins: [
            'lists', 'link', 'image', 'fullscreen', 'code'
        ],
        toolbar: 'undo redo | bold italic underline | ' +
            'alignleft aligncenter alignright | ' +
            'bullist numlist | link image | ' +
            'fullscreen | code | removeformat',
        toolbar_mode: 'wrap',
        promotion: false, // Remove "Upgrade" button
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
        
        // Setup callback to sync with Livewire
        setup: function(editor) {
            editor.on('init', function() {
                // Set initial content
                editor.setContent(document.querySelector('#product-description-editor').value || '');
            });
            
            // Update hidden input and trigger Livewire update when content changes
            editor.on('change keyup paste', function() {
                const content = editor.getContent();
                const hiddenInput = document.getElementById('product-description-hidden');
                if (hiddenInput) {
                    hiddenInput.value = content;
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
            
            // Before form submit, ensure content is synced
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const content = editor.getContent();
                    const hiddenInput = document.getElementById('product-description-hidden');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                    }
                });
            }
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, attempting to initialize TinyMCE...');
    initTinyMCE();
});

// Re-initialize after Livewire updates
document.addEventListener('livewire:navigated', function() {
    console.log('Livewire navigated, reinitializing TinyMCE...');
    tinyMCERetryCount = 0; // Reset retry count
    initTinyMCE();
});

// Listen for Livewire component updates (when step changes)
document.addEventListener('livewire:update', function() {
    console.log('Livewire updated, checking for TinyMCE...');
    tinyMCERetryCount = 0; // Reset retry count
    initTinyMCE();
});

// For Livewire v3
if (typeof Livewire !== 'undefined') {
    Livewire.hook('morph.updated', () => {
        console.log('Livewire morph updated, reinitializing TinyMCE...');
        tinyMCERetryCount = 0; // Reset retry count
        initTinyMCE();
    });
    
    Livewire.hook('commit', ({ component, commit, respond }) => {
        // After any Livewire action completes
        setTimeout(() => {
            if (document.getElementById('product-description-editor') && !tinymce.get('product-description-editor')) {
                console.log('TinyMCE element found after commit, initializing...');
                tinyMCERetryCount = 0;
                initTinyMCE();
            }
        }, 100);
    });
}
</script>
@endpush
