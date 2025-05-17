@extends('layouts.app')

@section('title', 'Booking Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking Request</h1>
                <p class="text-gray-600 mt-1">View your booking request details</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('booking-requests.my-requests') }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to My Requests
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-1.5 
                @if($bookingRequest->status === 'pending')
                    bg-yellow-400
                @elseif($bookingRequest->status === 'approved')
                    bg-green-500
                @else
                    bg-red-500
                @endif
            "></div>
            
            <div class="p-6">
                <!-- Status Badge -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Request #{{ $bookingRequest->id }}</h2>
                    
                    @if($bookingRequest->status === 'pending')
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                            Pending
                        </span>
                    @elseif($bookingRequest->status === 'approved')
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                            Rejected
                        </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Information</h3>
                        <dl class="space-y-3">
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Event Type</dt>
                                <dd class="text-sm text-gray-900 font-medium capitalize w-full sm:w-2/3">
                                    {{ $bookingRequest->event_type }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Date</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    @if($bookingRequest->date)
                                        {{ $bookingRequest->date->format('l, F d, Y') }}
                                    @else
                                        <span class="text-gray-400 italic">Not specified</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Session</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->session === 'morning' ? 'Morning' : 'Evening' }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Package</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->package ? $bookingRequest->package->name : 'Not specified' }}
                                </dd>
                            </div>
                            @if($bookingRequest->guest_count)
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Guest Count</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->guest_count }} guests
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>
                        <dl class="space-y-3">
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Name</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->name }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">Email</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->email }}
                                </dd>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 w-full sm:w-1/3">WhatsApp</dt>
                                <dd class="text-sm text-gray-900 font-medium w-full sm:w-2/3">
                                    {{ $bookingRequest->whatsapp }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                @if($bookingRequest->notes)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Notes</h3>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="text-gray-800 whitespace-pre-line">{{ $bookingRequest->notes }}</div>
                    </div>
                </div>
                @endif
                
                @if($bookingRequest->status === 'approved' && $bookingRequest->booking)
                <div class="bg-green-50 p-4 rounded-lg border border-green-100 mb-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Booking Created</h3>
                    <p class="text-green-700 mb-3">Your request has been approved and a booking has been created.</p>
                    <a href="{{ route('user.bookings.show', $bookingRequest->booking_id) }}" class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Booking
                    </a>
                </div>
                @endif
                
                @if($bookingRequest->status === 'rejected' && $bookingRequest->staff_notes)
                <div class="bg-red-50 p-4 rounded-lg border border-red-100 mb-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Request Rejected</h3>
                    <div class="text-red-700 whitespace-pre-line">{{ $bookingRequest->staff_notes }}</div>
                </div>
                @endif
                
                <div class="text-center mt-6">
                    <a href="{{ route('booking-requests.my-requests') }}" class="btn-primary py-2 px-6">
                        Back to My Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 