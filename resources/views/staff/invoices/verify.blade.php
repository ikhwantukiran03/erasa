<!-- resources/views/staff/invoices/verify.blade.php -->
@extends('layouts.app')

@section('title', 'Verify Payment Proof - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Verify Payment Proof</h1>
                <p class="text-gray-600 mt-2">Review and verify payment documentation</p>
            </div>
            <div>
                <a href="{{ route('staff.invoices.index') }}" class="text-primary hover:underline">Back to All Payments</a>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Booking Details -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Booking Details</h2>
                </div>
                
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Booking Reference</dt>
                            <dd class="mt-1 text-sm text-gray-900">Booking #{{ $booking->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <p>{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->whatsapp }}</p>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Venue</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->venue->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $booking->booking_date->format('M d, Y') }}
                                ({{ $booking->session === 'morning' ? 'Morning' : 'Evening' }})
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($booking->type) }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($booking->status === 'ongoing')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Ongoing
                                    </span>
                                @elseif($booking->status === 'waiting for deposit')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Waiting for Deposit
                                    </span>
                                @elseif($booking->status === 'waiting for full payment')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Waiting for Full Payment
                                    </span>
                                @elseif($booking->status === 'pending_verification')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Verification
                                    </span>
                                @elseif($booking->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-3">Total Package Value</h3>
                        <p class="text-gray-900 font-bold text-xl">
                            @php
                                $totalAmount = 0;
                                
                                if ($booking->price_id) {
                                    $price = \App\Models\Price::find($booking->price_id);
                                    if ($price) {
                                        $totalAmount = $price->price;
                                    }
                                } elseif ($booking->package) {
                                    $totalAmount = $booking->package->min_price;
                                }
                            @endphp
                            RM {{ number_format($totalAmount, 2) }}
                        </p>
                    </div>
                    
                    <!-- Payment Schedule -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-3">Payment Schedule</h3>
                        @php
                            $eventDate = \Carbon\Carbon::parse($booking->booking_date);
                            $today = \Carbon\Carbon::today();
                            
                            // Calculate payment schedule
                            if ($booking->type === 'wedding') {
                                $secondDepositDate = $eventDate->copy()->subMonths(6);
                                $balanceDate = $eventDate->copy()->subMonth();
                            } else {
                                $balanceDate = $eventDate->copy()->subWeek();
                            }
                            
                            // Check if full payment exists
                            $fullPaymentInvoice = $invoices->where('type', 'full_payment')->first();
                            $hasFullPayment = $fullPaymentInvoice && in_array($fullPaymentInvoice->status, ['pending', 'verified']);
                        @endphp
                        
                        @if($hasFullPayment)
                            <!-- Full Payment Display -->
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <div class="h-4 w-4 rounded-full {{ $fullPaymentInvoice->status === 'verified' ? 'bg-green-500' : ($fullPaymentInvoice->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }} mr-3"></div>
                                    <div>
                                        <span class="font-semibold text-green-800">Full Payment (100%)</span>
                                        <span class="ml-2 font-bold text-green-900">RM {{ number_format($fullPaymentInvoice->amount, 2) }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-green-700 mt-2">
                                    Status: {{ $fullPaymentInvoice->status === 'verified' ? 'Verified - Payment Complete' : ($fullPaymentInvoice->status === 'pending' ? 'Pending Verification' : 'Rejected') }}
                                </p>
                                <p class="text-xs text-green-600 mt-1">Complete payment covers entire booking amount</p>
                            </div>
                        @else
                            <!-- Regular Payment Schedule -->
                            @php
                                $depositInvoice = $invoices->where('type', 'deposit')->first();
                                $secondDepositInvoice = $invoices->where('type', 'second_deposit')->first();
                                $balanceInvoice = $invoices->where('type', 'balance')->first();
                                
                                // Check if all payments are completed
                                $allPaymentsCompleted = $depositInvoice && $depositInvoice->status === 'verified' &&
                                                      (!$booking->type === 'wedding' || ($secondDepositInvoice && $secondDepositInvoice->status === 'verified')) &&
                                                      $balanceInvoice && $balanceInvoice->status === 'verified';
                            @endphp
                            
                            @if($allPaymentsCompleted)
                                <!-- All Payments Completed Display -->
                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <div class="flex items-center">
                                        <div class="h-4 w-4 rounded-full bg-green-500 mr-3"></div>
                                        <div>
                                            <span class="font-semibold text-green-800">All Payments Completed</span>
                                            <span class="ml-2 font-bold text-green-900">RM {{ number_format($invoices->where('status', 'verified')->sum('amount'), 2) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-green-700 mt-2">All required payments have been verified</p>
                                    <div class="mt-3 space-y-1 text-xs text-green-600">
                                        @if($depositInvoice)
                                            <p>✓ Deposit: RM {{ number_format($depositInvoice->amount, 2) }}</p>
                                        @endif
                                        @if($secondDepositInvoice)
                                            <p>✓ Second Deposit: RM {{ number_format($secondDepositInvoice->amount, 2) }}</p>
                                        @endif
                                        @if($balanceInvoice)
                                            <p>✓ Balance: RM {{ number_format($balanceInvoice->amount, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Individual Payment Schedule -->
                                <div class="space-y-3 text-sm">
                                    @if(!$depositInvoice || $depositInvoice->status !== 'verified')
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Deposit:</span>
                                            <span class="font-medium">
                                                @if($depositInvoice)
                                                    RM {{ number_format($depositInvoice->amount, 2) }}
                                                    <span class="text-xs {{ $depositInvoice->status === 'verified' ? 'text-green-600' : ($depositInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                                        ({{ ucfirst($depositInvoice->status) }})
                                                    </span>
                                                @else
                                                    @if($booking->type === 'wedding')
                                                        RM 3,000
                                                    @else
                                                        RM {{ number_format($totalAmount * 0.50, 2) }} (50%)
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($booking->type === 'wedding' && (!$secondDepositInvoice || $secondDepositInvoice->status !== 'verified'))
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Second Deposit:</span>
                                            <span class="font-medium">
                                                @if($secondDepositInvoice)
                                                    RM {{ number_format($secondDepositInvoice->amount, 2) }}
                                                    <span class="text-xs {{ $secondDepositInvoice->status === 'verified' ? 'text-green-600' : ($secondDepositInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                                        ({{ ucfirst($secondDepositInvoice->status) }})
                                                    </span>
                                                @else
                                                    RM {{ number_format($totalAmount * 0.50, 2) }} (50%)
                                                @endif
                                            </span>
                                        </div>
                                        @if(!$secondDepositInvoice)
                                            <div class="text-xs text-gray-500 ml-4">Due: {{ $secondDepositDate->format('M d, Y') }}</div>
                                        @endif
                                    @endif
                                    
                                    @if(!$balanceInvoice || $balanceInvoice->status !== 'verified')
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Balance:</span>
                                            <span class="font-medium">
                                                @if($balanceInvoice)
                                                    RM {{ number_format($balanceInvoice->amount, 2) }}
                                                    <span class="text-xs {{ $balanceInvoice->status === 'verified' ? 'text-green-600' : ($balanceInvoice->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                                        ({{ ucfirst($balanceInvoice->status) }})
                                                    </span>
                                                @else
                                                    @if($booking->type === 'wedding')
                                                        RM {{ number_format($totalAmount - 3000 - ($totalAmount * 0.50), 2) }}
                                                    @else
                                                        RM {{ number_format($totalAmount * 0.50, 2) }} (50%)
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                        @if(!$balanceInvoice)
                                            <div class="text-xs text-gray-500 ml-4">Due: {{ $balanceDate->format('M d, Y') }}</div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment Proof Verification -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                @php
                    $pendingInvoice = $invoices->where('status', 'pending')->first();
                @endphp
                
                @if($pendingInvoice)
                    <div class="p-1 bg-yellow-400"></div>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Payment Proof Verification - 
                            @if($pendingInvoice->type === 'deposit')
                                @if($booking->type === 'wedding')
                                    Deposit Payment (RM 3,000)
                                @else
                                    Deposit Payment (50%)
                                @endif
                            @elseif($pendingInvoice->type === 'second_deposit')
                                Second Deposit (50%)
                            @elseif($pendingInvoice->type === 'balance')
                                @if($booking->type === 'wedding')
                                    Balance Payment
                                @else
                                    Balance Payment (50%)
                                @endif
                            @elseif($pendingInvoice->type === 'full_payment')
                                Full Payment (100%)
                            @else
                                {{ ucfirst(str_replace('_', ' ', $pendingInvoice->type)) }}
                            @endif
                        </h2>
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-3">Payment Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p><span class="font-medium">Payment Type:</span> 
                                        @if($pendingInvoice->type === 'deposit')
                                            @if($booking->type === 'wedding')
                                                Deposit Payment (RM 3,000)
                                            @else
                                                Deposit Payment (50%)
                                            @endif
                                        @elseif($pendingInvoice->type === 'second_deposit')
                                            Second Deposit (50% of total)
                                        @elseif($pendingInvoice->type === 'balance')
                                            @if($booking->type === 'wedding')
                                                Balance Payment (Remaining amount)
                                            @else
                                                Balance Payment (50%)
                                            @endif
                                        @elseif($pendingInvoice->type === 'full_payment')
                                            Full Payment (100%)
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $pendingInvoice->type)) }}
                                        @endif
                                    </p>
                                    <p><span class="font-medium">Amount:</span> RM {{ number_format($pendingInvoice->amount, 2) }}</p>
                                    <p><span class="font-medium">Submitted On:</span> {{ $pendingInvoice->invoice_submitted_at->format('M d, Y H:i') }}</p>
                                    <p><span class="font-medium">Due Date:</span> 
                                        @if($pendingInvoice->due_date)
                                            {{ $pendingInvoice->due_date->format('M d, Y') }}
                                            @if($pendingInvoice->isOverdue())
                                                <span class="text-red-600 text-xs">(Overdue)</span>
                                            @endif
                                        @else
                                            @if($pendingInvoice->type === 'deposit')
                                                Immediately
                                            @elseif($pendingInvoice->type === 'second_deposit' && $booking->type === 'wedding')
                                                {{ $secondDepositDate->format('M d, Y') }}
                                            @elseif($pendingInvoice->type === 'balance')
                                                {{ $balanceDate->format('M d, Y') }}
                                            @else
                                                Not specified
                                            @endif
                                        @endif
                                    </p>
                                    @if($pendingInvoice->invoice_notes)
                                        <p><span class="font-medium">Customer Notes:</span> {{ $pendingInvoice->invoice_notes }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-3">Payment Timeline</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    @php
                                        $depositInvoice = $invoices->where('type', 'deposit')->first();
                                        $secondDepositInvoice = $invoices->where('type', 'second_deposit')->first();
                                        $balanceInvoice = $invoices->where('type', 'balance')->first();
                                        $fullPaymentInvoice = $invoices->where('type', 'full_payment')->first();
                                    @endphp
                                    
                                    <ul class="space-y-2">
                                        <li class="flex items-center">
                                            <div class="h-4 w-4 rounded-full {{ $depositInvoice && $depositInvoice->status === 'verified' ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                            <span class="text-sm {{ $depositInvoice && $depositInvoice->status === 'verified' ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                                                Deposit
                                                @if($booking->type === 'wedding')
                                                    (RM 3,000)
                                                @else
                                                    (50%)
                                                @endif
                                            </span>
                                        </li>
                                        
                                        @if($booking->type === 'wedding')
                                            <li class="flex items-center">
                                                <div class="h-4 w-4 rounded-full {{ $secondDepositInvoice && $secondDepositInvoice->status === 'verified' ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                                <span class="text-sm {{ $secondDepositInvoice && $secondDepositInvoice->status === 'verified' ? 'text-green-600 font-medium' : 'text-gray-500' }}">Second Deposit (50%)</span>
                                            </li>
                                        @endif
                                        
                                        <li class="flex items-center">
                                            <div class="h-4 w-4 rounded-full {{ $balanceInvoice && $balanceInvoice->status === 'verified' ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                            <span class="text-sm {{ $balanceInvoice && $balanceInvoice->status === 'verified' ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                                                Balance Payment
                                                @if($booking->type !== 'wedding')
                                                    (50%)
                                                @endif
                                            </span>
                                        </li>
                                        
                                        @if($fullPaymentInvoice)
                                            <li class="flex items-center">
                                                <div class="h-4 w-4 rounded-full {{ $fullPaymentInvoice && $fullPaymentInvoice->status === 'verified' ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                                <span class="text-sm {{ $fullPaymentInvoice && $fullPaymentInvoice->status === 'verified' ? 'text-green-600 font-medium' : 'text-gray-500' }}">Full Payment (100%)</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="font-semibold text-gray-800 mb-3">Payment Proof Document</h3>
                            
                            @php
                                $fileExtension = pathinfo($pendingInvoice->invoice_path, PATHINFO_EXTENSION);
                                $isPdf = strtolower($fileExtension) === 'pdf';
                            @endphp
                            
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                @if($isPdf)
                                    <div class="bg-gray-100 p-4 flex justify-center items-center">
                                        <div class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">PDF Document</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-gray-50 flex justify-center">
                                        <a href="{{ $pendingInvoice->invoice_path }}" target="_blank" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition text-sm">
                                            View PDF
                                        </a>
                                    </div>
                                @else
                                    <div class="mb-4">
                                        <img src="{{ $pendingInvoice->invoice_path }}" alt="Payment Proof" class="mx-auto max-h-[500px]">
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <form action="{{ route('staff.invoices.verify', $booking) }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $pendingInvoice->id }}">
                            
                            <div class="mb-6">
                                <label for="staff_notes" class="block text-dark font-medium mb-1">Notes or Comments</label>
                                <textarea 
                                    id="staff_notes" 
                                    name="staff_notes" 
                                    rows="3" 
                                    class="form-input w-full @error('staff_notes') border-red-500 @enderror"
                                    placeholder="Add any notes about this payment verification..."
                                >{{ old('staff_notes') }}</textarea>
                                <p class="text-sm text-gray-500 mt-1">These notes will be visible to the customer</p>
                            </div>
                            
                            <div class="flex justify-end space-x-4">
                                <button type="submit" name="action" value="reject" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
                                    Reject Payment
                                </button>
                                <button type="submit" name="action" value="verify" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                                    Verify Payment
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-1 bg-blue-500"></div>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Payment History</h2>
                    </div>
                    
                    <div class="p-6">
                        @if($invoices->count() > 0)
                            <div class="space-y-6">
                                @foreach($invoices as $invoice)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                                            <div>
                                                <span class="font-medium text-gray-800">
                                                    @if($invoice->type === 'deposit')
                                                        @if($booking->type === 'wedding')
                                                            Deposit Payment (RM 3,000)
                                                        @else
                                                            Deposit Payment (50%)
                                                        @endif
                                                    @elseif($invoice->type === 'second_deposit')
                                                        Second Deposit (50%)
                                                    @elseif($invoice->type === 'balance')
                                                        @if($booking->type === 'wedding')
                                                            Balance Payment
                                                        @else
                                                            Balance Payment (50%)
                                                        @endif
                                                    @elseif($invoice->type === 'full_payment')
                                                        Full Payment (100%)
                                                    @else
                                                        {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                                                    @endif
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500">RM {{ number_format($invoice->amount, 2) }}</span>
                                            </div>
                                            <div>
                                                @if($invoice->status === 'verified')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Verified
                                                    </span>
                                                @elseif($invoice->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-sm text-gray-600"><span class="font-medium">Submitted:</span> {{ $invoice->invoice_submitted_at->format('M d, Y H:i') }}</p>
                                                    
                                                    @if($invoice->invoice_verified_at)
                                                        <p class="text-sm text-gray-600"><span class="font-medium">Verified:</span> {{ $invoice->invoice_verified_at->format('M d, Y H:i') }}</p>
                                                    @endif
                                                    
                                                    @if($invoice->verifier)
                                                        <p class="text-sm text-gray-600"><span class="font-medium">Verified by:</span> {{ $invoice->verifier->name }}</p>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($invoice->invoice_notes)
                                                        <p class="text-sm text-gray-600"><span class="font-medium">Customer Notes:</span> {{ $invoice->invoice_notes }}</p>
                                                    @endif
                                                    @if($invoice->admin_notes)
                                                        <p class="text-sm text-gray-600"><span class="font-medium">Staff Notes:</span> {{ $invoice->admin_notes }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @php
                                                $fileExtension = pathinfo($invoice->invoice_path, PATHINFO_EXTENSION);
                                                $isPdf = strtolower($fileExtension) === 'pdf';
                                            @endphp
                                            
                                            <div class="mt-4">
                                                @if($isPdf)
                                                    <a href="{{ $invoice->invoice_path }}" target="_blank" class="text-primary hover:underline text-sm">
                                                        View PDF Document
                                                    </a>
                                                @else
                                                    <a href="{{ $invoice->invoice_path }}" target="_blank" class="text-primary hover:underline text-sm">
                                                        View Image
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-10">
                                <p>No payment records found for this booking.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection