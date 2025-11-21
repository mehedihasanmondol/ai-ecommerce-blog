import '../css/app.css';
import './bootstrap';

// Import CropperJS (CSS will be linked in HTML)
import Cropper from 'cropperjs';
window.Cropper = Cropper;

// Import image cropper module
import './image-cropper.js';

// Note: Alpine.js is included with Livewire 3, no need to import/start it separately
