<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $paymentService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        // User must be logged in to checkout
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to proceed with checkout');
        }
        
        $cartItems = $this->cartService->getCart();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $totals = $this->cartService->calculateTotals($cartItems);
        
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('shop.checkout', compact('cartItems', 'totals', 'addresses', 'defaultAddress'));
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cod,bkash,nagad,sslcommerz',
            'address_id' => 'nullable|exists:customer_addresses,id',
            'address' => 'required_without:address_id|string|max:500',
            'city' => 'required_without:address_id|string|max:100',
            'division' => 'required_without:address_id|string|max:100',
            'district' => 'required_without:address_id|string|max:100',
            'thana' => 'required_without:address_id|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Get or create address
            $address = null;
            if ($validated['address_id']) {
                $address = CustomerAddress::findOrFail($validated['address_id']);
            } else {
                $address = CustomerAddress::create([
                    'user_id' => auth()->id(),
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'division' => $validated['division'],
                    'district' => $validated['district'],
                    'thana' => $validated['thana'],
                    'postal_code' => $validated['postal_code'] ?? null,
                    'country' => 'Bangladesh',
                    'is_default' => true,
                ]);
            }

            // Get cart items
            $cartItems = $this->cartService->getCart();
            $totals = $this->cartService->calculateTotals($cartItems);

            // Create order
            $orderData = [
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'shipping_address_id' => $address->id,
            ];

            $order = $this->orderService->createOrder($orderData, $cartItems, $totals);

            // Process payment
            $paymentResult = $this->paymentService->processPayment($order, $validated['payment_method']);

            DB::commit();

            // If payment requires redirect (bKash, Nagad, SSLCommerz)
            if (isset($paymentResult['url'])) {
                return response()->json([
                    'success' => true,
                    'redirect' => $paymentResult['url'],
                    'order_number' => $order->order_number,
                ]);
            }

            // For COD, redirect to success page
            return response()->json([
                'success' => true,
                'redirect' => route('payment.success', $order->order_number),
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle payment gateway callback
     */
    public function paymentCallback(Request $request, $method)
    {
        try {
            $result = $this->paymentService->handlePaymentCallback($request->all(), $method);

            if ($result['success']) {
                return redirect()->route('payment.success', $request->get('order_number'))
                    ->with('success', $result['message'] ?? 'Payment successful');
            }

            return redirect()->route('payment.cancel', $request->get('order_number'))
                ->with('error', $result['error'] ?? 'Payment failed');

        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->with(['items.product.images', 'payment'])
            ->firstOrFail();

        return view('shop.payment-success', compact('order'));
    }

    /**
     * Payment cancel page
     */
    public function paymentCancel($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();

        return view('shop.payment-cancel', compact('order'));
    }
}
