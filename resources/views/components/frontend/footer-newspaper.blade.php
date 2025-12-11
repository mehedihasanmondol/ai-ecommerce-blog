{{-- Newspaper Footer Component --}}
<footer class="bg-gray-100  mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- First Column - Site Logo --}}
            <div>
                @if($logo = \App\Models\SiteSetting::get('site_logo'))
                <img src="{{ asset('storage/' . $logo) }}"
                    alt="{{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}"
                    class="h-18 mb-4">
                @else
                <h3 class="text-2xl font-bold mb-4">
                    {{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}
                </h3>
                @endif

            </div>

            {{-- Second Column - Custom Content from Settings --}}
            <div class="text-sm">
                {!! \App\Models\FooterSetting::get('newspaper_second_column', '<h3 class="text-lg font-bold mb-4">About Us</h3>
                <p class="text-gray-400">Your trusted source for the latest news and updates.</p>') !!}
            </div>

            {{-- Third Column - Custom Content from Settings --}}
            <div class="text-sm">
                {!! \App\Models\FooterSetting::get('newspaper_third_column', '<h3 class="text-lg font-bold mb-4">Contact</h3>
                <p class="text-gray-400">Email: info@example.com<br>Phone: +123 456 7890</p>') !!}
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-200 mt-8 pt-6 text-center text-sm text-gray-400">
            {!! \App\Models\FooterSetting::get('copyright_text', '&copy; ' . date('Y') . ' ' . \App\Models\SiteSetting::get('site_name', config('app.name')) . '. All rights reserved.') !!}
        </div>
    </div>
</footer>