@props(['settings'])

@if($settings->enabled && $settings->news_text)
<div class="w-full" style="background-color: {{ $settings->bg_color }};">
    <div class="container mx-auto px-4 overflow-hidden my-2">
        <div class="flex items-center">
            {{-- Label Section --}}
            <div class="flex-shrink-0 px-6 py-3 font-bold text-white"
                style="background-color: {{ $settings->label_bg_color }};">
                {{ $settings->label }}
            </div>

            {{-- Scrolling News Text --}}
            <div class="flex-1 overflow-hidden py-3 px-4 pl-0">
                @if($settings->auto_scroll)
                <div class="marquee">
                    @if($settings->link_url)
                    <a href="{{ $settings->link_url }}" class="inline-block font-medium hover:underline"
                        style="color: {{ $settings->text_color }}; animation-duration: {{ 100 / $settings->scroll_speed }}s;">
                        {{ $settings->news_text }}
                    </a>
                    @else
                    <span class="inline-block font-medium"
                        style="color: {{ $settings->text_color }}; animation-duration: {{ 100 / $settings->scroll_speed }}s;">
                        {{ $settings->news_text }}
                    </span>
                    @endif
                </div>
                @else
                @if($settings->link_url)
                <a href="{{ $settings->link_url }}" class="block font-medium hover:underline truncate"
                    style="color: {{ $settings->text_color }};">
                    {{ $settings->news_text }}
                </a>
                @else
                <p class="font-medium truncate" style="color: {{ $settings->text_color }};">
                    {{ $settings->news_text }}
                </p>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

@if($settings->auto_scroll)
<style>
    .marquee {
        display: flex;
        overflow: hidden;
        white-space: nowrap;
    }

    .marquee span,
    .marquee a {
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
@endif
@endif