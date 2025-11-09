@extends('layouts.app')

@section('title', $post->seo_title)
@section('meta_description', $post->seo_description)
@section('meta_keywords', $post->seo_keywords)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar - Sticky -->
            <aside class="lg:col-span-3">
                <div class="lg:sticky lg:top-8 space-y-6">
                    <!-- Wellness Hub + Categories Combined -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <!-- Logo/Brand Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-900">Wellness Hub</h2>
                        </div>
                        
                        <!-- Categories Navigation -->
                        <nav class="py-2">
                            <!-- Home Link -->
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium">Home</span>
                            </a>

                            <!-- Dynamic Categories from Database -->
                            @foreach($categories as $category)
                                <a href="{{ route('blog.category', $category->slug) }}" 
                                   class="flex items-center justify-between gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors group {{ $post->category && $post->category->id === $category->id ? 'bg-green-50 text-green-700' : '' }}">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <!-- Category Image or Fallback Icon -->
                                        @if($category->image_path)
                                            <div class="w-8 h-8 flex-shrink-0 rounded-md overflow-hidden bg-gray-100">
                                                <img 
                                                    src="{{ asset('storage/' . $category->image_path) }}" 
                                                    alt="{{ $category->name }}"
                                                    class="w-full h-full object-cover"
                                                >
                                            </div>
                                        @else
                                            <div class="w-8 h-8 flex-shrink-0 rounded-md bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="font-medium truncate">{{ $category->name }}</span>
                                    </div>
                                    @if($category->published_posts_count > 0)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">
                                            {{ $category->published_posts_count }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Content Types -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Content Types</h3>
                        </div>
                        <nav class="py-2">
                            <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium">Articles</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Videos</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Content Team -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Content Team</h3>
                        </div>
                        <nav class="py-2">
                            <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">Wellness Experts</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <article class="lg:col-span-9">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Breadcrumb -->
                    <div class="px-8 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">Wellness Hub Home</a>
                            <span>/</span>
                            @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-green-600 transition-colors">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="px-8 pt-6 pb-8">
                        <!-- Title -->
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $post->title }}</h1>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>{{ number_format($post->views_count) }} views</span>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                EVIDENCE BASED
                            </span>
                        </div>

                        <!-- Author Info -->
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <div>
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $post->author->name }}</a>
                                <p class="text-sm text-gray-500">Posted on {{ $post->published_at->format('F j, Y') }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <button class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </button>
                            <button class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($post->featured_image)
                    <div class="px-8 pb-8">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->featured_image_alt }}" 
                             class="w-full rounded-xl">
                    </div>
                    @endif

                    <!-- Table of Contents -->
                    <div class="px-8 pb-6">
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                    Table of Contents
                                </h2>
                                <button class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#key-takeaways" class="text-blue-600 hover:text-blue-800">Key Takeaways</a></li>
                                <li><a href="#nutrition-reset" class="text-blue-600 hover:text-blue-800">Do You Need A Nutrition Reset?</a></li>
                                <li><a href="#science-tips" class="text-blue-600 hover:text-blue-800">7 Science-Backed Nutrition Tips</a></li>
                                <li><a href="#practice" class="text-blue-600 hover:text-blue-800">Putting It Into Practice: A Sample Healthy Eating Day</a></li>
                                <li><a href="#faq" class="text-blue-600 hover:text-blue-800">Frequently Asked Questions About Eating Habits</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-8 pb-8">
                        <div class="prose prose-lg max-w-none">
                            {!! $post->content !!}
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="px-8 py-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Tags:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}" 
                               class="inline-block bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 px-4 py-2 rounded-full transition duration-150">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Share -->
                    <div class="px-8 py-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Share this post:</h3>
                        <div class="flex space-x-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $post->slug)) }}" 
                               target="_blank"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('products.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                               target="_blank"
                               class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg">
                                Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('products.show', $post->slug)) }}" 
                               target="_blank"
                               class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg">
                                LinkedIn
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="bg-white rounded-lg shadow-sm mt-8 p-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->author->name }}</h3>
                        <p class="text-gray-600">Author bio goes here...</p>
                    </div>
                </div>
            </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="bg-white rounded-lg shadow-sm mt-8 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                    <article class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                        @if($related->featured_image)
                        <img src="{{ asset('storage/' . $related->featured_image) }}" 
                             alt="{{ $related->title }}" 
                             class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 mb-2 hover:text-blue-600">
                                <a href="{{ route('products.show', $related->slug) }}">
                                    {{ Str::limit($related->title, 50) }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">{{ $related->published_at->format('M d, Y') }}</p>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif

                <!-- Comments Section -->
                @if($post->allow_comments)
                <div class="bg-white rounded-lg shadow-sm mt-8 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Comments ({{ $post->approvedComments->count() }})
                </h2>

                <!-- Comment Form -->
                <form action="{{ route('blog.comments.store', $post->id) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="space-y-4">
                        @guest
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="guest_name" placeholder="Your Name *" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <input type="email" name="guest_email" placeholder="Your Email *" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        @endguest

                        <div>
                            <textarea name="content" rows="4" placeholder="Write your comment..." required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Post Comment
                        </button>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="space-y-6">
                    @foreach($post->approvedComments as $comment)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $comment->commenter_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>

                        <!-- Replies -->
                        @if($comment->approvedReplies->count() > 0)
                        <div class="mt-4 ml-8 space-y-4">
                            @foreach($comment->approvedReplies as $reply)
                            <div class="border-l-4 border-gray-300 pl-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h5 class="font-semibold text-gray-900">{{ $reply->commenter_name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-700">{{ $reply->content }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            </article>
        </div>
    </div>
</div>
@endsection
