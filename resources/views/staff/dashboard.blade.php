// staff/dashboard.blade.php
@extends('layouts.app')

@section('title', 'Staff Dashboard - Enak Rasa Wedding Hall')

@section('content')
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-amber-100 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-800">Booking Requests</h2>
                <p class="text-gray-600 mt-1">View and manage customer booking requests</p>
            </div>
        </div>
        <a href="{{ route('staff.requests.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:underline">Manage booking requests →</a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-800">Pending Requests</h2>
                <p class="text-gray-600 mt-1">
                    @php
                        $pendingCount = \App\Models\BookingRequest::where('status', 'pending')->count();
                    @endphp
                    <span class="font-semibold">{{ $pendingCount }}</span> request(s) waiting for response
                </p>
            </div>
        </div>
        <a href="{{ route('staff.requests.index', ['status' => 'pending']) }}" class="mt-4 inline-block text-sm text-green-600 hover:underline">View pending requests →</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-800">Create Booking</h2>
                <p class="text-gray-600 mt-1">Create a booking for a customer</p>
            </div>
        </div>
        <a href="{{ route('booking-requests.create') }}" class="mt-4 inline-block text-sm text-blue-600 hover:underline">Create new booking →</a>
    </div>
</div>

<!-- Recent Booking Requests -->
<div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Recent Booking Requests</h2>
    </div>
    
    <div class="p-6">
        @php
            $recentRequests = \App\Models\BookingRequest::with(['venue', 'package'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        @endphp
        
        @if($recentRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentRequests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->whatsapp_no }}</div>
                                    </div>
                                </div>
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('staff.requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    View
                                </a>
                                
                                @if($request->status === 'pending')
                                    <a href="{{ route('staff.requests.edit', $request) }}" class="text-blue-600 hover:text-blue-900">
                                        Process
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="{{ route('staff.requests.index') }}" class="text-primary hover:underline">View all requests →</a>
            </div>
        @else
            <div class="text-center text-gray-500 py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4">No booking requests found.</p>
            </div>
        @endif
    </div>
</div>
@endsection