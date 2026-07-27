@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-secondary mb-6">About Us</h1>
        <div class="bg-white rounded-lg shadow-md p-8">
            <p class="text-gray-600 mb-4">
                Welcome to Raangalay - your trusted online shopping destination!
            </p>
            <p class="text-gray-600 mb-4">
                This page is currently under construction. We're working hard to bring you a complete shopping experience.
            </p>
            <a href="{{ route('home') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
