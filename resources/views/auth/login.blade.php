@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-bg-primary py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo/logo.png') }}" alt="Raangalay" class="h-12 mx-auto">
            <h2 class="mt-4 text-3xl font-bold text-secondary">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Login to your account</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email/Phone -->
                <div>
                    <label for="identifier" class="block text-sm font-medium text-secondary mb-2">Email or Phone</label>
                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your email or phone">
                    @error('identifier')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-secondary mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-600">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition font-medium text-lg">
                    Login
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or</span>
                </div>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary hover:underline font-medium">Register now</a>
            </p>
        </div>

        <!-- Benefits -->
        <div class="mt-8 grid grid-cols-3 gap-4 text-center">
            <div>
                <svg class="w-8 h-8 mx-auto text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-xs text-gray-600">Easy Checkout</p>
            </div>
            <div>
                <svg class="w-8 h-8 mx-auto text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs text-gray-600">Order Tracking</p>
            </div>
            <div>
                <svg class="w-8 h-8 mx-auto text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <p class="text-xs text-gray-600">Wishlist</p>
            </div>
        </div>
    </div>
</div>
@endsection
