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

                <!-- Right Side - Why iHerb -->
                <div class="bg-gray-50 p-8 md:p-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Why iHerb?</h3>
                    
                    <div class="space-y-5">
                        <!-- Feature 1 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    Guaranteed freshness through our commitment to Good Manufacturing Practices (GMP).
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    Genuine reviews only from verified customers
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    No third-party sales. Direct from suppliers and authorised distributors
                                </p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    Independent lab testing on iHerb's House Brands
                                </p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    Expiration dates on product descriptions
                                </p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-700">
                                    24/7 customer support. Easy returns and refunds.
                                </p>
                            </div>
                        </div>

                        <!-- Reviews -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <span class="text-2xl font-bold text-gray-900">4.8</span>
                                            <div class="ml-2 flex">
                                                @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">iHerb</p>
                                        <p class="text-xs text-gray-500">Store Reviews</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500 px-4">
            <p>
                © Copyright 1997-2025 iHerb, LLC. All rights reserved. iHerb® is a registered trade mark of iHerb, LLC. Trusted Brands. Healthy Rewards.
                and the iHerb.com Trusted Brands. Healthy Rewards. Logo are trade marks of iHerb, LLC.*Disclaimer:Statements made, or products sold
                through this website, have not been evaluated by the United States Food and Drug Administration.They are not intended to diagnose,
                treat, cure or prevent any disease.
                <a href="#" class="text-blue-600 hover:underline">Read More »</a>
            </p>
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
