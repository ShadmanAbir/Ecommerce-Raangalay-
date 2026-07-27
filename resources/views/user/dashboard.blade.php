@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-secondary mb-8">My Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('user.orders') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition">
            <h3 class="text-xl font-bold text-secondary">My Orders</h3>
            <p class="text-gray-600 mt-2">View and track your orders</p>
        </a>
        <a href="{{ route('user.profile') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition">
            <h3 class="text-xl font-bold text-secondary">Profile</h3>
            <p class="text-gray-600 mt-2">Manage your account</p>
        </a>
        <a href="{{ route('user.wishlist') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition">
            <h3 class="text-xl font-bold text-secondary">Wishlist</h3>
            <p class="text-gray-600 mt-2">Your saved items</p>
        </a>
    </div>
</div>
@endsection
