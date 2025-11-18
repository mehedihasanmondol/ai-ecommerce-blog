@extends('layouts.app')

@section('title', 'Sign in or create an account')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-6xl">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-green-600">iHerb</h1>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Side - Login Form -->
                <div class="p-8 md:p-12">
                    <!-- Cancel Link -->
                    <div class="mb-6">
                        <a href="/" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Cancel</a>
                    </div>

                    <!-- Title -->
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Sign in or create an account</h2>
                    <p class="text-gray-600 text-sm mb-8">
                        Enter your email or mobile number to get started. If you already have an account, we'll find it for you.
                    </p>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email or Mobile Input -->
                        <div>
                            <input 
                                type="text" 
                                name="email_or_mobile" 
                                id="email_or_mobile"
                                value="{{ old('email_or_mobile') }}"
                                placeholder="Email or mobile number"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email_or_mobile') border-red-500 @enderror"
                                required
                                autofocus
                            >
                            @error('email_or_mobile')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Input (Initially Hidden) -->
                        <div id="password-field" class="hidden">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                placeholder="Password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Continue Button -->
                        <button 
                            type="submit"
                            class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 px-4 rounded-md transition duration-200"
                        >
                            Continue
                        </button>

                        <!-- Need Help Link -->
                        <div class="text-center">
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Need help?
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">or</span>
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="space-y-3">
                            <!-- Google -->
                            <button 
                                type="button"
                                class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-200"
                            >
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Sign in with Google</span>
                            </button>

                            <!-- Facebook -->
                            <button 
                                type="button"
                                class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-200"
                            >
                                <svg class="w-5 h-5 mr-3" fill="#1877F2" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Sign in with Facebook</span>
                            </button>

                            <!-- Apple -->
                            <button 
                                type="button"
                                class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-200"
                            >
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Sign in with Apple</span>
                            </button>
                        </div>

                        <!-- Keep Me Signed In -->
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="keep_signed_in" 
                                id="keep_signed_in"
                                value="1"
                                {{ old('keep_signed_in') ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                            >
                            <label for="keep_signed_in" class="ml-2 text-sm text-gray-700 flex items-center">
                                Keep me signed in
                                <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </label>
                        </div>

                        <!-- Terms and Conditions -->
                        <p class="text-xs text-gray-500">
                            By continuing, you've read and agree to our 
                            <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> 
                            and 
                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                        </p>
                    </form>
                </div>

                <!-- Right Side - Dynamic Content -->
                <div class="bg-gray-50 p-8 md:p-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">{{ \App\Models\SiteSetting::get('login_page_title', 'Why iHerb?') }}</h3>
                    
                    {!! \App\Models\SiteSetting::get('login_page_content', '<p class="text-gray-600">Welcome to our store!</p>') !!}
                </div>
            </div>
        </div>

       
    </div>
</div>

@push('scripts')
<script>
    // Show password field when user starts typing
    const emailOrMobile = document.getElementById('email_or_mobile');
    const passwordField = document.getElementById('password-field');
    const passwordInput = document.getElementById('password');

    emailOrMobile.addEventListener('input', function() {
        if (this.value.length > 0) {
            passwordField.classList.remove('hidden');
            passwordInput.setAttribute('required', 'required');
        } else {
            passwordField.classList.add('hidden');
            passwordInput.removeAttribute('required');
        }
    });

    // Show password field if there's an error
    @if($errors->any())
        passwordField.classList.remove('hidden');
        passwordInput.setAttribute('required', 'required');
    @endif
</script>
@endpush
@endsection
