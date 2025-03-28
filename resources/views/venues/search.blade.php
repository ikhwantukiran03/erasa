@extends('layouts.app')

@section('title', 'Search Venues - Enak Rasa Wedding Hall')

@section('content')
<!-- Hero Section -->
<div class="relative h-[300px] bg-gray-900">
    <div class="w-full h-full bg-gradient-to-r from-primary/80 to-primary/40 absolute z-10"></div>
    <img 
        src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2670&auto=format&fit=crop"
        alt="Wedding Venue Search" 
        class="w-full h-full object-cover opacity-80"
    >
    <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white drop-shadow-lg">Find Your Perfect Venue</h1>
            <p class="mt-4 text-lg text-white max-w-2xl mx-auto">Search and filter to discover the ideal wedding venue for your special day</p>
        </div>
    </div>
</div>

<!-- Search Filters -->
<div class="bg-white py-8 shadow-md">
    <div class="container mx-auto px-4">
        <form action="{{ route('venues.search') }}" method="GET" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="location" class="block text-dark font-medium mb-1">Location</label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="{{ request('location') }}" 
                        class="form-input"
                        placeholder="City, State or Postal Code"
                    >
                </div>
                
                <div>
                    <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                    <input 
                        type="date" 
                        id="event_date" 
                        name="event_date" 
                        value="{{ request('event_date') }}" 
                        class="form-input"
                    >
                </div>
                
                <div>
                    <label for="capacity" class="block text-dark font-medium mb-1">Guest Count</label>
                    <select id="capacity" name="capacity" class="form-input">
                        <option value="">Any capacity</option>
                        <option value="50" {{ request('capacity') == '50' ? 'selected' : '' }}>Up to 50 guests</option>
                        <option value="100" {{ request('capacity') == '100' ? 'selected' : '' }}>Up to 100 guests</option>
                        <option value="200" {{ request('capacity') == '200' ? 'selected' : '' }}>Up to 200 guests</option>
                        <option value="300" {{ request('capacity') == '300' ? 'selected' : '' }}>Up to 300 guests</option>
                        <option value="500" {{ request('capacity') == '500' ? 'selected' : '' }}>Up to 500 guests</option>
                        <option value="501" {{ request('capacity') == '501' ? 'selected' : '' }}>500+ guests</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md font-semibold hover:bg-opacity-90 transition">
                    Search Venues
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Search Results -->
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h2 class="text-3xl font-display font-bold text-primary mb-4">
                @if(request()->anyFilled(['location', 'event_date', 'capacity']))
                    Search Results
                @else
                    All Venues
                @endif
            </h2>
            
            @if($venues->count() > 0)
                <p class="text-gray-600">Found {{ $venues->count() }} {{ Str::plural('venue', $venues->count()) }}</p>
            @endif
        </div>

        @if($venues->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($venues as $venue)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-xl">
                    <div class="h-64 overflow-hidden">
                        @php
                            $featuredImage = $venue->galleries()->where('is_featured', true)->first();
                        @endphp

                        @if($featuredImage)
                            <img 
                                src="{{ $featuredImage->source === 'local' ? asset('storage/' . $featuredImage->image_path) : $featuredImage->image_url }}" 
                                alt="{{ $venue->name }}" 
                                class="w-full h-full object-cover transition duration-500 hover:scale-110"
                            >
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-display font-bold text-primary mb-2">{{ $venue->name }}</h3>
                        
                        <div class="flex items-center text-gray-500 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $venue->city }}, {{ $venue->state }}</span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $venue->description }}</p>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <div>
                                @php
                                    $packageCount = $venue->packages()->count();
                                    $minPrice = $venue->packages()->min('price');
                                @endphp
                                
                                @if($packageCount > 0)
                                    <span class="text-sm text-gray-500">From Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm text-gray-500">Contact for pricing</span>
                                @endif
                            </div>
                            <a href="{{ route('venues.show', $venue) }}" class="text-primary font-medium hover:underline flex items-center">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No Venues Found</h3>
                <p class="text-gray-600 mb-6">We couldn't find any venues that match your search criteria.</p>
                <a href="{{ route('venues.search') }}" class="text-primary hover:underline">Clear filters and try again</a>
            </div>
        @endif
    </div>
</div>

<!-- Call to Action -->
<div class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-display font-bold text-white mb-4">Can't Find What You're Looking For?</h2>
        <p class="text-white/80 mb-8 max-w-2xl mx-auto">Contact our team for personalized venue recommendations tailored to your specific needs.</p>
        
        <div class="flex flex-wrap justify-center gap-4">
            <a href="#" class="bg-white text-primary px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition">
                Contact Us
            </a>
            <a href="{{ route('venues.index') }}" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white/10 transition">
                View All Venues
            </a>
        </div>
    </div>
</div>
@endsection