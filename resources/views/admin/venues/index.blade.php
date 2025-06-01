@extends('layouts.app')

@section('title', 'Venues - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Venues</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Venues</h1>
                <p class="text-gray-600 mt-2">Manage wedding venues</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.venues.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition flex items-center w-max">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Venue
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">All Venues</h2>
                    <div class="mt-3 md:mt-0">
                        <form action="{{ route('admin.venues.index') }}" method="GET" class="flex">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Search venues..." 
                                    class="form-input pr-10 w-full md:w-64"
                                >
                                <button type="submit" class="absolute inset-y-0 right-0 px-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @forelse($venues as $venue)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                        <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                            @php
                                // Get venue images from Gallery model
                                $venueImages = $venue->galleries()->whereRaw('is_featured = true')->get();
                                if ($venueImages->isEmpty()) {
                                    $venueImages = $venue->galleries()->take(1)->get();
                                }
                                $venueImage = $venueImages->first();
                                
                                // Debug: Check if we have any galleries
                                $totalGalleries = $venue->galleries()->count();
                                
                                // Debug: Get all galleries for this venue
                                $allGalleries = $venue->galleries()->get();
                            @endphp
                            
                            @if($venueImage)
                                @php
                                    // Fix for Cloudinary URLs that are marked as 'local' in database
                                    $imageUrl = $venueImage->image_path;
                                    
                                    // If the path starts with http/https, use it directly (Cloudinary)
                                    if (str_starts_with($venueImage->image_path, 'http')) {
                                        $imageUrl = $venueImage->image_path;
                                    } 
                                    // If source is external or has image_url, use that
                                    elseif ($venueImage->source === 'external' || $venueImage->image_url) {
                                        $imageUrl = $venueImage->image_url;
                                    }
                                    // Otherwise, treat as local storage
                                    else {
                                        $imageUrl = asset('storage/' . $venueImage->image_path);
                                    }
                                @endphp
                                
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $venue->name }}" 
                                     class="w-full h-full object-cover">
                            @elseif($totalGalleries > 0)
                                <!-- Debug: Show that galleries exist but no featured/first image -->
                                <div class="text-center p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-500">{{ $totalGalleries }} images available</p>
                                    <p class="text-xs text-gray-400">Image loading issue</p>
                                </div>
                            @else
                                <!-- No galleries - show placeholder image -->
                                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=400&h=300&fit=crop&crop=center" 
                                     alt="{{ $venue->name }} placeholder" 
                                     class="w-full h-full object-cover opacity-60">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-xs text-white">No images uploaded</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $venue->name }}</h3>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $venue->description }}</p>
                            
                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $venue->city }}, {{ $venue->state }}</span>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.venues.show', $venue) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 rounded-md p-1.5" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 rounded-md p-1.5" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 rounded-md p-1.5" title="Delete" onclick="return confirm('Are you sure you want to delete this venue?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('admin.venues.show', $venue) }}" class="text-primary hover:underline text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center py-12 bg-white rounded-lg border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-lg font-medium text-gray-900 mb-2">No venues found</p>
                        @if(request('search'))
                            <p class="text-sm text-gray-500 mb-4">No results found for "{{ request('search') }}"</p>
                            <a href="{{ route('admin.venues.index') }}" class="text-primary hover:underline">Clear search</a>
                        @else
                            <p class="text-sm text-gray-500 mb-4">Get started by creating a new venue</p>
                            <a href="{{ route('admin.venues.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition">
                                Add New Venue
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
            
            
        </div>
    </div>
</div>
@endsection