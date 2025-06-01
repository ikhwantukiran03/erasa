@if($invoice)
<div class="space-y-4">
    <!-- Invoice Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex justify-between items-start">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">Payment Proof #{{ $invoice->id }}</h4>
                <p class="text-sm text-gray-600">Booking #{{ $invoice->booking->id }} - {{ $invoice->booking->venue ? $invoice->booking->venue->name : 'Venue not found' }}</p>
            </div>
            <div class="text-right">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                    $invoice->type === 'deposit' ? 'bg-blue-100 text-blue-800' : 
                    ($invoice->type === 'second_deposit' ? 'bg-purple-100 text-purple-800' : 
                    ($invoice->type === 'balance' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')) 
                }}">
                    @if($invoice->type === 'deposit')
                        Deposit
                    @elseif($invoice->type === 'second_deposit')
                        Second Deposit
                    @elseif($invoice->type === 'balance')
                        Balance
                    @elseif($invoice->type === 'full_payment')
                        Full Payment
                    @else
                        {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                    @endif
                </span>
                <div class="text-lg font-bold text-gray-900 mt-1">RM {{ number_format($invoice->amount, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h5 class="font-medium text-gray-900 mb-2">Customer Details</h5>
            <div class="text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Name:</span> {{ $invoice->booking->user ? $invoice->booking->user->name : 'User not found' }}</p>
                <p><span class="font-medium">Email:</span> {{ $invoice->booking->user ? $invoice->booking->user->email : 'Email not available' }}</p>
                @if($invoice->booking->user && $invoice->booking->user->whatsapp)
                <p><span class="font-medium">WhatsApp:</span> {{ $invoice->booking->user->whatsapp }}</p>
                @endif
            </div>
        </div>
        <div>
            <h5 class="font-medium text-gray-900 mb-2">Payment Details</h5>
            <div class="text-sm text-gray-600 space-y-1">
                <p><span class="font-medium">Submitted:</span> {{ $invoice->invoice_submitted_at->format('M d, Y H:i') }}</p>
                @if($invoice->due_date)
                <p><span class="font-medium">Due Date:</span> 
                    <span class="{{ $invoice->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                        {{ $invoice->due_date->format('M d, Y') }}
                        @if($invoice->isOverdue())
                            ({{ $invoice->due_date->diffForHumans() }})
                        @endif
                    </span>
                </p>
                @endif
                <p><span class="font-medium">Event Date:</span> {{ $invoice->booking->booking_date->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Customer Notes -->
    @if($invoice->invoice_notes)
    <div>
        <h5 class="font-medium text-gray-900 mb-2">Customer Notes</h5>
        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $invoice->invoice_notes }}</p>
    </div>
    @endif

    <!-- Payment Proof -->
    <div>
        <h5 class="font-medium text-gray-900 mb-2">Payment Proof</h5>
        @php
            $fileExtension = pathinfo($invoice->invoice_path, PATHINFO_EXTENSION);
            $isPdf = strtolower($fileExtension) === 'pdf';
        @endphp
        
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            @if($isPdf)
                <div class="bg-gray-100 p-8 flex justify-center items-center">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">PDF Document</p>
                        <a href="{{ $invoice->invoice_path }}" target="_blank" class="mt-2 inline-block bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition text-sm">
                            Open PDF
                        </a>
                    </div>
                </div>
            @else
                <img src="{{ $invoice->invoice_path }}" alt="Payment Proof" class="w-full h-auto max-h-96 object-contain">
                <div class="p-3 bg-gray-50 text-center">
                    <a href="{{ $invoice->invoice_path }}" target="_blank" class="text-primary hover:underline text-sm">
                        View Full Size
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <a href="{{ route('staff.invoices.show', $invoice->booking) }}" 
           class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition text-sm">
            Full Verification
        </a>
        <button onclick="closeQuickView()" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition text-sm">
            Close
        </button>
    </div>
</div>
@else
<div class="text-center py-8">
    <p class="text-red-600">Invoice not found or access denied.</p>
</div>
@endif 