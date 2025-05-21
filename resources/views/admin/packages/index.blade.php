@extends('layouts.app')

@section('title', 'Manage Packages - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-primary">Wedding Packages</h1>
                <p class="text-gray-600 mt-2">Manage your wedding packages and offers</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.packages.create') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Package
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form action="{{ route('admin.packages.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                            class="form-input w-full rounded-md" placeholder="Search by package name...">
                    </div>
                    
                    <div>
                        <label for="venue_filter" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <select id="venue_filter" name="venue" class="form-select w-full rounded-md">
                            <option value="">All Venues</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ request('venue') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                        <select id="price_range" name="price_range" class="form-select w-full rounded-md">
                            <option value="">Any Price</option>
                            <option value="0-5000" {{ request('price_range') == '0-5000' ? 'selected' : '' }}>Under RM 5,000</option>
                            <option value="5000-10000" {{ request('price_range') == '5000-10000' ? 'selected' : '' }}>RM 5,000 - RM 10,000</option>
                            <option value="10000-20000" {{ request('price_range') == '10000-20000' ? 'selected' : '' }}>RM 10,000 - RM 20,000</option>
                            <option value="20000-999999" {{ request('price_range') == '20000-999999' ? 'selected' : '' }}>Above RM 20,000</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 text-gray-500 bg-gray-100 rounded-md hover:bg-gray-200">
                        Reset
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-opacity-90">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        @if($packages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $package)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-display font-bold text-dark">{{ $package->name }}</h2>
                                <div class="bg-primary/10 text-primary text-sm px-2 py-1 rounded-full">
                                    {{ $package->venue->name }}
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mt-2 min-h-[3rem] line-clamp-2">
                                {{ $package->description ?: 'No description provided' }}
                            </p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500">Price Range</p>
                                        <p class="text-primary font-bold">
                                            @if($package->prices->count() > 0)
                                                RM {{ number_format($package->min_price, 0, ',', '.') }}
                                                @if($package->min_price != $package->max_price)
                                                    - {{ number_format($package->max_price, 0, ',', '.') }}
                                                @endif
                                            @else
                                                No prices set
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Capacity</p>
                                        <p class="text-gray-700">
                                            @if($package->prices->count() > 0)
                                                {{ $package->prices->min('pax') }} - {{ $package->prices->max('pax') }} guests
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mt-6">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.packages.show', $package) }}" class="text-blue-600 hover:text-blue-800 transition bg-blue-50 hover:bg-blue-100 rounded-md px-3 py-1.5 text-sm font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="text-indigo-600 hover:text-indigo-800 transition bg-indigo-50 hover:bg-indigo-100 rounded-md px-3 py-1.5 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.packages.duplicate', $package) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition bg-green-50 hover:bg-green-100 rounded-md px-3 py-1.5 text-sm font-medium">
                                            Duplicate
                                        </button>
                                    </form>
                                </div>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition bg-red-50 hover:bg-red-100 rounded-md px-3 py-1.5 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $packages->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-700">No Packages Found</h3>
                <p class="mt-2 text-gray-500">
                    @if(request()->has('search') || request()->has('venue') || request()->has('price_range'))
                        No packages match your search criteria. 
                        <a href="{{ route('admin.packages.index') }}" class="text-primary hover:underline">Clear filters</a>
                    @else
                        Get started by creating your first wedding package.
                        <a href="{{ route('admin.packages.create') }}" class="mt-6 inline-flex items-center bg-primary text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create First Package
                        </a>
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection