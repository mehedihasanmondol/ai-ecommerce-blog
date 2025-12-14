/**
 * Lazy Loading for Top Videos Section
 * Uses Intersection Observer API to load top videos when section enters viewport
 */

document.addEventListener('DOMContentLoaded', function () {
    const topVideosSection = document.querySelector('.lazy-top-videos-section');

    if (!topVideosSection) {
        console.log('No lazy top videos section found');
        return;
    }

    console.log('Found top videos section to lazy load');

    // Intersection Observer options
    const options = {
        root: null, // viewport
        rootMargin: '300px', // Load 300px before entering viewport
        threshold: 0.1 // Trigger when 10% visible
    };

    // Callback when section enters viewport
    const handleIntersection = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const section = entry.target;
                const isLoaded = section.dataset.loaded === 'true';

                if (!isLoaded) {
                    console.log('Loading top videos section');
                    loadTopVideosSection(section);
                    observer.unobserve(section); // Stop observing once loaded
                }
            }
        });
    };

    // Create observer
    const observer = new IntersectionObserver(handleIntersection, options);

    // Observe top videos section
    observer.observe(topVideosSection);

    /**
     * Load top videos section via AJAX
     */
    function loadTopVideosSection(sectionElement) {
        // Add loading state
        sectionElement.classList.add('loading');

        fetch('/api/load-top-videos-section', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                // Replace skeleton with actual content
                sectionElement.innerHTML = html;
                sectionElement.dataset.loaded = 'true';
                sectionElement.classList.remove('loading');
                sectionElement.classList.add('loaded');

                console.log('Top videos section loaded successfully');

                // Trigger any necessary re-initialization (e.g., Alpine.js components)
                if (window.Alpine) {
                    Alpine.initTree(sectionElement);
                }

                // Dispatch custom event for other scripts that might need to know
                const event = new CustomEvent('topVideosSectionLoaded', {
                    detail: { element: sectionElement }
                });
                document.dispatchEvent(event);
            })
            .catch(error => {
                console.error('Error loading top videos section:', error);
                sectionElement.classList.remove('loading');
                sectionElement.classList.add('error');

                // Show error message
                sectionElement.innerHTML = `
                <div class="p-6 bg-red-50 text-red-600 rounded">
                    <p class="font-bold mb-2">Failed to load top videos</p>
                    <p class="text-sm">Please refresh the page to try again.</p>
                </div>
            `;
            });
    }
});
