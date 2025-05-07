<!-- resources/views/user/customizations/show.blade.php -->
@extends('layouts.app')

@section('title', 'Customization Request Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Customization Request</h1>
                <p class="text-gray-600 mt-2">Details of your customization request</p>
            </div>
            <a href="{{ route('user.bookings.show', $booking) }}" class="text-primary hover:underline">Back to Booking</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-1 {{ $customization->status === 'pending' ? 'bg-yellow-400' : ($customization->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}"></div>
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Customization Request</h2>
                <div>
                    @if($customization->status === 'pending')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @elseif($customization->status === 'approved')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Item Details</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p><span class="font-medium">Item:</span> {{ $customization->packageItem->item->name }}</p>
                            <p><span class="font-medium">Category:</span> {{ $customization->packageItem->item->category->name }}</p>
                            @if($customization->packageItem->description)
                                <p><span class="font-medium">Description:</span> {{ $customization->packageItem->description }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Request Status</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p><span class="font-medium">Submitted on:</span> {{ $customization->created_at->format('M d, Y H:i') }}</p>
                            
                            @if($customization->status !== 'pending')
                                <p><span class="font-medium">Processed on:</span> {{ $customization->handled_at->format('M d, Y H:i') }}</p>
                                
                                @if($customization->handler)
                                    <p><span class="font-medium">Processed by:</span> {{ $customization->handler->name }}</p>
                                @endif
                            @endif
                            
                            <p class="mt-2"><span class="font-medium">Status:</span> 
                                @if($customization->status === 'pending')
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @elseif($customization->status === 'approved')
                                    <span class="text-green-600 font-semibold">Approved</span>
                                @else
                                    <span class="text-red-600 font-semibold">Rejected</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Your Customization Request</h3>
                    <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap">
                        {{ $customization->customization }}
                    </div>
                </div>
                
                @if($customization->staff_notes)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Staff Response</h3>
                        <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap {{ $customization->status === 'approved' ? 'text-green-800' : 'text-red-800' }}">
                            {{ $customization->staff_notes }}
                        </div>
                    </div>
                @endif
                
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('user.bookings.show', $booking) }}" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                        Back to Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection