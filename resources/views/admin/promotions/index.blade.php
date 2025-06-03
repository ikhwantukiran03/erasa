@extends('layouts.app')

@section('title', 'Promotions - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-4xl font-display font-bold text-primary mb-2">Promotions</h1>
                    <p class="text-gray-600 text-lg">Manage your wedding hall promotions</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Promotion
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <form action="{{ route('admin.promotions.index') }}" method="GET" class="p-6">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search promotions..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                        >
                    </div>
                    <div>
                        <button type="submit" class="w-full lg:w-auto px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Promotions List -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary/10 to-primary/5">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Package</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Discount</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Start Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">End Date</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($promotions as $promotion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($promotion->cloudinary_image_url)
                                            <img src="{{ $promotion->cloudinary_image_url }}" alt="{{ $promotion->title }}" class="h-10 w-10 rounded-lg object-cover mr-3">
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $promotion->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($promotion->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $promotion->package->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">RM {{ number_format($promotion->discount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $promotion->start_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $promotion->end_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="text-primary hover:text-primary-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium">No promotions found</p>
                                        <p class="text-sm">Get started by creating a new promotion</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($promotions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $promotions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 