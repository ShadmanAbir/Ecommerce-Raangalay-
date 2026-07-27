<!-- Editorial Category Grid - Fashion/Lifestyle Style -->
@php
    $categories = \App\Models\Category::active()->parents()->with('children')->get();
    $totalCategories = $categories->count();
@endphp

@if($totalCategories > 0)
<section class="mb-24">
    <!-- Section Header -->
    <div class="container mx-auto px-4 mb-10">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-primary text-xs font-semibold uppercase tracking-[0.4em] mb-2">Our Collections</p>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary">Explore Categories</h2>
            </div>
            <a href="#" class="hidden md:flex items-center gap-2 text-sm font-medium text-secondary hover:text-primary transition group">
                View All
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Editorial Grid Layout -->
    <div class="grid grid-cols-6 auto-rows-[180px] gap-2 w-full">
        @foreach($categories as $index => $category)
            @php
                // Editorial layout pattern (repeats every 7 items)
                $pos = $index % 7;
                $spanClass = match($pos) {
                    0 => 'col-span-4 row-span-2', // Hero featured
                    1 => 'col-span-2 row-span-1', // Side top
                    2 => 'col-span-2 row-span-1', // Side bottom
                    3 => 'col-span-2 row-span-1', // Bottom left
                    4 => 'col-span-2 row-span-1', // Bottom middle
                    5 => 'col-span-2 row-span-1', // Bottom right
                    6 => 'col-span-6 row-span-1', // Full width banner
                    default => 'col-span-2 row-span-1',
                };
                
                // Different text positioning based on card size
                $isLarge = in_array($pos, [0, 6]);
                $textPosition = $isLarge ? 'items-center justify-center text-center' : 'items-start justify-end text-left';
                $titleSize = $pos === 0 ? 'text-3xl md:text-4xl' : ($pos === 6 ? 'text-2xl md:text-3xl' : 'text-lg md:text-xl');
            @endphp
        <a href="{{ route('category.show', $category->slug) }}" 
           class="{{ $spanClass }} group relative overflow-hidden block bg-gray-100">
            <!-- Background Image -->
            @if($category->cat_image)
                <img src="{{ storage_asset($category->cat_image) }}" 
                     alt="{{ $category->name }}" 
                     class="w-full h-full object-cover absolute inset-0 group-hover:scale-105 transition-transform duration-700 ease-out">
            @else
                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 absolute inset-0"></div>
            @endif
            
            <!-- Overlay - lighter for fashion feel -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent group-hover:from-black/80 transition-all duration-500"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex flex-col {{ $textPosition }} p-5 md:p-8">
                <div class="transform translate-y-0 group-hover:translate-y-0 transition-transform duration-500">
                    <!-- Category Name -->
                    <h3 class="text-white {{ $titleSize }} font-bold mb-1 drop-shadow-lg tracking-tight">{{ $category->name }}</h3>
                    
                    <!-- Collection Count -->
                    @if($category->children->count() > 0)
                        <p class="text-white/70 text-xs md:text-sm mb-3 font-light">{{ $category->children->count() }} pieces</p>
                    @endif
                    
                    <!-- CTA - appears on hover -->
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="text-white text-xs font-medium uppercase tracking-[0.2em]">Discover</span>
                        <svg class="w-4 h-4 text-white transform translate-x-0 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Subtle border on hover -->
            <div class="absolute inset-0 border border-white/0 group-hover:border-white/20 transition-all duration-300 pointer-events-none"></div>
        </a>
        @endforeach
    </div>
</section>
@endif
