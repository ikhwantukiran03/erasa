@extends('layouts.app')

@section('title', 'Submit Payment Proof - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Submit Payment Proof</h1>
                <p class="text-gray-600 mt-2">For Booking #{{ $booking->id }}</p>
            </div>
            <a href="{{ route('user.bookings.show', $booking) }}" class="text-primary hover:underline">Back to Booking</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Payment Details</h2>
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
                
                <!-- Payment Information -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-700 mb-2">Payment Instructions</h3>
                    <p class="text-gray-700 mb-2">Please make your payment to the following account:</p>
                    <div class="bg-white p-3 rounded-md border border-blue-100 mb-4">
                        <p><span class="font-medium">Bank Name:</span> Bank Negara Malaysia</p>
                        <p><span class="font-medium">Account Number:</span> 1234-5678-9012</p>
                        <p><span class="font-medium">Account Holder:</span> Enak Rasa Wedding Hall</p>
                        <p><span class="font-medium">Reference:</span> BOOKING-{{ $booking->id }}</p>
                    </div>
                    <p class="text-gray-700 mb-2">After making the payment, please upload the payment proof (receipt, screenshot) below.</p>
                </div>
                
                <form action="{{ route('user.invoices.store', $booking) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="invoice" class="block text-dark font-medium mb-1">Payment Proof <span class="text-red-500">*</span></label>
                        <input 
                            type="file" 
                            id="invoice" 
                            name="invoice" 
                            required 
                            class="form-input w-full @error('invoice') border-red-500 @enderror"
                            accept=".jpg, .jpeg, .png, .pdf"
                        >
                        <p class="text-sm text-gray-500 mt-1">Upload a receipt, screenshot, or any proof of payment. Accepted formats: JPG, PNG, PDF (max 5MB)</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="notes" class="block text-dark font-medium mb-1">Additional Notes (Optional)</label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="3" 
                            class="form-input w-full @error('notes') border-red-500 @enderror"
                            placeholder="Any additional information about your payment..."
                        >{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('user.bookings.show', $booking) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                            Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection