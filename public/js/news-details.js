/**
 * News Details Page JavaScript
 * Handles font size controls, print functionality, and sticky sidebar behavior
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ================================================
    // Font Size Controls
    // ================================================
    const fontSizeButtons = document.querySelectorAll('.font-size-btn');
    const mainContent = document.querySelector('.news-main-content');
    
    // Load saved font size preference
    const savedFontSize = localStorage.getItem('news-font-size') || 'medium';
    setFontSize(savedFontSize);
    
    // Add click listeners to font size buttons
    fontSizeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const size = this.dataset.size;
            setFontSize(size);
            localStorage.setItem('news-font-size', size);
        });
    });
    
    function setFontSize(size) {
        if (!mainContent) return;
        
        // Remove all font size classes
        mainContent.classList.remove('font-size-small', 'font-size-medium', 'font-size-large');
        
        // Add selected font size class
        mainContent.classList.add('font-size-' + size);
        
        // Update active button
        fontSizeButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.size === size) {
                btn.classList.add('active');
            }
        });
    }
    
    // ================================================
    // Print Functionality
    // ================================================
    const printButton = document.querySelector('.print-article-btn');
    
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
    
    // ================================================
    // Sticky Sidebar Behavior
    // ================================================
    const leftSidebar = document.querySelector('.news-sidebar-left');
    const mainContentArea = document.querySelector('.news-main-content');
    
    if (leftSidebar && mainContentArea) {
        window.addEventListener('scroll', function() {
            const mainContentBottom = mainContentArea.getBoundingClientRect().bottom;
            const viewportHeight = window.innerHeight;
            
            // Stop sticky when main content ends
            if (mainContentBottom <= viewportHeight) {
                leftSidebar.style.position = 'absolute';
                leftSidebar.style.bottom = '0';
            } else {
                leftSidebar.style.position = 'sticky';
                leftSidebar.style.bottom = 'auto';
            }
        });
    }
    
    // ================================================
    // Social Share Functions
    // ================================================
    const shareButtons = document.querySelectorAll('.social-share-btn');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const platform = this.dataset.platform;
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.querySelector('h1').textContent);
            
            let shareUrl = '';
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${title}%20${url}`;
                    break;
                case 'messenger':
                    shareUrl = `fb-messenger://share/?link=${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
    
    // ================================================
    // Smooth Scroll for Related News
    // ================================================
    const scrollableNews = document.querySelectorAll('.scrollable-news');
    
    scrollableNews.forEach(container => {
        // Add smooth scrolling behavior
        container.style.scrollBehavior = 'smooth';
    });
});
