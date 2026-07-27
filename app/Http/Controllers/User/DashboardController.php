<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display user dashboard
     */
    public function index()
    {
        $orders = $this->orderService->getUserOrders(auth()->id());
        $recentOrders = $orders->take(5);
        
        $wishlist = auth()->user()->wishlists()
            ->with('product.images')
            ->latest()
            ->take(10)
            ->get();

        return view('user.dashboard', compact('recentOrders', 'wishlist'));
    }

    /**
     * Display user orders
     */
    public function orders()
    {
        $orders = $this->orderService->getUserOrders(auth()->id());

        return view('user.orders', compact('orders'));
    }

    /**
     * Display order details
     */
    public function orderDetails($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product.images', 'payment', 'address'])
            ->firstOrFail();

        return view('user.order-details', compact('order'));
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        $user = auth()->user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return view('user.profile', compact('user', 'addresses'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20|unique:users,phone,' . auth()->id(),
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = auth()->user();
        
        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'] ?? $user->email;
        $user->phone = $validated['phone'] ?? $user->phone;

        // Update password if provided
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Display user wishlist
     */
    public function wishlist()
    {
        $wishlist = auth()->user()->wishlists()
            ->with('product.images')
            ->latest()
            ->paginate(12);

        return view('user.wishlist', compact('wishlist'));
    }

    /**
     * Add address
     */
    public function addAddress(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'division' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'thana' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset others
        if ($validated['is_default'] ?? false) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create([
            'address' => $validated['address'],
            'city' => $validated['city'] ?? null,
            'division' => $validated['division'],
            'district' => $validated['district'],
            'thana' => $validated['thana'],
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => 'Bangladesh',
            'is_default' => $validated['is_default'] ?? false,
        ]);

        return back()->with('success', 'Address added successfully');
    }

    /**
     * Delete address
     */
    public function deleteAddress($id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Address deleted successfully');
    }
}
