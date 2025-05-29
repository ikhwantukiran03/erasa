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
                    
                    <!-- Payment Option Selection -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Choose Payment Option</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="payment-option border rounded-lg p-4 hover:border-primary cursor-pointer">
                                <input type="radio" name="payment_option" id="deposit_payment" value="deposit" class="hidden payment-radio" checked>
                                <label for="deposit_payment" class="cursor-pointer block">
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <span class="font-semibold text-gray-800">Deposit Payment</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Pay RM 3,000 deposit now, remaining amount later</p>
                                    <div class="mt-2 text-blue-600 font-bold">RM 3,000 (Deposit)</div>
                                </label>
                            </div>
                            
                            <div class="payment-option border rounded-lg p-4 hover:border-primary cursor-pointer">
                                <input type="radio" name="payment_option" id="full_payment" value="full" class="hidden payment-radio">
                                <label for="full_payment" class="cursor-pointer block">
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-semibold text-gray-800">Full Payment</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Pay the complete amount now (no additional payments needed)</p>
                                    <div class="mt-2 text-green-600 font-bold" id="fullPaymentAmount">Full Amount</div>
                                </label>
                            </div>
                        </div>
                        @error('payment_option')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Package Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select a Wedding Package</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($packages as $package)
                            <div class="package-selector border rounded-lg p-4 hover:border-primary cursor-pointer @error('package_id') border-red-500 @enderror">
                                <input type="radio" name="package_id" id="package_{{ $package->id }}" value="{{ $package->id }}" class="hidden package-radio" required>
                                <label for="package_{{ $package->id }}" class="cursor-pointer flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-800">{{ $package->name }}</h4>
                                            <button type="button" class="package-details-btn text-primary hover:text-primary-dark text-sm" data-package-id="{{ $package->id }}">
                                                View Details
                                            </button>
                                        </div>
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
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paxSelectionContainer = document.getElementById('paxSelectionContainer');
    const paxOptions = document.getElementById('paxOptions');
    const submitBtn = document.getElementById('submitBtn');
    const fullPaymentAmount = document.getElementById('fullPaymentAmount');
    
    let selectedPackageData = null;
    let selectedPriceData = null;
    
    // Package data with prices
    const packageData = {
        @foreach($packages as $package)
            {{ $package->id }}: {
                name: "{{ $package->name }}",
                description: "{{ $package->description }}",
                items: [
                    @foreach($package->items as $item)
                        {
                            name: "{{ $item->item->name }}",
                            description: "{{ $item->description ?? '' }}"
                        },
                    @endforeach
                ],
                prices: [
                    @foreach($package->prices as $price)
                        {
                            id: {{ $price->id }},
                            pax: {{ $price->pax }},
                            price: {{ $price->price }},
                        },
                    @endforeach
                ]
            },
        @endforeach
    };
    
    // Handle payment option selection
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Clear previous selections
            paymentOptions.forEach(o => o.classList.remove('border-primary', 'bg-primary-50'));
            
            // Select this option
            this.classList.add('border-primary', 'bg-primary-50');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            updateFullPaymentAmount();
        });
    });
    
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
            selectedPackageData = packageData[packageId];
            
            // Update pax options
            updatePaxOptions(packageId);
            updateFullPaymentAmount();
        });
    });
    
    // Handle package details buttons
    document.querySelectorAll('.package-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const packageId = this.getAttribute('data-package-id');
            showPackageDetails(packageId);
        });
    });
    
    function updatePaxOptions(packageId) {
        // Get prices for this package
        const prices = packageData[packageId]?.prices || [];
        
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
                
                selectedPriceData = price;
                updateFullPaymentAmount();
                
                // Enable submit button
                submitBtn.disabled = false;
            });
        });
    }
    
    function updateFullPaymentAmount() {
        if (selectedPriceData) {
            fullPaymentAmount.textContent = `RM ${selectedPriceData.price.toFixed(2)} (Full Amount)`;
        } else if (selectedPackageData && selectedPackageData.prices.length > 0) {
            const minPrice = Math.min(...selectedPackageData.prices.map(p => p.price));
            const maxPrice = Math.max(...selectedPackageData.prices.map(p => p.price));
            if (minPrice === maxPrice) {
                fullPaymentAmount.textContent = `RM ${minPrice.toFixed(2)} (Full Amount)`;
            } else {
                fullPaymentAmount.textContent = `RM ${minPrice.toFixed(2)} - RM ${maxPrice.toFixed(2)} (Full Amount)`;
            }
        } else {
            fullPaymentAmount.textContent = 'Full Amount';
        }
    }
    
    function showPackageDetails(packageId) {
        const package = packageData[packageId];
        if (!package) return;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">${package.name} - Package Details</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">${package.description}</p>
                    
                    <h4 class="font-semibold text-gray-800 mb-3">Package Includes:</h4>
                    <div class="space-y-2 mb-6">
                        ${package.items.map(item => `
                            <div class="flex items-start space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-medium text-gray-800">${item.name}</span>
                                    ${item.description ? `<p class="text-sm text-gray-600">${item.description}</p>` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <h4 class="font-semibold text-gray-800 mb-3">Pricing Options:</h4>
                    <div class="space-y-2">
                        ${package.prices.map(price => `
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span>${price.pax} guests</span>
                                <span class="font-bold text-primary">RM ${price.price.toFixed(2)}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Handle close
        modal.querySelector('.close-modal').addEventListener('click', function() {
            document.body.removeChild(modal);
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
    }
});
</script>
@endsection 