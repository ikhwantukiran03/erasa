@extends('layouts.app')

@section('title', 'Book Your Event - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-display font-bold text-primary">Book Your Special Day</h1>
                <p class="text-gray-600 mt-2">Fill out the form below to request a booking at Enak Rasa Wedding Hall</p>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc ml-4 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('booking-requests.store') }}" method="POST" class="booking p-6 md:p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="block text-dark font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="Enter your full name"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="block text-dark font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="your@email.com"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_no" class="block text-dark font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="whatsapp_no" 
                                name="whatsapp_no" 
                                value="{{ old('whatsapp_no', Auth::check() ? Auth::user()->whatsapp : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="+62 812 3456 7890"
                            >
                            <p class="text-sm text-gray-500 mt-1">Include country code for WhatsApp notifications</p>
                        </div>
                        
                        <!-- Booking Details -->
                        <div class="md:col-span-2 pt-4 border-t border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Booking Details</h2>
                        </div>
                        
                        <div class="form-group">
                            <label for="type" class="block text-dark font-medium mb-1">Request Type <span class="text-red-500">*</span></label>
                            <select 
                                id="type" 
                                name="type" 
                                required 
                                class="form-input w-full"
                            >
                                <option value="booking" {{ old('type') == 'booking' ? 'selected' : '' }}>Booking (Confirm Reservation)</option>
                                <option value="reservation" {{ old('type') == 'reservation' ? 'selected' : '' }}>Reservation (Hold Date)</option>
                                <option value="viewing" {{ old('type') == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                <option value="appointment" {{ old('type') == 'appointment' ? 'selected' : '' }}>Consultation Appointment</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                            <input 
                                type="date" 
                                id="event_date" 
                                name="event_date" 
                                value="{{ old('event_date') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="form-input w-full"
                            >
                            <p class="text-sm text-gray-500 mt-1">Tentative date for your event</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="venue_id" class="block text-dark font-medium mb-1">Preferred Venue</label>
                            <select id="venue_id" name="venue_id" class="form-input w-full">
                                <option value="">-- Select Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="package_id" class="block text-dark font-medium mb-1">Interested Package</label>
                            <select id="package_id" name="package_id" class="form-input w-full">
                                <option value="">-- Select Package --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} ({{ $package->venue->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- New Price Selection Field -->
                        <div class="form-group" id="price-selection-container">
                            <label for="price_id" class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                            <select id="price_id" name="price_id" class="form-input w-full">
                                <option value="">-- Select Number of Guests --</option>
                                <!-- Price options will be populated via JavaScript -->
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select the expected number of guests for your event</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="message" class="block text-dark font-medium mb-1">Your Message <span class="text-red-500">*</span></label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                required 
                                class="form-input w-full"
                                placeholder="Please share details about your event, guest count, and any special requirements or questions"
                            >{{ old('message') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90 transition">
                            Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Store package prices data
        const packagesData = @json($packages->map(function($package) {
            return [
                'id' => $package->id,
                'prices' => $package->prices->map(function($price) {
                    return [
                        'id' => $price->id,
                        'pax' => $price->pax,
                        'price' => $price->price
                    ];
                })
            ];
        }));
        
        // Get DOM elements
        const venueSelect = document.getElementById('venue_id');
        const packageSelect = document.getElementById('package_id');
        const priceSelect = document.getElementById('price_id');
        const priceContainer = document.getElementById('price-selection-container');
        
        // Store original package options
        const originalPackages = Array.from(packageSelect.options);
        
        // Initialize with any pre-selected values
        const oldPackageId = "{{ old('package_id') }}";
        const oldPriceId = "{{ old('price_id') }}";
        
        // Hide price selection initially if no package is selected
        if (!oldPackageId) {
            priceContainer.style.display = 'none';
        } else {
            updatePriceOptions(oldPackageId, oldPriceId);
        }
        
        // When venue changes, update package options
        venueSelect.addEventListener('change', function() {
            const selectedVenueId = this.value;
            
            // Reset packages dropdown
            packageSelect.innerHTML = '<option value="">-- Select Package --</option>';
            
            if (!selectedVenueId) {
                // If no venue selected, show all packages
                originalPackages.forEach(option => {
                    if (option.value) { // Skip the placeholder option
                        packageSelect.appendChild(option.cloneNode(true));
                    }
                });
            } else {
                // Filter packages for selected venue
                originalPackages.forEach(option => {
                    if (option.value && option.text.includes(`(${venueSelect.options[venueSelect.selectedIndex].text})`)) {
                        packageSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
            
            // Reset and hide price options since package changed
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            priceContainer.style.display = 'none';
        });
        
        // When package changes, update price options
        packageSelect.addEventListener('change', function() {
            const selectedPackageId = this.value;
            
            if (!selectedPackageId) {
                // If no package selected, hide price selection
                priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
                priceContainer.style.display = 'none';
            } else {
                // Show price selection and update options
                priceContainer.style.display = 'block';
                updatePriceOptions(selectedPackageId);
            }
        });
        
        // Function to update price options based on selected package
        function updatePriceOptions(packageId, selectedPriceId = null) {
            // Reset price dropdown
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            
            // Find package in packagesData
            const packageData = packagesData.find(pkg => pkg.id == packageId);
            
            if (packageData && packageData.prices && packageData.prices.length > 0) {
                // Add options for each price
                packageData.prices.forEach(price => {
                    const option = document.createElement('option');
                    option.value = price.id;
                    option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    
                    // Select if it matches the old value
                    if (selectedPriceId && price.id == selectedPriceId) {
                        option.selected = true;
                    }
                    
                    priceSelect.appendChild(option);
                });
                
                // Show price container
                priceContainer.style.display = 'block';
            } else {
                // Hide price container if no prices available
                priceContainer.style.display = 'none';
            }
        }
        
        // Add animation for form inputs
        const formInputs = document.querySelectorAll('.form-input');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.form-group').classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.closest('.form-group').classList.remove('input-focused');
            });
        });
    });
</script>
@endpush
@endsection