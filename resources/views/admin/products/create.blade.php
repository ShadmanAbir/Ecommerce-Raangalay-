@extends('layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div x-data="{
                    categories: {{ $categories->toJson() }},
                    selectedParent: {{ old('category_id') ? ($categories->firstWhere('id', old('category_id')) ? $categories->firstWhere('id', old('category_id'))->id : 'null') : 'null' }},
                    get subcategories() {
                        if (!this.selectedParent) return [];
                        const parent = this.categories.find(c => c.id == this.selectedParent);
                        return parent ? parent.children : [];
                    }
                }">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Main Category *</label>
                        <select 
                            x-model="selectedParent"
                            name="category_id" 
                            id="main_category"
                            required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Main Category</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subcategory</label>
                        <select 
                            name="subcategory_id" 
                            id="subcategory"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Subcategory (Optional)</option>
                            <template x-for="subcat in subcategories" :key="subcat.id">
                                <option :value="subcat.id" x-text="subcat.name"></option>
                            </template>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Select a main category first to see subcategories</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" required step="0.01" min="0" value="{{ old('price') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount (%)</label>
                    <input type="number" name="discount" step="0.01" min="0" max="100" value="{{ old('discount', 0) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input type="number" name="stock" required min="0" value="{{ old('stock', 0) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Product Flags</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Trending</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">New Arrival</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Featured</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Images *</label>
                    <input type="file" name="images[]" accept="image/*" multiple required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">You can select multiple images (Max 2MB each)</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="tag1, tag2, tag3"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                    <textarea name="remarks" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
