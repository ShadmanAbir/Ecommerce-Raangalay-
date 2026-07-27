<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\ViewModels\CartViewModel;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display cart
     */
    public function index()
    {
        $cartItems = $this->cartService->getCart();
        $totals = $this->cartService->calculateTotals($cartItems);
        
        $viewModel = CartViewModel::fromCart($cartItems, $totals);

        return view('shop.cart', compact('viewModel'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'product_stock_id' => 'nullable|exists:product_stocks,id',
        ]);

        try {
            $cart = $this->cartService->addToCart(
                $validated['product_id'],
                $validated['quantity'],
                $validated['size'] ?? null,
                $validated['color'] ?? null,
                $validated['product_stock_id'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $this->cartService->getCart()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->updateCart($id, $validated['quantity']);

            $cartItems = $this->cartService->getCart();
            $totals = $this->cartService->calculateTotals($cartItems);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'totals' => $totals,
                'cart_count' => $cartItems->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        try {
            $this->cartService->removeFromCart($id);

            $cartItems = $this->cartService->getCart();
            $totals = $this->cartService->calculateTotals($cartItems);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'totals' => $totals,
                'cart_count' => $cartItems->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
