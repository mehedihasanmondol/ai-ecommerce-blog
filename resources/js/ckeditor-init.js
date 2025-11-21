import {
    ClassicEditor,
    Essentials,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Paragraph,
    Heading,
    Link,
    List,
    BlockQuote,
    Table,
    TableToolbar,
    Image,
    ImageToolbar,
    ImageCaption,
    ImageStyle,
    ImageResize,
    ImageUpload,
    MediaEmbed,
    Indent,
    IndentBlock,
    Alignment,
    Font,
    RemoveFormat,
    SourceEditing,
    HorizontalLine,
    CodeBlock,
    Code,
    Subscript,
    Superscript,
    FindAndReplace,
    SpecialCharacters,
    SpecialCharactersEssentials,
    WordCount
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

// Initialize CKEditor on a textarea
export function initCKEditor(selector, options = {}) {
    const element = document.querySelector(selector);
    if (!element) {
        console.error(`CKEditor: Element ${selector} not found`);
        return null;
    }

    const defaultConfig = {
        licenseKey: 'GPL', // Free GPL license for open-source projects
        plugins: [
            Essentials,
            Bold,
            Italic,
            Underline,
            Strikethrough,
            Paragraph,
            Heading,
            Link,
            List,
            BlockQuote,
            Table,
            TableToolbar,
            Image,
            ImageToolbar,
            ImageCaption,
            ImageStyle,
            ImageResize,
            ImageUpload,
            MediaEmbed,
            Indent,
            IndentBlock,
            Alignment,
            Font,
            RemoveFormat,
            SourceEditing,
            HorizontalLine,
            CodeBlock,
            Code,
            Subscript,
            Superscript,
            FindAndReplace,
            SpecialCharacters,
            SpecialCharactersEssentials,
            WordCount
        ],
        toolbar: {
            items: [
                'undo', 'redo',
                '|',
                'heading',
                '|',
                'bold', 'italic', 'underline', 'strikethrough',
                '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                '|',
                'alignment',
                '|',
                'link', 'uploadImage', 'mediaEmbed', 'blockQuote', 'insertTable', 'codeBlock',
                '|',
                'bulletedList', 'numberedList',
                '|',
                'outdent', 'indent',
                '|',
                'subscript', 'superscript', 'code',
                '|',
                'specialCharacters', 'horizontalLine',
                '|',
                'findAndReplace',
                '|',
                'removeFormat',
                '|',
                'sourceEditing'
            ],
            shouldNotGroupWhenFull: true
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
            ]
        },
        fontSize: {
            options: [
                'tiny',
                'small',
                'default',
                'big',
                'huge'
            ]
        },
        fontFamily: {
            options: [
                'default',
                'Arial, Helvetica, sans-serif',
                'Courier New, Courier, monospace',
                'Georgia, serif',
                'Lucida Sans Unicode, Lucida Grande, sans-serif',
                'Tahoma, Geneva, sans-serif',
                'Times New Roman, Times, serif',
                'Trebuchet MS, Helvetica, sans-serif',
                'Verdana, Geneva, sans-serif'
            ]
        },
        image: {
            toolbar: [
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'toggleImageCaption',
                'imageTextAlternative',
                '|',
                'linkImage'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        link: {
            decorators: {
                openInNewTab: {
                    mode: 'manual',
                    label: 'Open in a new tab',
                    attributes: {
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    }
                }
            }
        },
        placeholder: 'Write your content here...',
        ...options
    };

    return ClassicEditor
        .create(element, defaultConfig)
        .then(editor => {
            window.editor = editor; // Make editor globally accessible for debugging
            
            // Word count tracking
            const wordCountPlugin = editor.plugins.get('WordCount');
            const wordCountContainer = options.wordCountContainer || '#word-count';
            const wordCountElement = document.querySelector(wordCountContainer);
            
            if (wordCountElement) {
                wordCountElement.appendChild(wordCountPlugin.wordCountContainer);
            }

            console.log('CKEditor initialized successfully');
            return editor;
        })
        .catch(error => {
            console.error('Error initializing CKEditor:', error);
            throw error;
        });
}

// Export for global use
window.initCKEditor = initCKEditor;
