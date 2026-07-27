<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [ShopProductController::class, 'index'])->name('home');

// Product routes
Route::get('/product/{slug}', [ShopProductController::class, 'show'])->name('product.show');
Route::get('/search', [ShopProductController::class, 'search'])->name('product.search');
Route::get('/category/{slug}', [ShopProductController::class, 'byCategory'])->name('category.show');

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Protected routes (requires authentication)
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // User dashboard
    Route::get('/my-orders', [UserDashboardController::class, 'orders'])->name('user.orders');
    Route::get('/my-orders/{order_number}', [UserDashboardController::class, 'orderDetails'])->name('user.order.details');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    
    // Wishlist
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('user.wishlist');
});

// Payment callback routes
Route::post('/payment/callback/{method}', [CheckoutController::class, 'paymentCallback'])
    ->name('payment.callback');

// Payment success/cancel
Route::get('/payment/success/{order_number}', [CheckoutController::class, 'paymentSuccess'])
    ->name('payment.success');
Route::get('/payment/cancel/{order_number}', [CheckoutController::class, 'paymentCancel'])
    ->name('payment.cancel');

// Placeholder routes (UI only - no backend logic)
Route::view('/vendors', 'placeholder.vendors')->name('vendors');
Route::view('/coupons', 'placeholder.coupons')->name('coupons');
Route::view('/blogs', 'placeholder.blog')->name('blogs');
Route::view('/about', 'placeholder.about')->name('about');
Route::view('/contact', 'placeholder.contact')->name('contact');
Route::view('/faq', 'placeholder.faq')->name('faq');

// Admin routes (requires admin authentication)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Admin Banners
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    
    // Admin Categories
    Route::resource('categories', CategoryController::class);
    
    // Admin Products
    Route::resource('products', AdminProductController::class);
    Route::delete('product-images/{image}', [AdminProductController::class, 'deleteImage'])->name('product-images.destroy');
});
