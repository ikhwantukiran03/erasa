@extends('layouts.app')

@section('title', 'User Dashboard - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-r from-primary/10 to-secondary/20 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div data-aos="fade-up">
                <h1 class="text-3xl font-display font-bold text-primary mb-1">My Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
            <div class="mt-4 md:mt-0" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Booking
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <!-- Book Now -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center">
                    <div class="bg-primary/10 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ready to plan your event?</p>
                        <p class="text-lg font-semibold text-gray-800">Book a Venue</p>
                    </div>
                </div>
                <a href="{{ route('booking-requests.create') }}" class="mt-4 inline-flex items-center text-sm text-primary hover:underline">
                    Submit a booking request
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <!-- My Booking Requests -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center">
                    <div class="bg-blue-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">My Booking Requests</p>
                        <p class="text-lg font-semibold text-gray-800">{{ \App\Models\BookingRequest::where('user_id', Auth::id())->count() }}</p>
                    </div>
                </div>
                <a href="{{ route('user.booking-requests') }}" class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline">
                    View all booking requests
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <!-- My Bookings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center">
                    <div class="bg-green-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">My Bookings</p>
                        <p class="text-lg font-semibold text-gray-800">{{ \App\Models\Booking::where('user_id', Auth::id())->count() }}</p>
                    </div>
                </div>
                <a href="{{ route('user.bookings') }}" class="mt-4 inline-flex items-center text-sm text-green-600 hover:underline">
                    View all bookings
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <!-- My Profile -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center">
                    <div class="bg-purple-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">My Profile</p>
                        <p class="text-lg font-semibold text-gray-800">Account Settings</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex items-center text-sm text-purple-600 hover:underline">
                    Edit your profile
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Upcoming Bookings Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-10 border border-gray-100" data-aos="fade-up">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800">Upcoming Bookings</h2>
                </div>
            </div>
            <div class="p-6">
                @php
                    $upcomingBookings = \App\Models\Booking::where('user_id', Auth::id())
                        ->where('booking_date', '>=', date('Y-m-d'))
                        ->where('status', 'ongoing')
                        ->orderBy('booking_date')
                        ->take(3)
                        ->get();
                @endphp
                
                @if($upcomingBookings->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @foreach($upcomingBookings as $booking)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 hover:shadow-md transition transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-lg">{{ $booking->venue->name }}</p>
                                        <div class="flex items-center mt-2 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm">{{ $booking->booking_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center mt-1 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm">{{ $booking->session === 'morning' ? 'Morning' : 'Evening' }}</span>
                                        </div>
                                        <p class="mt-2 inline-flex items-center text-xs px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ ucfirst($booking->type) }}
                                        </p>
                                        
                                        @if($booking->package)
                                            <p class="mt-2 text-xs text-gray-500">Package: {{ $booking->package->name }}</p>
                                        @endif
                                    </div>
                                    
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $booking->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Ref: #{{ $booking->id }}</span>
                                    <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex items-center text-primary text-sm hover:underline">
                                        View Details
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(\App\Models\Booking::where('user_id', Auth::id())->count() > 3)
                        <div class="mt-6 text-center">
                            <a href="{{ route('user.bookings') }}" class="inline-flex items-center text-primary hover:underline">
                                View all my bookings
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600 mb-4">You don't have any upcoming bookings.</p>
                        <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create your first booking
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Booking Requests Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100" data-aos="fade-up">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800">Recent Booking Requests</h2>
                </div>
            </div>
            <div class="p-6">
                @php
                    $recentRequests = \App\Models\BookingRequest::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                @endphp
                
                @if($recentRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue/Package</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentRequests as $request)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm capitalize">{{ $request->type }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            @if($request->venue)
                                                {{ $request->venue->name }}
                                            @else
                                                Not specified
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if($request->package)
                                                {{ $request->package->name }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($request->event_date)
                                            {{ $request->event_date->format('M d, Y') }}
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->status === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($request->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($request->status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('user.booking-requests.show', $request) }}" class="text-primary hover:underline">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('user.booking-requests') }}" class="inline-flex items-center text-primary hover:underline">
                            View all booking requests
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                        <p class="text-gray-600 mb-4">You haven't submitted any booking requests yet.</p>
                        <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit a booking request
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection