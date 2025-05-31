@extends('layouts.app')

@section('title', 'Book Your Event - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-display font-bold text-primary">Book Your Special Day</h1>
                <p class="text-gray-600 mt-3 text-lg">Follow the steps below to request a booking at Enak Rasa Wedding Hall</p>
                
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
            
            <!-- Form Data Restored Notification -->
            <div id="form-restored-notification" class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm" style="display: none;">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Your previous form data has been restored. You can continue where you left off.</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-blue-400 hover:text-blue-600" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
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
            
            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-primary bg-opacity-10 px-6 py-4 border-b border-primary border-opacity-20">
                    <!-- Desktop Step Indicators -->
                    <div class="hidden md:flex justify-center items-center">
                        <div class="flex items-center space-x-8">
                            <div class="flex items-center step-indicator active" data-step="1">
                                <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                                <span class="ml-2 text-sm font-medium text-primary">Personal Info</span>
                            </div>
                            <div class="flex items-center step-indicator" data-step="2">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                <span class="ml-2 text-sm text-gray-500">Request Type</span>
                        </div>
                            <div class="flex items-center step-indicator" data-step="3">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                <span class="ml-2 text-sm text-gray-500">Select Venue</span>
                            </div>
                            <div class="flex items-center step-indicator" data-step="4">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">4</div>
                                <span class="ml-2 text-sm text-gray-500">Choose Package</span>
                        </div>
                            <div class="flex items-center step-indicator" data-step="5">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">5</div>
                                <span class="ml-2 text-sm text-gray-500">Select Pricing</span>
                            </div>
                            <div class="flex items-center step-indicator" data-step="6">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">6</div>
                                <span class="ml-2 text-sm text-gray-500">Event Details</span>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Mobile Step Indicators -->
                    <div class="md:hidden">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-primary" id="mobile-step-title">Personal Information</span>
                            <span class="text-sm text-gray-500" id="mobile-step-counter">Step 1 of 6</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all duration-300" id="mobile-progress-bar" style="width: 20%"></div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('booking-requests.store') }}" method="POST" id="bookingForm" class="p-6 md:p-8">
                    @csrf
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="step-1">
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Personal Information</h2>
                                <p class="text-gray-600">Let's start with your contact details</p>
                        </div>
                        
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-dark font-medium mb-2">Full Name <span class="text-red-500">*</span></label>
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
                                            class="form-input w-full pl-10 py-3 text-lg focus:ring-primary focus:border-primary"
                                    placeholder="Enter your full name"
                                >
                            </div>
                        </div>
                        
                                <div>
                                    <label for="email" class="block text-dark font-medium mb-2">Email Address <span class="text-red-500">*</span></label>
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
                                            class="form-input w-full pl-10 py-3 text-lg focus:ring-primary focus:border-primary"
                                    placeholder="your@email.com"
                                >
                            </div>
                        </div>
                        
                                <div>
                                    <label for="whatsapp_no" class="block text-dark font-medium mb-2">WhatsApp Number <span class="text-red-500">*</span></label>
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
                                            class="form-input w-full pl-10 py-3 text-lg focus:ring-primary focus:border-primary"
                                            placeholder="+60 12 345 6789"
                                >
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-1">Include country code for WhatsApp notifications</p>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                    <!-- Step 2: Request Type -->
                    <div class="form-step" id="step-2">
                        <div class="max-w-4xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">What type of request is this?</h2>
                                <p class="text-gray-600">Choose the option that best describes your needs</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="request-type-card" data-type="booking">
                                    <input type="radio" id="type-booking" name="type" value="booking" class="sr-only" {{ old('type') == 'booking' ? 'checked' : '' }}>
                                    <label for="type-booking" class="block p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                            <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                                            <h3 class="text-lg font-semibold text-gray-800">Confirm Booking</h3>
                                        </div>
                                        <p class="text-gray-600 mb-2">Ready to book your wedding venue and confirm your date</p>
                                        <p class="text-xs text-blue-600 font-medium">📅 Requires 6+ months advance booking</p>
                                    </label>
                        </div>
                        
                                <div class="request-type-card" data-type="reservation">
                                    <input type="radio" id="type-reservation" name="type" value="reservation" class="sr-only" {{ old('type') == 'reservation' ? 'checked' : '' }}>
                                    <label for="type-reservation" class="block p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                            <h3 class="text-lg font-semibold text-gray-800">Hold Date</h3>
                            </div>
                                        <p class="text-gray-600 mb-2">Reserve your preferred date while you finalize details</p>
                                        <p class="text-xs text-blue-600 font-medium">📅 Requires 6+ months advance booking</p>
                                    </label>
                        </div>
                        
                                <div class="request-type-card" data-type="viewing">
                                    <input type="radio" id="type-viewing" name="type" value="viewing" class="sr-only" {{ old('type') == 'viewing' ? 'checked' : '' }}>
                                    <label for="type-viewing" class="block p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                            <h3 class="text-lg font-semibold text-gray-800">Venue Viewing</h3>
                            </div>
                                        <p class="text-gray-600">Schedule a visit to see our venues in person</p>
                                    </label>
                        </div>
                        
                                <div class="request-type-card" data-type="appointment">
                                    <input type="radio" id="type-appointment" name="type" value="appointment" class="sr-only" {{ old('type') == 'appointment' ? 'checked' : '' }}>
                                    <label for="type-appointment" class="block p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                            <h3 class="font-semibold text-gray-800">Consultation</h3>
                            </div>
                                        <p class="text-gray-600">Discuss your wedding plans with our team</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        </div>

                    <!-- Step 3: Venue Selection -->
                    <div class="form-step" id="step-3">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Choose Your Venue</h2>
                            <p class="text-gray-600">Select the perfect venue for your special day</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($venues as $venue)
                                @php
                                    $venueImage = $venue->galleries->first();
                                    $imageUrl = $venueImage ? 
                                        ($venueImage->source === 'local' ? $venueImage->image_path : $venueImage->image_url) : 
                                        'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=3270&auto=format&fit=crop';
                                @endphp
                                
                                <div class="venue-card" data-venue-id="{{ $venue->id }}">
                                    <input type="radio" id="venue-{{ $venue->id }}" name="venue_id" value="{{ $venue->id }}" class="sr-only" {{ old('venue_id', $prefilledData['venue_id']) == $venue->id ? 'checked' : '' }}>
                                    <label for="venue-{{ $venue->id }}" class="block bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow border-2 border-transparent">
                                        <div class="h-48 overflow-hidden">
                                            <img src="{{ $imageUrl }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $venue->name }}</h3>
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $venue->description }}</p>
                                            <div class="flex items-center text-gray-500 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                                {{ $venue->city }}, {{ $venue->state }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                            </div>
                            
                        <div class="mt-6 text-center">
                            <p class="text-gray-500 text-sm">Can't find what you're looking for? <a href="#" class="text-primary hover:underline">Contact us</a> for custom arrangements.</p>
                                        </div>
                    </div>

                    <!-- Step 4: Package Selection -->
                    <div class="form-step" id="step-4">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Select Your Package</h2>
                            <p class="text-gray-600">Choose a package that fits your needs and budget</p>
                        </div>
                        
                        <div id="packages-container">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                <p class="text-gray-500">Please select a venue first to see available packages</p>
                                        </div>
                                    </div>
                        
                        <div class="mt-6 text-center">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="skip_package" value="0">
                                <input type="checkbox" id="skip-package" name="skip_package" value="1" class="form-checkbox h-4 w-4 text-primary">
                                <span class="ml-2 text-gray-600">Skip package selection (I'll decide later)</span>
                            </label>
                        </div>
                                </div>
                                
                    <!-- Step 5: Select Pricing -->
                    <div class="form-step" id="step-5">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Select Pricing</h2>
                            <p class="text-gray-600">Choose a pricing option that suits your budget</p>
                                        </div>
                        
                        <div id="pricing-container">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                <p class="text-gray-500">Please select a package first to see pricing options</p>
                                        </div>
                                    </div>
                                </div>

                    <!-- Step 6: Event Details -->
                    <div class="form-step" id="step-6">
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Event Details</h2>
                                <p class="text-gray-600">Tell us about your special day</p>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="event_date" class="block text-dark font-medium mb-2">Event Date</label>
                                    <input 
                                        type="date" 
                                        id="event_date" 
                                        name="event_date" 
                                        value="{{ old('event_date', $prefilledData['event_date']) }}" 
                                        class="form-input w-full py-3 text-lg focus:ring-primary focus:border-primary"
                                        min="{{ date('Y-m-d', strtotime('+1 week')) }}"
                                        max="{{ date('Y-m-d', strtotime('+2 years')) }}"
                                    >
                                    <p class="text-sm text-gray-500 mt-1 ml-1" id="date-help-text">Select your preferred event date</p>
                        </div>
                        
                                <div>
                                    <label class="block text-dark font-medium mb-2">Session <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="session-card">
                                            <input type="radio" id="session-morning" name="session" value="morning" class="sr-only" {{ old('session') == 'morning' ? 'checked' : '' }}>
                                            <label for="session-morning" class="block p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-800">Morning Session</h3>
                                                        <p class="text-sm text-gray-600">11:00 AM - 4:00 PM</p>
                            </div>
                                                </div>
                                            </label>
                        </div>

                                        <div class="session-card">
                                            <input type="radio" id="session-evening" name="session" value="evening" class="sr-only" {{ old('session', 'evening') == 'evening' ? 'checked' : '' }}>
                                            <label for="session-evening" class="block p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-800">Evening Session</h3>
                                                        <p class="text-sm text-gray-600">7:00 PM - 11:00 PM</p>
                            </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                        </div>
                        
                                <div>
                            <label for="message" class="block text-dark font-medium mb-2">Your Message <span class="text-red-500">*</span></label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    required 
                                    class="form-input w-full focus:ring-primary focus:border-primary"
                                        placeholder="Please share details about your event, special requirements, or any questions you have..."
                                >{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button type="button" id="prevBtn" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-400 transition-colors" style="display: none;">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                Previous
                            </button>
                            
                            <button type="button" id="resetBtn" class="text-gray-500 hover:text-red-600 text-sm underline transition-colors">
                                Reset Form
                            </button>
                            </div>
                        
                        <div class="flex items-center space-x-4">
                            <button type="button" id="nextBtn" class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:bg-primary-dark transition-colors">
                                Next
                                <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                </button>
                            
                            <button type="submit" id="submitBtn" class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:bg-primary-dark transition-colors" style="display: none;">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                Submit Request
                                </button>
                        </div>
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

.request-type-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.venue-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.package-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.session-card input:checked + label {
    border-color: #D4A373;
    background-color: #FEF7F0;
}

.pricing-card input:checked + label {
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
    
    .request-type-card label {
        padding: 1rem;
    }
    
    .session-card label {
        padding: 1rem;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = parseInt(localStorage.getItem('bookingFormStep')) || 1;
    const totalSteps = 6;
    
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    // Package data for filtering
    const packages = @json($packages);
    
    // Save form data to localStorage
    function saveFormData() {
        const formData = new FormData(document.getElementById('bookingForm'));
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        localStorage.setItem('bookingFormData', JSON.stringify(data));
    }
    
    // Load form data from localStorage
    function loadFormData() {
        const savedData = localStorage.getItem('bookingFormData');
        if (savedData) {
            const data = JSON.parse(savedData);
            let hasData = false;
            
            Object.keys(data).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input && data[key]) {
                    hasData = true;
                    if (input.type === 'radio' || input.type === 'checkbox') {
                        if (input.value === data[key]) {
                            input.checked = true;
                        }
        } else {
                        input.value = data[key];
                    }
                }
            });
            
            // Show notification if data was restored
            if (hasData && currentStep > 1) {
                document.getElementById('form-restored-notification').style.display = 'block';
            }
        }
    }
    
    // Clear saved data
    function clearSavedData() {
        localStorage.removeItem('bookingFormStep');
        localStorage.removeItem('bookingFormData');
    }
    
    // Update date restrictions based on request type
    function updateDateRestrictions() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        const eventDateInput = document.getElementById('event_date');
        const dateHelpText = document.getElementById('date-help-text');
        
        if (!eventDateInput || !dateHelpText) return;
        
        const today = new Date();
        const oneWeekFromToday = new Date(today.getTime() + (7 * 24 * 60 * 60 * 1000));
        const sixMonthsFromToday = new Date(today.getFullYear(), today.getMonth() + 6, today.getDate());
        const oneYearFromToday = new Date(today.getFullYear() + 1, today.getMonth(), today.getDate());
        const twoYearsFromToday = new Date(today.getFullYear() + 2, today.getMonth(), today.getDate());
        
        // Format dates for input
        const formatDate = (date) => date.toISOString().split('T')[0];
        
        if (selectedType === 'booking' || selectedType === 'reservation') {
            // Require at least 6 months advance booking for booking and reservation
            eventDateInput.min = formatDate(sixMonthsFromToday);
            eventDateInput.max = formatDate(twoYearsFromToday);
            dateHelpText.textContent = 'Select your event date (must be at least 6 months from today)';
            dateHelpText.className = 'text-sm text-blue-600 mt-1 ml-1 font-medium';
            
            // Clear date if it's within 6 months
            if (eventDateInput.value) {
                const selectedDate = new Date(eventDateInput.value);
                if (selectedDate < sixMonthsFromToday) {
                    eventDateInput.value = '';
                    alert('Booking and reservation requests require at least 6 months advance notice. Please select a date 6 months or more from today.');
                }
            }
        } else {
            // Allow from 1 week for viewing and appointment
            eventDateInput.min = formatDate(oneWeekFromToday);
            eventDateInput.max = formatDate(oneYearFromToday);
            dateHelpText.textContent = 'Select your preferred date';
            dateHelpText.className = 'text-sm text-gray-500 mt-1 ml-1';
        }
    }
    
    function showStep(step) {
        // Save current step to localStorage
        localStorage.setItem('bookingFormStep', step);
        
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.step-indicator').forEach(s => {
            s.classList.remove('active', 'completed');
        });
        
        // Show current step
        document.getElementById(`step-${step}`).classList.add('active');
        
        // Update step indicators
        for (let i = 1; i <= totalSteps; i++) {
            const indicator = document.querySelector(`[data-step="${i}"]`);
            if (indicator) {
                if (i < step) {
                    indicator.classList.add('completed');
                } else if (i === step) {
                    indicator.classList.add('active');
                }
            }
        }
        
        // Update mobile progress indicators
        const stepTitles = [
            '', // 0 index
            'Personal Information',
            'Request Type',
            'Select Venue',
            'Choose Package',
            'Select Pricing',
            'Event Details'
        ];
        
        const mobileTitle = document.getElementById('mobile-step-title');
        const mobileCounter = document.getElementById('mobile-step-counter');
        const mobileProgressBar = document.getElementById('mobile-progress-bar');
        
        if (mobileTitle) mobileTitle.textContent = stepTitles[step];
        if (mobileCounter) mobileCounter.textContent = `Step ${step} of ${totalSteps}`;
        if (mobileProgressBar) mobileProgressBar.style.width = `${(step / totalSteps) * 100}%`;
        
        // Update navigation buttons
        prevBtn.style.display = step === 1 ? 'none' : 'inline-flex';
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-flex';
        submitBtn.style.display = step === totalSteps ? 'inline-flex' : 'none';
        
        // Check session availability when reaching step 6
        if (step === 6) {
            checkAvailableSessions();
            updateDateRestrictions();
        }
    }
    
    function validateStep(step) {
        switch(step) {
            case 1:
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const whatsapp = document.getElementById('whatsapp_no').value.trim();
                
                if (!name || !email || !whatsapp) {
                    alert('Please fill in all required personal information fields.');
                    return false;
                }
                
                if (!email.includes('@')) {
                    alert('Please enter a valid email address.');
                    return false;
                }
                break;
                
            case 2:
                const selectedType = document.querySelector('input[name="type"]:checked');
                if (!selectedType) {
                    alert('Please select a request type.');
                    return false;
                }
                break;
                
            case 3:
                const selectedVenue = document.querySelector('input[name="venue_id"]:checked');
                if (!selectedVenue) {
                    alert('Please select a venue.');
                    return false;
                }
                break;
                
            case 4:
                const selectedPackage = document.querySelector('input[name="package_id"]:checked');
                const skipPackage = document.getElementById('skip-package') && document.getElementById('skip-package').checked;
                
                if (!selectedPackage && !skipPackage) {
                    alert('Please select a package or check "Skip package selection".');
                    return false;
                }
                break;
                
            case 5:
                const selectedPrice = document.querySelector('input[name="price_id"]:checked');
                const skipPackage5 = document.getElementById('skip-package') && document.getElementById('skip-package').checked;
                
                if (!selectedPrice && !skipPackage5) {
                    alert('Please select a pricing option.');
                    return false;
                }
                break;
                
            case 6:
                const selectedSession = document.querySelector('input[name="session"]:checked');
                const message = document.getElementById('message').value.trim();
                
                if (!selectedSession) {
                    alert('Please select a session time.');
                    return false;
                }
                
                if (!message) {
                    alert('Please provide a message with details about your event.');
                    return false;
                }
                break;
        }
        return true;
    }
    
    async function checkAvailableSessions() {
        const requestType = document.querySelector('input[name="type"]:checked')?.value;
        const venueId = document.querySelector('input[name="venue_id"]:checked')?.value;
        const eventDate = document.getElementById('event_date')?.value;
        
        // Only check for booking and reservation types, and only if we have all required data
        if (!requestType || !['booking', 'reservation'].includes(requestType) || !venueId || !eventDate) {
            // Only show all sessions if we're on step 6 and have some data
            if (currentStep === 6 || document.querySelector('#step-6.active')) {
                showAllSessions();
            }
            return;
        }
        
        try {
            const response = await fetch('/api/check-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    venue_id: venueId,
                    event_date: eventDate,
                    type: requestType
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                updateSessionAvailability(data.unavailable_sessions || []);
            } else {
                console.error('Failed to check availability');
                if (currentStep === 6 || document.querySelector('#step-6.active')) {
                    showAllSessions();
                }
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            if (currentStep === 6 || document.querySelector('#step-6.active')) {
                showAllSessions();
            }
        }
    }
    
    function updateSessionAvailability(unavailableSessions) {
        const morningSession = document.getElementById('session-morning');
        const eveningSession = document.getElementById('session-evening');
        
        // Only proceed if session elements exist (we're on step 6)
        if (!morningSession || !eveningSession) {
            return;
        }
        
        const morningCard = morningSession.closest('.session-card');
        const eveningCard = eveningSession.closest('.session-card');
        
        // Reset all sessions to available state
        [morningCard, eveningCard].forEach(card => {
            if (card) {
                card.style.display = 'block';
                card.classList.remove('opacity-50', 'cursor-not-allowed');
                const label = card.querySelector('label');
                if (label) label.classList.remove('cursor-not-allowed');
                const input = card.querySelector('input');
                if (input) input.disabled = false;
            }
        });
        
        // Hide unavailable sessions
        unavailableSessions.forEach(session => {
            if (session === 'morning' && morningCard) {
                morningCard.style.display = 'none';
                morningSession.checked = false;
            } else if (session === 'evening' && eveningCard) {
                eveningCard.style.display = 'none';
                eveningSession.checked = false;
            }
        });
        
        // If all sessions are unavailable, show message
        if (unavailableSessions.length === 2) {
            const sessionContainer = document.querySelector('#step-6 .grid.grid-cols-1.md\\:grid-cols-2');
            if (sessionContainer) {
                sessionContainer.innerHTML = `
                    <div class="col-span-2 text-center py-8">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <svg class="w-12 h-12 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
                            <h3 class="text-lg font-semibold text-red-800 mb-2">No Available Sessions</h3>
                            <p class="text-red-600">Both morning and evening sessions are already booked for this date and venue. Please select a different date or venue.</p>
                        </div>
                    </div>
                `;
            }
        }
    }
    
    function showAllSessions() {
        const sessionContainer = document.querySelector('#step-6 .grid.grid-cols-1.md\\:grid-cols-2');
        if (sessionContainer) {
            sessionContainer.innerHTML = `
                <div class="session-card">
                    <input type="radio" id="session-morning" name="session" value="morning" class="sr-only">
                    <label for="session-morning" class="block p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Morning Session</h3>
                                <p class="text-sm text-gray-600">11:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </label>
                </div>
                
                <div class="session-card">
                    <input type="radio" id="session-evening" name="session" value="evening" class="sr-only">
                    <label for="session-evening" class="block p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Evening Session</h3>
                                <p class="text-sm text-gray-600">7:00 PM - 11:00 PM</p>
                            </div>
                        </div>
                    </label>
                </div>
            `;
        }
    }
    
    function updatePackages() {
        const selectedVenueId = document.querySelector('input[name="venue_id"]:checked')?.value;
        const packagesContainer = document.getElementById('packages-container');
        
        if (!selectedVenueId) {
            packagesContainer.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">No packages available for this venue yet</p>
                </div>
            `;
            return;
        }
        
        const venuePackages = packages.filter(pkg => pkg.venue_id == selectedVenueId);
        
        if (venuePackages.length === 0) {
            packagesContainer.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">No packages available for this venue yet</p>
                </div>
            `;
            return;
        }

        let packagesHtml = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
        
        venuePackages.forEach(pkg => {
            const minPrice = pkg.prices.length > 0 ? Math.min(...pkg.prices.map(p => p.price)) : 0;
            const maxPrice = pkg.prices.length > 0 ? Math.max(...pkg.prices.map(p => p.price)) : 0;
            const priceRange = minPrice === maxPrice ? 
                `RM ${minPrice.toLocaleString()}` : 
                `RM ${minPrice.toLocaleString()} - RM ${maxPrice.toLocaleString()}`;
            
            // Get venue image
            const venueImage = pkg.venue.galleries && pkg.venue.galleries.length > 0 ? 
                (pkg.venue.galleries[0].source === 'local' ? pkg.venue.galleries[0].image_path : pkg.venue.galleries[0].image_url) :
                'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=3270&auto=format&fit=crop';
            
            packagesHtml += `
                <div class="package-card" data-package-id="${pkg.id}">
                    <input type="radio" id="package-${pkg.id}" name="package_id" value="${pkg.id}" class="sr-only">
                    <label for="package-${pkg.id}" class="block bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow border-2 border-transparent">
                        <div class="h-48 overflow-hidden">
                            <img src="${venueImage}" alt="${pkg.name}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">${pkg.name}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">${pkg.description || 'Complete wedding package with all essentials'}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-primary font-semibold">${priceRange}</span>
                                <span class="text-xs text-gray-500">${pkg.prices.length} price option${pkg.prices.length !== 1 ? 's' : ''}</span>
                            </div>
                        </div>
                    </label>
                </div>
            `;
        });
        
        packagesHtml += '</div>';
        packagesContainer.innerHTML = packagesHtml;
    }
    
    function updatePricing() {
        const selectedPackageId = document.querySelector('input[name="package_id"]:checked')?.value;
        const pricingContainer = document.getElementById('pricing-container');
        
        if (!selectedPackageId) {
            pricingContainer.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">Please select a package first to see pricing options</p>
                </div>
            `;
            return;
        }
        
        const selectedPackage = packages.find(pkg => pkg.id == selectedPackageId);
        
        if (!selectedPackage || selectedPackage.prices.length === 0) {
            pricingContainer.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">No pricing options available for this package</p>
                </div>
            `;
            return;
        }

        let pricingHtml = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
        
        selectedPackage.prices.forEach(price => {
            const pricePerGuest = (price.price / price.pax).toFixed(0);
            
            pricingHtml += `
                <div class="pricing-card" data-price-id="${price.id}">
                    <input type="radio" id="price-${price.id}" name="price_id" value="${price.id}" class="sr-only">
                    <label for="price-${price.id}" class="block bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow border-2 border-transparent p-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">${price.pax} Guests</h3>
                            <div class="text-3xl font-bold text-primary mb-2">RM ${price.price.toLocaleString()}</div>
                            <p class="text-sm text-gray-500">RM ${pricePerGuest} per guest</p>
                        </div>
                    </label>
                </div>
            `;
        });
        
        pricingHtml += '</div>';
        pricingContainer.innerHTML = pricingHtml;
    }
    
    // Event listeners
    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            saveFormData(); // Save form data before moving to next step
            if (currentStep === 3) {
                updatePackages();
            }
            if (currentStep === 4) {
                updatePricing();
            }
            currentStep++;
            showStep(currentStep);
        }
    });
    
    prevBtn.addEventListener('click', function() {
        saveFormData(); // Save form data before moving to previous step
        currentStep--;
        showStep(currentStep);
    });
    
    // Reset form button
    resetBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            clearSavedData();
            document.getElementById('bookingForm').reset();
            currentStep = 1;
            showStep(currentStep);
            
            // Clear dynamic content
            document.getElementById('packages-container').innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                    <p class="text-gray-500">Please select a venue first to see available packages</p>
                </div>
            `;
            
            document.getElementById('pricing-container').innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">Please select a package first to see pricing options</p>
            </div>
        `;
        }
    });
    
    // Save form data on input changes
    document.addEventListener('input', function(e) {
        saveFormData();
    });
    
    // Save form data on radio/checkbox changes
    document.addEventListener('change', function(e) {
        saveFormData();
        
        if (e.target.name === 'venue_id') {
            updatePackages();
            // Only check sessions if we're on step 6 or later
            if (currentStep >= 6) {
                checkAvailableSessions();
            }
        }
        
        if (e.target.name === 'type') {
            // Only check sessions if we're on step 6 or later
            if (currentStep >= 6) {
                checkAvailableSessions();
            }
            // Update date restrictions based on selected type
            updateDateRestrictions();
        }
        
        if (e.target.name === 'package_id') {
            updatePricing();
        }
    });
    
    // Event date change handler
    document.addEventListener('change', function(e) {
        if (e.target.id === 'event_date') {
            checkAvailableSessions();
        }
    });
    
    // Clear saved data on successful form submission
    document.getElementById('bookingForm').addEventListener('submit', function() {
        clearSavedData();
    });
    
    // Initialize
    loadFormData(); // Load saved form data first
    showStep(currentStep); // Show the saved step
    
    // Update date restrictions based on loaded data
    setTimeout(() => {
        updateDateRestrictions();
    }, 50);
    
    // Update packages and pricing if data is already selected
    setTimeout(() => {
        const selectedVenue = document.querySelector('input[name="venue_id"]:checked');
        if (selectedVenue) {
            updatePackages();
        }
        
        const selectedPackage = document.querySelector('input[name="package_id"]:checked');
        if (selectedPackage) {
            updatePricing();
        }
    }, 100);
    
    // Pre-select venue if provided via URL
    @if($prefilledData['venue_id'])
        setTimeout(() => {
            updatePackages();
        }, 200);
    @endif
});
</script>
@endsection