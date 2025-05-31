@extends('layouts.app')

@section('title', 'My Bookings - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">My Bookings</h1>
                <p class="text-gray-600 mt-1">View and manage your venue bookings</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
                <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Request
                </a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            <div class="p-6">
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Search by venue, package, booking ID, or date..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary text-sm"
                            value="{{ request('search') }}"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="clearSearch" class="text-gray-400 hover:text-gray-600 focus:outline-none" style="display: none;">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Search by venue name, package name, booking ID (#123), or date (e.g., "Dec 2024")</p>
                </div>
                
                <!-- Filter options -->
                <div class="mb-6 overflow-x-auto">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('user.bookings', ['status' => 'all']) }}" class="px-4 py-2 {{ !request('status') || request('status') == 'all' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">All</a>
                        <a href="{{ route('user.bookings', ['status' => 'upcoming']) }}" class="px-4 py-2 {{ request('status') == 'upcoming' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Upcoming</a>
                        <a href="{{ route('user.bookings', ['type' => 'reservation']) }}" class="px-4 py-2 {{ request('type') == 'reservation' ? 'bg-purple-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Reservations</a>
                        <a href="{{ route('user.bookings', ['status' => 'waiting_for_deposit']) }}" class="px-4 py-2 {{ request('status') == 'waiting_for_deposit' ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Waiting for Deposit</a>
                        <a href="{{ route('user.bookings', ['status' => 'pending_verification']) }}" class="px-4 py-2 {{ request('status') == 'pending_verification' ? 'bg-purple-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Pending Verification</a>
                        <a href="{{ route('user.bookings', ['status' => 'ongoing']) }}" class="px-4 py-2 {{ request('status') == 'ongoing' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Ongoing</a>
                        <a href="{{ route('user.bookings', ['status' => 'completed']) }}" class="px-4 py-2 {{ request('status') == 'completed' ? 'bg-green-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Completed</a>
                        <a href="{{ route('user.bookings', ['status' => 'cancelled']) }}" class="px-4 py-2 {{ request('status') == 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-full text-sm transition-colors">Cancelled</a>
                    </div>
                </div>

@php
    $query = \App\Models\Booking::where('user_id', Auth::id())
        ->with(['venue', 'package']);
    
    // Apply search filter
    if(request('search')) {
        $searchTerm = request('search');
        $query->where(function($q) use ($searchTerm) {
            // Search by booking ID (remove # if present)
            $bookingId = str_replace('#', '', $searchTerm);
            if(is_numeric($bookingId)) {
                $q->orWhere('id', $bookingId);
            }
            
            // Search by venue name
            $q->orWhereHas('venue', function($venueQuery) use ($searchTerm) {
                $venueQuery->where('name', 'ILIKE', '%' . $searchTerm . '%');
            });
            
            // Search by package name
            $q->orWhereHas('package', function($packageQuery) use ($searchTerm) {
                $packageQuery->where('name', 'ILIKE', '%' . $searchTerm . '%');
            });
            
            // Search by booking date (flexible date search)
            $q->orWhere('booking_date', 'ILIKE', '%' . $searchTerm . '%');
            
            // Search by formatted date (e.g., "Dec 2024", "December", etc.)
            $q->orWhereRaw("TO_CHAR(booking_date, 'Mon YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
            $q->orWhereRaw("TO_CHAR(booking_date, 'Month YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
            $q->orWhereRaw("TO_CHAR(booking_date, 'Mon DD, YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
        });
    }
    
    // Apply status/type filters
    if(request('type') == 'reservation') {
        $query->where('type', 'reservation');
    } elseif(request('status') == 'upcoming') {
        $query->where('booking_date', '>=', date('Y-m-d'))
             ->where('status', 'ongoing');
    } elseif(request('status') == 'waiting_for_deposit') {
        $query->where('status', 'waiting for deposit');
    } elseif(request('status') && request('status') != 'all') {
        $query->where('status', request('status'));
    }
    
    $bookings = $query->orderBy('booking_date', 'desc')->get();
    
    // If search is active, also get the total count for display
    $totalBookings = \App\Models\Booking::where('user_id', Auth::id())->count();
@endphp
                
                @if($bookings->count() > 0)
                <!-- Search Results Info -->
                @if(request('search'))
                <div class="mb-4 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="text-sm text-blue-800">
                            Found <strong>{{ $bookings->count() }}</strong> result{{ $bookings->count() !== 1 ? 's' : '' }} for "<strong>{{ request('search') }}</strong>"
                            @if($totalBookings > 0)
                                out of {{ $totalBookings }} total booking{{ $totalBookings !== 1 ? 's' : '' }}
                            @endif
                        </span>
                    </div>
                    <a href="{{ route('user.bookings') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Clear search
                    </a>
                </div>
                @endif
                
                <!-- Card Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden">
                        <!-- Card Header with Status -->
                        <div class="relative">
                            <!-- Venue Image Background -->
                            @php
                                $venueImage = $booking->venue->galleries->first();
                                $imageUrl = $venueImage ? 
                                    ($venueImage->source === 'local' ? $venueImage->image_path : $venueImage->image_url) : 
                                    'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=3270&auto=format&fit=crop';
                            @endphp
                            <div class="h-32 bg-gradient-to-r from-primary to-primary-dark relative overflow-hidden">
                                <img src="{{ $imageUrl }}" alt="{{ $booking->venue->name }}" class="w-full h-full object-cover opacity-80">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($booking->status === 'ongoing')
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                            Ongoing
                                        </span>
                                    @elseif($booking->status === 'waiting for deposit')
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-blue-100 text-blue-800 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                            Waiting for Deposit
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-green-100 text-green-800 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Completed
                                        </span>
                                    @elseif($booking->status === 'pending_verification')
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-purple-100 text-purple-800 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                            Pending Verification
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-red-100 text-red-800 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                            Cancelled
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Booking ID -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-2 py-1 bg-black/30 text-white text-xs font-medium rounded-md backdrop-blur-sm">
                                        #{{ $booking->id }}
                                    </span>
                                </div>
                                
                                <!-- Venue Name -->
                                <div class="absolute bottom-3 left-3 right-3">
                                    <h3 class="text-white font-semibold text-lg truncate">{{ $booking->venue->name }}</h3>
                                    <p class="text-white/80 text-sm">
                                        @if($booking->package)
                                            {{ $booking->package->name }}
                                        @else
                                            <span class="italic">No package selected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-5">
                            <!-- Date and Session -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-1">
                                    @if($booking->session === 'morning')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Morning</span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Evening</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Type Badge -->
                            <div class="mb-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($booking->type) }}
                                </span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="space-y-2">
                                <!-- Primary Action -->
                                <a href="{{ route('user.bookings.show', $booking->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </a>
                                
                                <!-- Secondary Actions -->
                                @if($booking->type === 'reservation' && $booking->status !== 'cancelled' && $booking->status !== 'completed' && ($booking->package_id !== null))
                                <div class="flex space-x-2">
                                    <a href="{{ route('user.bookings.confirm.form', $booking) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Confirm
                                    </a>
                                    
                                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-xs bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                                @endif

                                @if($booking->status === 'completed')
                                    @if(!$booking->feedback)
                                        <a href="{{ route('user.bookings.feedback', $booking) }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-amber-100 text-amber-800 rounded-lg hover:bg-amber-200 transition-colors font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            Give Feedback
                                        </a>
                                    @else
                                        <div class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-gray-100 text-gray-800 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Feedback Submitted
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            @if(request('search'))
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        
                        @if(request('search'))
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
                            <p class="text-gray-500 mb-6">
                                No bookings match your search for "<strong>{{ request('search') }}</strong>".
                                <br>Try searching with different keywords or check your spelling.
                            </p>
                            <div class="space-y-3">
                                <a href="{{ route('user.bookings') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear search
                                </a>
                                <br>
                                <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create new booking request
                                </a>
                            </div>
                        @else
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings found</h3>
                            <p class="text-gray-500 mb-6">
                                You don't have any bookings{{ request('status') && request('status') != 'all' ? ' with this status' : '' }} yet.
                            </p>
                            <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create your first booking request
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    let searchTimeout;
    
    // Show/hide clear button based on input value
    function toggleClearButton() {
        if (searchInput.value.trim()) {
            clearButton.style.display = 'block';
        } else {
            clearButton.style.display = 'none';
        }
    }
    
    // Perform search with debouncing
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        const currentUrl = new URL(window.location);
        
        if (searchTerm) {
            currentUrl.searchParams.set('search', searchTerm);
        } else {
            currentUrl.searchParams.delete('search');
        }
        
        // Remove status and type filters when searching to show all results
        if (searchTerm) {
            currentUrl.searchParams.delete('status');
            currentUrl.searchParams.delete('type');
        }
        
        window.location.href = currentUrl.toString();
    }
    
    // Handle input events with debouncing
    searchInput.addEventListener('input', function() {
        toggleClearButton();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Set new timeout for search (500ms delay)
        searchTimeout = setTimeout(performSearch, 500);
    });
    
    // Handle Enter key press for immediate search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch();
        }
    });
    
    // Handle clear button click
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        toggleClearButton();
        
        // Clear search from URL
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('search');
        window.location.href = currentUrl.toString();
    });
    
    // Initialize clear button visibility
    toggleClearButton();
    
    // Focus search input if there's a search parameter
    if (new URLSearchParams(window.location.search).has('search')) {
        searchInput.focus();
        // Move cursor to end of input
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }
});
</script>

@endsection