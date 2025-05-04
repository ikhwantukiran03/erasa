@extends('layouts.app')

@section('title', 'My Bookings - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">My Bookings</h1>
                <p class="text-gray-600 mt-2">View and manage your venue bookings</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
                <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Create New Request</a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="p-6">
                <!-- Filter options -->
<div class="mb-6 flex flex-wrap gap-4">
    <a href="{{ route('user.bookings', ['status' => 'all']) }}" class="px-4 py-2 {{ !request('status') || request('status') == 'all' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">All</a>
    <a href="{{ route('user.bookings', ['status' => 'upcoming']) }}" class="px-4 py-2 {{ request('status') == 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">Upcoming</a>
    <a href="{{ route('user.bookings', ['status' => 'waiting_for_deposit']) }}" class="px-4 py-2 {{ request('status') == 'waiting_for_deposit' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">Waiting for Deposit</a>
    <a href="{{ route('user.bookings', ['status' => 'ongoing']) }}" class="px-4 py-2 {{ request('status') == 'ongoing' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">Ongoing</a>
    <a href="{{ route('user.bookings', ['status' => 'completed']) }}" class="px-4 py-2 {{ request('status') == 'completed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">Completed</a>
    <a href="{{ route('user.bookings', ['status' => 'cancelled']) }}" class="px-4 py-2 {{ request('status') == 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-opacity-90 transition">Cancelled</a>
</div>

@php
    $query = \App\Models\Booking::where('user_id', Auth::id());
    
    if(request('status') == 'upcoming') {
        $query->where('booking_date', '>=', date('Y-m-d'))
             ->where('status', 'ongoing');
    } elseif(request('status') == 'waiting_for_deposit') {
        $query->where('status', 'waiting for deposit');
    } elseif(request('status') && request('status') != 'all') {
        $query->where('status', request('status'));
    }
    
    $bookings = $query->orderBy('booking_date', 'desc')->get();
@endphp
                
                @if($bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue/Package</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->venue->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($booking->package)
                                                    {{ $booking->package->name }}
                                                @else
                                                    No package selected
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->session === 'morning' ? 'Morning' : 'Evening' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($booking->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
    @if($booking->status === 'ongoing')
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
            Ongoing
        </span>
    @elseif($booking->status === 'waiting for deposit')
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
            Waiting for Deposit
        </span>
    @elseif($booking->status === 'completed')
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
            Completed
        </span>
    @elseif($booking->status === 'cancelled')
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
            Cancelled
        </span>
    @endif
</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="text-primary hover:underline">View Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4">You don't have any bookings{{ request('status') && request('status') != 'all' ? ' with this status' : '' }}.</p>
                    <a href="{{ route('booking-requests.create') }}" class="mt-4 inline-block text-primary hover:underline">Create your first booking request</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection