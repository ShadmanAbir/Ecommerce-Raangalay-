@extends('layouts.app')

@section('title', $viewModel->product->name)

@section('content')
<div class="min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-100">
        <div class="container mx-auto px-4 py-3">
            <nav class="text-sm flex items-center space-x-2">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-primary transition">Home</a>
                <span class="text-gray-300">/</span>
                <a href="{{ route('category.show', $viewModel->product->category->slug) }}" class="text-gray-400 hover:text-primary transition">{{ $viewModel->product->category->name }}</a>
                <span class="text-gray-300">/</span>
                <span class="text-secondary font-medium truncate">{{ $viewModel->product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="container mx-auto px-4 py-10 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">

            <!-- Product Images -->
            <div x-data="{ currentImage: 0 }">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-4 relative">
                    @if($viewModel->product->images->count() > 0)
                        @foreach($viewModel->product->images as $index => $image)
                        <div x-show="currentImage === {{ $index }}" 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0" 
                             x-transition:enter-end="opacity-100"
                             class="absolute inset-0">
                            <img src="{{ storage_asset($image->image_path) }}" 
                                 alt="{{ $viewModel->product->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    @else
                        <img src="{{ asset('images/placeholder/product_placeholder.jpg') }}" 
                             alt="{{ $viewModel->product->name }}" 
                             class="w-full h-full object-cover">
                    @endif

                    <!-- Badges -->
                    <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                        @if($viewModel->product->discount > 0)
                        <span class="bg-danger text-white px-3 py-1 rounded text-xs font-bold">-{{ number_format($viewModel->product->discount, 0) }}%</span>
                        @endif
                        @if($viewModel->product->isNew())
                        <span class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold">NEW</span>
                        @endif
                    </div>
                </div>
                
                <!-- Thumbnails -->
                @if($viewModel->product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($viewModel->product->images as $index => $image)
                    <button @click="currentImage = {{ $index }}" 
                            class="aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200 hover:opacity-80"
                            :class="currentImage === {{ $index }} ? 'border-primary ring-2 ring-primary/20' : 'border-gray-200'">
                        <img src="{{ storage_asset($image->image_path) }}" alt="{{ $viewModel->product->name }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <!-- Category -->
                <a href="{{ route('category.show', $viewModel->product->category->slug) }}" 
                   class="text-xs font-semibold text-primary uppercase tracking-widest hover:underline mb-3">
                    {{ $viewModel->product->category->name }}
                </a>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary mb-4 leading-tight">{{ $viewModel->product->name }}</h1>

                <!-- SKU & Stock -->
                <div class="flex items-center gap-4 mb-6 text-sm">
                    @if($viewModel->product->sku)
                    <span class="text-gray-400">SKU: <span class="text-gray-600 font-medium">{{ $viewModel->product->sku }}</span></span>
                    @endif
                    @if($viewModel->product->isInStock())
                    <span class="flex items-center gap-1.5 text-green-600">
                        <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                        In Stock
                    </span>
                    @else
                    <span class="flex items-center gap-1.5 text-red-500">
                        <span class="w-2 h-2 bg-red-500 rounded-full inline-block"></span>
                        Out of Stock
                    </span>
                    @endif
                </div>

                <!-- Price -->
                <div class="mb-8 pb-8 border-b border-gray-100">
                    @if($viewModel->product->discount > 0)
                        <div class="flex items-baseline gap-3">
                            <span class="text-4xl font-bold text-primary">৳{{ number_format($viewModel->product->getDiscountedPrice(), 2) }}</span>
                            <span class="text-xl text-gray-300 line-through">৳{{ number_format($viewModel->product->price, 2) }}</span>
                        </div>
                        <p class="text-sm text-green-600 mt-2">You save ৳{{ number_format($viewModel->product->price - $viewModel->product->getDiscountedPrice(), 2) }}</p>
                    @else
                        <span class="text-4xl font-bold text-primary">৳{{ number_format($viewModel->product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Description -->
                @if($viewModel->product->description)
                <div class="mb-8">
                    <p class="text-gray-600 leading-relaxed">{!! nl2br(e($viewModel->product->description)) !!}</p>
                </div>
                @endif

                <!-- Tags -->
                @if($viewModel->product->tags && count($viewModel->product->tags) > 0)
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($viewModel->product->tags as $tag)
                        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-medium">{{ trim($tag) }}</span>
                    @endforeach
                </div>
                @endif

                <!-- Add to Cart -->
                @if($viewModel->product->isInStock())
                <div class="space-y-4 mb-8" x-data="{ qty: 1, max: {{ $viewModel->product->totalStock() }} }">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-500">Qty:</span>
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <button @click="qty = Math.max(1, qty - 1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-primary transition font-bold">-</button>
                            <input type="number" x-model.number="qty" :max="max" min="1" class="w-14 h-10 text-center border-x border-gray-200 text-sm font-semibold focus:outline-none">
                            <button @click="qty = Math.min(max, qty + 1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-primary transition font-bold">+</button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="addToCartDetail()" 
                                class="flex-1 bg-primary text-white py-3.5 rounded-lg hover:bg-primary/90 transition-all duration-300 font-semibold text-base shadow-lg hover:shadow-xl">
                            Add to Cart
                        </button>
                        <button class="w-12 h-12 border border-gray-200 rounded-lg hover:border-primary hover:text-primary transition-all duration-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Meta Info -->
                <div class="border-t border-gray-100 pt-6 space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400">Category:</span>
                        <a href="{{ route('category.show', $viewModel->product->category->slug) }}" class="text-primary hover:underline">{{ $viewModel->product->category->name }}</a>
                    </div>
                    @if($viewModel->product->remarks)
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400">Remark:</span>
                        <span class="text-gray-600">{{ $viewModel->product->remarks }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($viewModel->relatedProducts->count() > 0)
        <section class="mt-20 lg:mt-28">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <p class="text-primary text-xs font-semibold uppercase tracking-[0.4em] mb-2">You May Also Like</p>
                    <h2 class="text-3xl font-bold text-secondary">Related Products</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($viewModel->relatedProducts as $relatedProduct)
                    @include('shop.partials.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCartDetail() {
    const qtyInput = document.querySelector('[x-model\\.number="qty"]');
    const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: {{ $viewModel->product->id }},
            quantity: qty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
@extends('layouts.app')

@section('title', $viewModel->product->name)

@section('content')
<div class="min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <nav class="text-sm">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary transition">Home</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('category.show', $viewModel->product->category->slug) }}" class="text-gray-500 hover:text-primary transition">{{ $viewModel->product->category->name }}</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-secondary font-medium">{{ $viewModel->product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Product Images -->
            <div x-data="{ currentImage: 0 }">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-4 shadow-lg">
                    @if($viewModel->product->images->count() > 0)
                        <template x-for="(image, index) in {{ $viewModel->product->images->toJson() }}" :key="index">
                            <div x-show="currentImage === index" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <img :src="'{{ asset('') }}' + image.image_path" alt="{{ $viewModel->product->name }}" class="w-full h-full object-cover">
                            </div>
                        </template>
                    @else
                        <img src="{{ asset('images/placeholder/product_placeholder.jpg') }}" alt="{{ $viewModel->product->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <!-- Thumbnail Images -->
                @if($viewModel->product->images->count() > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($viewModel->product->images as $index => $image)
                    <button @click="currentImage = {{ $index }}" 
                            class="aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200 hover:scale-105"
                            :class="currentImage === {{ $index }} ? 'border-primary shadow-md' : 'border-transparent'">
                        <img src="{{ storage_asset($image->image_path) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <!-- Category Badge -->
                <div class="mb-4">
                    <a href="{{ route('category.show', $viewModel->product->category->slug) }}" 
                       class="inline-block text-xs font-semibold text-primary uppercase tracking-wider hover:underline">
                        {{ $viewModel->product->category->name }}
                    </a>
                </div>

                <!-- Product Title -->
                <h1 class="text-4xl md:text-5xl font-bold text-secondary mb-4 leading-tight">{{ $viewModel->product->name }}</h1>
                
                <!-- Rating (if available) -->
                @if(isset($viewModel->product->rating))
                <div class="flex items-center space-x-2 mb-6">
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $viewModel->product->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">({{ $viewModel->product->reviews_count ?? 0 }} reviews)</span>
                </div>
                @else
                <div class="mb-6"></div>
                @endif

                <!-- Price -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    @if($viewModel->product->discount > 0)
                        <div class="flex items-baseline space-x-3">
                            <span class="text-5xl font-bold text-primary">৳{{ number_format($viewModel->product->getDiscountedPrice(), 2) }}</span>
                            <span class="text-2xl text-gray-400 line-through">৳{{ number_format($viewModel->product->price, 2) }}</span>
                            <span class="bg-danger text-white px-3 py-1 rounded-full text-sm font-bold">-{{ number_format($viewModel->product->discount, 0) }}%</span>
                        </div>
                        <p class="text-sm text-green-600 mt-2">You save ৳{{ number_format($viewModel->product->price - $viewModel->product->getDiscountedPrice(), 2) }}</p>
                    @else
                        <span class="text-5xl font-bold text-primary">৳{{ number_format($viewModel->product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Description -->
                @if($viewModel->product->description)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-secondary mb-3">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{!! nl2br(e($viewModel->product->description)) !!}</p>
                </div>
                @endif

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($viewModel->product->isInStock())
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-green-600 font-medium">In Stock</span>
                            <span class="text-sm text-gray-500">({{ $viewModel->product->totalStock() }} available)</span>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-red-600 font-medium">Out of Stock</span>
                        </div>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                @if($viewModel->product->isInStock())
                <form id="addToCartForm" class="space-y-6 mb-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $viewModel->product->id }}">
                    
                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-3">Quantity</label>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="updateQuantity(-1)" class="w-12 h-12 rounded-lg border-2 border-gray-300 hover:border-primary hover:bg-primary hover:text-white transition-all duration-200 flex items-center justify-center text-lg font-bold">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $viewModel->product->totalStock() }}" class="w-24 h-12 text-center border-2 border-gray-300 rounded-lg text-lg font-semibold focus:outline-none focus:border-primary">
                            <button type="button" onclick="updateQuantity(1)" class="w-12 h-12 rounded-lg border-2 border-gray-300 hover:border-primary hover:bg-primary hover:text-white transition-all duration-200 flex items-center justify-center text-lg font-bold">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-primary text-white py-4 rounded-lg hover:bg-primary/90 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl">
                            Add to Cart
                        </button>
                        <button type="button" class="w-14 h-14 border-2 border-gray-300 rounded-lg hover:border-primary hover:text-primary transition-all duration-200 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                @endif

                <!-- Product Meta -->
                <div class="border-t border-gray-200 pt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">SKU:</span>
                        <span class="text-secondary font-semibold">{{ $viewModel->product->sku ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Category:</span>
                        <a href="{{ route('category.show', $viewModel->product->category->slug) }}" class="text-primary hover:underline font-semibold">{{ $viewModel->product->category->name }}</a>
                    </div>
                    @if($viewModel->product->tags)
                    <div>
                        <span class="text-gray-500 font-medium">Tags:</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($viewModel->product->tags as $tag)
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium hover:bg-primary hover:text-white transition cursor-pointer">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-20">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8" x-data="{ activeTab: 'description' }">
                    <button @click="activeTab = 'description'" 
                            :class="activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-semibold text-sm uppercase tracking-wider transition">
                        Description
                    </button>
                    <button @click="activeTab = 'specifications'" 
                            :class="activeTab === 'specifications' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-semibold text-sm uppercase tracking-wider transition">
                        Specifications
                    </button>
                    <button @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-semibold text-sm uppercase tracking-wider transition">
                        Reviews ({{ $viewModel->product->reviews_count ?? 0 }})
                    </button>
                </nav>
            </div>

            <div class="py-8" x-data="{ activeTab: 'description' }">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" x-transition>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed text-lg">{!! nl2br(e($viewModel->product->description)) !!}</p>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div x-show="activeTab === 'specifications'" x-transition>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <table class="w-full">
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 font-semibold text-secondary">Product Name</td>
                                    <td class="py-3 text-gray-600">{{ $viewModel->product->name }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 font-semibold text-secondary">Price</td>
                                    <td class="py-3 text-gray-600">৳{{ number_format($viewModel->product->price, 2) }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 font-semibold text-secondary">SKU</td>
                                    <td class="py-3 text-gray-600">{{ $viewModel->product->sku ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-semibold text-secondary">Category</td>
                                    <td class="py-3 text-gray-600">{{ $viewModel->product->category->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" x-transition>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No reviews yet</p>
                        <p class="text-gray-400 text-sm mt-2">Be the first to review this product</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($viewModel->relatedProducts->count() > 0)
        <section class="mt-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-3">You May Also Like</h2>
                <div class="w-24 h-0.5 bg-primary mx-auto"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($viewModel->relatedProducts as $relatedProduct)
                    @include('shop.partials.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + change;
    if (value < 1) value = 1;
    if (value > {{ $viewModel->product->totalStock() }}) value = {{ $viewModel->product->totalStock() }};
    input.value = value;
}

document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        product_id: this.product_id.value,
        quantity: this.quantity.value
    };

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
@endsection
