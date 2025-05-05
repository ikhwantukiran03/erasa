@extends('layouts.app')

@section('title', 'Booking Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-2">View your booking details</p>
            </div>
            <a href="{{ route('user.bookings') }}" class="text-primary hover:underline">Back to My Bookings</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-1 {{ $booking->status === 'ongoing' ? 'bg-yellow-400' : ($booking->status === 'waiting for deposit' ? 'bg-blue-400' : ($booking->status === 'completed' ? 'bg-green-500' : 'bg-red-500')) }}"></div>
        <!-- Action Buttons -->
<div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap justify-end gap-4">
    <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
        Create New Booking
    </a>
    
    @if($booking->status === 'waiting for deposit')
        <a href="{{ route('user.invoices.create', $booking) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Submit Payment Proof
        </a>
    @endif
    
    @if($booking->status === 'ongoing' && $booking->booking_date->isAfter(now()))
        <a href="https://wa.me/60123456789" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition flex items-center" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            Request Changes via WhatsApp
        </a>
    @endif
</div>
<div class="p-6">
    <!-- Booking Status -->
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
        <div>
            <span class="text-sm text-gray-500">Booking Status</span>
            <div class="mt-1">
                @if($booking->status === 'ongoing')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Ongoing
                    </span>
                @elseif($booking->status === 'waiting for deposit')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        Waiting for Deposit
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Completed
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
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
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                                <dd class="text-sm text-gray-900 col-span-2 capitalize">{{ $booking->type }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->booking_date->format('l, F d, Y') }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Session</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->session === 'morning' ? 'Morning Session' : 'Evening Session' }}</dd>
                            </div>
                            @if($booking->expiry_date)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                                <dd class="text-sm text-gray-900 col-span-2">
                                    {{ $booking->expiry_date->format('F d, Y') }}
                                    @if(now()->gt($booking->expiry_date) && $booking->status === 'ongoing')
                                        <span class="text-red-600 text-xs">(Expired)</span>
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    
                    <!-- Venue Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Venue Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Venue Name</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->venue->name }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->venue->full_address }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Package Information -->
                    @if($booking->package)
                    <div class="md:col-span-2 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Package Name</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->package->name }}</dd>
                            </div>
                            @if($booking->package->description)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->package->description }}</dd>
                            </div>
                            @endif
                            
                            @if($booking->package->prices->count() > 0)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Price Range</dt>
                                <dd class="text-sm font-medium text-primary col-span-2">
                                    RM {{ number_format($booking->package->min_price, 0, ',', '.') }}
                                    @if($booking->package->min_price != $booking->package->max_price)
                                        - RM {{ number_format($booking->package->max_price, 0, ',', '.') }}
                                    @endif
                                </dd>
                            </div>
                            @endif
                            
                            <!-- Package Items -->
                            @if($booking->package->packageItems->count() > 0)
                            <div class="mt-6 col-span-3">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Included Items</dt>
                                <dd class="text-sm text-gray-900">
                                    @php
                                        $packageItemsByCategory = $booking->package->packageItems->groupBy(function($item) {
                                            return $item->item->category->name;
                                        });
                                    @endphp
                                    
                                    <div class="space-y-4 mt-2">
                                        @foreach($packageItemsByCategory as $categoryName => $packageItems)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="font-medium text-gray-700 mb-2">{{ $categoryName }}</h4>
                                                <ul class="pl-5 list-disc space-y-1">
                                                    @foreach($packageItems as $packageItem)
                                                        <li class="text-gray-600 text-sm">
                                                            <span class="font-medium">{{ $packageItem->item->name }}</span>
                                                            @if($packageItem->description)
                                                                - {{ $packageItem->description }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                    
                    <!-- Booking Contact Information -->
                    <div class="md:col-span-2 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>
                        <p class="text-gray-600 mb-4">If you have any questions about your booking, please contact us:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Phone</p>
                                    <p class="text-gray-600">+60 123 456 789</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Email</p>
                                    <p class="text-gray-600">info@enakrasa.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap justify-end gap-4">
                    <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Create New Booking
                    </a>
                    
                    @if($booking->status === 'ongoing' && $booking->booking_date->isAfter(now()))
                    <a href="https://wa.me/60123456789" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition flex items-center" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Request Changes via WhatsApp
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection