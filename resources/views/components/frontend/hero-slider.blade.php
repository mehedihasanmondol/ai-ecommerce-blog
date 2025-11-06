{{-- 
/**
 * ModuleName: Frontend Hero Slider Component
 * Purpose: Auto-rotating hero slider for homepage with slide name indicators
 * 
 * Features:
 * - Auto-play with 5-second interval
 * - Manual navigation (prev/next arrows)
 * - Slide name indicators (instead of dots)
 * - Pause on hover
 * - Full-width banner images
 * 
 * @category Frontend
 * @package  Components
 * @created  2025-11-06
 */
--}}

<section class="relative overflow-visible bg-gray-100 mb-8" 
         x-data="{ 
            currentSlide: 0,
            autoplayInterval: null,
            isPlaying: true,
            slides: [
                {
                    title: 'Up to 70% off',
                    subtitle: 'iHerb Brands',
                    image: 'https://via.placeholder.com/1920x400/1e3a8a/ffffff?text=Nutricost+-+Take+control+of+your+health'
                },
                {
                    title: 'Trusted Brands',
                    subtitle: 'Up to 20% off',
                    image: 'https://via.placeholder.com/1920x400/059669/ffffff?text=Trusted+Brands+-+Shop+Now'
                },
                {
                    title: 'Wellness Hub',
                    subtitle: 'Learn More',
                    image: 'https://via.placeholder.com/1920x400/7c3aed/ffffff?text=Wellness+Hub+-+Discover+Health'
                },
                {
                    title: '20 High-Fibre Foods',
                    subtitle: 'Find out more',
                    image: 'https://via.placeholder.com/1920x400/dc2626/ffffff?text=High+Fibre+Foods+-+Nutrition+Guide'
                },
                {
                    title: 'Nutricost',
                    subtitle: 'Shop now',
                    image: 'https://via.placeholder.com/1920x400/ea580c/ffffff?text=Nutricost+-+Premium+Supplements'
                }
            ],
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                if (!this.autoplayInterval) {
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                    this.isPlaying = true;
                }
            },
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
                this.isPlaying = false;
            },
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            },
            goToSlide(index) {
                this.currentSlide = index;
            }
         }"
         @mouseenter="stopAutoplay()"
         @mouseleave="startAutoplay()">
    
    <!-- Slider Container with Overlay Navigation -->
    <div class="relative h-[300px] md:h-[400px]">
        <!-- Slider Images -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="currentSlide === index"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                
                <!-- Full Width Banner Image -->
                <img :src="slide.image" 
                     :alt="slide.title" 
                     class="w-full h-full object-cover">
            </div>
        </template>
        
        <!-- Previous Button -->
        <button @click="prevSlide()" 
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full backdrop-blur-sm transition z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <!-- Next Button -->
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full backdrop-blur-sm transition z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Slide Name Indicators (over 50% overlaying slider) -->
        <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 z-20">
            <div class="bg-gray-50/98 shadow-lg rounded-lg px-4 py-2.5">
                <div class="flex items-center gap-2">
                    <!-- Navigation Buttons -->
                    <div class="flex items-center gap-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="goToSlide(index)" 
                                    class="px-3 py-2 text-center rounded-md transition-all duration-300 min-w-[100px]"
                                    :class="currentSlide === index ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200'">
                                <div class="text-sm font-semibold leading-tight" x-text="slide.title"></div>
                                <div class="text-xs mt-0.5" x-text="slide.subtitle"></div>
                            </button>
                        </template>
                    </div>
                    
                    <!-- View All Link -->
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm whitespace-nowrap flex items-center gap-1 px-3 border-l border-gray-300">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pause/Play Button -->
    <button @click="isPlaying ? stopAutoplay() : startAutoplay()" 
            class="absolute bottom-6 right-6 bg-white/30 hover:bg-white/50 text-white p-2 rounded-full backdrop-blur-sm transition z-20">
        <svg x-show="isPlaying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <svg x-show="!isPlaying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </button>
</section>
