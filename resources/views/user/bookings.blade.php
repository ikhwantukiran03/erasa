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
    $query = \App\Models\Booking::where('user_id', Auth::id());
    
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
@endphp
                
                @if($bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Venue/Package</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-medium">{{ $booking->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->venue->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($booking->package)
                                                    {{ $booking->package->name }}
                                                @else
                                                    <span class="text-gray-400 italic">No package selected</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($booking->session === 'morning')
                                            <span class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Morning
                                            </span>
                                        @else
                                            <span class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                </svg>
                                                Evening
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($booking->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->status === 'ongoing')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                            Ongoing
                                        </span>
                                    @elseif($booking->status === 'waiting for deposit')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                            Waiting for Deposit
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Completed
                                        </span>
                                    @elseif($booking->status === 'pending_verification')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                            Pending Verification
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="inline-flex items-center px-3 py-1.5 bg-primary bg-opacity-10 text-primary rounded-md hover:bg-opacity-20 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Details
                                    </a>
                                    
                                    @if($booking->type === 'reservation' && $booking->status !== 'cancelled')
                                    <div class="mt-2 flex space-x-1">
                                        <a href="{{ route('user.bookings.confirm.form', $booking) }}" class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Confirm
                                        </a>
                                        
                                        <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors">
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
                                            <div class="mt-2">
                                                <a href="{{ route('user.bookings.feedback', $booking) }}" class="inline-flex items-center px-2 py-1 text-xs bg-primary text-white rounded hover:bg-primary-dark transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                    Give Feedback
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Feedback Submitted
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-gray-500 text-lg">You don't have any bookings{{ request('status') && request('status') != 'all' ? ' with this status' : '' }}.</p>
                    <a href="{{ route('booking-requests.create') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create your first booking request
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection