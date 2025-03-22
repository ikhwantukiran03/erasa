@extends('layouts.app')

@section('title', 'Manage Items - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Items</h1>
                <p class="text-gray-600 mt-2">View and manage items</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-primary hover:underline">Back to Admin Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Items</h2>
                <a href="{{ route('admin.items.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Add New Items</a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="overflow-x-auto">
                @if($items->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $item->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-500">{{ Str::limit($item->description, 50) ?: 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.items.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="{{ route('admin.items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                
                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="mt-4">No items have been added yet.</p>
                    <a href="{{ route('admin.items.create') }}" class="mt-4 inline-block text-primary hover:underline">Add your first item</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection