@extends('layouts.app')

@section('title', 'View Venue - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $venue->name }}</h1>
                <p class="text-gray-600 mt-2">View venue details</p>
            </div>
            <a href="{{ route('admin.venues.index') }}" class="text-primary hover:underline">Back to Venues</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Venue Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Venue Name</p>
                                <p class="text-gray-800 font-medium">{{ $venue->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="text-gray-800">{{ $venue->description ?: 'No description provided' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Location Details</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Address</p>
                                <p class="text-gray-800">{{ $venue->address_line_1 }}</p>
                                @if($venue->address_line_2)
                                    <p class="text-gray-800">{{ $venue->address_line_2 }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">City, State, Postal Code</p>
                                <p class="text-gray-800">{{ $venue->city }}, {{ $venue->state }} {{ $venue->postal_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Full Address</p>
                                <p class="text-gray-800">{{ $venue->full_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('admin.venues.edit', $venue) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Edit Venue
                    </a>
                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this venue?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Delete Venue
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection