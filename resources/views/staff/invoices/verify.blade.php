@extends('layouts.app')

@section('title', 'Verify Payment - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Verify Payment</h1>
                <p class="text-gray-600 mt-2">Booking #{{ $booking->id }} - {{ $booking->user->name }}</p>
            </div>
            <a href="{{ route('staff.invoices.index') }}" class="text-primary hover:underline">Back to Payment List</a>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Payment Details -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 bg-blue-400"></div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Payment Proof Details</h2>
                </div>
                
                <div class="p-6">
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Booking Information</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Venue</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->venue->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Package</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($booking->package)
                                        {{ $booking->package->name }}
                                    @else
                                        No package selected
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking->booking_date->format('M d, Y') }} ({{ $booking->session === 'morning' ? 'Morning' : 'Evening' }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($booking->package && $booking->package->prices->count() > 0)
                                        RM {{ number_format($booking->package->min_price, 0, ',', '.') }}
                                        @if($booking->package->min_price != $booking->package->max_price)
                                            - {{ number_format($booking->package->max_price, 0, ',', '.') }}
                                        @endif
                                    @else
                                        Not specified
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Payment proof preview -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Payment Proof</h3>
                        
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            @php
                                $extension = pathinfo(storage_path('app/public/' . $booking->invoice->invoice_path), PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $booking->invoice->invoice_path) }}" alt="Payment Proof" class="w-full">
                            @elseif(strtolower($extension) == 'pdf')
                                <div class="bg-gray-100 p-4 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-600 mb-3">PDF Document</p>
                                    <a href="{{ asset('storage/' . $booking->invoice->invoice_path) }}" target="_blank" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                        View PDF
                                    </a>
                                </div>
                            @else
                                <div class="bg-gray-100 p-4 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-600 mb-3">Document</p>
                                    <a href="{{ asset('storage/' . $booking->invoice->invoice_path) }}" target="_blank" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Customer notes if any -->
                    @if($booking->invoice->invoice_notes)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-2">Customer Notes</h3>
                            <div class="bg-gray-50 p-3 rounded whitespace-pre-wrap text-sm text-gray-700">
                                {{ $booking->invoice->invoice_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Verification Form -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Verification</h2>
                </div>
                
                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                            <p class="font-bold">Please fix the following errors:</p>
                            <ul class="list-disc ml-4 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('staff.invoices.verify', $booking) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-dark font-medium mb-2">Verification Result <span class="text-red-500">*</span></label>
                            
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="approve" 
                                        name="verification_result" 
                                        value="approve" 
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                        {{ old('verification_result') == 'approve' ? 'checked' : '' }}
                                        required
                                    >
                                    <label for="approve" class="ml-2 block text-sm text-gray-900">
                                        Approve Payment
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="reject" 
                                        name="verification_result" 
                                        value="reject" 
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                        {{ old('verification_result') == 'reject' ? 'checked' : '' }}
                                        required
                                    >
                                    <label for="reject" class="ml-2 block text-sm text-gray-900">
                                        Reject Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="notes" class="block text-dark font-medium mb-1">Notes</label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="4" 
                                class="form-input w-full @error('notes') border-red-500 @enderror"
                                placeholder="Add verification notes or feedback for the customer..."
                            >{{ old('notes') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">If rejecting, please provide a reason for the customer to understand why.</p>
                        </div>
                        
                        <div class="flex flex-col space-y-3">
                            <button type="submit" name="verification_result" value="approve" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                Approve Payment
                            </button>
                            <button type="submit" name="verification_result" value="reject" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                Reject Payment
                            </button>
                            <a href="{{ route('staff.invoices.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection