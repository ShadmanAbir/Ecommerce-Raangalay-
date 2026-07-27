@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary">Home</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-secondary font-medium">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-secondary mb-2">{{ $category->name }}</h1>
        @if($category->description)
        <p class="text-gray-600">{{ $category->description }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h3 class="text-lg font-bold text-secondary mb-4">Subcategories</h3>
                @if($category->children->count() > 0)
                <ul class="space-y-2">
                    @foreach($category->children as $child)
                    <li>
                        <a href="{{ route('category.show', $child->slug) }}" class="text-gray-600 hover:text-primary transition">
                            {{ $child->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-500 text-sm">No subcategories</p>
                @endif

                <hr class="my-6">

                <h3 class="text-lg font-bold text-secondary mb-4">All Categories</h3>
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="text-gray-600 hover:text-primary transition {{ $cat->id === $category->id ? 'font-semibold text-primary' : '' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($products as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-secondary mb-2">No products in this category</h3>
                <p class="text-gray-600">Check back later or browse other categories</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
