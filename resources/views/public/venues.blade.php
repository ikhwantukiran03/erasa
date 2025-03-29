@extends('layouts.app')

@section('title', 'Wedding Venues - Enak Rasa Wedding Hall')

@section('content')
<!-- Venues Overview -->
<div class="bg-white py-10">
    <div class="container mx-auto px-4">
        <!-- All Venues Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($venues as $venue)
                @php
                    $venueImage = \App\Models\Gallery::where('venue_id', $venue->id)->where('is_featured', true)->first();
                    if (!$venueImage) {
                        $venueImage = \App\Models\Gallery::where('venue_id', $venue->id)->first();
                    }
                    
                    // Get min and max price for this venue
                    $venuePackages = \App\Models\Package::where('venue_id', $venue->id)->get();
                    $minPrice = 0;
                    $maxPrice = 0;
                    
                    if($venuePackages->count() > 0) {
                        $pricesByPackage = [];
                        foreach($venuePackages as $venuePackage) {
                            $prices = $venuePackage->prices;
                            if($prices->count() > 0) {
                                $pricesByPackage[] = [
                                    'min' => $prices->min('price'),
                                    'max' => $prices->max('price')
                                ];
                            }
                        }
                        
                        if(count($pricesByPackage) > 0) {
                            $minPrice = min(array_column($pricesByPackage, 'min'));
                            $maxPrice = max(array_column($pricesByPackage, 'max'));
                        }
                    }
                @endphp
                
                <a href="{{ route('public.venues', ['venue_id' => $venue->id]) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 block">
                    <!-- Venue Image -->
                    <div class="h-60 overflow-hidden">
                        @if($venueImage)
                            @if($venueImage->source === 'local' && $venueImage->image_path)
                                <img src="{{ asset('storage/' . $venueImage->image_path) }}" 
                                     alt="{{ $venue->name }}" 
                                     class="w-full h-full object-cover transition duration-500 hover:scale-105">
                            @elseif($venueImage->source === 'external' && $venueImage->image_url)
                                <img src="{{ $venueImage->image_url }}" 
                                     alt="{{ $venue->name }}" 
                                     class="w-full h-full object-cover transition duration-500 hover:scale-105">
                            @endif
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Venue Details -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-dark">{{ $venue->name }}</h3>
                        
                        <div class="flex items-center text-gray-600 mt-1 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $venue->city }}, {{ $venue->state }}</span>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500">Price Range</p>
                                <p class="text-primary font-bold">
                                    @if($minPrice > 0)
                                        RM {{ number_format($minPrice, 0, ',', '.') }}
                                        @if($minPrice != $maxPrice)
                                            - {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    @else
                                        Contact us
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($selectedVenue)
            <!-- Selected Venue Details -->
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md mb-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Venue Image -->
                    @php
                        $featuredImage = $galleries->where('is_featured', true)->first();
                    @endphp
                    <div class="h-[350px] overflow-hidden">
                        @if($featuredImage)
                            @if($featuredImage->source === 'local')
                                <img src="{{ asset('storage/' . $featuredImage->image_path) }}" 
                                     alt="{{ $selectedVenue->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <img src="{{ $featuredImage->image_url }}" 
                                     alt="{{ $selectedVenue->name }}" 
                                     class="w-full h-full object-cover">
                            @endif
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Venue Details -->
                    <div class="p-6">
                        <h2 class="text-2xl font-display font-bold text-dark">{{ $selectedVenue->name }}</h2>
                        
                        <div class="flex items-center text-gray-600 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $selectedVenue->city }}, {{ $selectedVenue->state }}</span>
                        </div>
                        
                        <div class="mt-4 text-gray-700">{{ $selectedVenue->description }}</div>
                        
                        <div class="mt-4 text-gray-600 text-sm">
                            <div><strong>Address:</strong></div>
                            <div>{{ $selectedVenue->address_line_1 }}</div>
                            @if($selectedVenue->address_line_2)
                                <div>{{ $selectedVenue->address_line_2 }}</div>
                            @endif
                            <div>{{ $selectedVenue->city }}, {{ $selectedVenue->state }} {{ $selectedVenue->postal_code }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Packages Section -->
            @if($packages->count() > 0)
                <h3 class="text-xl font-bold text-primary mb-4">Available Packages</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach($packages as $package)
                        <a href="{{ route('public.package', $package) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 block">
                            <div class="p-1 bg-primary"></div>
                            <div class="p-4">
                                <h4 class="text-lg font-bold text-dark">{{ $package->name }}</h4>
                                <div class="mt-2 text-gray-600 text-sm h-12 overflow-hidden">
                                    {{ Str::limit($package->description, 80) }}
                                </div>
                                
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500">Starting from</p>
                                            <p class="text-primary font-bold">
                                                @if($package->prices->count() > 0)
                                                    RM {{ number_format($package->min_price, 0, ',', '.') }}
                                                @else
                                                    Contact us
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Gallery Section -->
            <h3 class="text-xl font-bold text-primary mb-4">Gallery</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($galleries as $gallery)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-64 overflow-hidden">
                            @if($gallery->source === 'local' && $gallery->image_path)
                                <img src="{{ asset('storage/' . $gallery->image_path) }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="w-full h-full object-cover transition duration-500 hover:scale-105">
                            @elseif($gallery->source === 'external' && $gallery->image_url)
                                <img src="{{ $gallery->image_url }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="w-full h-full object-cover transition duration-500 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image available</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-dark">{{ $gallery->title }}</h4>
                            @if($gallery->description)
                                <p class="text-gray-600 mt-1">{{ Str::limit($gallery->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($galleries->count() === 0)
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-4 text-gray-500">No gallery images available for this venue</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection