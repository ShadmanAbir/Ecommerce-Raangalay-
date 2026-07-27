<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder($orderData, $cartItems, $totals)
    {
        return DB::transaction(function () use ($orderData, $cartItems, $totals) {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . time() . '-' . auth()->id(),
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'] ?? 0,
                'vat_amount' => $totals['vat_amount'] ?? 0,
                'shipping_cost' => $totals['shipping_amount'] ?? 0,
                'total_amount' => $totals['payable_amount'],
                'payment_method' => $orderData['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'shipping_address_id' => $orderData['shipping_address_id'] ?? null,
                'notes' => $orderData['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $price = $cartItem->product->getDiscountedPrice();
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_stock_id' => $cartItem->product_stock_id,
                    'quantity' => $cartItem->quantity,
                    'size' => $cartItem->size,
                    'color' => $cartItem->color,
                    'price' => $price,
                    'subtotal' => $price * $cartItem->quantity,
                ]);

                // Update product stock
                if ($cartItem->product_stock_id) {
                    $stock = \App\Models\ProductStock::find($cartItem->product_stock_id);
                    if ($stock) {
                        $stock->decrement('quantity', $cartItem->quantity);
                    }
                }
            }

            // Clear cart
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
            }

            return $order;
        });
    }

    public function getUserOrders($userId)
    {
        return Order::with(['items.product.images', 'payment', 'shippingAddress'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrderById($orderId)
    {
        return Order::with(['items.product.images', 'payment', 'shippingAddress', 'user'])
            ->findOrFail($orderId);
    }
}
