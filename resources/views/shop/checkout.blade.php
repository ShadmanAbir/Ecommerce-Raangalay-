@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-secondary mb-8">Checkout</h1>

    <form id="checkoutForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- Checkout Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-secondary mb-4">Shipping Address</h2>
                
                @if($addresses->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary mb-2">Select Saved Address</label>
                    <select id="addressSelect" name="address_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" onchange="toggleAddressFields()">
                        <option value="">-- Choose an address --</option>
                        @foreach($addresses as $address)
                        <option value="{{ $address->id }}" {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                            {{ $address->full_address }}
                        </option>
                        @endforeach
                        <option value="new">+ Add New Address</option>
                    </select>
                </div>
                @endif

                <div id="newAddressFields" class="{{ $addresses->count() == 0 ? '' : 'hidden' }} space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Division</label>
                            <input type="text" name="division" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="e.g., Dhaka">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">District</label>
                            <input type="text" name="district" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="e.g., Dhaka">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Thana/Upazila</label>
                            <input type="text" name="thana" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="e.g., Mirpur">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">City</label>
                            <input type="text" name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="e.g., Dhaka">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Full Address</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="House no, Road no, Area..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Postal Code (Optional)</label>
                        <input type="text" name="postal_code" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="e.g., 1216">
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-secondary mb-4">Payment Method</h2>
                
                <div class="space-y-3">
                    <!-- Cash on Delivery -->
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-primary transition payment-option">
                        <input type="radio" name="payment_method" value="cod" checked class="h-4 w-4 text-primary">
                        <div class="ml-3">
                            <span class="font-medium text-secondary">Cash on Delivery</span>
                            <p class="text-sm text-gray-500">Pay when you receive your order</p>
                        </div>
                    </label>

                    <!-- bKash -->
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-primary transition payment-option">
                        <input type="radio" name="payment_method" value="bkash" class="h-4 w-4 text-primary">
                        <div class="ml-3">
                            <span class="font-medium text-secondary">bKash</span>
                            <p class="text-sm text-gray-500">Pay with bKash mobile wallet</p>
                        </div>
                    </label>

                    <!-- Nagad -->
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-primary transition payment-option">
                        <input type="radio" name="payment_method" value="nagad" class="h-4 w-4 text-primary">
                        <div class="ml-3">
                            <span class="font-medium text-secondary">Nagad</span>
                            <p class="text-sm text-gray-500">Pay with Nagad mobile wallet</p>
                        </div>
                    </label>

                    <!-- SSLCommerz -->
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-primary transition payment-option">
                        <input type="radio" name="payment_method" value="sslcommerz" class="h-4 w-4 text-primary">
                        <div class="ml-3">
                            <span class="font-medium text-secondary">Credit/Debit Card</span>
                            <p class="text-sm text-gray-500">Pay with Visa, Mastercard, or other cards</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-secondary mb-4">Order Notes (Optional)</h2>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Any special instructions for your order..."></textarea>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-xl font-bold text-secondary mb-4">Order Summary</h2>

                <!-- Cart Items -->
                <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-3">
                        <div class="w-16 h-16 flex-shrink-0">
                            @if($item->product->images->count() > 0)
                                <img src="{{ storage_asset($item->product->images->first()->image_path) }}" class="w-full h-full object-cover rounded">
                            @else
                                <img src="{{ asset('images/placeholder/product_placeholder.jpg') }}" class="w-full h-full object-cover rounded">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-secondary truncate">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-bold text-primary">৳{{ number_format($item->subtotal, 2) }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">৳{{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    @if($totals['discount'] > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span>-৳{{ number_format($totals['discount'], 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">VAT (5%)</span>
                        <span class="font-medium">৳{{ number_format($totals['vat'], 2) }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-primary">৳{{ number_format($totals['payable'], 2) }}</span>
                    </div>
                </div>

                <!-- Place Order Button -->
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition font-medium text-lg mt-6">
                    Place Order
                </button>

                <p class="text-xs text-gray-500 text-center mt-3">
                    By placing your order, you agree to our Terms & Conditions
                </p>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function toggleAddressFields() {
    const select = document.getElementById('addressSelect');
    const newAddressFields = document.getElementById('newAddressFields');
    
    if (select.value === 'new' || select.value === '') {
        newAddressFields.classList.remove('hidden');
    } else {
        newAddressFields.classList.add('hidden');
    }
}

// Handle checkout form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('{{ route("checkout.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            alert(data.message || 'Checkout failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endsection
