@extends('layouts.app')

@section('title', $venue->name . ' - Enak Rasa Wedding Hall')

@section('content')
<!-- Venue Hero Section -->
<div class="relative h-[500px] bg-gray-900">
    <!-- Featured Image - display a featured gallery image if available -->
    @php
        $featuredImage = $venue->galleries()->where('is_featured', true)->first();
    @endphp
    
    @if($featuredImage)
        <img 
            src="{{ $featuredImage->source === 'local' ? asset('storage/' . $featuredImage->image_path) : $featuredImage->image_url }}" 
            alt="{{ $venue->name }}" 
            class="w-full h-full object-cover opacity-70"
        >
    @else
        <div class="w-full h-full bg-gradient-to-r from-primary/80 to-primary/40"></div>
    @endif
    
    <div class="absolute inset-0 bg-black/30 flex items-center justify-center text-center px-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white drop-shadow-lg">{{ $venue->name }}</h1>
            <p class="mt-4 text-lg text-white max-w-2xl mx-auto">{{ Str::limit($venue->description, 120) }}</p>
            <div class="mt-6 flex justify-center">
                <a href="#booking" class="bg-primary text-white px-6 py-3 rounded-md font-semibold hover:bg-opacity-90 transition">
                    Book This Venue
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumbs -->
<div class="bg-gray-100 py-3">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm">
            <a href="{{ route('home') }}" class="text-primary hover:text-primary-dark">Home</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('venues.index') }}" class="text-primary hover:text-primary-dark">Venues</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-700">{{ $venue->name }}</span>
        </nav>
    </div>
</div>

<!-- Venue Details Section -->
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-wrap items-center justify-between mb-8">
                <h2 class="text-3xl font-display font-bold text-primary mb-2 sm:mb-0">About This Venue</h2>
                <div class="flex items-center">
                    <div class="bg-primary/10 text-primary text-sm px-3 py-1 rounded-full">
                        {{ $venue->city }}, {{ $venue->state }}
                    </div>
                </div>
            </div>
            
            <div class="prose max-w-none">
                <p class="text-gray-700 text-lg mb-6">{{ $venue->description }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Venue Details</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <span class="font-medium text-gray-700">Address:</span>
                                <p class="text-gray-600">{{ $venue->full_address }}</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            <div>
                                <span class="font-medium text-gray-700">Capacity:</span>
                                <p class="text-gray-600">Up to 500 guests</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="font-medium text-gray-700">Operating Hours:</span>
                                <p class="text-gray-600">Monday - Sunday: 8AM - 10PM</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Amenities</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Air Conditioning</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Sound System</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Projector & Screen</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Changing Rooms</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Free Parking</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Catering Services</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Decoration Services</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">WiFi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Venue Gallery</h2>
        <p class="text-gray-600 text-center mb-10">Explore our beautiful wedding venue through these images</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 gallery-container" id="gallery-grid">
            @foreach($venue->galleries()->orderBy('display_order')->limit(6)->get() as $gallery)
                <div class="gallery-item rounded-lg overflow-hidden shadow-lg transition transform hover:scale-105 cursor-pointer" 
                     data-src="{{ $gallery->source === 'local' ? asset('storage/' . $gallery->image_path) : $gallery->image_url }}">
                    <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                        <img 
                            src="{{ $gallery->source === 'local' ? asset('storage/' . $gallery->image_path) : $gallery->image_url }}" 
                            alt="{{ $gallery->title }}" 
                            class="object-cover w-full h-full"
                            onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                        >
                    </div>
                    <div class="p-4 bg-white">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $gallery->title }}</h3>
                        @if($gallery->description)
                            <p class="text-gray-600 mt-1 text-sm line-clamp-2">{{ $gallery->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($venue->galleries()->count() > 6)
            <div class="text-center mt-8">
                <a href="#" class="inline-block bg-white text-primary px-6 py-3 rounded-md border border-primary font-medium hover:bg-primary hover:text-white transition">
                    View All Photos
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Packages Available at this Venue -->
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Available Packages</h2>
        <p class="text-gray-600 text-center mb-10">Choose from our selection of wedding packages for this venue</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($venue->packages as $package)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-display font-bold text-dark mb-2">{{ $package->name }}</h3>
                        
                        @if($package->description)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $package->description }}</p>
                        @endif
                        
                        <div class="mb-4 pt-4 border-t border-gray-100">
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Price Range</p>
                                    <p class="text-primary font-bold">
                                        @if($package->prices->count() > 0)
                                            Rp {{ number_format($package->min_price, 0, ',', '.') }}
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
                        
                        <a href="{{ route('packages.show', $package) }}" class="block text-center bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="mt-4 text-gray-500">No packages available for this venue yet.</p>
                    <p class="mt-2 text-gray-500">Please contact us for custom packages.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Location Map -->
<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Location</h2>
        <p class="text-gray-600 text-center mb-10">Find us at this address</p>
        
        <div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
            <!-- Embed Google Maps iframe or other map provider -->
            <div class="h-[400px] bg-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-gray-500">Map Placeholder - Replace with actual Google Maps embed</p>
                    <p class="text-sm text-gray-500 mt-2">You would integrate Google Maps API here with the venue's coordinates</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600 mb-2">{{ $venue->full_address }}</p>
            <a href="https://maps.google.com/?q={{ urlencode($venue->full_address) }}" target="_blank" class="text-primary hover:underline flex items-center justify-center">
                <span>Get Directions</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Booking Section -->
<div id="booking" class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-display font-bold text-white mb-4">Ready to Book This Venue?</h2>
        <p class="text-white/80 mb-8 max-w-2xl mx-auto">Contact us today to check availability and start planning your perfect wedding day at {{ $venue->name }}.</p>
        
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-dark font-medium mb-1">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-dark font-medium mb-1">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" required>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-dark font-medium mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input" required>
                    </div>
                    
                    <div>
                        <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                        <input type="date" id="event_date" name="event_date" class="form-input" required>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="package" class="block text-dark font-medium mb-1">Interested Package</label>
                        <select id="package" name="package_id" class="form-input">
                            <option value="">Select a package (optional)</option>
                            @foreach($venue->packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="guests" class="block text-dark font-medium mb-1">Estimated Number of Guests</label>
                        <input type="number" id="guests" name="guests" min="1" class="form-input" required>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-dark font-medium mb-1">Additional Information</label>
                        <textarea id="message" name="message" rows="3" class="form-input"></textarea>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-md font-semibold hover:bg-primary-dark transition">
                    Submit Inquiry
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Other Venues You May Like -->
<div class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Other Venues You May Like</h2>
        <p class="text-gray-600 text-center mb-10">Explore our other beautiful wedding venues</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(\App\Models\Venue::where('id', '!=', $venue->id)->inRandomOrder()->limit(3)->get() as $otherVenue)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-xl">
                    <div class="h-48 overflow-hidden">
                        @php
                            $otherFeaturedImage = $otherVenue->galleries()->where('is_featured', true)->first();
                        @endphp

                        @if($otherFeaturedImage)
                            <img 
                                src="{{ $otherFeaturedImage->source === 'local' ? asset('storage/' . $otherFeaturedImage->image_path) : $otherFeaturedImage->image_url }}" 
                                alt="{{ $otherVenue->name }}" 
                                class="w-full h-full object-cover transition duration-500 hover:scale-110"
                            >
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-display font-bold text-primary mb-1">{{ $otherVenue->name }}</h3>
                        <p class="text-gray-500 text-sm mb-3">{{ $otherVenue->city }}, {{ $otherVenue->state }}</p>
                        <a href="{{ route('venues.show', $otherVenue) }}" class="text-primary hover:underline text-sm flex items-center">
                            View Details
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Lightbox Modal for Gallery -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
    <button id="close-lightbox" class="absolute top-4 right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <button id="prev-image" class="absolute left-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    
    <div id="lightbox-content" class="max-w-4xl max-h-[90vh] overflow-hidden">
        <img id="lightbox-image" src="" alt="Gallery Image" class="max-w-full max-h-[80vh] mx-auto">
        <div class="bg-white p-4">
            <h3 id="lightbox-title" class="font-semibold text-lg text-gray-800"></h3>
            <p id="lightbox-description" class="text-gray-600 mt-1"></p>
        </div>
    </div>
    
    <button id="next-image" class="absolute right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 75%;
    }
    
    .aspect-h-12 {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery Lightbox functionality
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightboxModal = document.getElementById('lightbox-modal');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxDescription = document.getElementById('lightbox-description');
        const closeLightbox = document.getElementById('close-lightbox');
        const prevImage = document.getElementById('prev-image');
        const nextImage = document.getElementById('next-image');
        
        let currentIndex = 0;
        const galleries = @json($venue->galleries()->orderBy('display_order')->get());
        
        // Open lightbox when clicking on a gallery item
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                currentIndex = index;
                updateLightbox();
                lightboxModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        });
        
        // Close lightbox
        closeLightbox.addEventListener('click', function() {
            lightboxModal.classList.add('hidden');
            document.body.style.overflow = ''; // Re-enable scrolling
        });
        
        // Close the lightbox when clicking outside the content
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === lightboxModal) {
                lightboxModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Navigate to previous image
        prevImage.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + galleries.length) % galleries.length;
            updateLightbox();
        });
        
        // Navigate to next image
        nextImage.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % galleries.length;
            updateLightbox();
        });
        
        // Update lightbox content
        function updateLightbox() {
            const gallery = galleries[currentIndex];
            const imgSrc = gallery.source === 'local' 
                ? '/storage/' + gallery.image_path 
                : gallery.image_url;
            
            lightboxImage.src = imgSrc;
            lightboxTitle.textContent = gallery.title;
            lightboxDescription.textContent = gallery.description || '';
            
            // Handle navigation button visibility
            if (galleries.length <= 1) {
                prevImage.classList.add('hidden');
                nextImage.classList.add('hidden');
            } else {
                prevImage.classList.remove('hidden');
                nextImage.classList.remove('hidden');
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightboxModal.classList.contains('hidden')) return;
            
            if (e.key === 'Escape') {
                lightboxModal.classList.add('hidden');
                document.body.style.overflow = '';
            } else if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + galleries.length) % galleries.length;
                updateLightbox();
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % galleries.length;
                updateLightbox();
            }
        });
    });
</script>
@endpush
@endsection