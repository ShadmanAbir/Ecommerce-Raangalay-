@extends('layouts.admin')

@section('title', 'Edit Banner')
@section('page-title', 'Edit Banner')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                <input type="url" name="link" value="{{ old('link', $banner->link) }}" placeholder="https://example.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image</label>
                @if($banner->image)
                    <div class="mb-2">
                        <img src="{{ storage_asset($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-48 object-cover rounded mb-2">
                        <p class="text-sm text-gray-500">Current image</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <p class="text-sm text-gray-500 mt-1">Upload new image to replace current (Max 2MB)</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }} 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Update Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
