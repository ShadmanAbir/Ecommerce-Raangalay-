<!-- Top Info Bar (Red Theme) -->
<div class="bg-primary text-white py-2 text-sm relative z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-xs md:text-sm font-light tracking-wide">Welcome to Raangalay Online Store</span>
                <span class="hidden md:inline text-white/60">|</span>
                <a href="#" class="hidden md:flex items-center space-x-1 hover:text-white transition text-xs md:text-sm font-light">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span>Track Order</span>
                </a>
                <span class="hidden md:inline text-white/60">|</span>
                <a href="mailto:info@raangalay.com" class="hidden md:flex items-center space-x-1 hover:text-white transition text-xs md:text-sm font-light">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>info@raangalay.com</span>
                </a>
            </div>
            <div class="flex items-center">
                <!-- Language Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 bg-white/20 text-white px-4 py-1.5 rounded hover:bg-white/30 transition-all duration-300 text-xs font-medium">
                        <span>🇬🇧 EN</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-36 bg-white rounded shadow-lg overflow-hidden z-[100]">
                        <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition border-b border-gray-100">
                            <span class="mr-2 text-lg">🇬🇧</span> <span>English</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">
                            <span class="mr-2 text-lg">🇧🇩</span> <span>বাংলা</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Raangalay" class="h-8 md:h-10">
                </a>
            </div>

            <!-- Search Bar with Category Dropdown (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-3xl mx-8">
                <form action="{{ route('product.search') }}" method="GET" class="w-full">
                    <div class="flex h-11">
                        <!-- Category Dropdown (Left Side) -->
                        @php
                            $categories = \App\Models\Category::active()->parents()->with('children')->get();
                        @endphp
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="flex items-center space-x-2 h-full px-4 bg-gray-50 border border-gray-300 border-r-0 rounded-l-lg hover:bg-gray-100 transition focus:outline-none">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="text-gray-700 text-sm font-medium whitespace-nowrap">All Categories</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Category Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute top-full left-0 mt-2 w-72 bg-white rounded-xl shadow-2xl z-50 border border-gray-200 max-h-[500px] overflow-y-auto">
                                <div class="py-2">
                                    <div class="px-4 py-3 bg-gradient-to-r from-primary to-primary/90 text-white mb-2">
                                        <p class="text-xs font-medium opacity-90">BROWSE</p>
                                        <p class="text-sm font-bold">All Categories</p>
                                    </div>
                                    <a href="{{ route('product.search', ['q' => '']) }}" class="flex items-center space-x-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-b border-gray-100 mb-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                        </svg>
                                        <span class="font-semibold">View All Products</span>
                                    </a>
                                    @foreach($categories as $category)
                                        <div x-data="{ expanded: false }">
                                            <a href="{{ route('category.show', $category->slug) }}" 
                                               class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition group">
                                                <span class="font-medium group-hover:translate-x-1 transition-transform">{{ $category->name }}</span>
                                                @if($category->children->count() > 0)
                                                    <button type="button" @click.prevent.stop="expanded = !expanded" 
                                                            class="p-1 rounded hover:bg-gray-200 transition">
                                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" 
                                                             :class="{ 'rotate-90': expanded }"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </a>
                                            @if($category->children->count() > 0)
                                                <div x-show="expanded" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     class="bg-gray-50 border-l-2 border-gray-200 ml-2">
                                                    @foreach($category->children as $child)
                                                        <a href="{{ route('category.show', $child->slug) }}" 
                                                           class="block px-8 py-2 text-sm text-gray-600 hover:bg-white hover:text-primary transition">
                                                            {{ $child->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <input type="text" name="q" 
                               placeholder="Search for products..." 
                               class="flex-1 px-4 border-y border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition text-sm"
                               value="{{ request('q') }}">
                        <button type="submit" class="px-6 bg-primary text-white rounded-r-lg hover:bg-primary/90 transition flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-medium hidden lg:inline">Search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-4">
                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        @auth
                            {{ auth()->user()->carts()->count() }}
                        @else
                            00
                        @endauth
                    </span>
                </a>

                <!-- Wishlist -->
                <a href="{{ auth()->check() ? route('user.wishlist') : route('login') }}" class="hidden md:block relative p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ auth()->check() ? auth()->user()->wishlists()->count() : '00' }}
                    </span>
                </a>

                <!-- User Menu -->
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-full transition">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name }}</span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('user.orders') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-danger">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="hidden md:flex flex-col items-start">
                    <span class="text-xs text-gray-500">Welcome</span>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-secondary hover:text-primary transition">Sign in / Register</a>
                </div>
                <a href="{{ route('login') }}" class="md:hidden px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    Login
                </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden pb-4">
            <form action="{{ route('product.search') }}" method="GET">
                <input type="text" name="q" 
                       placeholder="Search products..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                       value="{{ request('q') }}">
            </form>
        </div>
    </div>
</header>
