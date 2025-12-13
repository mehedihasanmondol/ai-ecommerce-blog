/**
 * Lazy Loading for Featured Category Sections
 * Uses Intersection Observer API to load category sections when they enter viewport
 */

document.addEventListener('DOMContentLoaded', function () {
    const categorySections = document.querySelectorAll('.lazy-category-section');

    if (!categorySections.length) {
        console.log('No lazy category sections found');
        return;
    }

    console.log(`Found ${categorySections.length} category sections to lazy load`);

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
                const categoryId = section.dataset.categoryId;
                const isLoaded = section.dataset.loaded === 'true';

                if (!isLoaded) {
                    console.log(`Loading category section: ${categoryId}`);
                    loadCategorySection(section, categoryId);
                    observer.unobserve(section); // Stop observing once loaded
                }
            }
        });
    };

    // Create observer
    const observer = new IntersectionObserver(handleIntersection, options);

    // Observe all category sections
    categorySections.forEach(section => {
        observer.observe(section);
    });

    /**
     * Load category section via AJAX
     */
    function loadCategorySection(sectionElement, categoryId) {
        // Add loading state
        sectionElement.classList.add('loading');

        fetch(`/api/load-category-section/${categoryId}`, {
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

                console.log(`Category section ${categoryId} loaded successfully`);

                // Trigger any necessary re-initialization (e.g., Alpine.js components)
                if (window.Alpine) {
                    Alpine.initTree(sectionElement);
                }

                // Dispatch custom event for other scripts that might need to know
                const event = new CustomEvent('categorySectionLoaded', {
                    detail: { categoryId, element: sectionElement }
                });
                document.dispatchEvent(event);
            })
            .catch(error => {
                console.error('Error loading category section:', error);
                sectionElement.classList.remove('loading');
                sectionElement.classList.add('error');

                // Show error message
                sectionElement.innerHTML = `
                <div class="lg:col-span-12 p-6 bg-red-50 text-red-600 rounded">
                    <p class="font-bold mb-2">Failed to load content</p>
                    <p class="text-sm">Please refresh the page to try again.</p>
                </div>
            `;
            });
    }
});
