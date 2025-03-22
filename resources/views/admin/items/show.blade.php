@extends('layouts.app')

@section('title', 'View Item - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $item->name }}</h1>
                <p class="text-gray-600 mt-2">View item details</p>
            </div>
            <a href="{{ route('admin.items.index') }}" class="text-primary hover:underline">Back to Items</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Item Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Item Name</p>
                                <p class="text-gray-800 font-medium">{{ $item->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="text-gray-800">{{ $item->description ?: 'No description provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Category</p>
                                <p class="text-gray-800">{{ $item->category->name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Created At</p>
                                <p class="text-gray-800">{{ $item->created_at->format('M d, Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="text-gray-800">{{ $item->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('admin.items.edit', $item) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Edit Item
                    </a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection