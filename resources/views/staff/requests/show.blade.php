@extends('layouts.app')

@section('title', 'Booking Request Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking Request #{{ $bookingRequest->id }}</h1>
                <p class="text-gray-600 mt-2">Detailed information about this booking request</p>
            </div>
            <div>
                <a href="{{ route('staff.requests.index') }}" class="text-primary hover:underline">Back to All Requests</a>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Request Details -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 {{ $bookingRequest->status === 'pending' ? 'bg-yellow-400' : ($bookingRequest->status === 'approved' ? 'bg-green-500' : ($bookingRequest->status === 'rejected' ? 'bg-red-500' : 'bg-gray-500')) }}"></div>
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
                        
                        @if($bookingRequest->status !== 'pending')
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Admin Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-wrap">
                                {{ $bookingRequest->admin_notes ?: 'No admin notes provided' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->handler)
                                    {{ $bookingRequest->handler->name }}
                                @else
                                    Not available
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed At</dt>
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
            
            <!-- Customer Information -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Customer Information</h2>
                </div>
                
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bookingRequest->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $bookingRequest->email }}" class="text-primary hover:underline">
                                    {{ $bookingRequest->email }}
                                </a>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                {{ $bookingRequest->whatsapp_no }}
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bookingRequest->whatsapp_no) }}" target="_blank" class="ml-2 bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    Chat
                                </a>
                            </dd>
                        </div>
                        
                        @if($bookingRequest->user_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Account</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($bookingRequest->user)
                                    <div class="bg-gray-50 p-3 rounded">
                                        <p><span class="font-medium">Name:</span> {{ $bookingRequest->user->name }}</p>
                                        <p><span class="font-medium">Email:</span> {{ $bookingRequest->user->email }}</p>
                                        <p><span class="font-medium">WhatsApp:</span> {{ $bookingRequest->user->whatsapp }}</p>
                                        <p><span class="font-medium">Role:</span> {{ ucfirst($bookingRequest->user->role) }}</p>
                                        <p><span class="font-medium">Joined:</span> {{ $bookingRequest->user->created_at->format('M d, Y') }}</p>
                                    </div>
                                @else
                                    User not found
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                    
                    <div class="mt-8 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                            
                            <div class="space-y-3">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bookingRequest->whatsapp_no) }}" target="_blank" class="block w-full bg-green-600 text-white text-center px-4 py-2 rounded hover:bg-green-700 transition">
                                    Contact via WhatsApp
                                </a>
                                
                                @if($bookingRequest->status === 'pending')
                                <a href="{{ route('staff.requests.edit', $bookingRequest) }}" class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700 transition">
                                    Process Request
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection