@extends('layouts.app')

@section('title', 'Confirm Reservation - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Confirm Reservation</h1>
                <p class="text-gray-600 mt-1">Select a package and guest count to finalize your booking</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Booking Details
                </a>
            </div>
        </div>
        
        <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <!-- Booking Info -->
                <div class="bg-blue-50 p-5 rounded-lg mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Reservation Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Venue</p>
                            <p class="font-medium text-gray-800">{{ $booking->venue->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium text-gray-800">{{ $booking->booking_date->format('l, F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Session</p>
                            <p class="font-medium text-gray-800">{{ $booking->session === 'morning' ? 'Morning' : 'Evening' }}</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('user.bookings.confirm', $booking) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Package Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select a Wedding Package</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($packages as $package)
                            <div class="package-selector border rounded-lg p-4 hover:border-primary cursor-pointer @error('package_id') border-red-500 @enderror">
                                <input type="radio" name="package_id" id="package_{{ $package->id }}" value="{{ $package->id }}" class="hidden package-radio" required>
                                <label for="package_{{ $package->id }}" class="cursor-pointer flex flex-col h-full">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $package->name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $package->description }}</p>
                                        <div class="mt-2 text-primary font-bold">
                                            @if($package->min_price == $package->max_price)
                                                RM {{ number_format($package->min_price, 2) }}
                                            @else
                                                RM {{ number_format($package->min_price, 2) }} - RM {{ number_format($package->max_price, 2) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <span class="text-sm font-medium text-primary">Click to select</span>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('package_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Pax Selection -->
                    <div id="paxSelectionContainer" class="hidden mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Guest Count (Pax)</h3>
                        <div id="paxOptions" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <!-- Will be populated via JavaScript -->
                        </div>
                        @error('price_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit" id="submitBtn" disabled class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-lg font-medium transition-colors hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirm Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageSelectors = document.querySelectorAll('.package-selector');
    const paxSelectionContainer = document.getElementById('paxSelectionContainer');
    const paxOptions = document.getElementById('paxOptions');
    const submitBtn = document.getElementById('submitBtn');
    
    // Package data with prices
    const packageData = {
        @foreach($packages as $package)
            {{ $package->id }}: [
                @foreach($package->prices as $price)
                    {
                        id: {{ $price->id }},
                        pax: {{ $price->pax }},
                        price: {{ $price->price }},
                    },
                @endforeach
            ],
        @endforeach
    };
    
    // Handle package selection
    packageSelectors.forEach(selector => {
        selector.addEventListener('click', function() {
            // Clear previous selections
            packageSelectors.forEach(s => s.classList.remove('border-primary', 'bg-primary-50'));
            
            // Select this package
            this.classList.add('border-primary', 'bg-primary-50');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Get package id
            const packageId = radio.value;
            
            // Update pax options
            updatePaxOptions(packageId);
        });
    });
    
    function updatePaxOptions(packageId) {
        // Get prices for this package
        const prices = packageData[packageId] || [];
        
        // Clear previous options
        paxOptions.innerHTML = '';
        
        if (prices.length === 0) {
            paxSelectionContainer.classList.add('hidden');
            return;
        }
        
        // Show container
        paxSelectionContainer.classList.remove('hidden');
        
        // Add options
        prices.forEach(price => {
            const option = document.createElement('div');
            option.className = 'pax-option border rounded-lg p-3 hover:border-primary cursor-pointer';
            option.innerHTML = `
                <input type="radio" name="price_id" id="price_${price.id}" value="${price.id}" class="hidden pax-radio" required>
                <label for="price_${price.id}" class="cursor-pointer block">
                    <div class="font-medium text-gray-800">${price.pax} Guests</div>
                    <div class="text-primary font-bold mt-1">RM ${price.price.toFixed(2)}</div>
                </label>
            `;
            paxOptions.appendChild(option);
            
            // Handle selection
            option.addEventListener('click', function() {
                document.querySelectorAll('.pax-option').forEach(o => 
                    o.classList.remove('border-primary', 'bg-primary-50'));
                this.classList.add('border-primary', 'bg-primary-50');
                this.querySelector('input[type="radio"]').checked = true;
                
                // Enable submit button
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
@endsection 