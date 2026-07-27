@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-bg-primary py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo/logo.png') }}" alt="Raangalay" class="h-12 mx-auto">
            <h2 class="mt-4 text-3xl font-bold text-secondary">Create Account</h2>
            <p class="text-gray-600 mt-2">Join Raangalay today</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="fullName" class="block text-sm font-medium text-secondary mb-2">Full Name</label>
                    <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your full name">
                    @error('fullName')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email/Phone -->
                <div>
                    <label for="identifier" class="block text-sm font-medium text-secondary mb-2">Email or Phone</label>
                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter your email or phone number">
                    @error('identifier')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-secondary mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Create a password (min 6 characters)">
                    @error('password')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-secondary mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Confirm your password">
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" id="terms" required class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 block text-sm text-gray-600">
                        I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition font-medium text-lg">
                    Create Account
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

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Login here</a>
            </p>
        </div>
    </div>
</div>
@endsection
