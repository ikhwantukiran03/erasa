@extends('layouts.app')

@section('title', 'Wedding Venues - Enak Rasa Wedding Hall')

@section('content')
<!-- Venues Hero Section -->
<div class="bg-gradient-to-r from-primary/20 to-secondary/30 py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-primary mb-4" data-aos="fade-up">Our Beautiful Wedding Venues</h1>
        <p class="text-gray-700 md:text-lg max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">Discover the perfect setting for your special day with our selection of elegant and versatile venues</p>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white py-10 border-b border-gray-100">
    <div class="container mx-auto px-4">
        <form action="{{ route('public.venues') }}" method="GET" class="max-w-xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-grow">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Filter by City</label>
                    <select id="city" name="city" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-primary text-white py-2 px-4 rounded-md hover:bg-opacity-90 transition">
                        Filter
                    </button>
                    @if(request()->has('city'))
                        <a href="{{ route('public.venues') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Venues Overview -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        @if(request()->has('city'))
            <div class="mb-8 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-primary">
                    Showing venues in <span class="font-bold">{{ request('city') }}</span>
                    <span class="text-gray-500 text-base">({{ $venues->total() }} {{ Str::plural('venue', $venues->total()) }})</span>
                </h2>
                <a href="{{ route('public.venues') }}" class="text-primary hover:underline text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filter
                </a>
            </div>
        @endif

        <!-- All Venues Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
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
                
                <a href="{{ route('public.venues', ['venue_id' => $venue->id]) }}" class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 block border border-gray-100" data-aos="fade-up">
                    <!-- Venue Image -->
                    <div class="h-72 overflow-hidden relative">
                        @if($venueImage)
                            @if($venueImage->source === 'local' && $venueImage->image_path)
                                <img src="{{ asset('storage/' . $venueImage->image_path) }}" 
                                     alt="{{ $venue->name }}" 
                                     class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            @elseif($venueImage->source === 'external' && $venueImage->image_url)
                                <img src="{{ $venueImage->image_url }}" 
                                     alt="{{ $venue->name }}" 
                                     class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        @endif
                        
                        <!-- Venue Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary text-white text-xs px-3 py-1.5 rounded-full font-medium uppercase tracking-wide shadow-md">
                                {{ $venue->city }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Venue Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-display font-bold text-dark group-hover:text-primary transition-colors">{{ $venue->name }}</h3>
                        
                        <div class="flex items-center text-gray-600 mt-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $venue->city }}, {{ $venue->state }}</span>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Price Range</p>
                                <div class="text-lg font-bold text-primary mt-2">
                                    @if($minPrice > 0)
                                        RM {{ number_format($minPrice, 0, ',', '.') }}
                                        @if($minPrice != $maxPrice)
                                            - RM {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    @else
                                        Contact us
                                    @endif
                                </div>
                            </div>
                            <span class="bg-primary text-white text-sm px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 shadow-md">
                                View Details
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 mb-6">
            {{ $venues->appends(request()->query())->links() }}
        </div>
        
        @if($selectedVenue)
            <!-- Selected Venue Details -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg mb-12 border border-gray-100" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Venue Image -->
                    @php
                        $featuredImage = $galleries->where('is_featured', true)->first();
                    @endphp
                    <div class="h-[450px] overflow-hidden">
                        @if($featuredImage)
                            @if($featuredImage->source === 'local')
                                <img src="{{ asset('storage/' . $featuredImage->image_path) }}" 
                                     alt="{{ $selectedVenue->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                            @else
                                <img src="{{ $featuredImage->image_url }}" 
                                     alt="{{ $selectedVenue->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                            @endif
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Venue Details -->
                    <div class="p-8 lg:p-10 flex flex-col justify-center">
                        <span class="text-sm text-primary font-medium uppercase tracking-wider mb-2">Featured Venue</span>
                        <h2 class="text-3xl font-display font-bold text-primary mb-3">{{ $selectedVenue->name }}</h2>
                        
                        <div class="flex items-center text-gray-600 mt-2 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $selectedVenue->city }}, {{ $selectedVenue->state }}</span>
                        </div>
                        
                        <div class="mt-4 text-gray-700 leading-relaxed">{{ $selectedVenue->description }}</div>
                        
                        <div class="mt-7 p-5 bg-gradient-to-r from-primary/10 to-secondary/20 rounded-lg text-gray-600 text-sm">
                            <div class="font-medium text-primary mb-2">Address:</div>
                            <div>{{ $selectedVenue->address_line_1 }}</div>
                            @if($selectedVenue->address_line_2)
                                <div>{{ $selectedVenue->address_line_2 }}</div>
                            @endif
                            <div>{{ $selectedVenue->city }}, {{ $selectedVenue->state }} {{ $selectedVenue->postal_code }}</div>
                        </div>
                        
                        <div class="mt-8">
                            <a href="{{ route('booking-requests.create', ['venue_id' => $selectedVenue->id]) }}" 
                               class="inline-block bg-primary text-white px-8 py-3 rounded-full font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                                Book This Venue
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Packages Section -->
            @if($packages->count() > 0)
                <div class="mb-16">
                    <h3 class="text-2xl font-display font-bold text-primary mb-6 flex items-center" data-aos="fade-up">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        Available Packages
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($packages as $package)
                            <a href="{{ route('public.package', $package) }}" class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 block border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                                <div class="h-2 bg-primary"></div>
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                        </svg>
                                        <h4 class="text-xl font-display font-bold text-dark group-hover:text-primary transition-colors">{{ $package->name }}</h4>
                                    </div>
                                    <div class="mt-3 text-gray-600 text-sm h-16 overflow-hidden">
                                        {{ Str::limit($package->description, 100) }}
                                    </div>
                                    
                                    <div class="mt-5 pt-4 border-t border-gray-100">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Starting from</p>
                                                <p class="text-primary font-bold text-lg">
                                                    @if($package->prices->count() > 0)
                                                        RM {{ number_format($package->min_price, 0, ',', '.') }}
                                                    @else
                                                        Contact us
                                                    @endif
                                                </p>
                                            </div>
                                            <span class="bg-primary text-white text-sm px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 shadow-md">
                                                View Details
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Gallery Section -->
            <div class="mb-16">
                <h3 class="text-2xl font-display font-bold text-primary mb-6 flex items-center" data-aos="fade-up">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Venue Gallery
                </h3>
                
                <!-- Masonry Gallery Layout -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-min">
                    @foreach($galleries as $index => $gallery)
                        @php
                            // Determine if image should be larger (for visual interest in masonry layout)
                            $isLarge = $index % 5 == 0 || $index % 7 == 0;
                            $gridClass = $isLarge ? 'col-span-2 row-span-2' : '';
                        @endphp
                        
                        <div class="group relative overflow-hidden rounded-xl shadow-md bg-white {{ $gridClass }}" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="{{ $isLarge ? 'h-80' : 'h-64' }} overflow-hidden">
                                @if($gallery->source === 'local' && $gallery->image_path)
                                    <img src="{{ asset('storage/' . $gallery->image_path) }}" 
                                         alt="{{ $gallery->title }}" 
                                         class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                @elseif($gallery->source === 'external' && $gallery->image_url)
                                    <img src="{{ $gallery->image_url }}" 
                                         alt="{{ $gallery->title }}" 
                                         class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No image available</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end">
                                    <div class="p-4 text-white">
                                        <h4 class="font-semibold">{{ $gallery->title }}</h4>
                                        @if($gallery->description)
                                            <p class="text-sm opacity-80 mt-1">{{ Str::limit($gallery->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($galleries->count() === 0)
                        <div class="col-span-4 text-center py-16 bg-gray-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg text-gray-500">No gallery images available for this venue</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-10 text-center" data-aos="fade-up">
                    <a href="{{ route('booking-requests.create', ['venue_id' => $selectedVenue->id]) }}" 
                       class="inline-block bg-primary text-white px-8 py-3 rounded-full font-semibold hover:bg-opacity-90 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center mx-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Book This Venue Now
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection