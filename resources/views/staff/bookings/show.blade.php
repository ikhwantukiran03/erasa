@extends('layouts.app')

@section('title', 'Booking Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-2">Viewing detailed booking information</p>
            </div>
            <a href="{{ route('staff.bookings.index') }}" class="text-primary hover:underline">Back to Bookings</a>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Booking Details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-1 {{ $booking->status === 'ongoing' ? 'bg-yellow-400' : ($booking->status === 'waiting for deposit' ? 'bg-blue-400' : ($booking->status === 'completed' ? 'bg-green-500' : 'bg-red-500')) }}"></div>
<div class="px-6 py-4 border-b border-gray-200">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Booking Details</h2>
        <div>
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
</div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Booking Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $booking->type }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $booking->booking_date->format('M d, Y') }} ({{ $booking->session === 'morning' ? 'Morning' : 'Evening' }} Session)
                            </dd>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-primary bg-opacity-20 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $booking->venue ? $booking->venue->name : 'Venue not found' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($booking->package)
                                        {{ $booking->package->name }}
                                    @else
                                        No package selected
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($booking->package && $booking->package->prices->count() > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Package Price Range</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                RM {{ number_format($booking->package->min_price, 0, ',', '.') }}
                                @if($booking->package->min_price != $booking->package->max_price)
                                    - RM {{ number_format($booking->package->max_price, 0, ',', '.') }}
                                @endif
                            </dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($booking->expiry_date)
                                    {{ $booking->expiry_date->format('M d, Y') }}
                                    @if(now()->gt($booking->expiry_date) && $booking->status === 'ongoing')
                                        <span class="text-red-600 text-xs">(Expired)</span>
                                    @endif
                                @else
                                    Not set
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Handled By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($booking->handler)
                                    {{ $booking->handler->name }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                    
                    <!-- Package Details (if any) -->
                    @if($booking->package && $booking->package->packageItems->count() > 0)
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Inclusions</h3>
                        
                        @php
                            $packageItemsByCategory = $booking->package->packageItems->groupBy(function($item) {
                                return $item->item && $item->item->category ? $item->item->category->name : 'Uncategorized';
                            });
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($packageItemsByCategory as $categoryName => $packageItems)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 mb-2">{{ $categoryName }}</h4>
                                    <ul class="pl-5 list-disc space-y-1">
                                        @foreach($packageItems as $packageItem)
                                            <li class="text-gray-600 text-sm">
                                                <span class="font-medium">{{ $packageItem->item ? $packageItem->item->name : 'Item not found' }}</span>
                                                @if($packageItem->description)
                                                    - {{ $packageItem->description }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="mt-8 border-t border-gray-200 pt-6 flex flex-wrap gap-4">
                        <a href="{{ route('staff.bookings.edit', $booking) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Edit Booking
                        </a>
                        
                        @if($booking->status !== 'cancelled')
                            <form action="{{ route('staff.bookings.cancel', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel Booking
                                </button>
                            </form>
                        @else
                            <form action="{{ route('staff.bookings.destroy', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition" onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                                    Delete Booking
                                </button>
                            </form>
                        @endif
                        
                        @if($booking->status === 'ongoing')
                            <form action="{{ route('staff.bookings.update', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="user_id" value="{{ $booking->user_id }}">
                                <input type="hidden" name="venue_id" value="{{ $booking->venue_id }}">
                                <input type="hidden" name="package_id" value="{{ $booking->package_id }}">
                                <input type="hidden" name="booking_date" value="{{ $booking->booking_date->format('Y-m-d') }}">
                                <input type="hidden" name="session" value="{{ $booking->session }}">
                                <input type="hidden" name="type" value="{{ $booking->type }}">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition" onclick="return confirm('Are you sure you want to mark this booking as completed?')">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif

                        @if($booking->status === 'waiting for deposit')
    <form action="{{ route('staff.bookings.update', $booking) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="ongoing">
        <input type="hidden" name="user_id" value="{{ $booking->user_id }}">
        <input type="hidden" name="venue_id" value="{{ $booking->venue_id }}">
        <input type="hidden" name="package_id" value="{{ $booking->package_id }}">
        <input type="hidden" name="booking_date" value="{{ $booking->booking_date->format('Y-m-d') }}">
        <input type="hidden" name="session" value="{{ $booking->session }}">
        <input type="hidden" name="type" value="{{ $booking->type }}">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition" onclick="return confirm('Are you sure you want to mark this as deposit received?')">
            Mark Deposit Received
        </button>
    </form>
@endif
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Customer Information</h2>
                </div>
                
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->user ? $booking->user->name : 'User not found' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($booking->user)
                                    <a href="mailto:{{ $booking->user->email }}" class="text-primary hover:underline">
                                        {{ $booking->user->email }}
                                    </a>
                                @else
                                    Email not available
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                @if($booking->user && $booking->user->whatsapp)
                                    {{ $booking->user->whatsapp }}
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->whatsapp) }}" target="_blank" class="ml-2 bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700 inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Chat
                                    </a>
                                @else
                                    WhatsApp not available
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">
                                @if($booking->user)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($booking->user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $booking->user->role }}
                                    </span>
                                @else
                                    Account type not available
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->user ? $booking->user->created_at->format('M d, Y') : 'Date not available' }}</dd>
                        </div>
                    </dl>
                    
                    <!-- Other Bookings from this Customer -->
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Other Bookings from this Customer</h3>
                        
                        @php
                            $otherBookings = \App\Models\Booking::where('user_id', $booking->user_id)
                                ->where('id', '!=', $booking->id)
                                ->orderBy('booking_date', 'desc')
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @if($otherBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($otherBookings as $otherBooking)
                                    <a href="{{ route('staff.bookings.show', $otherBooking) }}" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">Booking #{{ $otherBooking->id }}</p>
                                                <p class="text-xs text-gray-500">{{ $otherBooking->venue ? $otherBooking->venue->name : 'Venue not found' }} - {{ $otherBooking->booking_date->format('M d, Y') }}</p>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $otherBooking->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($otherBooking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($otherBooking->status) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if(\App\Models\Booking::where('user_id', $booking->user_id)->count() > 3)
                                <div class="mt-2 text-center">
                                    <a href="{{ route('staff.bookings.index', ['user_id' => $booking->user_id]) }}" class="text-primary text-sm hover:underline">
                                        View all bookings from this customer
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-sm">No other bookings found for this customer.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
