<!-- resources/views/user/invoices/create.blade.php -->
@extends('layouts.app')

@section('title', 'Submit Payment Proof - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Submit Payment Proof</h1>
                <p class="text-gray-600 mt-1">Upload your payment receipt or bank transfer proof</p>
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
        
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="h-1.5 bg-primary"></div>
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                        </svg>
                        Upload Payment Proof
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-bold">Please fix the following errors:</p>
                                    <ul class="list-disc ml-4 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(count($availableTypes) > 0)
                        <!-- Debug Information (remove in production) -->
                        @if(config('app.debug'))
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2" Information:</h4>
                            <p class="text-sm text-yellow-700">Available payment types: {{ implode(', ', $availableTypes) }}</p>
                            <p class="text-sm text-yellow-700">Booking status: {{ $booking->status }}</p>
                            <p class="text-sm text-yellow-700">Event date: {{ $booking->booking_date }}</p>
                            <p class="text-sm text-yellow-700">Days until event: {{ \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($booking->booking_date), false) }}</p>
                            <p class="text-sm text-yellow-700">Existing invoices: {{ $invoices->count() }}</p>
                            @foreach($invoices as $invoice)
                                <p class="text-xs text-yellow-600">- {{ $invoice->type }}: {{ $invoice->status }}</p>
                            @endforeach
                            
                            <!-- Payment Schedule Debug -->
                            <div class="mt-3 pt-3 border-t border-yellow-300">
                                <p class="text-sm font-semibold text-yellow-800">Payment Deadlines:</p>
                                @php
                                    $today = \Carbon\Carbon::today();
                                @endphp
                                <p class="text-xs text-yellow-600">Deposit: No deadline</p>
                                
                                @if($booking->type === 'wedding')
                                    <p class="text-xs text-yellow-600">Second Deposit: {{ $paymentSchedule['second_deposit']->format('M d, Y') }} 
                                        @if($today->greaterThan($paymentSchedule['second_deposit']))
                                            <span class="text-red-600 font-bold">(PASSED)</span>
                                        @else
                                            <span class="text-green-600">({{ $today->diffInDays($paymentSchedule['second_deposit'], false) }} days left)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-yellow-600">Balance: {{ $paymentSchedule['balance']->format('M d, Y') }}
                                        @if($today->greaterThan($paymentSchedule['balance']))
                                            <span class="text-red-600 font-bold">(PASSED)</span>
                                        @else
                                            <span class="text-green-600">({{ $today->diffInDays($paymentSchedule['balance'], false) }} days left)</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="text-xs text-yellow-600">Balance: {{ $paymentSchedule['balance']->format('M d, Y') }}
                                        @if($today->greaterThan($paymentSchedule['balance']))
                                            <span class="text-red-600 font-bold">(PASSED)</span>
                                        @else
                                            <span class="text-green-600">({{ $today->diffInDays($paymentSchedule['balance'], false) }} days left)</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <form action="{{ route('user.invoices.store', $booking) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-6">
                                <label for="type" class="block text-dark font-medium mb-1">Payment Type <span class="text-red-500">*</span></label>
                                <select id="type" name="type" required class="form-input w-full @error('type') border-red-500 @enderror">
                                    <option value="">-- Select Payment Type --</option>
                                    @foreach($availableTypes as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            @if($type === 'deposit')
                                                @if($booking->type === 'wedding')
                                                    Deposit Payment (RM 3,000)
                                                @else
                                                    Deposit Payment (50%)
                                                @endif
                                            @elseif($type === 'second_deposit')
                                                Second Deposit (50% of total)
                                            @elseif($type === 'balance')
                                                @if($booking->type === 'wedding')
                                                    Balance Payment (Remaining amount)
                                                @else
                                                    Balance Payment (50%)
                                                @endif
                                            @elseif($type === 'full_payment')
                                                Full Payment (100%)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Select the type of payment you are submitting</p>
                            </div>
                            
                            @php
                                $totalAmount = 0;
                                // Calculate total amount
                                if ($booking->price_id) {
                                    $price = \App\Models\Price::find($booking->price_id);
                                    if ($price) {
                                        $totalAmount = $price->price;
                                    }
                                } elseif ($booking->package) {
                                    $totalAmount = $booking->package->min_price;
                                }
                            @endphp
                            
                            <div class="mb-6 payment-amounts hidden">
                                <label class="block text-dark font-medium mb-1">Payment Amount</label>
                                <div class="bg-blue-50 rounded-lg p-4 text-blue-800">
                                    <div class="deposit-amount hidden">
                                        @if($booking->type === 'wedding')
                                            <span class="font-semibold">Deposit:</span> 
                                            <span class="font-bold">RM 3,000</span>
                                        @else
                                            <span class="font-semibold">Deposit (50%):</span> 
                                            <span class="font-bold">RM {{ number_format($totalAmount * 0.50, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="second-deposit-amount hidden">
                                        <span class="font-semibold">Second Deposit (50% of total):</span> 
                                        <span class="font-bold">RM {{ number_format($totalAmount * 0.50, 2) }}</span>
                                    </div>
                                    <div class="balance-amount hidden">
                                        @if($booking->type === 'wedding')
                                            <span class="font-semibold">Balance Payment:</span> 
                                            <span class="font-bold">RM {{ number_format($totalAmount - 3000 - ($totalAmount * 0.50), 2) }}</span>
                                        @else
                                            <span class="font-semibold">Balance Payment (50%):</span> 
                                            <span class="font-bold">RM {{ number_format($totalAmount * 0.50, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="full-payment-amount hidden">
                                        <span class="font-semibold">Full Payment (100%):</span> 
                                        <span class="font-bold">RM {{ number_format($totalAmount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label for="invoice_file" class="block text-dark font-medium mb-1">Payment Proof <span class="text-red-500">*</span></label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="invoice_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-1 text-sm text-gray-500">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-500">PDF, JPG, or PNG (max 5MB)</p>
                                        </div>
                                        <input 
                                            type="file" 
                                            id="invoice_file" 
                                            name="invoice_file" 
                                            required 
                                            class="hidden @error('invoice_file') border-red-500 @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                        >
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-1" id="file-name-display">No file selected</p>
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
                                <a href="{{ route('user.bookings.show', $booking) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition mr-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Submit Payment Proof
                                </button>
                            </div>
                        </form>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const typeSelect = document.getElementById('type');
                                const paymentAmounts = document.querySelector('.payment-amounts');
                                const depositAmount = document.querySelector('.deposit-amount');
                                const secondDepositAmount = document.querySelector('.second-deposit-amount');
                                const balanceAmount = document.querySelector('.balance-amount');
                                const fullPaymentAmount = document.querySelector('.full-payment-amount');
                                
                                // File input display
                                const fileInput = document.getElementById('invoice_file');
                                const fileNameDisplay = document.getElementById('file-name-display');
                                
                                fileInput.addEventListener('change', function() {
                                    if (fileInput.files.length > 0) {
                                        fileNameDisplay.textContent = fileInput.files[0].name;
                                    } else {
                                        fileNameDisplay.textContent = 'No file selected';
                                    }
                                });
                                
                                // Payment type change handler
                                typeSelect.addEventListener('change', function() {
                                    paymentAmounts.classList.remove('hidden');
                                    depositAmount.classList.add('hidden');
                                    secondDepositAmount.classList.add('hidden');
                                    balanceAmount.classList.add('hidden');
                                    fullPaymentAmount.classList.add('hidden');
                                    
                                    if (this.value === 'deposit') {
                                        depositAmount.classList.remove('hidden');
                                    } else if (this.value === 'second_deposit') {
                                        secondDepositAmount.classList.remove('hidden');
                                    } else if (this.value === 'balance') {
                                        balanceAmount.classList.remove('hidden');
                                    } else if (this.value === 'full_payment') {
                                        fullPaymentAmount.classList.remove('hidden');
                                    } else {
                                        paymentAmounts.classList.add('hidden');
                                    }
                                });
                                
                                // Trigger change event if there's a pre-selected value
                                if (typeSelect.value) {
                                    typeSelect.dispatchEvent(new Event('change'));
                                }
                            });
                        </script>
                    @else
                        <div class="text-center py-8 px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 text-lg mb-2">No payments are available for submission at this time.</p>
                            
                            @php
                                $existingInvoices = $invoices;
                                $hasFullPayment = $existingInvoices->where('type', 'full_payment')->whereIn('status', ['pending', 'verified'])->count() > 0;
                                $eventDate = \Carbon\Carbon::parse($booking->booking_date);
                                $today = \Carbon\Carbon::today();
                                $daysUntilEvent = $today->diffInDays($eventDate, false);
                            @endphp
                            
                            @if($hasFullPayment)
                                <p class="text-gray-500">You have already submitted a full payment for this booking.</p>
                            @elseif($today->greaterThan($eventDate))
                                <p class="text-gray-500">The event date has passed. No more payments can be submitted.</p>
                                <p class="text-sm text-red-600 mt-2">Event date: {{ $eventDate->format('M d, Y') }}</p>
                            @else
                                <p class="text-gray-500">All required payments have been submitted or completed.</p>
                                <p class="text-sm text-gray-500 mt-2">If you need to submit a late payment, please contact our staff for assistance.</p>
                            @endif
                            
                            <!-- Debug Information for "no payments" case -->
                            @if(config('app.debug'))
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-left">
                                <h4 class="font-semibold text-red-800 mb-2">Debug - Why no payments available:</h4>
                                <p class="text-sm text-red-700">Booking status: {{ $booking->status }}</p>
                                <p class="text-sm text-red-700">Event date: {{ $booking->booking_date }}</p>
                                <p class="text-sm text-red-700">Days until event: {{ $daysUntilEvent }}</p>
                                <p class="text-sm text-red-700">Existing invoices: {{ $existingInvoices->count() }}</p>
                                @foreach($existingInvoices as $invoice)
                                    <p class="text-xs text-red-600">- {{ $invoice->type }}: {{ $invoice->status }} (Amount: RM {{ number_format($invoice->amount, 2) }})</p>
                                @endforeach
                                
                                @if($today->greaterThan($eventDate))
                                    <p class="text-xs text-red-600 font-bold mt-2">Event date has passed - No more payments allowed</p>
                                @endif
                                
                                @if($hasFullPayment)
                                    <p class="text-xs text-red-600 font-bold mt-2">Full payment already submitted</p>
                                @endif
                            </div>
                            @endif
                            
                            <a href="{{ route('user.bookings.show', $booking) }}" class="mt-6 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Return to Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Schedule -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Payment Schedule
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $totalAmount = 0;
                            // Calculate total amount
                            if ($booking->price_id) {
                                $price = \App\Models\Price::find($booking->price_id);
                                if ($price) {
                                    $totalAmount = $price->price;
                                }
                            } elseif ($booking->package) {
                                $totalAmount = $booking->package->min_price;
                            }
                        @endphp
                        
                        <div class="space-y-6">
                            <!-- Deposit Payment -->
                            <div class="relative pb-6 border-l-2 border-gray-200">
                                @php
                                    $depositInvoice = $invoices->where('type', 'deposit')->first();
                                    $statusColor = '';
                                    $statusIcon = '';
                                    
                                    if($depositInvoice) {
                                        if($depositInvoice->status === 'verified') {
                                            $statusColor = 'bg-green-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                        } elseif($depositInvoice->status === 'pending') {
                                            $statusColor = 'bg-yellow-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                        } else {
                                            $statusColor = 'bg-red-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                        }
                                    } else {
                                        $statusColor = 'bg-gray-300';
                                        $statusIcon = '1';
                                    }
                                @endphp
                                
                                <div class="absolute left-0 mt-0.5 -ml-3.5 h-7 w-7 rounded-full {{ $statusColor }} flex items-center justify-center border-4 border-white shadow-sm">
                                    {!! $statusIcon !!}
                                </div>
                                
                                <div class="ml-6">
                                    <h3 class="font-bold text-gray-800 flex items-center">
                                        Deposit
                                        <span class="ml-2 px-2 py-0.5 bg-primary bg-opacity-10 text-primary rounded-full text-xs font-bold">
                                            @if($booking->type === 'wedding')
                                                RM 3,000
                                            @else
                                                RM {{ number_format($totalAmount * 0.50, 2) }}
                                            @endif
                                        </span>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">Due: Immediately</p>
                                    @if($depositInvoice)
                                        <p class="mt-1 text-sm {{ $depositInvoice->status === 'verified' ? 'text-green-600' : ($depositInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $depositInvoice->status === 'verified' ? 'Verified' : ($depositInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($booking->type === 'wedding')
                                <!-- Second Deposit (Wedding Only) -->
                                <div class="relative pb-6 border-l-2 border-gray-200">
                                    @php
                                        $secondDepositInvoice = $invoices->where('type', 'second_deposit')->first();
                                        $statusColor = '';
                                        $statusIcon = '';
                                        
                                        if($secondDepositInvoice) {
                                            if($secondDepositInvoice->status === 'verified') {
                                                $statusColor = 'bg-green-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                            } elseif($secondDepositInvoice->status === 'pending') {
                                                $statusColor = 'bg-yellow-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                            } else {
                                                $statusColor = 'bg-red-500';
                                                $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                            }
                                        } else {
                                            $statusColor = 'bg-gray-300';
                                            $statusIcon = '2';
                                        }
                                    @endphp
                                    
                                    <div class="absolute left-0 mt-0.5 -ml-3.5 h-7 w-7 rounded-full {{ $statusColor }} flex items-center justify-center border-4 border-white shadow-sm">
                                        {!! $statusIcon !!}
                                    </div>
                                    
                                    <div class="ml-6">
                                        <h3 class="font-bold text-gray-800 flex items-center">
                                            Second Deposit (50%)
                                            <span class="ml-2 px-2 py-0.5 bg-primary bg-opacity-10 text-primary rounded-full text-xs font-bold">
                                                RM {{ number_format($totalAmount * 0.50, 2) }}
                                            </span>
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">Due: {{ $paymentSchedule['second_deposit']->format('M d, Y') }}</p>
                                        <div class="inline-flex items-center mt-1 px-2 py-1 bg-orange-50 border border-orange-100 rounded-md text-xs text-orange-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Recommended 6 months before event
                                        </div>
                                        @if($secondDepositInvoice)
                                            <p class="mt-1 text-sm {{ $secondDepositInvoice->status === 'verified' ? 'text-green-600' : ($secondDepositInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $secondDepositInvoice->status === 'verified' ? 'Verified' : ($secondDepositInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Balance Payment -->
                            <div class="relative">
                                @php
                                    $balanceInvoice = $invoices->where('type', 'balance')->first();
                                    $statusColor = '';
                                    $statusIcon = '';
                                    
                                    if($balanceInvoice) {
                                        if($balanceInvoice->status === 'verified') {
                                            $statusColor = 'bg-green-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                        } elseif($balanceInvoice->status === 'pending') {
                                            $statusColor = 'bg-yellow-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                        } else {
                                            $statusColor = 'bg-red-500';
                                            $statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                        }
                                    } else {
                                        $statusColor = 'bg-gray-300';
                                        $statusIcon = $booking->type === 'wedding' ? '3' : '2';
                                    }
                                @endphp
                                
                                <div class="absolute left-0 mt-0.5 -ml-3.5 h-7 w-7 rounded-full {{ $statusColor }} flex items-center justify-center border-4 border-white shadow-sm">
                                    {!! $statusIcon !!}
                                </div>
                                
                                <div class="ml-6">
                                    <h3 class="font-bold text-gray-800 flex items-center">
                                        Balance Payment
                                        <span class="ml-2 px-2 py-0.5 bg-primary bg-opacity-10 text-primary rounded-full text-xs font-bold">
                                            @if($booking->type === 'wedding')
                                                RM {{ number_format($totalAmount - 3000 - ($totalAmount * 0.50), 2) }}
                                            @else
                                                RM {{ number_format($totalAmount * 0.50, 2) }}
                                            @endif
                                        </span>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">Due: {{ $paymentSchedule['balance']->format('M d, Y') }}</p>
                                    <div class="inline-flex items-center mt-1 px-2 py-1 bg-blue-50 border border-blue-100 rounded-md text-xs text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Recommended {{ $booking->type === 'wedding' ? '1 month' : '1 week' }} before event
                                    </div>
                                    @if($balanceInvoice)
                                        <p class="mt-1 text-sm {{ $balanceInvoice->status === 'verified' ? 'text-green-600' : ($balanceInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $balanceInvoice->status === 'verified' ? 'Verified' : ($balanceInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Account Details -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payment Details
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="space-y-3 text-sm">
                                    <div class="flex">
                                        <span class="font-medium text-gray-700 w-40">Bank Name:</span>
                                        <span class="text-gray-800">Maybank</span>
                                    </div>
                                    <div class="flex">
                                        <span class="font-medium text-gray-700 w-40">Account Name:</span>
                                        <span class="text-gray-800">Kumpulan Enak Rasa Sdn Bhd</span>
                                    </div>
                                    <div class="flex">
                                        <span class="font-medium text-gray-700 w-40">Account Number:</span>
                                        <span class="text-gray-800 font-mono">5624 0563 2039</span>
                                    </div>
                                    <div class="flex">
                                        <span class="font-medium text-gray-700 w-40">Reference:</span>
                                        <span class="text-primary font-mono font-bold">BOOKING-{{ $booking->id }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-md text-sm">
                                    <p class="text-red-700 flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-1.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Please include your booking reference in the payment description</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Status Section -->
                <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Payment Status
                        </h2>
                    </div>
                    
                    <div class="p-6">
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
                            $remainingAmount = $totalAmount - $paidAmount;
                        @endphp
                        
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Payment Progress</span>
                            <span class="text-sm font-bold text-primary">{{ number_format($percentage, 0) }}% Complete</span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                            <div class="bg-primary h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                            <div class="bg-primary bg-opacity-5 p-3 rounded-lg text-center">
                                <span class="block text-gray-600 mb-1">Paid</span>
                                <span class="font-semibold text-primary text-lg">RM {{ number_format($paidAmount, 2) }}</span>
                            </div>
                            
                            <div class="bg-gray-100 p-3 rounded-lg text-center">
                                <span class="block text-gray-600 mb-1">Remaining</span>
                                <span class="font-semibold text-gray-800 text-lg">RM {{ number_format($remainingAmount, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-sm">
                            <span class="block text-center text-gray-600">Total: <span class="font-semibold">RM {{ number_format($totalAmount, 2) }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Payment Option (if available) -->
@php
    $fullPaymentInvoice = $invoices->where('type', 'full_payment')->first();
@endphp
@if($fullPaymentInvoice || in_array('full_payment', $availableTypes ?? []))
<div class="mt-6 pt-6 border-t border-gray-200">
    <div class="bg-green-50 p-4 rounded-lg">
        <h3 class="font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Full Payment Option
        </h3>
        <p class="text-sm text-gray-700 mt-2">Pay the full amount at once:</p>
        <div class="font-bold text-gray-900 mt-1">RM {{ number_format($totalAmount, 2) }}</div>
        
        @if($fullPaymentInvoice)
            <p class="mt-2 text-sm {{ $fullPaymentInvoice->status === 'verified' ? 'text-green-600' : ($fullPaymentInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                Status: {{ $fullPaymentInvoice->status === 'verified' ? 'Verified' : ($fullPaymentInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected - Please resubmit') }}
            </p>
        @endif
    </div>
</div>
@endif
@endsection