@extends('layouts.app')

@section('title', 'Confirm Reservation - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Confirm Your Reservation</h1>
                <p class="text-gray-600 mt-1">Select a package to convert your reservation to a wedding booking</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Booking
                </a>
            </div>
        </div>
        
        <!-- Booking Summary -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-xl font-display font-bold text-dark mb-4">Booking Summary</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600">Venue: <span class="font-semibold text-gray-800">{{ $booking->venue ? $booking->venue->name : 'Venue not found' }}</span></p>
                        <p class="text-gray-600 mt-2">Date: <span class="font-semibold text-gray-800">{{ $booking->booking_date->format('l, F d, Y') }}</span></p>
                        <p class="text-gray-600 mt-2">Session: 
                            <span class="font-semibold text-gray-800">
                                {{ $booking->session === 'morning' ? 'Morning' : 'Evening' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="text-2xl font-display font-bold text-dark mb-6">Select a Package</h2>
        
        @if($packages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $package)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="h-1.5 bg-primary"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-display font-bold text-primary mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                        
                        @if($package->prices->count() > 0)
                            <div class="border-t border-gray-100 pt-4 mb-4">
                                <p class="text-gray-700 font-medium mb-2">Price Options:</p>
                                <form action="{{ route('user.bookings.confirm', $booking) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                    <input type="hidden" name="payment_option" value="deposit">
                                    
                                    <div class="form-group">
                                        <label for="price_id_{{ $package->id }}" class="block text-sm font-medium text-gray-700 mb-2">Select Guest Count:</label>
                                        <select id="price_id_{{ $package->id }}" name="price_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            @foreach($package->prices as $price)
                                                <option value="{{ $price->id }}">
                                                    {{ $price->pax }} guests - RM {{ number_format($price->price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition">
                                        Select Package
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('user.bookings.confirm', $booking) }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <input type="hidden" name="payment_option" value="deposit">
                                <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition">
                                    Select Package
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <p class="text-gray-600">No packages available for this venue at the moment. Please contact our staff for assistance.</p>
                <a href="{{ route('user.bookings.show', $booking) }}" class="inline-block mt-4 bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition">
                    Return to Booking
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 