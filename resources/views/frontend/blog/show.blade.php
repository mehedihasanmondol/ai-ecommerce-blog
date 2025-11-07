@extends('layouts.app')

@section('title', $post->seo_title)
@section('meta_description', $post->seo_description)
@section('meta_keywords', $post->seo_keywords)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <article class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <!-- Category -->
                @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" 
                   class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mb-4 hover:bg-blue-200">
                    {{ $post->category->name }}
                </a>
                @endif

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                <!-- Meta -->
                <div class="flex flex-wrap items-center text-gray-600 mb-6">
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $post->author->name }}</span>
                    </div>
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $post->reading_time_text }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ number_format($post->views_count) }} views</span>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                     alt="{{ $post->featured_image_alt }}" 
                     class="w-full rounded-lg mb-6">
                @endif

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                <div class="mt-8 pt-8 border-t border-gray-200">
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
                <div class="mt-8 pt-8 border-t border-gray-200">
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
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
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
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
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
            <div class="bg-white rounded-lg shadow-md p-8">
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
        </div>
    </article>
</div>
@endsection
