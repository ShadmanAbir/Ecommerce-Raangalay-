<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Banner;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_users' => User::where('role', 'customer')->count(),
            'total_banners' => Banner::count(),
            'active_banners' => Banner::where('status', 1)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $recentProducts = Product::with('category')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentProducts'));
    }
}
