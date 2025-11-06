@props(['categories'])

<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Shop by category</h2>
        </div>

        <!-- Main Categories Slider -->
        <div class="relative mb-8" x-data="categorySlider()">
            <!-- Previous Button -->
            <button 
                @click="prev()" 
                class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition -ml-5"
                :class="{ 'opacity-50 cursor-not-allowed': atStart }"
                :disabled="atStart"
            >
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Categories Container -->
            <div class="overflow-hidden">
                <div 
                    x-ref="slider"
                    class="flex gap-4 transition-transform duration-300 ease-in-out"
                    :style="`transform: translateX(-${currentIndex * slideWidth}px)`"
                >
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="flex-shrink-0 flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition group"
                           style="width: calc((100% - 7 * 16px) / 8);">
                            <!-- Icon Circle -->
                            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-100 transition overflow-hidden">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                    <!-- Default SVG Icon -->
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                @endif
                            </div>
                            <!-- Category Name -->
                            <span class="text-sm font-medium text-gray-900 text-center">{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Next Button -->
            <button 
                @click="next()" 
                class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition -mr-5"
                :class="{ 'opacity-50 cursor-not-allowed': atEnd }"
                :disabled="atEnd"
            >
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Subcategories / Tags -->
        @if($categories->count() > 0)
            <div class="flex flex-wrap gap-3 items-center">
                @php
                    $subcategories = [
                        'Antioxidants', 'Omegas & Fish Oils (EPA DHA)', 'Amino Acids', 
                        'Minerals', 'Bee Products', 'Herbs', "Men's Health", 
                        'Gut Health', 'Sleep'
                    ];
                @endphp
                
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('shop', ['search' => $subcategory]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition">
                        {{ $subcategory }}
                    </a>
                @endforeach
                
                <!-- Next Arrow -->
                <button class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</section>

<script>
function categorySlider() {
    return {
        currentIndex: 0,
        slideWidth: 0,
        itemsPerView: 8,
        totalItems: {{ $categories->count() }},
        atStart: true,
        atEnd: false,
        
        init() {
            this.calculateSlideWidth();
            this.updateButtonStates();
            
            window.addEventListener('resize', () => {
                this.calculateSlideWidth();
                this.updateButtonStates();
            });
        },
        
        calculateSlideWidth() {
            const container = this.$refs.slider;
            if (container && container.children.length > 0) {
                const item = container.children[0];
                this.slideWidth = item.offsetWidth + 16; // item width + gap
            }
        },
        
        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.updateButtonStates();
            }
        },
        
        next() {
            const maxIndex = Math.max(0, this.totalItems - this.itemsPerView);
            if (this.currentIndex < maxIndex) {
                this.currentIndex++;
                this.updateButtonStates();
            }
        },
        
        updateButtonStates() {
            this.atStart = this.currentIndex === 0;
            this.atEnd = this.currentIndex >= Math.max(0, this.totalItems - this.itemsPerView);
        }
    }
}
</script>
