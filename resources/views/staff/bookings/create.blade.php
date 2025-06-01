@extends('layouts.app')

@section('title', 'Create Booking - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-display font-bold text-primary">Create New Booking</h1>
                    <p class="text-gray-600 mt-2">Complete all fields to create a new booking</p>
                </div>
                <a href="{{ route('staff.bookings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Bookings
                </a>
            </div>
            
            @php
                $bookingRequestData = session('booking_request_data');
            @endphp
            
            @if($bookingRequestData)
            <!-- Booking Request Information -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800 font-medium">Creating booking from approved booking request</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Customer: <strong>{{ $bookingRequestData['customer_name'] }}</strong> ({{ $bookingRequestData['customer_email'] }})
                            @if($bookingRequestData['account_created'])
                                <br><span class="text-green-700 font-medium">✓ New user account created</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
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
                <form action="{{ route('staff.bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    @if($bookingRequestData)
                        <input type="hidden" name="booking_request_id" value="{{ $bookingRequestData['booking_request_id'] }}">
                    @endif
                    
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
                                                <option value="{{ $user->id }}" {{ 
                                                    ($bookingRequestData && $bookingRequestData['user_id'] == $user->id) || 
                                                    old('user_id') == $user->id ? 'selected' : '' 
                                                }}>
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
                                                    <option value="{{ $venue->id }}" {{ 
                                                        ($bookingRequestData && $bookingRequestData['venue_id'] == $venue->id) || 
                                                        old('venue_id') == $venue->id ? 'selected' : '' 
                                                    }}>
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
                                                        {{ 
                                                            ($bookingRequestData && $bookingRequestData['package_id'] == $package->id) || 
                                                            old('package_id') == $package->id ? 'selected' : '' 
                                                        }}
                                                    >
                                                        {{ $package->name }} ({{ $package->venue->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div id="price-selection-container" style="display: none;">
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
                                            <input type="date" id="booking_date" name="booking_date" value="{{ 
                                                $bookingRequestData['booking_date'] ?? old('booking_date') 
                                            }}" required class="form-input w-full" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                            <p class="text-sm text-gray-500 mt-1" id="booking-date-help">Select the event date</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-dark font-medium mb-2">Session <span class="text-red-500">*</span></label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="session-container">
                                                <div class="session-card">
                                                    <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" {{ 
                                                        ($bookingRequestData && $bookingRequestData['session'] == 'morning') || 
                                                        old('session') == 'morning' ? 'checked' : '' 
                                                    }}>
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
                                                    <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" {{ 
                                                        ($bookingRequestData && $bookingRequestData['session'] == 'evening') || 
                                                        old('session') == 'evening' || 
                                                        (!$bookingRequestData && !old('session')) ? 'checked' : '' 
                                                    }}>
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
                                                <option value="wedding" {{ 
                                                    ($bookingRequestData && $bookingRequestData['type'] == 'wedding') || 
                                                    old('type') == 'wedding' || 
                                                    (!$bookingRequestData && !old('type')) ? 'selected' : '' 
                                                }}>Wedding</option>
                                                <option value="viewing" {{ 
                                                    ($bookingRequestData && $bookingRequestData['type'] == 'viewing') || 
                                                    old('type') == 'viewing' ? 'selected' : '' 
                                                }}>Venue Viewing</option>
                                                <option value="reservation" {{ 
                                                    ($bookingRequestData && $bookingRequestData['type'] == 'reservation') || 
                                                    old('type') == 'reservation' ? 'selected' : '' 
                                                }}>Reservation</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="status" class="block text-dark font-medium mb-2">Status <span class="text-red-500">*</span></label>
                                            <select id="status" name="status" required class="form-input w-full">
                                                <option value="waiting for deposit" {{ 
                                                    ($bookingRequestData && $bookingRequestData['status'] == 'waiting for deposit') || 
                                                    old('status') == 'waiting for deposit' || 
                                                    (!$bookingRequestData && !old('status')) ? 'selected' : '' 
                                                }}>Waiting for Deposit</option>
                                                <option value="ongoing" {{ 
                                                    ($bookingRequestData && $bookingRequestData['status'] == 'ongoing') || 
                                                    old('status') == 'ongoing' ? 'selected' : '' 
                                                }}>Ongoing</option>
                                                <option value="pending_verification" {{ 
                                                    ($bookingRequestData && $bookingRequestData['status'] == 'pending_verification') || 
                                                    old('status') == 'pending_verification' ? 'selected' : '' 
                                                }}>Pending Verification</option>
                                                <option value="completed" {{ 
                                                    ($bookingRequestData && $bookingRequestData['status'] == 'completed') || 
                                                    old('status') == 'completed' ? 'selected' : '' 
                                                }}>Completed</option>
                                                <option value="cancelled" {{ 
                                                    ($bookingRequestData && $bookingRequestData['status'] == 'cancelled') || 
                                                    old('status') == 'cancelled' ? 'selected' : '' 
                                                }}>Cancelled</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="expiry_date" class="block text-dark font-medium mb-2">
                                                Expiry Date
                                                <span class="text-red-500 reservation-required" style="display: none;">*</span>
                                            </label>
                                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', date('Y-m-d', strtotime('+7 days'))) }}" class="form-input w-full">
                                            <p class="text-sm text-gray-500 mt-1" id="expiry-date-help">Date when booking expires if not confirmed (defaults to 7 days from today)</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Summary -->
                                <div class="bg-primary bg-opacity-10 p-4 rounded-lg border border-primary border-opacity-20">
                                    <h3 class="text-lg font-semibold text-primary mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Booking Summary
                                    </h3>
                                    <div id="booking-summary" class="text-sm text-gray-700 space-y-1">
                                        <p><strong>Customer:</strong> <span id="summary-customer">Not selected</span></p>
                                        <p><strong>Venue:</strong> <span id="summary-venue">Not selected</span></p>
                                        <p><strong>Package:</strong> <span id="summary-package">No package</span></p>
                                        <p><strong>Date:</strong> <span id="summary-date">Not selected</span></p>
                                        <p><strong>Session:</strong> <span id="summary-session">Not selected</span></p>
                                        <p><strong>Type:</strong> <span id="summary-type">Wedding</span></p>
                                        <p><strong>Status:</strong> <span id="summary-status">Waiting for deposit</span></p>
                                        <p><strong>Expires:</strong> <span id="summary-expiry">7 days from today</span></p>
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
                            Create Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-step {
    display: none;
}

.form-step.active {
    display: block;
}

.step-indicator.active .w-8 {
    background-color: #D4A373;
    color: white;
}

.step-indicator.active span {
    color: #D4A373;
    font-weight: 500;
}

.step-indicator.completed .w-8 {
    background-color: #10B981;
    color: white;
}

.step-indicator.completed span {
    color: #10B981;
    font-weight: 500;
}

.venue-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.package-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.pricing-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.session-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 768px) {
    .venue-card, .package-card {
        margin-bottom: 1rem;
    }
    
    .form-step {
        padding: 0.5rem;
    }
    
    .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .grid.grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

.form-input {
    border-radius: 0.5rem;
    border: 1px solid #D1D5DB;
    transition: all 0.2s ease;
}

.form-input:focus {
    outline: none;
    border-color: #D4A373;
    box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.1);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packagesData = {!! json_encode($packages->map(function($package) {
        return [
            'id' => $package->id,
            'venue_id' => $package->venue_id,
            'name' => $package->name,
            'description' => $package->description,
            'prices' => $package->prices->map(function($price) {
                return [
                    'id' => $price->id,
                    'pax' => $price->pax,
                    'price' => $price->price
                ];
            })
        ];
    })) !!};

    const venuesData = {!! json_encode($venues->map(function($venue) {
        return [
            'id' => $venue->id,
            'name' => $venue->name
        ];
    })) !!};

    const usersData = {!! json_encode($users->map(function($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    })) !!};

    // Check if this is from an approved booking request
    const isFromBookingRequest = {{ $bookingRequestData ? 'true' : 'false' }};
    let formSubmitted = false;
    
    // Prevent leaving the page if from booking request and form not submitted
    if (isFromBookingRequest) {
        // Add beforeunload event listener
        window.addEventListener('beforeunload', function(e) {
            if (!formSubmitted) {
                const message = 'You have an approved booking request that needs to be completed. Are you sure you want to leave without finishing the booking creation?';
                e.preventDefault();
                e.returnValue = message;
                return message;
            }
        });
        
        // Add click listeners to navigation links
        document.addEventListener('click', function(e) {
            if (!formSubmitted) {
                const link = e.target.closest('a');
                if (link && link.href && !link.href.includes('#') && !link.classList.contains('allow-navigation')) {
                    e.preventDefault();
                    showNavigationWarning(link.href);
                }
            }
        });
        
        // Show warning banner
        showBookingRequestWarning();
    }
    
    // Mark form as submitted when form is actually submitted
    document.getElementById('bookingForm').addEventListener('submit', function() {
        formSubmitted = true;
    });
    
    function showBookingRequestWarning() {
        const warningBanner = document.createElement('div');
        warningBanner.id = 'booking-request-warning';
        warningBanner.className = 'fixed top-0 left-0 right-0 bg-orange-500 text-white p-3 z-50 shadow-lg';
        warningBanner.innerHTML = `
            <div class="container mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="font-medium">⚠️ Complete Booking Required - You must finish creating this booking from the approved request</span>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        document.body.insertBefore(warningBanner, document.body.firstChild);
        
        // Adjust page padding to account for warning banner
        document.body.style.paddingTop = '60px';
    }
    
    function showNavigationWarning(targetUrl) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Incomplete Booking</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    You have an approved booking request that needs to be completed. Leaving now will require you to manually create the booking later.
                </p>
                <div class="flex space-x-3">
                    <button onclick="continueNavigation('${targetUrl}')" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Leave Anyway
                    </button>
                    <button onclick="closeModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Stay & Complete
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    window.continueNavigation = function(url) {
        formSubmitted = true; // Allow navigation
        window.location.href = url;
    };
    
    window.closeModal = function() {
        const modal = document.querySelector('.fixed.inset-0.bg-black');
        if (modal) {
            modal.remove();
        }
    };

    // Elements
    const userSelect = document.getElementById('user_id');
    const venueSelect = document.getElementById('venue_id');
    const packageSelect = document.getElementById('package_id');
    const priceSelect = document.getElementById('price_id');
    const priceContainer = document.getElementById('price-selection-container');
    const bookingDateInput = document.getElementById('booking_date');
    const typeSelect = document.getElementById('type');
    const statusSelect = document.getElementById('status');
    const packageOptions = Array.from(packageSelect.options);

    // Summary elements
    const summaryCustomer = document.getElementById('summary-customer');
    const summaryVenue = document.getElementById('summary-venue');
    const summaryPackage = document.getElementById('summary-package');
    const summaryDate = document.getElementById('summary-date');
    const summarySession = document.getElementById('summary-session');
    const summaryType = document.getElementById('summary-type');
    const summaryStatus = document.getElementById('summary-status');
    const summaryExpiry = document.getElementById('summary-expiry');
    const expiryDateInput = document.getElementById('expiry_date');

    // Initialize
    updateDateValidation();
    updateExpiryDateRequirement();
    updateSummary();
    setDefaultExpiryDate();
    
    // Event Listeners
    userSelect.addEventListener('change', updateSummary);
    venueSelect.addEventListener('change', () => {
        filterPackages();
        checkAvailability();
        updateSummary();
    });
    packageSelect.addEventListener('change', () => {
        updatePriceOptions();
        updateSummary();
    });
    bookingDateInput.addEventListener('change', () => {
        checkAvailability();
        updateSummary();
    });
    document.addEventListener('change', function(e) {
        if (e.target.name === 'session') {
            updateSummary();
        }
    });
    typeSelect.addEventListener('change', () => {
        updateDateValidation();
        updateExpiryDateRequirement();
        updateSummary();
    });
    statusSelect.addEventListener('change', updateSummary);
    expiryDateInput.addEventListener('change', updateSummary);

    function setDefaultExpiryDate() {
        // Set expiry date to 7 days from today if not already set
        if (!expiryDateInput.value) {
            const today = new Date();
            const expiryDate = new Date(today.getTime() + (7 * 24 * 60 * 60 * 1000));
            expiryDateInput.value = expiryDate.toISOString().split('T')[0];
        }
    }

    function updateSummary() {
        // Customer
        const selectedUser = usersData.find(user => user.id == userSelect.value);
        summaryCustomer.textContent = selectedUser ? `${selectedUser.name} (${selectedUser.email})` : 'Not selected';
        
        // Venue
        const selectedVenue = venuesData.find(venue => venue.id == venueSelect.value);
        summaryVenue.textContent = selectedVenue ? selectedVenue.name : 'Not selected';
        
        // Package
        const selectedPackage = packagesData.find(pkg => pkg.id == packageSelect.value);
        summaryPackage.textContent = selectedPackage ? selectedPackage.name : 'No package';
        
        // Date
        summaryDate.textContent = bookingDateInput.value ? new Date(bookingDateInput.value).toLocaleDateString() : 'Not selected';
        
        // Session
        const selectedSession = document.querySelector('input[name="session"]:checked');
        if (selectedSession) {
            summarySession.textContent = selectedSession.value === 'morning' ? 'Morning (11AM-4PM)' : 'Evening (7PM-11PM)';
        } else {
            summarySession.textContent = 'Not selected';
        }
        
        // Type & Status
        summaryType.textContent = typeSelect.options[typeSelect.selectedIndex].text;
        summaryStatus.textContent = statusSelect.options[statusSelect.selectedIndex].text;
        
        // Expiry Date
        summaryExpiry.textContent = expiryDateInput.value ? new Date(expiryDateInput.value).toLocaleDateString() : '7 days from today';
    }

    function filterPackages() {
        const venueId = venueSelect.value;
        packageSelect.innerHTML = '<option value="">-- No Package --</option>';

        packageOptions.forEach(option => {
            if (option.value && (!venueId || option.dataset.venueId === venueId)) {
                packageSelect.appendChild(option.cloneNode(true));
            }
        });

        // Reset price selection
        priceContainer.style.display = 'none';
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
    }

    function updatePriceOptions() {
        const packageId = packageSelect.value;
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';

        if (packageId) {
            const packageData = packagesData.find(pkg => pkg.id == packageId);
            
            if (packageData && packageData.prices.length > 0) {
                packageData.prices.forEach(price => {
                    const option = document.createElement('option');
                    option.value = price.id;
                    option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2})}`;
                    priceSelect.appendChild(option);
                });
                
                priceContainer.style.display = 'block';
            } else {
                priceContainer.style.display = 'none';
            }
        } else {
            priceContainer.style.display = 'none';
        }
    }

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
        
        const dateHelpText = document.getElementById('booking-date-help');
        if (dateHelpText) dateHelpText.textContent = helpText;
        
        if (bookingDateInput.value && new Date(bookingDateInput.value) < minDate) {
            bookingDateInput.value = '';
        }
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

    function resetSessionOptions() {
        const sessionContainer = document.getElementById('session-container');
        const noSessionsMessage = document.getElementById('no-sessions-message');
        const currentSelection = document.querySelector('input[name="session"]:checked');
        const currentValue = currentSelection ? currentSelection.value : null;
        
        // Only show sessions if no date/venue is selected, otherwise wait for availability check
        const date = bookingDateInput.value;
        const venueId = venueSelect.value;
        
        if (!date || !venueId) {
            sessionContainer.innerHTML = `
                <div class="session-card">
                    <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" ${currentValue === 'morning' ? 'checked' : ''}>
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
                    <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" ${currentValue === 'evening' || !currentValue ? 'checked' : ''}>
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
        updateSummary();
    }

    function checkAvailability() {
        const date = bookingDateInput.value;
        const venueId = venueSelect.value;

        console.log('Checking availability - Date:', date, 'Venue ID:', venueId);

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

        console.log('Making API calls for availability check');
        
        const morningUrl = `/api/check-availability?date=${date}&session=morning&venue_id=${venueId}`;
        const eveningUrl = `/api/check-availability?date=${date}&session=evening&venue_id=${venueId}`;
        
        console.log('Morning URL:', morningUrl);
        console.log('Evening URL:', eveningUrl);
        
        Promise.all([
            fetch(morningUrl).then(response => {
                console.log('Morning response status:', response.status);
                console.log('Morning response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`Morning API call failed: ${response.status} ${response.statusText}`);
                }
                return response.json();
            }),
            fetch(eveningUrl).then(response => {
                console.log('Evening response status:', response.status);
                console.log('Evening response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`Evening API call failed: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
        ]).then(([morningData, eveningData]) => {
            console.log('=== DETAILED AVAILABILITY CHECK RESULTS ===');
            console.log('Morning data:', morningData);
            console.log('Evening data:', eveningData);
            console.log('Morning available:', morningData.available, 'Evening available:', eveningData.available);
            console.log('Date:', date, 'Venue ID:', venueId);
            console.log('Morning URL called:', morningUrl);
            console.log('Evening URL called:', eveningUrl);
            console.log('=== END DETAILED RESULTS ===');
            
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
            updateSummary();
        });
    }

    function updateSessionOptions(morningAvailable, eveningAvailable) {
        const sessionContainer = document.getElementById('session-container');
        const noSessionsMessage = document.getElementById('no-sessions-message');
        const currentSelection = document.querySelector('input[name="session"]:checked');
        const currentValue = currentSelection ? currentSelection.value : null;

        sessionContainer.innerHTML = '';

        if (morningAvailable) {
            const morningCard = document.createElement('div');
            morningCard.className = 'session-card';
            morningCard.innerHTML = `
                <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" ${currentValue === 'morning' ? 'checked' : ''}>
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

        if (eveningAvailable) {
            const eveningCard = document.createElement('div');
            eveningCard.className = 'session-card';
            eveningCard.innerHTML = `
                <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" ${currentValue === 'evening' ? 'checked' : ''}>
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

        if (!morningAvailable && !eveningAvailable) {
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
            
            if (currentValue && !document.querySelector(`input[name="session"][value="${currentValue}"]`)) {
                const firstAvailable = sessionContainer.querySelector('input[name="session"]');
                if (firstAvailable) {
                    firstAvailable.checked = true;
                }
            } else if (!currentValue) {
                const firstAvailable = sessionContainer.querySelector('input[name="session"]');
                if (firstAvailable) {
                    firstAvailable.checked = true;
                }
            }
        }
        
        updateSummary();
    }

    // Initialize form with existing data
    setTimeout(() => {
        console.log('Page load - checking availability after timeout');
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
@endpush

@endsection