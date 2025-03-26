<!-- resources/views/venues/show.blade.php -->
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
            <h2 class="text-3xl font-display font-bold text-primary mb-6">Venue Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Location</h3>
                    <p class="text-gray-600">{{ $venue->full_address }}</p>
                    
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Contact</h3>
                        <p class="text-gray-600">Email: info@enakrasa.com</p>
                        <p class="text-gray-600">Phone: +62 812 3456 7890</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Features</h3>
                    <ul class="text-gray-600 space-y-2">
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
            </div>
            
            <div class="mt-8 text-center">
                <a href="#" class="inline-block bg-primary text-white px-6 py-3 rounded-md font-medium hover:bg-opacity-90 transition">
                    Book This Venue
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Section -->
@include('components.venue-gallery', ['galleries' => $venue->galleries()->orderBy('display_order')->get()])

<!-- Packages Available at this Venue -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Available Packages</h2>
        <p class="text-gray-600 text-center mb-10">Choose from our selection of wedding packages for this venue</p>
        
        @php
            $packages = \App\Models\Package::where('venue_id', $venue->id)->get();
        @endphp
        
        @if($packages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $package)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-display font-bold text-dark mb-2">{{ $package->name }}</h3>
                            
                            @if($package->description)
                                <p class="text-gray-600 mb-4">{{ Str::limit($package->description, 100) }}</p>
                            @endif
                            
                            <div class="mb-4 pt-4 border-t border-gray-100">
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
                                                Contact for pricing
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
                            
                            <a href="#" class="block text-center bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-10">
                <p>No packages available for this venue yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Location Map -->
<div class="py-12 bg-white">
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
            <a href="#" class="text-primary hover:underline">Get Directions</a>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-display font-bold text-white mb-4">Ready to Book This Venue?</h2>
        <p class="text-white/80 mb-8 max-w-2xl mx-auto">Contact us today to check availability and start planning your perfect wedding day at {{ $venue->name }}.</p>
        
        <div class="flex flex-wrap justify-center gap-4">
            <a href="#" class="bg-white text-primary px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition">
                Book Now
            </a>
            <a href="#" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white/10 transition">
                Contact Us
            </a>
        </div>
    </div>
</div>
@endsection