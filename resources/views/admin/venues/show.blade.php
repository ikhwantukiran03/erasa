@extends('layouts.app')

@section('title', $venue->name . ' - Enak Rasa Wedding Hall')

@section('content')
<!-- Venue Hero Section -->
<div class="relative h-[400px] bg-gray-900">
    <!-- Featured Image - display a featured gallery image if available -->
    @php
        $featuredImage = $venue->galleries()->where('is_featured', true)->first();
    @endphp
    
    @if($featuredImage)
        <img 
            src="{{ $featuredImage->source === 'local' ? asset('storage/' . $featuredImage->image_path) : $featuredImage->image_url }}" 
            alt="{{ $venue->name }}" 
            class="w-full h-full object-cover opacity-60"
        >
    @else
        <div class="w-full h-full bg-gradient-to-r from-primary/80 to-primary/40"></div>
    @endif
    
    <div class="absolute inset-0 flex items-center justify-center text-center px-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white drop-shadow-lg">{{ $venue->name }}</h1>
            <p class="mt-4 text-lg text-white max-w-2xl mx-auto">{{ $venue->description }}</p>
        </div>
    </div>
</div>

<!-- Venue Details Section -->
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-display font-bold text-primary">Venue Information</h2>
                <a href="{{ route('admin.venues.index') }}" class="text-primary hover:underline">Back to Venues</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Location Details</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 mb-2"><span class="font-medium">Address:</span> {{ $venue->address_line_1 }}</p>
                        @if($venue->address_line_2)
                            <p class="text-gray-700 mb-2"><span class="font-medium">Address Line 2:</span> {{ $venue->address_line_2 }}</p>
                        @endif
                        <p class="text-gray-700 mb-2"><span class="font-medium">City:</span> {{ $venue->city }}</p>
                        <p class="text-gray-700 mb-2"><span class="font-medium">State/Province:</span> {{ $venue->state }}</p>
                        <p class="text-gray-700 mb-2"><span class="font-medium">Postal Code:</span> {{ $venue->postal_code }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Contact Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 mb-2"><span class="font-medium">Email:</span> info@enakrasa.com</p>
                            <p class="text-gray-700 mb-2"><span class="font-medium">Phone:</span> +62 812 3456 7890</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Venue Features</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="text-gray-700 space-y-2">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Spacious venue with elegant interiors</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Capacity for up to 500 guests</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Modern audio-visual equipment</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Ample parking available</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Dedicated bridal suite</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Management Options</h3>
                        <div class="bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                            <a href="{{ route('admin.venues.edit', $venue) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                Edit Venue
                            </a>
                            
                            <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to delete this venue?')">
                                    Delete Venue
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Location Map -->
<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Location</h2>
        <p class="text-gray-600 text-center mb-10">Find us at this address</p>
        
        <div class="max-w-4xl mx-auto border border-gray-200 rounded-lg overflow-hidden shadow-md">
            <!-- Embed Google Maps iframe or other map provider -->
            <div class="h-[400px] bg-gray-200 flex items-center justify-center">
                <p class="text-gray-500">Map Placeholder - Replace with actual Google Maps embed</p>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600 mb-2">{{ $venue->full_address }}</p>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($venue->full_address) }}" class="text-primary hover:underline" target="_blank">Get Directions</a>
        </div>
    </div>
</div>
@endsection