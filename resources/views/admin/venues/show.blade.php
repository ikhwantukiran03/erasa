@extends('layouts.app')

@section('title', $venue->name . ' - Admin')

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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('admin.venues.index') }}" class="ml-1 text-gray-700 hover:text-primary md:ml-2">Venues</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">{{ $venue->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $venue->name }}</h1>
                <p class="text-gray-600 mt-2">Venue Details</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.venues.edit', $venue) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center" onclick="return confirm('Are you sure you want to delete this venue?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Venue Information -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Name</label>
                                <p class="text-gray-900">{{ $venue->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Description</label>
                                <p class="text-gray-900">{{ $venue->description ?: 'No description provided' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Created</label>
                                <p class="text-gray-900">{{ $venue->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="text-gray-900">{{ $venue->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Address</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Address Line 1</label>
                                <p class="text-gray-900">{{ $venue->address_line_1 }}</p>
                            </div>
                            @if($venue->address_line_2)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Address Line 2</label>
                                <p class="text-gray-900">{{ $venue->address_line_2 }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="text-sm font-medium text-gray-500">City</label>
                                <p class="text-gray-900">{{ $venue->city }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">State</label>
                                <p class="text-gray-900">{{ $venue->state }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Postal Code</label>
                                <p class="text-gray-900">{{ $venue->postal_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Packages</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $venue->packages()->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Gallery Images</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $venue->galleries()->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Booking::where('venue_id', $venue->id)->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.packages.index', ['venue_id' => $venue->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm">
                            Manage Packages
                        </a>
                        <a href="{{ route('admin.galleries.index', ['venue_id' => $venue->id]) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm">
                            Manage Gallery
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection