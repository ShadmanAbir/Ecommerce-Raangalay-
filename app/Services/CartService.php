<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected $guestService;

    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    public function getCart()
    {
        $user = auth()->user();
        
        if (!$user) {
            // Guest users cannot have a cart - must login first
            return collect([]);
        }
        
        $query = Cart::with(['product.images', 'productStock'])
            ->where('user_id', $user->id);

        return $query->get();
    }

    public function addToCart($productId, $quantity = 1, $size = null, $color = null, $stockId = null)
    {
        $product = Product::findOrFail($productId);
        
        // User must be logged in to add to cart
        if (!auth()->check()) {
            throw new \Exception('Please login to add items to cart');
        }
        
        $data = [
            'product_id' => $productId,
            'product_stock_id' => $stockId,
            'quantity' => $quantity,
            'size' => $size,
            'color' => $color,
            'user_id' => auth()->id(),
            'price' => $product->getDiscountedPrice(),
        ];

        $cart = Cart::where([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'product_stock_id' => $stockId,
            'size' => $size,
            'color' => $color,
        ])->first();

        if ($cart) {
            $cart->increment('quantity', $quantity);
            return $cart;
        }

        return Cart::create($data);
    }

    public function updateCart($cartId, $quantity)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->update(['quantity' => $quantity]);
        return $cart;
    }

    public function removeFromCart($cartId)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->delete();
        return true;
    }

    public function calculateTotals($cartItems)
    {
        $subtotal = 0;
        $discount = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->product->getDiscountedPrice() * $item->quantity;
            $subtotal += $itemTotal;
        }

        $subtotalAfterDiscount = $subtotal - $discount;
        $vat = round($subtotalAfterDiscount * 0.05); // 5% VAT
        $shipping = 0; // Can be calculated later
        $payable = round($subtotalAfterDiscount + $vat + $shipping);

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'vat_amount' => $vat,
            'shipping_amount' => $shipping,
            'payable_amount' => $payable,
        ];
    }
}
