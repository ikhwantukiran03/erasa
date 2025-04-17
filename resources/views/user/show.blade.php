@extends('layouts.app')

@section('title', 'Booking Request Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking Request #{{ $bookingRequest->id }}</h1>
                <p class="text-gray-600 mt-2">View the details of your booking request</p>
            </div>
            <div>
                <a href="{{ route('user.booking-requests') }}" class="text-primary hover:underline">Back to My Requests</a>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Request Details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 {{ $bookingRequest->status === 'pending' ? 'bg-yellow-400' : ($bookingRequest->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}"></div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Request Details</h2>
                        <div>
                            @if($bookingRequest->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($bookingRequest->status === 'approved')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @elseif($bookingRequest->status === 'rejected')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($bookingRequest->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Request Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $bookingRequest->type }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date Submitted</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bookingRequest->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->event_date)
                                    {{ $bookingRequest->event_date->format('M d, Y') }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Venue</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->venue)
                                    {{ $bookingRequest->venue->name }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Package</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->package)
                                    {{ $bookingRequest->package->name }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Contact Information</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <p>{{ $bookingRequest->email }}</p>
                                <p>{{ $bookingRequest->whatsapp_no }}</p>
                            </dd>
                        </div>
                        
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Your Message</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">{{ $bookingRequest->message }}</dd>
                        </div>
                        
                        @if($bookingRequest->status !== 'pending')
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Response from Staff</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">
                                {{ $bookingRequest->admin_notes ?: 'No additional notes provided.' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Handled By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->handler)
                                    {{ $bookingRequest->handler->name }}
                                @else
                                    Staff member
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed On</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->handled_at)
                                    {{ $bookingRequest->handled_at->format('M d, Y H:i') }}
                                @else
                                    Not available
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <!-- Status Card -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Status Information</h2>
                </div>
                
                <div class="p-6">
                    @if($bookingRequest->status === 'pending')
                        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Awaiting Response</h3>
                                    <p class="mt-2 text-sm text-yellow-700">
                                        Your booking request is currently being reviewed by our staff. We'll get back to you shortly via WhatsApp.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">
                            Request submitted on {{ $bookingRequest->created_at->format('M d, Y') }} at {{ $bookingRequest->created_at->format('H:i') }}.
                        </p>
                        
                    @elseif($bookingRequest->status === 'approved')
                        <div class="bg-green-50 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Request Approved</h3>
                                    <p class="mt-2 text-sm text-green-700">
                                        Great news! Your booking request has been approved. You can now view the details of your confirmed booking.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $booking = \App\Models\Booking::where('user_id', Auth::id())
                                ->where('venue_id', $bookingRequest->venue_id)
                                ->where('booking_date', $bookingRequest->event_date)
                                ->first();
                        @endphp
                        
                        @if($booking)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                                <h3 class="font-medium text-gray-800 mb-2">Your Booking</h3>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Booking ID:</span> #{{ $booking->id }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Venue:</span> {{ $booking->venue->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Date:</span> {{ $booking->booking_date->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Session:</span> {{ ucfirst($booking->session) }}
                                </p>
                                
                                <div class="mt-4">
                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="inline-flex items-center text-primary hover:underline">
                                        View booking details
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                    @elseif($bookingRequest->status === 'rejected')
                        <div class="bg-red-50 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Request Rejected</h3>
                                    <p class="mt-2 text-sm text-red-700">
                                        We're sorry, but your booking request could not be accommodated. Please see the staff response for more details.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('booking-requests.create') }}" class="block w-full bg-primary text-white text-center px-4 py-2 rounded hover:bg-opacity-90 transition">
                                Create New Request
                            </a>
                            
                            @if($bookingRequest->status === 'rejected')
                                <p class="mt-4 text-sm text-gray-600">
                                    If you'd like to discuss this further or explore other options, please contact our team.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection