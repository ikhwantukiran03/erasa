@extends('layouts.app')

@section('title', 'Booking Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-1">View your booking details</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('user.bookings') }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to My Bookings
                </a>
            </div>
        </div>
        
        <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Status color indicator at the top of the card -->
            <div class="h-1.5 {{ 
                $booking->status === 'ongoing' ? 'bg-yellow-400' : 
                ($booking->status === 'waiting for deposit' ? 'bg-blue-400' : 
                ($booking->status === 'completed' ? 'bg-green-500' : 
                ($booking->status === 'pending_verification' ? 'bg-purple-500' : 'bg-red-500'))) 
            }}"></div>
            
            <div class="p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Reservation Explanation -->
                @if($booking->type === 'reservation' && $booking->status !== 'cancelled' && ($booking->package_id !== null))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800 font-medium">Reservation Information</p>
                            <p class="text-sm text-blue-700 mt-1">
                                You have a preliminary reservation for {{ $booking->venue ? $booking->venue->name : 'Venue not found' }} on {{ $booking->booking_date->format('l, F d, Y') }} 
                                ({{ $booking->session === 'morning' ? 'Morning Session (11:00 AM - 4:00 PM)' : 'Evening Session (7:00 PM - 11:00 PM)' }}).
                                To confirm this reservation, please click the "Confirm Reservation" button below.
                                Once confirmed, you will need to proceed with a deposit payment to secure your booking.
                                @if($booking->expiry_date)
                                <br><br>
                                <strong>Note:</strong> This reservation will expire on {{ $booking->expiry_date->format('F d, Y') }} if not confirmed.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Booking Status -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <span class="text-sm text-gray-500">Booking Status</span>
                        <div class="mt-1.5">
                            @if($booking->status === 'ongoing')
                                <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                    Ongoing
                                </span>
                            @elseif($booking->status === 'waiting for deposit')
                                <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                    Waiting for Deposit
                                </span>
                            @elseif($booking->status === 'completed')
                                <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Completed
                                </span>
                            @elseif($booking->status === 'pending_verification')
                                <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                    Pending Verification
                                </span>
                            @else
                                <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Cancelled
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Booking Reference</span>
                        <p class="text-lg font-semibold text-gray-800">B-{{ $booking->id }}</p>
                    </div>
                </div>
                
                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Event Information -->
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Event Information
                            </span>
                            <button type="button" id="toggleEventInfo" class="text-primary hover:text-primary-dark text-sm flex items-center">
                                <span class="show-text hidden">Show</span>
                                <span class="hide-text">Hide</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 toggle-icon rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </h3>
                        <dl class="space-y-3 event-info-details">
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Event Type</dt>
                                <dd class="text-sm text-gray-900 font-medium capitalize w-full sm:w-2/3">
                                    {{ $booking->type }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Event Date</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $booking->booking_date->format('l, F d, Y') }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Session</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    @if($booking->session === 'morning')
                                        <span class="inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            Morning Session (11:00 AM - 4:00 PM)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                            Evening Session (7:00 PM - 11:00 PM)
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($booking->expiry_date)
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Expiry Date</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $booking->expiry_date->format('F d, Y') }}
                                    @if(now()->gt($booking->expiry_date) && $booking->status === 'ongoing')
                                        <span class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">Expired</span>
                                    @endif
                                </dd>
                            </div>
                            @endif
                            
                            <!-- Reservation Action Buttons -->
                            @if($booking->type === 'reservation' && $booking->status !== 'cancelled' && $booking->status !== 'completed')
                            <div class="flex flex-col sm:flex-row pt-4 border-t border-gray-200 mt-2">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Actions</dt>
                                <dd class="text-sm w-full sm:w-2/3 flex flex-col xs:flex-row gap-2">
                                    <button type="button" id="showPackageDetailsBtn" class="w-full xs:w-auto inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        View Package Options
                                    </button>
                                    
                                    <a href="{{ route('user.bookings.confirm.form', $booking) }}" class="w-full xs:w-auto inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Confirm Reservation
                                    </a>
                                    
                                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')">
                                        @csrf
                                        <button type="submit" class="w-full xs:w-auto inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel Reservation
                                        </button>
                                    </form>
                                </dd>
                            </div>
                            @endif
                            
                            <!-- Regular Booking Cancel Button -->
                            @if($booking->type !== 'reservation' && $booking->status !== 'cancelled' && $booking->status !== 'completed')
                            <div class="flex flex-col sm:flex-row pt-4 border-t border-gray-200 mt-2">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Actions</dt>
                                <dd class="text-sm w-full sm:w-2/3">
                                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel Booking
                                        </button>
                                    </form>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    
                    <!-- Venue Information -->
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Venue Information
                            </span>
                            <button type="button" id="toggleVenueInfo" class="text-primary hover:text-primary-dark text-sm flex items-center">
                                <span class="show-text hidden">Show</span>
                                <span class="hide-text">Hide</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 toggle-icon rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </h3>
                        <dl class="space-y-3 venue-info-details">
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Venue Name</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $booking->venue ? $booking->venue->name : 'Venue not found' }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Address</dt>
                                <dd class="text-sm text-gray-900 w-full sm:w-2/3">
                                    <address class="not-italic">
                                        {{ $booking->venue ? $booking->venue->full_address : 'Address not available' }}
                                    </address>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Package Information -->
                    @if($booking->package && $booking->type === 'wedding')
                    <div class="md:col-span-2 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Package Information
                            </span>
                            <button type="button" id="togglePackageInfo" class="text-primary hover:text-primary-dark text-sm flex items-center">
                                <span class="show-text">Show</span>
                                <span class="hide-text hidden">Hide</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 toggle-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </h3>
                        
                        <div class="bg-gray-50 p-5 rounded-lg mb-6 package-info-details hidden">
                            <dl class="space-y-4">
                                <div class="flex flex-col sm:flex-row">
                                    <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/4">Package Name</dt>
                                    <dd class="text-sm text-gray-900 font-medium w-full sm:w-3/4">
                                        {{ $booking->package ? $booking->package->name : 'Package not found' }}
                                    </dd>
                                </div>
                                
                                @if($booking->package && $booking->package->description)
                                <div class="flex flex-col sm:flex-row">
                                    <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/4">Description</dt>
                                    <dd class="text-sm text-gray-900 w-full sm:w-3/4">{{ $booking->package->description }}</dd>
                                </div>
                                @endif
                                
                                <!-- Selected Guest Count (Pax) -->
                                @if($booking->price_id)
                                <div class="flex flex-col sm:flex-row">
                                    <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/4">Guest Count</dt>
                                    <dd class="text-sm font-medium text-gray-900 w-full sm:w-3/4">
                                        @php
                                            $price = \App\Models\Price::find($booking->price_id);
                                        @endphp
                                        @if($price)
                                            <span class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $price->pax }} guests
                                            </span>
                                            <span class="ml-2 px-2.5 py-1 bg-primary bg-opacity-10 text-primary rounded-full text-xs font-bold">
                                                RM {{ number_format($price->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Not specified</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                                
                                @if($booking->package && $booking->package->prices && $booking->package->prices->count() > 0)
                                <div class="flex flex-col sm:flex-row">
                                    <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/4">Price Range</dt>
                                    <dd class="text-sm font-medium text-primary w-full sm:w-3/4">
                                        RM {{ number_format($booking->package->min_price, 0, ',', '.') }}
                                        @if($booking->package->min_price != $booking->package->max_price)
                                            - RM {{ number_format($booking->package->max_price, 0, ',', '.') }}
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        
                        @if($booking->type === 'wedding' && $booking->status === 'ongoing' && $booking->package && $booking->package->packageItems && $booking->package->packageItems->count() > 0)
                        <div class="md:col-span-2 border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Package Items Customization
                                </h3>
                                <div class="mt-2 sm:mt-0">
                                    <a href="{{ route('user.customizations.index', $booking) }}" class="inline-flex items-center text-sm text-primary hover:text-primary-dark transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                        View All Customization Requests
                                    </a>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                                <p class="text-gray-700 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 inline-block mr-1.5 -mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Click on any item below to request customization for your wedding package
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Package Items -->
                        @if($booking->package && $booking->package->packageItems && $booking->package->packageItems->count() > 0)
                        <div class="mt-6 col-span-3 package-info-details hidden">
                            <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Included Items
                            </h4>
                            
                            @php
                                $packageItemsByCategory = $booking->package->packageItems->groupBy(function($item) {
                                    return $item->item && $item->item->category ? $item->item->category->name : 'Uncategorized';
                                });
                            @endphp
                            
                            <div class="space-y-4 mt-2">
                                @foreach($packageItemsByCategory as $categoryName => $packageItems)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <h5 class="font-medium text-gray-700 mb-2 flex items-center text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            {{ $categoryName }}
                                        </h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach($packageItems as $packageItem)
                                                <li class="text-gray-600 text-sm">
                                                    <span class="font-medium">{{ $packageItem->item ? $packageItem->item->name : 'Item not found' }}</span>
                                                    @if($packageItem->description)
                                                        - {{ $packageItem->description }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Booking Contact Information -->
                    <div class="md:col-span-2 border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Information
                        </h3>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <p class="text-gray-700">If you have any questions about your booking, please contact us:</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 flex items-start">
                                <div class="bg-primary bg-opacity-10 rounded-full p-2 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Phone</p>
                                    <p class="text-gray-600 mt-1">013-331 4389</p>
                                    <a href="tel:0133314389" class="text-primary text-sm font-medium hover:underline mt-2 inline-block">
                                        Call Now
                                    </a>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg border border-gray-200 flex items-start">
                                <div class="bg-primary bg-opacity-10 rounded-full p-2 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Email</p>
                                    <p class="text-gray-600 mt-1">rasa.enak@gmail.com</p>
                                    <a href="mailto:rasa.enak@gmail.com" class="text-primary text-sm font-medium hover:underline mt-2 inline-block">
                                        Send Message
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <!-- Add this section to the user booking-show.blade.php -->
@if($booking->type === 'wedding' && in_array($booking->status, ['waiting for deposit', 'ongoing']))
    <!-- Payment Information Section -->
    <div class="md:col-span-2 border-t border-gray-200 pt-6 mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Payment Information
            </h3>
            <a href="{{ route('user.invoices.create', $booking) }}" class="mt-2 sm:mt-0 inline-flex items-center bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Submit Payment Proof
            </a>
        </div>
        
        @php
            $totalAmount = 0;
            $paidAmount = 0;
            
            // Calculate total amount
            if ($booking->price_id) {
                $price = \App\Models\Price::find($booking->price_id);
                if ($price) {
                    $totalAmount = $price->price;
                }
            } elseif ($booking->package) {
                $totalAmount = $booking->package->min_price;
            }
            
            // Calculate paid amount
            $paidAmount = $booking->total_paid;
            
            // Calculate percentage
            $percentage = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
            
            // Get invoices
            $invoices = $booking->invoice()->get();
        @endphp
        
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Payment Progress</span>
                    <span class="text-sm font-bold text-primary">{{ number_format($percentage, 0) }}%</span>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                    <div class="bg-primary h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Paid: <span class="font-semibold text-primary">RM {{ number_format($paidAmount, 2) }}</span></span>
                    <span class="text-gray-600">Total: <span class="font-semibold">RM {{ number_format($totalAmount, 2) }}</span></span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Payment History
                    </h4>
                    
                    @if($invoices->count() > 0)
                        <div class="space-y-2 bg-gray-50 p-3 rounded-lg">
                            @foreach($invoices as $invoice)
                                <div class="flex justify-between items-center p-2 bg-white rounded border border-gray-200 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-800">
                                            @if($invoice->type === 'deposit')
                                                Deposit
                                            @elseif($invoice->type === 'payment_1')
                                                First Installment
                                            @elseif($invoice->type === 'payment_2')
                                                Second Installment
                                            @elseif($invoice->type === 'final_payment')
                                                Final Payment
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                                            @endif
                                        </span>
                                        <span class="text-gray-500 ml-2 font-semibold">RM {{ number_format($invoice->amount, 2) }}</span>
                                    </div>
                                    @if($invoice->status === 'verified')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                            Verified
                                        </span>
                                    @elseif($invoice->status === 'pending')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1"></span>
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1"></span>
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
                            No payment records found.
                        </div>
                    @endif
                </div>
                
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Payment Details
                    </h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-3 text-sm">
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-40">Bank Name:</span>
                                <span class="text-gray-800">MAYBANK</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-40">Account Name:</span>
                                <span class="text-gray-800">KUMPULAN ENAK RASA SDN BHD</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-40">Account Number:</span>
                                <span class="text-gray-800 font-mono">5624 0563 2039</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium text-gray-700 w-40">Reference:</span>
                                <span class="text-primary font-mono font-bold">BOOKING-{{ $booking->id }}</span>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-md text-sm">
                            <p class="text-red-700 flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-1.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <span>Please include your booking reference in the payment description</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


    

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle sections visibility
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = [
            { buttonId: 'toggleEventInfo', contentClass: 'event-info-details', initiallyVisible: true },
            { buttonId: 'toggleVenueInfo', contentClass: 'venue-info-details', initiallyVisible: true },
            { buttonId: 'togglePackageInfo', contentClass: 'package-info-details', initiallyVisible: false }
        ];
        
        toggleButtons.forEach(toggle => {
            const button = document.getElementById(toggle.buttonId);
            if (button) {
                button.addEventListener('click', function() {
                    const content = document.querySelectorAll('.' + toggle.contentClass);
                    const showText = this.querySelector('.show-text');
                    const hideText = this.querySelector('.hide-text');
                    const icon = this.querySelector('.toggle-icon');
                    
                    content.forEach(element => {
                        element.classList.toggle('hidden');
                    });
                    
                    // Toggle button text
                    showText.classList.toggle('hidden');
                    hideText.classList.toggle('hidden');
                    
                    // Rotate icon
                    icon.classList.toggle('rotate-180');
                });
            }
        });
    });
</script>

<!-- Package Details for Reservation Confirmation Modal -->
@if($booking->type === 'reservation' && $booking->status !== 'cancelled')
<div id="packageDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Available Wedding Packages for {{ $booking->venue ? $booking->venue->name : 'Venue not found' }}</h3>
            <button id="closePackageDetails" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div id="packageContent" class="space-y-6">
                @php
                    $availablePackages = \App\Models\Package::where('venue_id', $booking->venue_id)
                        ->with(['prices', 'packageItems.item.category'])
                        ->get();
                @endphp
                
                @if($availablePackages->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($availablePackages as $package)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                            <h4 class="font-semibold text-gray-800 text-lg mb-2">{{ $package->name }}</h4>
                            <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                            
                            <div class="mb-4">
                                <h5 class="font-medium text-gray-800 mb-2">Package Includes:</h5>
                                <div class="space-y-1">
                                    @foreach($package->packageItems as $item)
                                    <div class="flex items-start space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">{{ $item->item ? $item->item->name : 'Item not found' }}</span>
                                            @if($item->description)
                                                <p class="text-xs text-gray-600">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="font-medium text-gray-800 mb-2">Pricing Options:</h5>
                                <div class="space-y-1">
                                    @foreach($package->prices as $price)
                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded text-sm">
                                        <span>{{ $price->pax }} guests</span>
                                        <span class="font-bold text-primary">RM {{ number_format($price->price, 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <span class="text-lg font-bold text-primary">
                                    @if($package->min_price == $package->max_price)
                                        RM {{ number_format($package->min_price, 2) }}
                                    @else
                                        RM {{ number_format($package->min_price, 2) }} - RM {{ number_format($package->max_price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600 mb-4">Ready to select a package and confirm your reservation?</p>
                        <a href="{{ route('user.bookings.confirm.form', $booking) }}" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Continue to Package Selection
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-gray-600">No packages are currently available for this venue.</p>
                        <p class="text-sm text-gray-500 mt-2">Please contact our staff for custom arrangements.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Package details modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const showPackageDetailsBtn = document.getElementById('showPackageDetailsBtn');
        const packageDetailsModal = document.getElementById('packageDetailsModal');
        const closePackageDetails = document.getElementById('closePackageDetails');
        
        if (showPackageDetailsBtn && packageDetailsModal) {
            showPackageDetailsBtn.addEventListener('click', function() {
                packageDetailsModal.classList.remove('hidden');
            });
            
            if (closePackageDetails) {
                closePackageDetails.addEventListener('click', function() {
                    packageDetailsModal.classList.add('hidden');
                });
            }
            
            // Close modal when clicking outside
            packageDetailsModal.addEventListener('click', function(e) {
                if (e.target === packageDetailsModal) {
                    packageDetailsModal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endif