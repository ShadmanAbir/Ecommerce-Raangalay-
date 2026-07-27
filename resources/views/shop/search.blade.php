@extends('layouts.app')

@section('title', 'Search Results - ' . ($keyword ?? ''))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-secondary mb-2">Search Results</h1>
        @if($keyword)
        <p class="text-gray-600">Showing results for "<span class="font-semibold">{{ $keyword }}</span>"</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h3 class="text-lg font-bold text-secondary mb-4">Categories</h3>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}" class="text-gray-600 hover:text-primary transition">
                            {{ $category->name }}
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-secondary mb-2">No products found</h3>
                <p class="text-gray-600">Try a different search term or browse our categories</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
