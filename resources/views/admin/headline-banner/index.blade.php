@extends('layouts.admin')

@section('title', 'শিরোনাম ব্যানার সেটিংস')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{
    enabled: {{ $settings->enabled ? 'true' : 'false' }},
    label: '{{ $settings->label }}',
    newsText: '{{ $settings->news_text }}',
    bgColor: '{{ $settings->bg_color ?? '#f1f2f4' }}',
    textColor: '{{ $settings->text_color ?? '#000000' }}',
    labelBgColor: '{{ $settings->label_bg_color ?? '#C41E3A' }}',
    autoScroll: {{ $settings->auto_scroll ? 'true' : 'false' }},
    scrollSpeed: {{ $settings->scroll_speed ?? 50 }},
    linkUrl: '{{ $settings->link_url ?? '' }}'
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">শিরোনাম ব্যানার সেটিংস</h1>

        @if(auth()->user()->hasPermission('headline-banner.edit'))
        <button
            onclick="toggleBanner()"
            id="toggleBtn"
            class="px-4 py-2 rounded-lg transition-colors {{ $settings->enabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white">
            {{ $settings->enabled ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Settings Form --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">ব্যানার কনফিগারেশন</h2>

            <form action="{{ route('admin.blog.headline-banner.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Enable/Disable --}}
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="enabled" value="1"
                            x-model="enabled"
                            {{ $settings->enabled ? 'checked' : '' }}
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                            {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'disabled' : '' }}>
                        <span class="ml-2 text-gray-700">ব্যানার সক্রিয় করুন</span>
                    </label>
                </div>

                {{-- Label --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        লেবেল টেক্সট
                    </label>
                    <input type="text" name="label"
                        x-model="label"
                        value="{{ old('label', $settings->label) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                        {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'readonly' : '' }}>
                    @error('label')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- News Text --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        সংবাদ টেক্সট
                    </label>
                    <textarea name="news_text" rows="3"
                        x-model="newsText"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                        {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'readonly' : '' }}>{{ old('news_text', $settings->news_text) }}</textarea>
                    @error('news_text')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Colors --}}
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ব্যাকগ্রাউন্ড
                        </label>
                        <input type="color" name="bg_color"
                            x-model="bgColor"
                            :value="bgColor"
                            class="w-full h-10 rounded border border-gray-300"
                            {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            টেক্সট কালার
                        </label>
                        <input type="color" name="text_color"
                            x-model="textColor"
                            :value="textColor"
                            class="w-full h-10 rounded border border-gray-300"
                            {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            লেবেল কালার
                        </label>
                        <input type="color" name="label_bg_color"
                            x-model="labelBgColor"
                            :value="labelBgColor"
                            class="w-full h-10 rounded border border-gray-300"
                            {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'disabled' : '' }}>
                    </div>
                </div>

                {{-- Auto Scroll --}}
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="auto_scroll" value="1"
                            x-model="autoScroll"
                            {{ $settings->auto_scroll ? 'checked' : '' }}
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                            {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'disabled' : '' }}>
                        <span class="ml-2 text-gray-700">অটো স্ক্রল সক্রিয় করুন</span>
                    </label>
                </div>

                {{-- Scroll Speed --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        স্ক্রল স্পিড (পিক্সেল/সেকেন্ড)
                    </label>
                    <input type="number" name="scroll_speed"
                        x-model="scrollSpeed"
                        value="{{ old('scroll_speed', $settings->scroll_speed) }}"
                        min="5" max="200"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                        {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'readonly' : '' }}>
                    @error('scroll_speed')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Link URL --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        লিংক URL (ঐচ্ছিক)
                    </label>
                    <input type="url" name="link_url"
                        x-model="linkUrl"
                        value="{{ old('link_url', $settings->link_url) }}"
                        placeholder="https://example.com"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                        {{ !auth()->user()->hasPermission('headline-banner.edit') ? 'readonly' : '' }}>
                    @error('link_url')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if(auth()->user()->hasPermission('headline-banner.edit'))
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    সংরক্ষণ করুন
                </button>
                @endif
            </form>
        </div>

        {{-- Live Preview --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">লাইভ প্রিভিউ</h2>

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                {{-- Live Preview Banner --}}
                <div x-show="enabled" class="w-full overflow-hidden" :style="`background-color: ${bgColor};`">
                    <div class="flex items-center">
                        {{-- Label Section --}}
                        <div class="flex-shrink-0 px-6 py-3 font-bold text-white"
                            :style="`background-color: ${labelBgColor};`">
                            <span x-text="label"></span>
                        </div>

                        {{-- Scrolling News Text --}}
                        <div class="flex-1 overflow-hidden py-3 px-4">
                            <template x-if="autoScroll">
                                <div class="marquee">
                                    <span class="inline-block"
                                        :style="`color: ${textColor}; animation-duration: ${100 / scrollSpeed}s;`"
                                        x-text="newsText"></span>
                                </div>
                            </template>
                            <template x-if="!autoScroll">
                                <p class="font-medium truncate"
                                    :style="`color: ${textColor};`"
                                    x-text="newsText"></p>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-show="!enabled" class="p-4 text-center text-gray-500">
                    ব্যানার নিষ্ক্রিয় আছে
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <p class="mb-2"><strong>নোট:</strong></p>
                <ul class="list-disc list-inside space-y-1">
                    <li>ব্যানারটি নিউজপেপার হোমপেজের হেডারের নিচে প্রদর্শিত হবে</li>
                    <li>স্ক্রল স্পিড কম হলে স্ক্রলিং ধীর হবে</li>
                    <li>লিংক URL দিলে ব্যানারে ক্লিক করা যাবে</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .marquee {
        display: flex;
        overflow: hidden;
        white-space: nowrap;
    }

    .marquee span {
        display: inline-block;
        padding-left: 100%;
        animation: marquee linear infinite;
    }

    @keyframes marquee {
        0% {
            transform: translate(0, 0);
        }

        100% {
            transform: translate(-100%, 0);
        }
    }
</style>

@if(auth()->user()->hasPermission('headline-banner.edit'))
<script>
    function toggleBanner() {
        fetch('{{ route('
                admin.blog.headline - banner.toggle ') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById('toggleBtn');
                    if (data.enabled) {
                        btn.textContent = 'নিষ্ক্রিয় করুন';
                        btn.className = 'px-4 py-2 rounded-lg transition-colors bg-red-600 hover:bg-red-700 text-white';
                    } else {
                        btn.textContent = 'সক্রিয় করুন';
                        btn.className = 'px-4 py-2 rounded-lg transition-colors bg-green-600 hover:bg-green-700 text-white';
                    }
                    location.reload();
                }
            });
    }
</script>
@endif
@endsection