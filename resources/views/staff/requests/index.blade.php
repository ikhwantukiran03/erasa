@extends('layouts.app')

@section('title', 'Manage Booking Requests - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Booking Requests</h1>
                <p class="text-gray-600 mt-2">Review and process incoming booking requests</p>
            </div>
            <div>
                <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">All Booking Requests</h2>
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
                <!-- Filter tabs -->
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route('staff.requests.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'pending' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Pending
                    </a>
                    <a href="{{ route('staff.requests.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Approved
                    </a>
                    <a href="{{ route('staff.requests.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Rejected
                    </a>
                    <a href="{{ route('staff.requests.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Cancelled
                    </a>
                    <a href="{{ route('staff.requests.index') }}" class="px-4 py-2 rounded-full text-sm {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        All Requests
                    </a>
                </div>
                
                @if($bookingRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue/Package</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bookingRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $request->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->email }}</div>
                                                <div class="text-xs text-gray-500">{{ $request->whatsapp_no }}</div>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('staff.requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                                View
                                            </a>
                                            
                                            @if($request->status === 'pending')
                                                <a href="{{ route('staff.requests.edit', $request) }}" class="text-blue-600 hover:text-blue-900">
                                                    Process
                                                </a>
                                            @endif
                                            
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->whatsapp_no) }}" target="_blank" class="text-green-600 hover:text-green-900">
                                                WhatsApp
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </div>
</div>
@endsection