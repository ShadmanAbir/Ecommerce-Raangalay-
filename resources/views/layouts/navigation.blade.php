<nav class="bg-secondary text-white" x-data="{ mobileMenu: false }">
    <div class="container mx-auto px-4">
        <div class="hidden md:flex items-center justify-between h-12">
            <!-- Categories Dropdown -->
            @php
                $categories = \App\Models\Category::active()->parents()->with('children')->get();
            @endphp
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span>All Categories</span>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-1 w-64 bg-white text-gray-900 rounded-lg shadow-lg z-50">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="block px-4 py-2 hover:bg-gray-100">
                            {{ $category->name }}
                        </a>
                        @if($category->children->count() > 0)
                            <div class="pl-8">
                                @foreach($category->children as $child)
                                    <a href="{{ route('category.show', $child->slug) }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
                <a href="{{ route('product.search', ['q' => '']) }}" class="hover:text-primary transition">All Products</a>
                <a href="{{ route('about') }}" class="hover:text-primary transition">About</a>
                <a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a>
            </div>
        </div>
    </div>
</nav>
