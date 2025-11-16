@extends('layouts.app')

@section('title', $post->seo_title)
@section('meta_description', $post->seo_description)
@section('meta_keywords', $post->seo_keywords)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar - Collapsible -->
            <x-blog.sidebar 
                title="{{ \App\Models\SiteSetting::get('blog_title', 'Wellness Hub') }}"
                subtitle="{{ \App\Models\SiteSetting::get('blog_tagline', 'Health & Lifestyle Blog') }}"
                :categories="$categories"
                :currentCategory="$post->category"
            />

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
                            <!-- Tick Marks -->
                            <x-blog.tick-marks :post="$post" />
                        </div>

                        <!-- Author Info -->
                        <div class="flex items-center gap-3">
                            @if($post->author->authorProfile?->avatar_or_fallback)
                                <img src="{{ asset('storage/' . $post->author->authorProfile->avatar_or_fallback) }}" 
                                     alt="{{ $post->author->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                    {{ substr($post->author->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('blog.author', $post->author->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $post->author->name }}</a>
                                <p class="text-sm text-gray-500">Posted on {{ $post->published_at->format('F j, Y') }}</p>
                            </div>
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

                    <!-- YouTube Video -->
                    @if($post->youtube_url && $post->youtube_video_id)
                    <div class="px-8 pb-8">
                        <div class="relative rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0;">
                            <iframe 
                                src="{{ $post->youtube_embed_url }}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen
                                class="absolute top-0 left-0 w-full h-full">
                            </iframe>
                        </div>
                    </div>
                    @endif

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
                            @if($post->author->authorProfile?->avatar_or_fallback)
                                <img src="{{ asset('storage/' . $post->author->authorProfile->avatar_or_fallback) }}" 
                                     alt="{{ $post->author->name }}"
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($post->author->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">
                                <a href="{{ route('blog.author', $post->author->id) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $post->author->name }}
                                </a>
                            </h3>
                            @if($post->author->authorProfile?->job_title)
                                <p class="text-sm text-gray-500 mb-2">{{ $post->author->authorProfile->job_title }}</p>
                            @endif
                            @if($post->author->authorProfile?->bio)
                                <p class="text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($post->author->authorProfile->bio, 200) }}</p>
                            @else
                                <p class="text-gray-600 mb-3">Content writer and blogger passionate about sharing knowledge.</p>
                            @endif
                            <a href="{{ route('blog.author', $post->author->id) }}" 
                               class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm">
                                View author profile
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
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
                @livewire('blog.comment-section', ['post' => $post])
                @endif

                <!-- Old Comment Section (Backup) -->
                @if(false && $post->allow_comments)
                <div class="bg-white rounded-lg shadow-sm mt-8 p-8" x-data="{ replyingTo: null }">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Comments ({{ $post->approvedComments->count() }})
                        </h2>
                    </div>

                    @if(session('comment_success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('comment_success') }}
                    </div>
                    @endif

                    <!-- Comment Form -->
                    <div class="mb-8 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave a Comment</h3>
                        <form action="{{ route('blog.comments.store', $post->id) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                @guest
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                        <input type="text" 
                                               name="guest_name" 
                                               value="{{ old('guest_name') }}"
                                               placeholder="Your Name" 
                                               required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('guest_name') border-red-500 @enderror">
                                        @error('guest_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                        <input type="email" 
                                               name="guest_email" 
                                               value="{{ old('guest_email') }}"
                                               placeholder="your@email.com" 
                                               required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('guest_email') border-red-500 @enderror">
                                        @error('guest_email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                @endguest

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment *</label>
                                    <textarea name="content" 
                                              rows="4" 
                                              placeholder="Share your thoughts..." 
                                              required
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Your comment will be reviewed before being published.</p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Post Comment
                                    </button>
                                    @guest
                                    <p class="text-sm text-gray-600">
                                        Have an account? 
                                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Sign in</a>
                                    </p>
                                    @endguest
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    @if($post->approvedComments->count() > 0)
                    <div class="space-y-6">
                        @foreach($post->approvedComments as $comment)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($comment->commenter_name, 0, 1) }}
                                    </div>
                                </div>

                                <!-- Comment Content -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $comment->commenter_name }}</h4>
                                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $comment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>

                                    <!-- Replies -->
                                    @if($comment->approvedReplies->count() > 0)
                                    <div class="mt-4 space-y-4">
                                        @foreach($comment->approvedReplies as $reply)
                                        <div class="flex items-start gap-3 pl-4 border-l-2 border-blue-200">
                                            <!-- Reply Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($reply->commenter_name, 0, 1) }}
                                                </div>
                                            </div>

                                            <!-- Reply Content -->
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h5 class="font-semibold text-gray-900 text-sm">{{ $reply->commenter_name }}</h5>
                                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No comments yet</p>
                        <p class="text-gray-400 text-sm mt-1">Be the first to share your thoughts!</p>
                    </div>
                    @endif
                </div>
                @endif
            </article>
        </div>
    </div>
</div>
@endsection
