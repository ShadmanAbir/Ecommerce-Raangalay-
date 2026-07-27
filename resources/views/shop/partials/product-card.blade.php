<!-- Product Card Component -->
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
    <a href="{{ route('product.show', $product->slug) }}" class="block">
        <!-- Product Image -->
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            @if($product->images->count() > 0)
                <img src="{{ storage_asset($product->images->first()->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @else
                <img src="{{ asset('images/placeholder/product_placeholder.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            @endif
            
            <!-- Discount Badge -->
            @if($product->discount > 0)
            <div class="absolute top-2 left-2 bg-danger text-white px-2 py-1 rounded text-xs font-bold">
                -{{ number_format($product->discount, 0) }}%
            </div>
            @endif
            
            <!-- New Badge -->
            @if($product->isNew())
            <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                NEW
            </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
            <h3 class="text-sm font-medium text-secondary line-clamp-2 mb-2 group-hover:text-primary transition">
                {{ $product->name }}
            </h3>
            
            <!-- Price -->
            <div class="flex items-center space-x-2 mb-2">
                @if($product->discount > 0)
                    <span class="text-lg font-bold text-primary">৳{{ number_format($product->getDiscountedPrice(), 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-lg font-bold text-primary">৳{{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            
            <!-- Rating placeholder (reviews not yet implemented) -->
            
            <!-- Stock Status -->
            @if(!$product->isInStock())
            <div class="text-xs text-danger font-medium mt-2">Out of Stock</div>
            @endif
        </div>
    </a>
    
    <!-- Quick Add to Cart -->
    @if($product->isInStock())
    <div class="px-4 pb-4">
        <button onclick="addToCart({{ $product->id }})" 
                class="w-full bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition text-sm font-medium">
            Add to Cart
        </button>
    </div>
    @endif
</div>

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
