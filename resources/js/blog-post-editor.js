import { initCKEditor } from './ckeditor-init.js';

// Global editor instance
let editor;

// Initialize CKEditor when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    initCKEditor('#ckeditor', {
        wordCountContainer: '#word-count',
        placeholder: 'Write your blog post content here...'
    }).then(editorInstance => {
        editor = editorInstance;
        console.log('CKEditor initialized for blog post');
        
        // Setup auto-save on editor change
        editor.model.document.on('change:data', () => {
            autoSave();
        });
    });

    // Listen for CKEditor uploader event and trigger Livewire modal
    window.addEventListener('open-ckeditor-uploader', (event) => {
        const { field, multiple } = event.detail;
        
        // Dispatch Livewire event to open the media library (Library tab by default)
        Livewire.dispatch('openMediaLibrary', { 
            field: field, 
            multiple: multiple 
        });
    });
    
    // WordPress-style Editor Functions
    const titleInput = document.getElementById('post-title');
    const slugInput = document.getElementById('post-slug');
    
    // Auto-generate slug from title
    if (titleInput && slugInput) {
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
        
        // Auto-save on title change
        titleInput.addEventListener('input', autoSave);
    }
    
    // Focus title on load
    if (titleInput) {
        titleInput.focus();
    }
    
    // Form validation before submit
    const postForm = document.getElementById('post-form');
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            // Check if title is empty
            if (!titleInput || !titleInput.value.trim()) {
                e.preventDefault();
                alert('Please enter a post title.');
                if (titleInput) titleInput.focus();
                return false;
            }
            
            // Check if content is empty
            if (editor) {
                const content = editor.getData().replace(/<[^>]*>/g, '').trim();
                if (!content || content.length === 0) {
                    e.preventDefault();
                    alert('Please add some content to your post.');
                    editor.editing.view.focus();
                    return false;
                }
            }
            
            return true;
        });
        
        // Warn before leaving with unsaved changes
        let formChanged = false;
        postForm.addEventListener('input', () => formChanged = true);
        postForm.addEventListener('submit', () => formChanged = false);
        
        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }
});

// Mark slug as manually edited
window.editSlug = function() {
    const slugInput = document.getElementById('post-slug');
    if (slugInput) {
        slugInput.dataset.manualEdit = 'true';
        slugInput.focus();
        slugInput.select();
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

// Save draft function
window.saveDraft = function(isAutoSave = false) {
    const postForm = document.getElementById('post-form');
    if (!postForm) return;
    
    const formData = new FormData(postForm);
    formData.set('status', 'draft');
    
    // Show indicator
    const indicator = document.getElementById('autosave-indicator');
    if (indicator) {
        indicator.classList.remove('hidden');
        
        // Hide after 2 seconds
        setTimeout(() => {
            indicator.classList.add('hidden');
        }, 2000);
    }
    
    console.log(isAutoSave ? 'Auto-saving draft...' : 'Saving draft...');
}

// Preview post with CKEditor
window.previewPost = function() {
    const titleInput = document.getElementById('post-title');
    const title = titleInput ? titleInput.value : '';
    const content = editor ? editor.getData() : '';
    
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
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const scheduledDiv = document.getElementById('scheduled-date');
            if (scheduledDiv) {
                scheduledDiv.style.display = this.value === 'scheduled' ? 'block' : 'none';
            }
        });
    }
});
