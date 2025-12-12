@extends('layouts.app')

@section('title', $seoData['title'] ?? $post->title)

@section('description', $seoData['description'] ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160))

@section('keywords', $seoData['keywords'] ?? 'blog, article')

@section('og_type', $seoData['og_type'] ?? 'article')
@section('og_title', $seoData['title'] ?? $post->title)
@section('og_description', $seoData['description'] ?? $post->excerpt)
@section('og_image', $seoData['og_image'] ?? asset('images/og-default.jpg'))
@section('canonical', $seoData['canonical'] ?? url($post->slug))

@section('twitter_card', 'summary_large_image')
@section('twitter_title', $seoData['title'] ?? $post->title)
@section('twitter_description', $seoData['description'] ?? $post->excerpt)
@section('twitter_image', $seoData['og_image'] ?? asset('images/og-default.jpg'))

@if(isset($seoData['author_name']))
@section('author', $seoData['author_name'])
@endif

@push('meta_tags')
<!-- Article Specific Meta -->
<meta property="article:published_time" content="{{ $seoData['published_at']->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $seoData['updated_at']->toIso8601String() }}">
@if($post->author)
<meta property="article:author" content="{{ $post->author->name }}">
@endif
@if($post->categories && $post->categories->count() > 0)
@foreach($post->categories as $category)
<meta property="article:section" content="{{ $category->name }}">
@endforeach
@endif
@foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag->name }}">
@endforeach
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-green-600 transition-colors">{{ \App\Models\SiteSetting::get('site_name', 'হোম') }}</a>
            <span>/</span>
            @if($post->categories && $post->categories->count() > 0)
            @foreach($post->categories as $index => $category)
            <a href="{{ url($category->slug) }}" class="hover:text-green-600 transition-colors">
                {{ $category->name }}
            </a>
            @if(!$loop->last)
            <span>/</span>
            @endif
            @endforeach
            <span>/</span>
            @endif
            <span class="text-gray-400">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Left Sidebar -->
            <aside class="lg:col-span-2">
                <div class="sticky top-18 space-y-4">
                    <div class="author-box">
                        <div class="w-24">

                            @if($post->author)
                            @if($post->author->authorProfile?->media)
                            <img src="{{ $post->author->authorProfile->media->medium_url }}"
                                alt="{{ $post->author->name }}">
                            @elseif($post->author->authorProfile?->avatar)
                            <img src="{{ asset('storage/' . $post->author->authorProfile->avatar) }}"
                                alt="{{ $post->author->name }}">
                            @elseif($post->author->media)
                            <img src="{{ $post->author->media->medium_url }}"
                                alt="{{ $post->author->name }}">
                            @elseif($post->author->avatar)
                            <img src="{{ asset('storage/' . $post->author->avatar) }}"
                                alt="{{ $post->author->name }}">
                            @else
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: bold;">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            @endif
                        </div>


                        <h2 class="text-xl font-bold">{{ $post->author->name }}</h2>

                        @if($post->author->authorProfile?->bio)
                        <p class="bio">{{ \Illuminate\Support\Str::limit($post->author->authorProfile->bio, 100) }}</p>
                        @endif
                        @endif

                        <!-- Post Time in Bangla -->
                        <div class="post-time border-t border-gray-200 mb-4">
                            প্রকাশিত: {{ bengali_date($post->published_at, 'short_time') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>

                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-sky-500 flex items-center justify-center hover:bg-sky-600 transition">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>

                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>


                    </div>

                    {{-- Font Size and Print Controls --}}
                    <div class="flex items-center gap-2 mt-4">
                        {{-- Print Button --}}
                        <button onclick="printArticle()"
                            class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-gray-800 transition"
                            title="প্রিন্ট করুন">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>

                        {{-- Font Size Decrease --}}
                        <button onclick="decreaseFontSize()"
                            class="w-10 h-10 rounded-full bg-orange-600 flex items-center justify-center hover:bg-orange-700 transition"
                            title="ফন্ট ছোট করুন">
                            <span class="text-white font-bold text-lg">A−</span>
                        </button>

                        {{-- Font Size Increase --}}
                        <button onclick="increaseFontSize()"
                            class="w-10 h-10 rounded-full bg-orange-600 flex items-center justify-center hover:bg-orange-700 transition"
                            title="ফন্ট বড় করুন">
                            <span class="text-white font-bold text-xl">A+</span>
                        </button>
                    </div>
                </div>
            </aside>


            <!-- Main Content -->
            <article class="lg:col-span-7">
                <div class="bg-white shadow-sm">

                    <!-- Header -->
                    <div class="px-4 pt-2 pb-2">
                        <!-- Title -->
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $post->title }}</h1>




                    </div>

                    <!-- Featured Image -->
                    @if($post->media || $post->featured_image)
                    <div class="pb-4">
                        @if($post->media)
                        <img src="{{ $post->media->large_url }}"
                            alt="{{ $post->featured_image_alt ?? $post->title }}"
                            class="w-full ">
                        @elseif($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                            alt="{{ $post->featured_image_alt }}"
                            class="w-full ">
                        @endif
                    </div>
                    @endif

                    <!-- YouTube Video -->
                    @if($post->youtube_url && $post->youtube_video_id)
                    <div class="px-4 pb-4">
                        <div class="relative  overflow-hidden shadow-lg" style="padding-bottom: 56.25%; height: 0;">
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
                        <div id="article-content" class="prose prose-lg max-w-none">
                            {!! $post->content !!}
                        </div>
                    </div>

                    <!-- Tags -->
                    @if(\App\Models\SiteSetting::get('blog_show_tags', '1') === '1' && $post->tags->count() > 0)
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





                <!-- Comments Section -->
                @if(\App\Models\SiteSetting::get('blog_show_comments', '1') === '1' && $post->allow_comments)
                @livewire('blog.comment-section', ['post' => $post])
                @endif

                <!-- Old Comment Section (Backup) -->
                @if(false && $post->allow_comments)
                <div class="bg-white  shadow-sm mt-8 p-8" x-data="{ replyingTo: null }">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Comments ({{ $post->approvedComments->count() }})
                        </h2>
                    </div>

                    @if(session('comment_success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3  flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('comment_success') }}
                    </div>
                    @endif

                    <!-- Comment Form -->
                    <div class="mb-8 bg-gray-50  p-6">
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
                                            class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('guest_email') border-red-500 @enderror">
                                        @error('guest_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center gap-3 p-3 bg-white  border border-gray-200">
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
                                        class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                                    @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Your comment will be reviewed before being published.</p>
                                </div>

                                <div class="flex gap-2 items-center justify-between">
                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No comments yet</p>
                        <p class="text-gray-400 text-sm mt-1">Be the first to share your thoughts!</p>
                    </div>
                    @endif
                </div>
                @endif
            </article>



            <!-- Right Sidebar -  -->
            <div class="lg:col-span-3 space-y-8">
                {{-- Ad Placeholder --}}
                <div class="bg-gray-100 shadow-md text-center">
                    <div class="h-64 bg-white rounded flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-400">বিজ্ঞাপন ৩০০ × ২৫০</span>
                    </div>
                </div>

                <!-- Latest News Widget -->
                @if(isset($latestPosts) && $latestPosts->count() > 0)
                <div class="bg-white shadow-sm border border-gray-100 ">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
                            সর্বশেষ খবর
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100 overflow-y-auto" style="max-height: 400px;">
                        @foreach($latestPosts->take(10) as $latestPost)
                        <a href="{{ url($latestPost->slug) }}" class="group block p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex gap-4">
                                {{-- Image --}}
                                <div class="flex-shrink-0 relative overflow-hidden  w-24 bg-gray-100">
                                    @if($latestPost->media || $latestPost->featured_image)
                                    @if($latestPost->media)
                                    <img src="{{ $latestPost->media->small_url }}" alt="{{ $latestPost->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                    @elseif($latestPost->featured_image)
                                    <img src="{{ asset('storage/' . $latestPost->featured_image) }}" alt="{{ $latestPost->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="text-sm font-semibold text-gray-900 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2 mb-1.5">
                                        {{ $latestPost->title }}
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ bengali_date($latestPost->published_at, 'short_time') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Most Read Widget -->
                @if(isset($mostReadPosts) && $mostReadPosts->count() > 0)
                <div class="bg-white  shadow-sm border border-gray-100">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-1 h-5 bg-red-600 rounded-full"></span>
                            সর্বাধিক পঠিত
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100 overflow-y-auto" style="max-height: 400px;">
                        @foreach($mostReadPosts->take(10) as $index => $mostRead)
                        <a href="{{ url($mostRead->slug) }}" class="group block p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex gap-4 items-start">
                                {{-- Counter Badge --}}
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 text-xs font-bold group-hover:bg-red-100 group-hover:text-red-600 transition-colors mt-1">
                                    {{ bengali_number($index + 1) }}
                                </span>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 leading-snug group-hover:text-red-600 transition-colors line-clamp-2 mb-1.5">
                                        {{ $mostRead->title }}
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ bengali_number(number_format($mostRead->views_count)) }} বার পড়া হয়েছে
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>


        </div>

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
        <div class=" mt-8 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6"> আরো পড়ুন </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($relatedPosts as $related)
                <article class="border border-gray-200 bg-white shadow-sm  overflow-hidden hover:shadow-lg transition duration-300">
                    @if($related->media || $related->featured_image)
                    @if($related->media)
                    <img src="{{ $related->media->medium_url }}"
                        alt="{{ $related->title }}"
                        class="w-full h-40 object-cover">
                    @elseif($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                        alt="{{ $related->title }}"
                        class="w-full h-40 object-cover">
                    @endif
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2 hover:text-blue-600">
                            <a href="{{ route('products.show', $related->slug) }}">
                                {{ Str::limit($related->title, 50) }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500">{{ bengali_date($related->published_at, 'short_time') }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {

        /* Hide everything except article content */
        header,
        footer,
        aside,
        nav,
        .sidebar,
        .related-posts,
        .comments-section {
            display: none !important;
        }

        /* Show only the main article */
        #article-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
        }

        /* Ensure proper formatting for print */
        body {
            background: white !important;
            color: black !important;
        }

        /* Remove shadows and borders for print */
        * {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
    }
</style>

{{-- Font Size and Print JavaScript --}}
<script>
    // Font size control
    let currentFontSize = 16; // Default font size in pixels
    const minFontSize = 12;
    const maxFontSize = 50;
    const fontSizeStep = 2;

    function increaseFontSize() {
        if (currentFontSize < maxFontSize) {
            currentFontSize += fontSizeStep;
            applyFontSize();
        }
    }

    function decreaseFontSize() {
        if (currentFontSize > minFontSize) {
            currentFontSize -= fontSizeStep;
            applyFontSize();
        }
    }

    function applyFontSize() {
        const articleContent = document.getElementById('article-content');
        if (articleContent) {
            // Apply font size directly to the article content container
            articleContent.style.fontSize = currentFontSize + 'px';
            articleContent.style.transition = 'font-size 0.3s ease';

            // Save preference to localStorage
            localStorage.setItem('articleFontSize', currentFontSize);

            console.log('Font size applied:', currentFontSize + 'px');
        } else {
            console.error('Article content element not found');
        }
    }

    // Print function
    function printArticle() {
        window.print();
    }

    // Load saved font size on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedFontSize = localStorage.getItem('articleFontSize');
        if (savedFontSize) {
            currentFontSize = parseInt(savedFontSize);
            applyFontSize();
        }
    });
</script>

@endsection