@extends('layouts.app')

@section('title', 'My Booking Requests - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-800">My Booking Requests</h1>
                <p class="text-gray-600 mt-2">Track and manage your venue booking requests</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="text-primary hover:underline flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-1 bg-primary"></div>
                    <div class="p-6">
                        <h2 class="font-semibold text-lg text-gray-800 mb-4">Actions</h2>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('booking-requests.create') }}" class="flex items-center text-primary hover:text-primary-dark transition">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create New Request
                                </a>
                            </li>
                        </ul>
                        
                        <h2 class="font-semibold text-lg text-gray-800 mt-6 mb-4">Filter by Status</h2>
                        <ul class="space-y-2">
                            <li>
                                <a href="#" class="flex items-center text-gray-600 hover:text-primary transition">
                                    <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span>
                                    Pending
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center text-gray-600 hover:text-primary transition">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                    Approved
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center text-gray-600 hover:text-primary transition">
                                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                    Declined
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">All Requests</h2>
                        <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition flex items-center">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Request
                        </a>
                    </div>
                    
                    @if(count($bookingRequests) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue & Package</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookingRequests as $request)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900 capitalize">{{ $request->type }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
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
                                            @if($request->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($request->status == 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @elseif($request->status == 'declined')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Declined
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('booking-requests.show', $request) }}" class="text-primary hover:text-primary-dark mr-3">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-10 px-6 text-center">
                            <svg class="h-16 w-16 text-gray-300 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">No booking requests found</h3>
                            <p class="text-gray-500 mb-6">You haven't made any booking requests yet.</p>
                            <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition inline-flex items-center">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Your First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection