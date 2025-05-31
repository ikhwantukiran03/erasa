@extends('layouts.app')

@section('title', 'Manage Bookings - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Bookings</h1>
                <p class="text-gray-600 mt-2">View and manage all venue reservations</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Staff Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
                <a href="{{ route('staff.bookings.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Create New Booking</a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="p-6">
                <!-- Search Form -->
                <div class="mb-6">
                    <form action="{{ route('staff.bookings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $search ?? '' }}" 
                                    placeholder="Search by customer name, email, venue, package, or booking ID..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="{{ $status ?? '' }}">
                        <div class="flex gap-2">
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition flex items-center">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                            @if($search ?? false)
                                <a href="{{ route('staff.bookings.index', ['status' => $status ?? '']) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Filter options -->
                <div class="mb-6 flex flex-wrap gap-4">
                    <a href="{{ route('staff.bookings.index', ['search' => $search ?? '']) }}" class="px-4 py-2 {{ request('status') ? 'bg-gray-200 text-gray-700' : 'bg-primary text-white' }} rounded-full text-sm hover:bg-opacity-90 transition">All</a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'ongoing', 'search' => $search ?? '']) }}" class="px-4 py-2 {{ request('status') == 'ongoing' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-gray-300 hover:bg-opacity-90 transition">Ongoing</a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'waiting for deposit', 'search' => $search ?? '']) }}" class="px-4 py-2 {{ request('status') == 'waiting for deposit' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-gray-300 hover:bg-opacity-90 transition">Waiting for Deposit</a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'completed', 'search' => $search ?? '']) }}" class="px-4 py-2 {{ request('status') == 'completed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-gray-300 hover:bg-opacity-90 transition">Completed</a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'search' => $search ?? '']) }}" class="px-4 py-2 {{ request('status') == 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full text-sm hover:bg-gray-300 hover:bg-opacity-90 transition">Cancelled</a>
                </div>

                @if($bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
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
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->venue->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($booking->package)
                                            {{ $booking->package->name }}
                                        @else
                                            No package selected
                                        @endif
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
                                    <a href="{{ route('staff.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <a href="{{ route('staff.bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    
                                    @if($booking->status !== 'cancelled')
                                        <form action="{{ route('staff.bookings.cancel', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                        </form>
                                    @else
                                        <form action="{{ route('staff.bookings.destroy', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">Delete</button>
                                        </form>
                                    @endif
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
                    <p class="mt-4">No bookings found</p>
                    <a href="{{ route('staff.bookings.create') }}" class="mt-4 inline-block text-primary hover:underline">Create your first booking</a>
                </div>
                @endif
                
                @if($bookings->count() > 0 && method_exists($bookings, 'links'))
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection