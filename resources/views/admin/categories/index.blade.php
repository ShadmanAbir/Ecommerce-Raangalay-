@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        Add New Category
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4">
                        @if($category->cat_image)
                            <img src="{{ storage_asset($category->cat_image) }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                        <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $category->parent->name ?? 'None (Parent)' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $category->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No categories found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
