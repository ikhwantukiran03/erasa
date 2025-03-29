@extends('layouts.app')

@section('title', $package->name . ' - Enak Rasa Wedding Hall')

@section('content')
<!-- Package Header -->
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('public.venues') }}" class="hover:text-primary transition">Venues</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('public.venues', ['venue_id' => $package->venue_id]) }}" class="hover:text-primary transition">{{ $package->venue->name }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-primary">{{ $package->name }}</span>
        </div>
        
        <h1 class="text-3xl font-display font-bold text-dark">{{ $package->name }}</h1>
        <p class="text-gray-600 mt-1">{{ $package->venue->name }}</p>
    </div>
</div>

<!-- Package Content -->
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content (Left Side) -->
            <div class="lg:col-span-2">
                <!-- Package Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-dark mb-4">About This Package</h2>
                    <div class="text-gray-700">
                        <p>{{ $package->description }}</p>
                    </div>
                </div>
                
                <!-- Package Items -->
                @if($package->packageItems->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-dark mb-4">What's Included</h2>
                        
                        @php
                            $itemsByCategory = $package->packageItems->groupBy(function($item) {
                                return $item->item->category->name;
                            });
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($itemsByCategory as $categoryName => $items)
                                <div class="border border-gray-100 rounded-lg overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                                        <h3 class="font-medium text-gray-800">{{ $categoryName }}</h3>
                                    </div>
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($items as $packageItem)
                                                <div class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-800">{{ $packageItem->item->name }}</div>
                                                        @if($packageItem->description)
                                                            <p class="text-xs text-gray-600 mt-1">{{ $packageItem->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Venue Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <span class="block text-gray-800 font-medium">{{ $package->venue->name }}</span>
                            <span class="text-gray-600 text-sm">{{ $package->venue->address_line_1 }}, {{ $package->venue->city }}, {{ $package->venue->state }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Sidebar (Right Side) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-8">
                    <div class="p-1 bg-primary"></div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-dark mb-3">Package Pricing</h3>
                        
                        @if($package->prices->count() > 0)
                            <div class="bg-gray-50 rounded-lg overflow-hidden mb-4">
                                <div class="px-4 py-2 bg-gray-100 text-dark font-medium text-sm">
                                    Price by Guest Count
                                </div>
                                <div class="p-3">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($package->prices->sortBy('pax') as $price)
                                            <div class="py-2 flex justify-between items-center">
                                                <span class="text-gray-700">{{ $price->pax }} guests</span>
                                                <span class="font-semibold text-primary">RM {{ number_format($price->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Starting Price Highlight -->
                            <div class="text-center mb-4">
                                <p class="text-gray-500 text-xs">Starting from</p>
                                <p class="text-primary text-2xl font-bold">RM {{ number_format($package->min_price, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 p-3 rounded-lg text-center mb-4">
                                <p class="text-gray-600">Contact us for pricing details</p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="space-y-2 mb-4">
                            <a href="#" class="block w-full bg-primary text-white text-center py-2 rounded hover:bg-opacity-90 transition text-sm">
                                Book This Package
                            </a>
                            <a href="https://wa.me/60123456789?text=I'm interested in {{ $package->name }} at {{ $package->venue->name }}" target="_blank" class="block w-full bg-green-500 text-white text-center py-2 rounded hover:bg-opacity-90 transition text-sm flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                                </svg>
                                WhatsApp
                            </a>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="text-center text-sm text-gray-600">
                            <p>Questions? Call us at</p>
                            <p class="font-medium text-gray-800">+60 123 456 789</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Packages (if any) -->
        @if($relatedPackages->count() > 0)
            <div class="mt-10 pt-6 border-t border-gray-100">
                <h2 class="text-xl font-bold text-dark mb-4">Other Packages at {{ $package->venue->name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPackages as $relatedPackage)
                        <a href="{{ route('public.package', $relatedPackage) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 block">
                            <div class="p-1 bg-primary"></div>
                            <div class="p-4">
                                <h4 class="font-bold text-dark">{{ $relatedPackage->name }}</h4>
                                <div class="my-3 pt-3 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500">Starting from</p>
                                            <p class="text-primary font-bold">
                                                @if($relatedPackage->prices->count() > 0)
                                                    RM {{ number_format($relatedPackage->min_price, 0, ',', '.') }}
                                                @else
                                                    Contact us
                                                @endif
                                            </p>
                                        </div>
                                        
                                        <span class="bg-primary text-white px-3 py-1 rounded text-sm">
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
        
        <!-- Other Venues Section -->
        <div class="mt-10 pt-6 border-t border-gray-100">
            <h2 class="text-xl font-bold text-dark mb-4">Other Venues</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $otherVenues = \App\Models\Venue::where('id', '!=', $package->venue_id)->take(3)->get();
                @endphp
                
                @foreach($otherVenues as $venue)
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
                        <div class="h-48 overflow-hidden">
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
                            <h4 class="font-bold text-dark">{{ $venue->name }}</h4>
                            
                            <div class="flex items-center text-gray-600 mt-1 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $venue->city }}, {{ $venue->state }}</span>
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center">
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
                                    
                                    <span class="bg-primary text-white px-3 py-1 rounded text-sm">
                                        View Details
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection @php
                            $itemsByCategory = $package->packageItems->groupBy(function($item) {
                                return $item->item->category->name;
                            });
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($itemsByCategory as $categoryName => $items)
                                <div class="border border-gray-100 rounded-lg overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                                        <h3 class="font-medium text-gray-800">{{ $categoryName }}</h3>
                                    </div>
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($items as $packageItem)
                                                <div class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-800">{{ $packageItem->item->name }}</div>
                                                        @if($packageItem->description)
                                                            <p class="text-xs text-gray-600 mt-1">{{ $packageItem->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Venue Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <span class="block text-gray-800 font-medium">{{ $package->venue->name }}</span>
                            <span class="text-gray-600 text-sm">{{ $package->venue->address_line_1 }}, {{ $package->venue->city }}, {{ $package->venue->state }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Sidebar (Right Side) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-8">
                    <div class="p-1 bg-primary"></div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-dark mb-3">Package Pricing</h3>
                        
                        @if($package->prices->count() > 0)
                            <div class="bg-gray-50 rounded-lg overflow-hidden mb-4">
                                <div class="px-4 py-2 bg-gray-100 text-dark font-medium text-sm">
                                    Price by Guest Count
                                </div>
                                <div class="p-3">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($package->prices->sortBy('pax') as $price)
                                            <div class="py-2 flex justify-between items-center">
                                                <span class="text-gray-700">{{ $price->pax }} guests</span>
                                                <span class="font-semibold text-primary">RM {{ number_format($price->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Starting Price Highlight -->
                            <div class="text-center mb-4">
                                <p class="text-gray-500 text-xs">Starting from</p>
                                <p class="text-primary text-2xl font-bold">RM {{ number_format($package->min_price, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 p-3 rounded-lg text-center mb-4">
                                <p class="text-gray-600">Contact us for pricing details</p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="space-y-2 mb-4">
                            <a href="#" class="block w-full bg-primary text-white text-center py-2 rounded hover:bg-opacity-90 transition text-sm">
                                Book This Package
                            </a>
                            <a href="https://wa.me/60123456789?text=I'm interested in {{ $package->name }} at {{ $package->venue->name }}" target="_blank" class="block w-full bg-green-500 text-white text-center py-2 rounded hover:bg-opacity-90 transition text-sm flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                                </svg>
                                WhatsApp
                            </a>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="text-center text-sm text-gray-600">
                            <p>Questions? Call us at</p>
                            <p class="font-medium text-gray-800">+60 123 456 789</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Packages (if any) -->
        @if($relatedPackages->count() > 0)
            <div class="mt-10 pt-6 border-t border-gray-100">
                <h2 class="text-xl font-bold text-dark mb-4">Other Packages at {{ $package->venue->name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPackages as $relatedPackage)
                        <a href="{{ route('public.package', $relatedPackage) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 block">
                            <div class="p-1 bg-primary"></div>
                            <div class="p-4">
                                <h4 class="font-bold text-dark">{{ $relatedPackage->name }}</h4>
                                <div class="my-3 pt-3 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500">Starting from</p>
                                            <p class="text-primary font-bold">
                                                @if($relatedPackage->prices->count() > 0)
                                                    RM {{ number_format($relatedPackage->min_price, 0, ',', '.') }}
                                                @else
                                                    Contact us
                                                @endif
                                            </p>
                                        </div>
                                        
                                        <span class="bg-primary text-white px-3 py-1 rounded text-sm">
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
    </div>
</div>
@endsection