<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $uploadService;

    public function __construct(ImageUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::parents()->with('children')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|max:100',
                'category_id' => 'nullable|exists:categories,id',
                'subcategory_id' => 'nullable|exists:categories,id',
                'images' => 'required|array|min:1',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
                'status' => 'required|in:draft,active,inactive,out_of_stock',
                'is_trending' => 'nullable|boolean',
                'is_new' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'remarks' => 'nullable|string|max:240',
                'tags' => 'nullable|string',
                'sizes' => 'nullable|string',
                'colors' => 'nullable|string',
            ]);

            // Use subcategory_id if provided, otherwise use category_id
            $categoryId = $request->subcategory_id ?? $request->category_id;

            \Log::info('Creating product: ' . $request->name);
            \Log::info('Category ID: ' . $categoryId);

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'stock' => $request->stock,
                'sku' => $request->sku,
                'category_id' => $categoryId,
                'status' => $request->status,
                'is_trending' => $request->has('is_trending'),
                'is_new' => $request->has('is_new'),
                'is_featured' => $request->has('is_featured'),
                'remarks' => $request->remarks,
                'tags' => $request->tags ? explode(',', $request->tags) : null,
                'sizes' => $request->sizes ? explode(',', $request->sizes) : null,
                'colors' => $request->colors ? explode(',', $request->colors) : null,
            ]);

            // Upload product images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    if ($file->isValid()) {
                        $imagePath = $this->uploadService->upload($file, 'products');
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'sort_order' => $index,
                            'is_primary' => $index === 0,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Product $product)
    {
        // Load all categories with their children for hierarchical display
        $categories = Category::parents()->with('children')->get();
        $product->load(['images', 'category']);
        
        \Log::info('Editing product: ' . $product->name);
        \Log::info('Product category_id: ' . $product->category_id);
        \Log::info('Categories count: ' . $categories->count());
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|max:100',
                'category_id' => 'nullable|exists:categories,id',
                'subcategory_id' => 'nullable|exists:categories,id',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
                'status' => 'required|in:draft,active,inactive,out_of_stock',
                'is_trending' => 'nullable|boolean',
                'is_new' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'remarks' => 'nullable|string|max:240',
                'tags' => 'nullable|string',
                'sizes' => 'nullable|string',
                'colors' => 'nullable|string',
            ]);

            // Use subcategory_id if provided, otherwise use category_id
            $categoryId = $request->subcategory_id ?? $request->category_id;

            \Log::info('Updating product: ' . $product->name . ' (ID: ' . $product->id . ')');
            \Log::info('Category ID: ' . $categoryId);

            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'discount' => $request->discount ?? 0,
                'stock' => $request->stock,
                'sku' => $request->sku,
                'category_id' => $categoryId,
                'status' => $request->status,
                'is_trending' => $request->has('is_trending'),
                'is_new' => $request->has('is_new'),
                'is_featured' => $request->has('is_featured'),
                'remarks' => $request->remarks,
                'tags' => $request->tags ? explode(',', $request->tags) : null,
                'sizes' => $request->sizes ? explode(',', $request->sizes) : null,
                'colors' => $request->colors ? explode(',', $request->colors) : null,
            ]);

            // Upload new images if provided (adds to existing images)
            if ($request->hasFile('images')) {
                $uploadedCount = 0;
                foreach ($request->file('images') as $file) {
                    if ($file->isValid()) {
                        $imagePath = $this->uploadService->upload($file, 'products');
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'sort_order' => $product->images->count() + $uploadedCount,
                        ]);
                        $uploadedCount++;
                        \Log::info('Added new image: ' . $imagePath);
                    }
                }
                \Log::info('Uploaded ' . $uploadedCount . ' new image(s)');
            }

            \Log::info('Product updated successfully');

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Product update failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        // Delete all product images
        foreach ($product->images as $image) {
            $this->uploadService->delete($image->image_path);
            $image->delete();
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function deleteImage(ProductImage $image)
    {
        try {
            $productId = $image->product_id;
            
            \Log::info('Deleting image ID: ' . $image->id . ' from product ID: ' . $productId);
            
            // Delete the physical file
            $this->uploadService->delete($image->image_path);
            
            // Delete the database record
            $image->delete();
            
            \Log::info('Image deleted successfully');

            return back()->with('success', 'Image deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to delete image: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete image: ' . $e->getMessage()]);
        }
    }
}
