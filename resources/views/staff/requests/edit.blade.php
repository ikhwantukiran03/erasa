@extends('layouts.app')

@section('title', 'Process Booking Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Process Booking Request</h1>
                <p class="text-gray-600 mt-2">Review and respond to this booking request</p>
            </div>
            <div>
                <a href="{{ route('staff.requests.index') }}" class="text-primary hover:underline">Back to All Requests</a>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Request Details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 bg-yellow-400"></div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Request #{{ $bookingRequest->id }} Details</h2>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bookingRequest->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Contact Information</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <p>{{ $bookingRequest->email }}</p>
                                <p>{{ $bookingRequest->whatsapp_no }}</p>
                            </dd>
                        </div>
                        
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
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->user_id)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Registered User
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Guest (No Account)
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Message from Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">{{ $bookingRequest->message }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Response Form -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Your Response</h2>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm">How would you like to respond to this booking request?</p>
                    </div>
                    
                    <div class="flex flex-col gap-4">
                        <!-- Approve Form -->
                        <div>
                            <h3 class="font-semibold text-green-700 mb-2">Approve Request</h3>
                            <form action="{{ route('staff.requests.approve', $bookingRequest) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (Optional)</label>
                                    <textarea id="approve_notes" name="admin_notes" rows="4" class="form-input w-full" placeholder="Add any additional information for the customer..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                    Approve Request
                                </button>
                            </form>
                        </div>
                        
                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-2 text-gray-500 text-sm">OR</span>
                            </div>
                        </div>
                        
                        <!-- Reject Form -->
                        <div>
                            <h3 class="font-semibold text-red-700 mb-2">Reject Request</h3>
                            <form action="{{ route('staff.requests.reject', $bookingRequest) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                                    <textarea id="reject_notes" name="admin_notes" rows="4" class="form-input w-full" placeholder="Provide a detailed reason for rejection..." required></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
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