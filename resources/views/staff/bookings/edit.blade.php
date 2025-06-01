@extends('layouts.app')

@section('title', 'Edit Booking - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-display font-bold text-primary">Edit Booking #{{ $booking->id }}</h1>
                    <p class="text-gray-600 mt-2">Update booking information</p>
                </div>
                <a href="{{ route('staff.bookings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Bookings
                </a>
            </div>
            
            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold">Please fix the following errors:</p>
                            <ul class="list-disc ml-5 mt-1 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Main Form -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <form action="{{ route('staff.bookings.update', $booking) }}" method="POST" id="bookingForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Customer Information -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Customer Information
                                    </h3>
                                    <div>
                                        <label for="user_id" class="block text-dark font-medium mb-2">Customer <span class="text-red-500">*</span></label>
                                        <select id="user_id" name="user_id" required class="form-input w-full">
                                            <option value="">-- Select Customer --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Venue & Package -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Venue & Package
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="venue_id" class="block text-dark font-medium mb-2">Venue <span class="text-red-500">*</span></label>
                                            <select id="venue_id" name="venue_id" required class="form-input w-full">
                                                <option value="">-- Select Venue --</option>
                                                @foreach($venues as $venue)
                                                    <option value="{{ $venue->id }}" {{ old('venue_id', $booking->venue_id) == $venue->id ? 'selected' : '' }}>
                                                        {{ $venue->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="package_id" class="block text-dark font-medium mb-2">Package (Optional)</label>
                                            <select id="package_id" name="package_id" class="form-input w-full">
                                                <option value="">-- No Package --</option>
                                                @foreach($packages as $package)
                                                    <option 
                                                        value="{{ $package->id }}" 
                                                        data-venue-id="{{ $package->venue_id }}"
                                                        {{ old('package_id', $booking->package_id) == $package->id ? 'selected' : '' }}
                                                    >
                                                        {{ $package->name }} ({{ $package->venue->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div id="price-selection-container" {{ $booking->package_id ? '' : 'style=display:none;' }}>
                                            <label for="price_id" class="block text-dark font-medium mb-2">Number of Guests</label>
                                            <select id="price_id" name="price_id" class="form-input w-full">
                                                <option value="">-- Select Number of Guests --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Date & Session -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Date & Session
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="booking_date" class="block text-dark font-medium mb-2">Booking Date <span class="text-red-500">*</span></label>
                                            <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required class="form-input w-full">
                                            <p class="text-sm text-gray-500 mt-1" id="booking-date-help">Select the event date</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-dark font-medium mb-2">Session <span class="text-red-500">*</span></label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="session-container">
                                                <div class="session-card">
                                                    <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" {{ old('session', $booking->session) == 'morning' ? 'checked' : '' }}>
                                                    <label for="session-morning" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h4 class="font-medium text-gray-800">Morning</h4>
                                                                <p class="text-xs text-gray-600">11AM - 4PM</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="session-card">
                                                    <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" {{ old('session', $booking->session) == 'evening' ? 'checked' : '' }}>
                                                    <label for="session-evening" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h4 class="font-medium text-gray-800">Evening</h4>
                                                                <p class="text-xs text-gray-600">7PM - 11PM</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="no-sessions-message" class="text-sm text-red-600 mt-2 text-center" style="display: none;">
                                                No sessions available for the selected date and venue.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Booking Details -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Booking Details
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="type" class="block text-dark font-medium mb-2">Booking Type <span class="text-red-500">*</span></label>
                                            <select id="type" name="type" required class="form-input w-full">
                                                <option value="wedding" {{ old('type', $booking->type) == 'wedding' ? 'selected' : '' }}>Wedding</option>
                                                <option value="viewing" {{ old('type', $booking->type) == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                                <option value="reservation" {{ old('type', $booking->type) == 'reservation' ? 'selected' : '' }}>Reservation</option>
                                                <option value="appointment" {{ old('type', $booking->type) == 'appointment' ? 'selected' : '' }}>Appointment</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="status" class="block text-dark font-medium mb-2">Status <span class="text-red-500">*</span></label>
                                            <select id="status" name="status" required class="form-input w-full">
                                                <option value="waiting for deposit" {{ old('status', $booking->status) == 'waiting for deposit' ? 'selected' : '' }}>Waiting for Deposit</option>
                                                <option value="pending_verification" {{ old('status', $booking->status) == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                                                <option value="ongoing" {{ old('status', $booking->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="expiry_date" class="block text-dark font-medium mb-2">
                                                Expiry Date
                                                <span class="text-red-500 reservation-required" style="display: none;">*</span>
                                            </label>
                                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $booking->expiry_date ? $booking->expiry_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}" class="form-input w-full">
                                            <p class="text-sm text-gray-500 mt-1" id="expiry-date-help">Date when booking expires if not confirmed (defaults to 7 days from today)</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Current Booking Summary -->
                                <div class="bg-primary bg-opacity-10 p-4 rounded-lg border border-primary border-opacity-20">
                                    <h3 class="text-lg font-semibold text-primary mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Current Booking Info
                                    </h3>
                                    <div class="text-sm text-gray-700 space-y-1">
                                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                                        <p><strong>Created:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
                                        <p><strong>Original Customer:</strong> {{ $booking->user ? $booking->user->name : 'User not found' }}</p>
                                        <p><strong>Original Venue:</strong> {{ $booking->venue ? $booking->venue->name : 'Venue not found' }}</p>
                                        <p><strong>Original Package:</strong> {{ $booking->package ? $booking->package->name : 'No package' }}</p>
                                        <p><strong>Original Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</p>
                                        <p><strong>Original Session:</strong> {{ ucfirst($booking->session) }}</p>
                                        <p><strong>Current Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($booking->status == 'completed') bg-green-100 text-green-800
                                            @elseif($booking->status == 'ongoing') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                        </span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <a href="{{ route('staff.bookings.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                            ← Cancel & Return
                        </a>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-dark transition-colors">
                            Update Booking
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
    const packagesData = {!! json_encode($packages->map(function($package) {
        return [
            'id' => $package->id,
            'venue_id' => $package->venue_id,
            'name' => $package->name,
            'prices' => $package->prices->map(function($price) {
                return [
                    'id' => $price->id,
                    'pax' => $price->pax,
                    'price' => $price->price
                ];
            })
        ];
    })) !!};

    const venueSelect = document.getElementById('venue_id');
    const packageSelect = document.getElementById('package_id');
    const priceSelect = document.getElementById('price_id');
    const priceContainer = document.getElementById('price-selection-container');
    const packageOptions = Array.from(packageSelect.options);
    const typeSelect = document.getElementById('type');
    const bookingDateInput = document.getElementById('booking_date');
    const dateHelpText = document.getElementById('booking-date-help');

    const oldPackageId = "{{ old('package_id', $booking->package_id) }}";
    const oldPriceId = "{{ old('price_id', $booking->price_id) }}";
    
    // Date validation function
    function updateDateValidation() {
        const selectedType = typeSelect.value;
        const today = new Date();
        let minDate;
        let helpText;
        
        if (selectedType === 'reservation' || selectedType === 'wedding') {
            minDate = new Date(today.getFullYear(), today.getMonth() + 6, today.getDate());
            helpText = 'Booking date must be at least 6 months from today for reservations and wedding bookings';
        } else {
            minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
            helpText = 'Select the event date';
        }
        
        const formattedMinDate = minDate.toISOString().split('T')[0];
        bookingDateInput.setAttribute('min', formattedMinDate);
        if (dateHelpText) dateHelpText.textContent = helpText;
    }

    function updateExpiryDateRequirement() {
        const selectedType = typeSelect.value;
        const expiryDateInput = document.getElementById('expiry_date');
        const expiryDateHelp = document.getElementById('expiry-date-help');
        const requiredIndicator = document.querySelector('.reservation-required');
        
        if (selectedType === 'reservation') {
            // Show required indicator for reservations
            if (requiredIndicator) {
                requiredIndicator.style.display = 'inline';
            }
            if (expiryDateInput) {
                expiryDateInput.setAttribute('required', 'required');
            }
            if (expiryDateHelp) {
                expiryDateHelp.textContent = 'Date when booking expires if not confirmed (required for reservations)';
                expiryDateHelp.className = 'text-sm text-red-600 mt-1';
            }
        } else {
            // Hide required indicator for other booking types
            if (requiredIndicator) {
                requiredIndicator.style.display = 'none';
            }
            if (expiryDateInput) {
                expiryDateInput.removeAttribute('required');
            }
            if (expiryDateHelp) {
                expiryDateHelp.textContent = 'Date when booking expires if not confirmed (optional - defaults to 7 days from today)';
                expiryDateHelp.className = 'text-sm text-gray-500 mt-1';
            }
        }
    }

    function filterPackages() {
        const venueId = venueSelect.value;
        packageSelect.innerHTML = '<option value="">-- No Package --</option>';

        packageOptions.forEach(option => {
            if (option.value && (!venueId || option.dataset.venueId === venueId)) {
                packageSelect.appendChild(option.cloneNode(true));
            }
        });

        if (oldPackageId) {
            Array.from(packageSelect.options).forEach(option => {
                if (option.value === oldPackageId) {
                    option.selected = true;
                }
            });
        }
    }

    function updatePriceOptions(packageId, selectedPriceId = null) {
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';

        const packageData = packagesData.find(pkg => pkg.id == packageId);

        if (packageData && packageData.prices.length > 0) {
            packageData.prices.forEach(price => {
                const option = document.createElement('option');
                option.value = price.id;
                option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2})}`;

                if (selectedPriceId && price.id == selectedPriceId) {
                    option.selected = true;
                }

                priceSelect.appendChild(option);
            });

            priceContainer.style.display = 'block';
        } else {
            priceContainer.style.display = 'none';
        }
    }

    function resetSessionOptions() {
        const sessionContainer = document.getElementById('session-container');
        const noSessionsMessage = document.getElementById('no-sessions-message');
        const originalBookingSession = "{{ $booking->session }}";
        const currentSelection = document.querySelector('input[name="session"]:checked');
        const currentValue = currentSelection ? currentSelection.value : null;
        
        // Only show sessions if no date/venue is selected, otherwise wait for availability check
        const date = bookingDateInput.value;
        const venueId = venueSelect.value;
        
        if (!date || !venueId) {
            sessionContainer.innerHTML = `
                <div class="session-card">
                    <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" ${currentValue === 'morning' || originalBookingSession === 'morning' ? 'checked' : ''}>
                    <label for="session-morning" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Morning</h4>
                                <p class="text-xs text-gray-600">11AM - 4PM</p>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="session-card">
                    <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" ${currentValue === 'evening' || originalBookingSession === 'evening' ? 'checked' : ''}>
                    <label for="session-evening" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Evening</h4>
                                <p class="text-xs text-gray-600">7PM - 11PM</p>
                            </div>
                        </div>
                    </label>
                </div>
            `;
        } else {
            // If date and venue are selected, show loading state until availability check completes
            sessionContainer.innerHTML = `
                <div class="col-span-2 text-center py-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="animate-spin w-6 h-6 border-2 border-primary border-t-transparent rounded-full mx-auto mb-2"></div>
                        <p class="text-sm text-gray-600">Checking session availability...</p>
                    </div>
                </div>
            `;
        }
        
        noSessionsMessage.style.display = 'none';
    }

    function checkAvailability() {
        const date = bookingDateInput.value;
        const venueId = venueSelect.value;
        const currentBookingId = {{ $booking->id }};

        console.log('Edit form - Checking availability - Date:', date, 'Venue ID:', venueId, 'Exclude booking:', currentBookingId);

        if (!date || !venueId) {
            console.log('Missing date or venue, resetting sessions');
            resetSessionOptions();
            return;
        }

        // Show loading state immediately
        const sessionContainer = document.getElementById('session-container');
        sessionContainer.innerHTML = `
            <div class="col-span-2 text-center py-4">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="animate-spin w-6 h-6 border-2 border-primary border-t-transparent rounded-full mx-auto mb-2"></div>
                    <p class="text-sm text-gray-600">Checking session availability...</p>
                </div>
            </div>
        `;

        console.log('Making API calls for availability check (edit mode)');
        
        const morningUrl = `/api/check-availability?date=${date}&session=morning&venue_id=${venueId}&exclude_booking_id=${currentBookingId}`;
        const eveningUrl = `/api/check-availability?date=${date}&session=evening&venue_id=${venueId}&exclude_booking_id=${currentBookingId}`;
        
        console.log('Morning URL:', morningUrl);
        console.log('Evening URL:', eveningUrl);
        
        Promise.all([
            fetch(morningUrl).then(response => {
                console.log('Morning response status:', response.status);
                if (!response.ok) {
                    throw new Error(`Morning API call failed: ${response.status} ${response.statusText}`);
                }
                return response.json();
            }),
            fetch(eveningUrl).then(response => {
                console.log('Evening response status:', response.status);
                if (!response.ok) {
                    throw new Error(`Evening API call failed: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
        ]).then(([morningData, eveningData]) => {
            console.log('Morning data:', morningData);
            console.log('Evening data:', eveningData);
            console.log('Edit form - Morning available:', morningData.available, 'Evening available:', eveningData.available);
            updateSessionOptions(morningData.available, eveningData.available);
        }).catch(error => {
            console.error('Availability check failed:', error);
            console.error('Error details:', error.message);
            // On error, show all sessions but with a warning
            sessionContainer.innerHTML = `
                <div class="col-span-2 text-center py-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <svg class="w-6 h-6 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-sm text-yellow-800 font-medium">Unable to check availability</p>
                        <p class="text-xs text-yellow-700 mt-1">Error: ${error.message}</p>
                        <p class="text-xs text-yellow-700">Please verify session availability manually</p>
                    </div>
                </div>
            `;
        });
    }

    function updateSessionOptions(morningAvailable, eveningAvailable) {
        const sessionContainer = document.getElementById('session-container');
        const noSessionsMessage = document.getElementById('no-sessions-message');
        const originalBookingSession = "{{ $booking->session }}";
        const currentSelection = document.querySelector('input[name="session"]:checked');
        const currentValue = currentSelection ? currentSelection.value : null;

        sessionContainer.innerHTML = '';

        if (morningAvailable || originalBookingSession === 'morning') {
            const morningCard = document.createElement('div');
            morningCard.className = 'session-card';
            morningCard.innerHTML = `
                <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" ${currentValue === 'morning' || originalBookingSession === 'morning' ? 'checked' : ''}>
                <label for="session-morning" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Morning</h4>
                            <p class="text-xs text-gray-600">11AM - 4PM</p>
                        </div>
                    </div>
                </label>
            `;
            sessionContainer.appendChild(morningCard);
        }

        if (eveningAvailable || originalBookingSession === 'evening') {
            const eveningCard = document.createElement('div');
            eveningCard.className = 'session-card';
            eveningCard.innerHTML = `
                <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" ${currentValue === 'evening' || originalBookingSession === 'evening' ? 'checked' : ''}>
                <label for="session-evening" class="block p-3 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Evening</h4>
                            <p class="text-xs text-gray-600">7PM - 11PM</p>
                        </div>
                    </div>
                </label>
            `;
            sessionContainer.appendChild(eveningCard);
        }

        if (!morningAvailable && !eveningAvailable && originalBookingSession !== 'morning' && originalBookingSession !== 'evening') {
            const noSessionCard = document.createElement('div');
            noSessionCard.className = 'col-span-2';
            noSessionCard.innerHTML = `
                <div class="p-3 bg-gray-100 border-2 border-gray-300 rounded-lg text-center">
                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-600">No Sessions Available</h4>
                    <p class="text-xs text-gray-500">Choose a different date</p>
                </div>
            `;
            sessionContainer.appendChild(noSessionCard);
            noSessionsMessage.style.display = 'block';
        } else {
            noSessionsMessage.style.display = 'none';
        }
    }

    // Event Listeners
    venueSelect.addEventListener('change', () => {
        filterPackages();
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
        priceContainer.style.display = 'none';
        checkAvailability();
    });

    packageSelect.addEventListener('change', function() {
        const packageId = this.value;
        if (packageId) {
            updatePriceOptions(packageId);
        } else {
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            priceContainer.style.display = 'none';
        }
    });

    bookingDateInput.addEventListener('change', checkAvailability);
    typeSelect.addEventListener('change', updateDateValidation);
    typeSelect.addEventListener('change', updateExpiryDateRequirement);

    // Initialize
    filterPackages();
    if (oldPackageId) {
        updatePriceOptions(oldPackageId, oldPriceId);
    }
    updateDateValidation();
    updateExpiryDateRequirement();
    
    // Check availability on page load
    setTimeout(() => {
        console.log('Edit form - Page load - checking availability after timeout');
        // If date and venue are already selected, check availability immediately
        const date = bookingDateInput.value;
        const venueId = venueSelect.value;
        
        if (date && venueId) {
            // Show loading state immediately if we have date and venue
            const sessionContainer = document.getElementById('session-container');
            sessionContainer.innerHTML = `
                <div class="col-span-2 text-center py-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="animate-spin w-6 h-6 border-2 border-primary border-t-transparent rounded-full mx-auto mb-2"></div>
                        <p class="text-sm text-gray-600">Checking session availability...</p>
                    </div>
                </div>
            `;
        }
        
        checkAvailability();
    }, 100);
});
</script>

<style>
.session-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.form-input {
    border-radius: 0.5rem;
    border: 1px solid #D1D5DB;
    transition: all 0.2s ease;
    padding: 0.5rem 0.75rem;
}

.form-input:focus {
    outline: none;
    border-color: #D4A373;
    box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.1);
}
</style>
@endpush
@endsection