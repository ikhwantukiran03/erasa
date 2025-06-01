<!-- resources/views/user/customizations/show.blade.php -->
@extends('layouts.app')

@section('title', 'Customization Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Customization Request</h1>
                <p class="text-gray-600 mt-1">View your customization request details</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex items-center text-primary hover:text-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Booking
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-1.5 
                @if($customization->status === 'pending')
                    bg-yellow-400
                @elseif($customization->status === 'approved')
                    bg-green-500
                @else
                    bg-red-500
                @endif
            "></div>
            
            <div class="p-6">
                <!-- Status Badge -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Request #{{ $customization->id }}</h2>
                    
                    @if($customization->status === 'pending')
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                            Pending Review
                        </span>
                    @elseif($customization->status === 'approved')
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1.5 inline-flex items-center text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                            Rejected
                        </span>
                    @endif
                </div>
                
                <!-- Package Item Info -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Item Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Category:</p>
                            <p class="font-medium text-gray-800">{{ $packageItem->item && $packageItem->item->category ? $packageItem->item->category->name : 'Category not found' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Item Name:</p>
                            <p class="font-medium text-gray-800">{{ $packageItem->item ? $packageItem->item->name : 'Item not found' }}</p>
                        </div>
                        @if($packageItem->description)
                        <div class="md:col-span-2">
                            <p class="text-gray-600">Default Description:</p>
                            <p class="font-medium text-gray-800">{{ $packageItem->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Customization Request -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Customization Request</h3>
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-gray-800 whitespace-pre-line">{{ $customization->customization }}</p>
                    </div>
                </div>
                
                <!-- Staff Response -->
                @if($customization->staff_response)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Staff Response</h3>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800 whitespace-pre-line">{{ $customization->staff_response }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-between">
                    <div>
                        @if($customization->status === 'pending')
                            <div class="flex flex-col xs:flex-row gap-3">
                                <a href="{{ route('user.customizations.edit', [$booking, $customization]) }}" class="btn-secondary py-2 px-4 text-center">
                                    Edit Request
                                </a>
                                <form action="{{ route('user.customizations.destroy', [$booking, $customization]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this customization request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger py-2 px-4 w-full">
                                        Cancel Request
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4 sm:mb-0">
                        <a href="{{ route('user.customizations.index', $booking) }}" class="btn-outline py-2 px-4">
                            View All Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection