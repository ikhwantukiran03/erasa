@extends('layouts.app')

@section('title', 'Process Booking Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb navigation -->
        <nav class="mb-6 text-sm">
            <ol class="list-none p-0 inline-flex items-center text-gray-500">
                <li class="flex items-center">
                    <a href="{{ route('staff.dashboard') }}" class="hover:text-primary transition">Dashboard</a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('staff.requests.index') }}" class="hover:text-primary transition">Booking Requests</a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="text-primary font-medium">Process Request #{{ $bookingRequest->id }}</li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Process Booking Request</h1>
                <p class="text-gray-600 mt-2">Review and respond to customer booking #{{ $bookingRequest->id }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('staff.requests.index') }}" class="flex items-center text-primary hover:underline transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to All Requests
                </a>
            </div>
        </div>
        
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Request Details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-1 bg-yellow-400"></div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Request #{{ $bookingRequest->id }} Details</h2>
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <div class="space-y-1">
                            <h3 class="text-sm font-medium text-gray-500">Customer Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-900">{{ $bookingRequest->name }}</p>
                                <p class="text-sm text-gray-700">{{ $bookingRequest->email }}</p>
                                <p class="text-sm text-gray-700 flex items-center mt-1">
                                    {{ $bookingRequest->whatsapp_no }}
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bookingRequest->whatsapp_no) }}" target="_blank" class="ml-2 inline-flex items-center text-xs text-green-700 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Chat
                                    </a>
                                </p>
                                <div class="mt-2">
                                    @if($bookingRequest->user_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Registered User
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="mr-1.5 h-2 w-2 text-yellow-500" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Guest (No Account)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <h3 class="text-sm font-medium text-gray-500">Request Details</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm"><span class="font-medium">Request Type:</span> <span class="capitalize">{{ $bookingRequest->type }}</span></p>
                                <p class="text-sm"><span class="font-medium">Date Submitted:</span> {{ $bookingRequest->created_at->format('M d, Y H:i') }}</p>
                                <p class="text-sm"><span class="font-medium">Event Date:</span> 
                                    @if($bookingRequest->event_date)
                                        {{ $bookingRequest->event_date->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-500 italic">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <h3 class="text-sm font-medium text-gray-500">Venue & Package</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm"><span class="font-medium">Requested Venue:</span> 
                                    @if($bookingRequest->venue)
                                        {{ $bookingRequest->venue->name }}
                                    @else
                                        <span class="text-gray-500 italic">Not specified</span>
                                    @endif
                                </p>
                                <p class="text-sm"><span class="font-medium">Requested Package:</span> 
                                    @if($bookingRequest->package)
                                        {{ $bookingRequest->package->name }}
                                    @else
                                        <span class="text-gray-500 italic">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-1 md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Message from Customer</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="whitespace-pre-wrap text-sm text-gray-700">{{ $bookingRequest->message ?: 'No message provided.' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Response Form -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Your Response</h2>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm">How would you like to respond to this booking request?</p>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Approve Form -->
                        <div class="bg-green-50 p-5 rounded-lg border border-green-100">
                            <h3 class="font-semibold text-green-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Approve Request
                            </h3>
                            <form action="{{ route('staff.requests.approve', $bookingRequest) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (Optional)</label>
                                    <textarea id="approve_notes" name="admin_notes" rows="4" class="form-input w-full text-sm focus:border-green-500 focus:ring-green-500" placeholder="Add any additional information for the customer..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Approve Request
                                </button>
                            </form>
                        </div>
                        
                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-2 text-gray-500 text-sm">OR</span>
                            </div>
                        </div>
                        
                        <!-- Reject Form -->
                        <div class="bg-red-50 p-5 rounded-lg border border-red-100">
                            <h3 class="font-semibold text-red-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Reject Request
                            </h3>
                            <form action="{{ route('staff.requests.reject', $bookingRequest) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                                    <textarea id="reject_notes" name="admin_notes" rows="4" class="form-input w-full text-sm focus:border-red-500 focus:ring-red-500" placeholder="Provide a detailed reason for rejection..." required></textarea>
                                    <p class="mt-1 text-xs text-gray-500">This message will be sent to the customer</p>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Reject Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection