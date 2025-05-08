<!-- resources/views/user/invoices/create.blade.php -->
@extends('layouts.app')

@section('title', 'Submit Payment Proof - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Submit Payment Proof</h1>
                <p class="text-gray-600 mt-2">Upload your payment receipt or bank transfer proof</p>
            </div>
            <a href="{{ route('user.bookings.show', $booking) }}" class="text-primary hover:underline">Back to Booking</a>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 bg-primary"></div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Upload Payment Proof</h2>
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
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(count($availableTypes) > 0)
                        <form action="{{ route('user.invoices.store', $booking) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-6">
                                <label for="type" class="block text-dark font-medium mb-1">Payment Type <span class="text-red-500">*</span></label>
                                <select id="type" name="type" required class="form-input w-full @error('type') border-red-500 @enderror">
                                    <option value="">-- Select Payment Type --</option>
                                    @foreach($availableTypes as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            @if($type === 'deposit')
                                                Deposit Payment (25%)
                                            @elseif($type === 'payment_1')
                                                First Installment (25%)
                                            @elseif($type === 'payment_2')
                                                Second Installment (25%)
                                            @elseif($type === 'final_payment')
                                                Final Payment (25%)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Select the type of payment you are submitting</p>
                            </div>
                            
                            <div class="mb-6">
                                <label for="invoice_file" class="block text-dark font-medium mb-1">Payment Proof <span class="text-red-500">*</span></label>
                                <input 
                                    type="file" 
                                    id="invoice_file" 
                                    name="invoice_file" 
                                    required 
                                    class="form-input w-full @error('invoice_file') border-red-500 @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >
                                <p class="text-sm text-gray-500 mt-1">Upload your bank transfer receipt or payment proof (PDF, JPG, or PNG, max 5MB)</p>
                            </div>
                            
                            <div class="mb-6">
                                <label for="notes" class="block text-dark font-medium mb-1">Additional Notes</label>
                                <textarea 
                                    id="notes" 
                                    name="notes" 
                                    rows="3" 
                                    class="form-input w-full @error('notes') border-red-500 @enderror"
                                    placeholder="Any additional information about this payment..."
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
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>No payments are available for submission at this time.</p>
                            <p class="mt-2">All required payments have been submitted or are not yet due.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Schedule -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Payment Schedule</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                @php
                                    $depositInvoice = $invoices->where('type', 'deposit')->first();
                                @endphp
                                <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3 {{ $depositInvoice ? ($depositInvoice->status === 'verified' ? 'bg-green-100 text-green-600' : ($depositInvoice->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600')) : 'bg-gray-100 text-gray-400' }}">
                                    @if($depositInvoice)
                                        @if($depositInvoice->status === 'verified')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($depositInvoice->status === 'pending')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    @else
                                        1
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Deposit (25%)</p>
                                    <p class="text-xs text-gray-500">Due: Immediately</p>
                                    @if($depositInvoice)
                                    <p class="text-xs {{ $depositInvoice->status === 'verified' ? 'text-green-600' : ($depositInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $depositInvoice->status === 'verified' ? 'Verified' : ($depositInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                            
                            <li class="flex items-center">
                                @php
                                    $payment1Invoice = $invoices->where('type', 'payment_1')->first();
                                @endphp
                                <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3 {{ $payment1Invoice ? ($payment1Invoice->status === 'verified' ? 'bg-green-100 text-green-600' : ($payment1Invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600')) : 'bg-gray-100 text-gray-400' }}">
                                    @if($payment1Invoice)
                                        @if($payment1Invoice->status === 'verified')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($payment1Invoice->status === 'pending')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    @else
                                        2
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">First Installment (25%)</p>
                                    <p class="text-xs text-gray-500">Due: {{ $paymentSchedule['payment_1']->format('M d, Y') }}</p>
                                    @if($payment1Invoice)
                                        <p class="text-xs {{ $payment1Invoice->status === 'verified' ? 'text-green-600' : ($payment1Invoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $payment1Invoice->status === 'verified' ? 'Verified' : ($payment1Invoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                            
                            <li class="flex items-center">
                                @php
                                    $payment2Invoice = $invoices->where('type', 'payment_2')->first();
                                @endphp
                                <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3 {{ $payment2Invoice ? ($payment2Invoice->status === 'verified' ? 'bg-green-100 text-green-600' : ($payment2Invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600')) : 'bg-gray-100 text-gray-400' }}">
                                    @if($payment2Invoice)
                                        @if($payment2Invoice->status === 'verified')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($payment2Invoice->status === 'pending')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    @else
                                        3
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Second Installment (25%)</p>
                                    <p class="text-xs text-gray-500">Due: {{ $paymentSchedule['payment_2']->format('M d, Y') }}</p>
                                    @if($payment2Invoice)
                                        <p class="text-xs {{ $payment2Invoice->status === 'verified' ? 'text-green-600' : ($payment2Invoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $payment2Invoice->status === 'verified' ? 'Verified' : ($payment2Invoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                            
                            <li class="flex items-center">
                                @php
                                    $finalInvoice = $invoices->where('type', 'final_payment')->first();
                                @endphp
                                <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3 {{ $finalInvoice ? ($finalInvoice->status === 'verified' ? 'bg-green-100 text-green-600' : ($finalInvoice->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600')) : 'bg-gray-100 text-gray-400' }}">
                                    @if($finalInvoice)
                                        @if($finalInvoice->status === 'verified')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($finalInvoice->status === 'pending')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    @else
                                        4
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Final Payment (25%)</p>
                                    <p class="text-xs text-gray-500">Due: {{ $paymentSchedule['final_payment']->format('M d, Y') }}</p>
                                    <p class="text-xs text-red-600">Must be paid at least 30 days before event</p>
                                    @if($finalInvoice)
                                        <p class="text-xs {{ $finalInvoice->status === 'verified' ? 'text-green-600' : ($finalInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $finalInvoice->status === 'verified' ? 'Verified' : ($finalInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                        </ul>
                        
                        <!-- Bank Account Details -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-800 mb-3">Payment Details</h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm">
                                <p><span class="font-medium">Bank Name:</span> Bank Negara Malaysia</p>
                                <p><span class="font-medium">Account Name:</span> Enak Rasa Wedding Hall Sdn Bhd</p>
                                <p><span class="font-medium">Account Number:</span> 1234-5678-9012</p>
                                <p><span class="font-medium">Reference:</span> BOOKING-{{ $booking->id }}</p>
                                <p class="mt-2 text-xs text-gray-500">Please include your booking reference in the payment description</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Status -->
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Payment Status</h2>
                    </div>
                    
                    <div class="p-4">
                        @php
                            $totalAmount = 0;
                            $paidAmount = 0;
                            
                            // Calculate total amount
                            if ($booking->price_id) {
                                $price = \App\Models\Price::find($booking->price_id);
                                if ($price) {
                                    $totalAmount = $price->price;
                                }
                            } elseif ($booking->package) {
                                $totalAmount = $booking->package->min_price;
                            }
                            
                            // Calculate paid amount
                            foreach ($invoices->where('status', 'verified') as $invoice) {
                                $paidAmount += $invoice->amount;
                            }
                            
                            // Calculate percentage
                            $percentage = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
                        @endphp
                        
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Payment Progress</span>
                            <span class="text-sm font-medium">{{ number_format($percentage, 0) }}%</span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        
                        <div class="mt-4 flex justify-between text-sm">
                            <span class="text-gray-600">Paid: RM {{ number_format($paidAmount, 2) }}</span>
                            <span class="text-gray-600">Total: RM {{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection