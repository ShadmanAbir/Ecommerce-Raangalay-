<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Services\CartService;
use App\ViewModels\ProductViewModel;

class ProductController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display homepage with products
     */
    public function index()
    {
        $categories = Category::active()->parents()->with('children')->get();
        $banners = Banner::active()->get();
        
        // Load trending products dynamically
        $trendingProducts = Product::active()
            ->trending()
            ->with('images')
            ->limit(8)
            ->get();

        // Load new arrivals dynamically
        $newArrivals = Product::active()
            ->newArrivals()
            ->with('images')
            ->limit(8)
            ->get();

        // Load featured products dynamically
        $featuredProducts = Product::active()
            ->featured()
            ->with('images')
            ->limit(8)
            ->get();

        return view('shop.index', compact(
            'categories',
            'banners',
            'trendingProducts',
            'newArrivals',
            'featuredProducts'
        ));
    }

    /**
     * Display product details
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'images', 'stocks'])
            ->firstOrFail();

        $categories = Category::active()->parents()->get();
        
        $cartCount = auth()->check() 
            ? $this->cartService->getCart()->count() 
            : 0;

        $viewModel = ProductViewModel::fromProduct($product, null, $categories, $cartCount);

        return view('shop.product-details', compact('viewModel'));
    }

    /**
     * Search products
     */
    public function search()
    {
        $keyword = request('q', '');
        
        $products = Product::active()
            ->search($keyword)
            ->with('images')
            ->paginate(12);

        $categories = Category::active()->parents()->get();

        return view('shop.search', compact('products', 'categories', 'keyword'));
    }

    /**
     * List products by category
     */
    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('images')
            ->paginate(12);

        $categories = Category::active()->parents()->get();

        return view('shop.category', compact('products', 'category', 'categories'));
    }
}
