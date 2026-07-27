@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Add New Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                <input type="file" name="cat_image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <p class="text-sm text-gray-500 mt-1">Accepted: JPEG, PNG, JPG, WEBP (Max 2MB)</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">None (This is a parent category)</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
