<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Favicon -->
    @php
    $favicon = \App\Models\SiteSetting::get('site_favicon');
    @endphp
    @if($favicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    @stack('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Typography CSS Variables -->
    @php
    echo '<style>
        ' . \App\Models\SiteSetting::getTypographyCssVariables() . '
    </style>';
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white">
        {{-- Dynamic Header --}}
        @php
        $headerType = \App\Models\SiteSetting::get('frontend_header_type', 'default');
        @endphp
        <x-dynamic-component :component="'frontend.header-' . $headerType" />

        {{-- Headline Banner (Breaking News) --}}
        @if(isset($headlineBanner))
        <x-blog.headline-banner :settings="$headlineBanner" />
        @endif

        {{-- Main Content --}}
        @yield('content')
    </div>

    {{-- Dynamic Footer --}}
    @php
    $footerType = \App\Models\SiteSetting::get('frontend_footer_type', 'default');
    @endphp
    <x-dynamic-component :component="'frontend.footer-' . $footerType" />

    @livewireScripts
    @stack('scripts')
</body>

</html>