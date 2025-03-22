<!-- resources/views/admin/categories/show.blade.php -->
@extends('layouts.app')

@section('title', 'View Category - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $category->name }}</h1>
                <p class="text-gray-600 mt-2">View category details</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline">Back to Categories</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Category Name</p>
                                <p class="text-gray-800 font-medium">{{ $category->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="text-gray-800">{{ $category->description ?: 'No description provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Created At</p>
                                <p class="text-gray-800">{{ $category->created_at->format('M d, Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="text-gray-800">{{ $category->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Edit Category
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection