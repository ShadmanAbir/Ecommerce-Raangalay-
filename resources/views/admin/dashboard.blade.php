@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Products -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Products</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</p>
                <p class="text-green-600 text-sm mt-1">{{ $stats['active_products'] }} active</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Categories</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_categories'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
                <p class="text-orange-600 text-sm mt-1">{{ $stats['pending_orders'] }} pending</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
        </div>
        <div class="p-6">
            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders->take(5) as $order)
                        <div class="flex justify-between items-center pb-4 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">৳{{ number_format($order->total_amount, 2) }}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No orders yet</p>
            @endif
        </div>
    </div>

    <!-- Recent Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Recent Products</h3>
        </div>
        <div class="p-6">
            @if($recentProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProducts->take(5) as $product)
                        <div class="flex justify-between items-center pb-4 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No category' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">৳{{ number_format($product->price, 2) }}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    @if($product->status == 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No products yet</p>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white rounded-lg shadow p-6 hover:bg-blue-700 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Add Product</h3>
                <p class="text-blue-100 text-sm mt-1">Create a new product</p>
            </div>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
    </a>

    <a href="{{ route('admin.categories.create') }}" class="bg-green-600 text-white rounded-lg shadow p-6 hover:bg-green-700 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Add Category</h3>
                <p class="text-green-100 text-sm mt-1">Create a new category</p>
            </div>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
    </a>

    <a href="{{ route('admin.banners.create') }}" class="bg-purple-600 text-white rounded-lg shadow p-6 hover:bg-purple-700 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Add Banner</h3>
                <p class="text-purple-100 text-sm mt-1">Create a new banner</p>
            </div>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
    </a>
</div>
@endsection
