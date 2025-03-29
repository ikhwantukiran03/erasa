<!-- resources/views/staff/requests/index.blade.php -->
@extends('layouts.app')

@section('title', 'Manage Booking Requests - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking Requests</h1>
                <p class="text-gray-600 mt-2">Manage customer booking inquiries and requests</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Requests</h2>
                
                <!-- Filter By Status -->
                <div class="flex items-center space-x-2">
                    <form action="{{ route('staff.requests.index') }}" method="GET" class="flex items-center">
                        <label for="status" class="mr-2 text-sm text-gray-600">Filter by:</label>
                        <select id="status" name="status" class="form-input py-1 text-sm" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </form>
                </div>
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
            
            <div class="overflow-x-auto">
                @if($requests->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $request->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $request->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-500">{{ $request->email }}</div>
                                <div class="text-gray-500">{{ $request->whatsapp }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-500">
                                    @if($request->venue)
                                        <span class="font-medium">Venue:</span> {{ $request->venue->name }}<br>
                                    @endif
                                    @if($request->package)
                                        <span class="font-medium">Package:</span> {{ $request->package->name }}
                                    @endif
                                </div>
                                <div class="text-gray-500 mt-1 text-sm">
                                    <div class="line-clamp-2">{{ Str::limit($request->message, 80) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-500">{{ $request->created_at->format('M d, Y') }}</div>
                                @if($request->event_date)
                                    <div class="text-xs text-gray-500">
                                        Event: {{ $request->event_date->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($request->status == 'pending') bg-yellow-100 text-yellow-800 
                                    @elseif($request->status == 'contacted') bg-blue-100 text-blue-800 
                                    @elseif($request->status == 'confirmed') bg-green-100 text-green-800 
                                    @elseif($request->status == 'canceled') bg-red-100 text-red-800 
                                    @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- View Message Modal Trigger -->
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3" 
                                    onclick="document.getElementById('message-modal-{{ $request->id }}').classList.remove('hidden')">
                                    View Message
                                </button>
                                
                                <!-- Update Status Dropdown -->
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" type="button" class="text-indigo-600 hover:text-indigo-900">
                                        Update Status
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                            <form action="{{ route('staff.requests.updateStatus', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-100" role="menuitem">
                                                    Mark as Pending
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('staff.requests.updateStatus', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="contacted">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-blue-700 hover:bg-blue-100" role="menuitem">
                                                    Mark as Contacted
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('staff.requests.updateStatus', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-green-100" role="menuitem">
                                                    Mark as Confirmed
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('staff.requests.updateStatus', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-red-100" role="menuitem">
                                                    Mark as Canceled
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Message Modal -->
                                <div id="message-modal-{{ $request->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                    <div class="bg-white rounded-lg max-w-lg w-full p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-semibold">Request from {{ $request->name }}</h3>
                                            <button type="button" class="text-gray-400 hover:text-gray-500" 
                                                onclick="document.getElementById('message-modal-{{ $request->id }}').classList.add('hidden')">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-500 mb-1">Contact Info:</p>
                                            <p class="text-gray-700">Email: {{ $request->email }}</p>
                                            <p class="text-gray-700">WhatsApp: {{ $request->whatsapp }}</p>
                                        </div>
                                        
                                        @if($request->venue || $request->package || $request->event_date)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-500 mb-1">Request Details:</p>
                                            @if($request->venue)
                                                <p class="text-gray-700">Venue: {{ $request->venue->name }}</p>
                                            @endif
                                            @if($request->package)
                                                <p class="text-gray-700">Package: {{ $request->package->name }}</p>
                                            @endif
                                            @if($request->event_date)
                                                <p class="text-gray-700">Event Date: {{ $request->event_date->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-500 mb-1">Message:</p>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-700 whitespace-pre-line">{{ $request->message }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex justify-between">
                                            <p class="text-sm text-gray-500">Submitted on {{ $request->created_at->format('M d, Y \a\t h:i A') }}</p>
                                            <button type="button" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90" 
                                                onclick="document.getElementById('message-modal-{{ $request->id }}').classList.add('hidden')">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="px-6 py-4">
                    {{ $requests->links() }}
                </div>
                @else
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4">No booking requests found.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@endpush