@extends('layouts.app')

@section('title', 'Book Your Event - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-display font-bold text-primary">Book Your Special Day</h1>
                <p class="text-gray-600 mt-3 text-lg">Fill out the form below to request a booking at Enak Rasa Wedding Hall</p>
                <div class="w-24 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
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
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Booking progress indicator -->
                <div class="bg-primary bg-opacity-10 px-6 py-4 border-b border-primary border-opacity-20">
                    <div class="flex justify-between">
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center mx-auto">
                                <span>1</span>
                            </div>
                            <p class="text-sm mt-1 font-medium text-primary">Request Details</p>
                        </div>
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center mx-auto">
                                <span>2</span>
                            </div>
                            <p class="text-sm mt-1 text-gray-500">Confirmation</p>
                        </div>
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center mx-auto">
                                <span>3</span>
                            </div>
                            <p class="text-sm mt-1 text-gray-500">Complete</p>
                        </div>
                    </div>
                    <div class="mt-2 flex">
                        <div class="h-1 w-full bg-primary"></div>
                        <div class="h-1 w-full bg-gray-300"></div>
                    </div>
                </div>
                
                <form action="{{ route('booking-requests.store') }}" method="POST" class="booking p-6 md:p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-800">Personal Information</h2>
                            </div>
                            <p class="text-gray-500 mb-4 -mt-2 pl-8">Please provide your contact details below</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="block text-dark font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" 
                                    required 
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                    placeholder="Enter your full name"
                                >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="block text-dark font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                                    required 
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                    placeholder="your@email.com"
                                >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_no" class="block text-dark font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="whatsapp_no" 
                                    name="whatsapp_no" 
                                    value="{{ old('whatsapp_no', Auth::check() ? Auth::user()->whatsapp : '') }}" 
                                    required 
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                    placeholder="+62 812 3456 7890"
                                >
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-1">Include country code for WhatsApp notifications</p>
                        </div>
                        
                        <!-- Booking Details -->
                        <div class="md:col-span-2 pt-6 border-t border-gray-200">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-800">Booking Details</h2>
                            </div>
                            <p class="text-gray-500 mb-4 -mt-2 pl-8">Tell us about your event and preferences</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="type" class="block text-dark font-medium mb-1">Request Type <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <select 
                                    id="type" 
                                    name="type" 
                                    required 
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                >
                                    <option value="">-- Select Request Type --</option>
                                    <option value="booking" {{ old('type') == 'booking' ? 'selected' : '' }}>Booking (Confirm Reservation)</option>
                                    <option value="reservation" {{ old('type') == 'reservation' ? 'selected' : '' }}>Reservation (Hold Date)</option>
                                    <option value="viewing" {{ old('type') == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                    <option value="appointment" {{ old('type') == 'appointment' ? 'selected' : '' }}>Consultation Appointment</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    type="date" 
                                    id="event_date" 
                                    name="event_date" 
                                    value="{{ old('event_date') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                >
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-1">Tentative date for your event</p>
                        </div>
                        
                        <!-- Session Selection -->
                        <div class="form-group">
                            <label for="session" class="block text-dark font-medium mb-1">Session <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <select 
                                    id="session" 
                                    name="session" 
                                    required 
                                    class="form-input w-full pl-10 focus:ring-primary focus:border-primary"
                                >
                                    <option value="">-- Select Session --</option>
                                    <option value="morning" {{ old('session') == 'morning' ? 'selected' : '' }}>Morning Session</option>
                                    <option value="evening" {{ old('session') == 'evening' ? 'selected' : '' }}>Evening Session</option>
                                </select>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-1">Choose your preferred time of day</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="venue_id" class="block text-dark font-medium mb-1">Preferred Venue</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <select id="venue_id" name="venue_id" class="form-input w-full pl-10 focus:ring-primary focus:border-primary">
                                    <option value="">-- Select Venue --</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="package_id" class="block text-dark font-medium mb-1">Interested Package</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <select id="package_id" name="package_id" class="form-input w-full pl-10 focus:ring-primary focus:border-primary">
                                    <option value="">-- Select Package --</option>
                                    @foreach($packages as $package)
                                        <option 
                                            value="{{ $package->id }}" 
                                            data-venue-id="{{ $package->venue_id }}"
                                            {{ old('package_id') == $package->id ? 'selected' : '' }}
                                        >
                                            {{ $package->name }} ({{ $package->venue->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- New Price Selection Field -->
                        <div class="form-group" id="price-selection-container">
                            <label for="price_id" class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <select id="price_id" name="price_id" class="form-input w-full pl-10 focus:ring-primary focus:border-primary">
                                    <option value="">-- Select Number of Guests --</option>
                                    <!-- Price options will be populated via JavaScript -->
                                </select>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-1">Select the expected number of guests for your event</p>
                        </div>
                        
                        <div class="md:col-span-2 rounded-lg bg-gray-50 p-4 border border-gray-200 mt-2">
                            <label for="message" class="block text-dark font-medium mb-2">Your Message <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    required 
                                    class="form-input w-full focus:ring-primary focus:border-primary"
                                    placeholder="Please share details about your event, guest count, and any special requirements or questions"
                                >{{ old('message') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <div class="flex flex-col md:flex-row items-center justify-between">
                            <div class="text-gray-500 mb-4 md:mb-0">
                                <p class="flex items-center text-sm">
                                    <svg class="h-4 w-4 mr-1 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Fields marked with <span class="text-red-500">*</span> are required
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="history.back()" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-md font-medium hover:bg-gray-50 transition">
                                    Back
                                </button>
                                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90 transition shadow-sm flex items-center">
                                    <span>Submit Booking Request</span>
                                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Additional information card -->
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">What Happens Next?</h3>
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary text-white">1</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-md font-medium text-gray-900">Submit Your Request</h4>
                            <p class="text-sm text-gray-500">Fill out the form with your event details and preferences.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary text-white">2</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-md font-medium text-gray-900">Review Process</h4>
                            <p class="text-sm text-gray-500">Our team will review your request and check venue availability.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary text-white">3</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-md font-medium text-gray-900">Confirmation</h4>
                            <p class="text-sm text-gray-500">We'll contact you via email or WhatsApp within 24 hours to confirm details.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Package and Price data
    const packagesData = {!! json_encode($packages->map(function($package) {
        return [
            'id' => $package->id,
            'venue_id' => $package->venue_id,
            'prices' => $package->prices->map(function($price) {
                return [
                    'id' => $price->id,
                    'pax' => $price->pax,
                    'price' => $price->price
                ];
            })
        ];
    })) !!};

    // Form elements
    const venueSelect = document.getElementById('venue_id');
    const packageSelect = document.getElementById('package_id');
    const priceSelect = document.getElementById('price_id');
    const priceContainer = document.getElementById('price-selection-container');
    const packageOptions = Array.from(packageSelect.options);
    const formElements = document.querySelectorAll('.form-input');
    
    // Form animation
    formElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary', 'ring-opacity-50');
        });
        
        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary', 'ring-opacity-50');
        });
    });

    // Previously selected values (if any)
    const oldPackageId = "{{ old('package_id') }}";
    const oldPriceId = "{{ old('price_id') }}";

    // Filter packages based on selected venue
    function filterPackages() {
        const venueId = venueSelect.value;
        
        // Reset and create default option
        packageSelect.innerHTML = '<option value="">-- Select Package --</option>';

        // Add filtered options
        packageOptions.forEach(option => {
            if (option.value && (!venueId || option.dataset.venueId === venueId)) {
                const newOption = option.cloneNode(true);
                packageSelect.appendChild(newOption);
                
                // Highlight new options with animation if venue selected
                if (venueId) {
                    newOption.classList.add('bg-green-50');
                    setTimeout(() => {
                        newOption.classList.remove('bg-green-50');
                    }, 1000);
                }
            }
        });

        // Restore previously selected package if it exists in new options
        if (oldPackageId) {
            Array.from(packageSelect.options).forEach(option => {
                if (option.value === oldPackageId) {
                    option.selected = true;
                }
            });
        }
        
        // Trigger price update
        updatePriceOptions(packageSelect.value);
    }

    // Update price options based on selected package
    function updatePriceOptions(packageId, selectedPriceId = null) {
        // Reset price select
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
        
        // Find package data
        const packageData = packagesData.find(pkg => pkg.id == packageId);

        if (packageData && packageData.prices.length > 0) {
            // Sort prices by pax count
            const sortedPrices = [...packageData.prices].sort((a, b) => a.pax - b.pax);
            
            // Add price options
            sortedPrices.forEach(price => {
                const option = document.createElement('option');
                option.value = price.id;
                option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2})}`;

                if (selectedPriceId && price.id == selectedPriceId) {
                    option.selected = true;
                }

                priceSelect.appendChild(option);
            });

            // Show the price container with animation
            priceContainer.style.display = 'block';
            priceContainer.classList.add('animate-fade-in');
        } else {
            // Hide the price container
            priceContainer.style.display = 'none';
        }
    }
    
    // Check venue availability
    function checkAvailability() {
        const date = document.getElementById('event_date').value;
        const venueId = venueSelect.value;

        if (!date || !venueId) return;

        // Visual indication that check is in progress
        venueSelect.classList.add('bg-yellow-50');
        
        fetch(`/api/check-availability?date=${date}&venue_id=${venueId}`)
            .then(response => response.json())
            .then(data => {
                // Reset background
                venueSelect.classList.remove('bg-yellow-50');
                
                if (!data.available) {
                    // Show availability warning
                    const warning = document.createElement('div');
                    warning.className = 'mt-2 text-amber-600 text-sm flex items-center';
                    warning.innerHTML = `
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        This venue may have limited availability on the selected date.
                    `;
                    
                    // Remove any existing warnings
                    const existingWarning = venueSelect.parentElement.querySelector('.text-amber-600');
                    if (existingWarning) {
                        existingWarning.remove();
                    }
                    
                    // Add the new warning
                    venueSelect.parentElement.appendChild(warning);
                } else {
                    // Remove any existing warnings
                    const existingWarning = venueSelect.parentElement.querySelector('.text-amber-600');
                    if (existingWarning) {
                        existingWarning.remove();
                    }
                    
                    // Show availability confirmation
                    const confirmation = document.createElement('div');
                    confirmation.className = 'mt-2 text-green-600 text-sm flex items-center';
                    confirmation.innerHTML = `
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Venue is available on this date!
                    `;
                    
                    venueSelect.parentElement.appendChild(confirmation);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        confirmation.remove();
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Availability check failed:', error);
                venueSelect.classList.remove('bg-yellow-50');
            });
    }

    // Event Listeners
    venueSelect.addEventListener('change', () => {
        filterPackages();
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

    document.getElementById('event_date').addEventListener('change', checkAvailability);

    // Initialize form on page load
    filterPackages();
    if (oldPackageId) {
        updatePriceOptions(oldPackageId, oldPriceId);
    }
    
    // Add CSS for fade-in animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
@endsection