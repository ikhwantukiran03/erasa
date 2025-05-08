@extends('layouts.app')

@section('title', 'Booking Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-2">View your booking details</p>
            </div>
            <a href="{{ route('user.bookings') }}" class="text-primary hover:underline">Back to My Bookings</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-1 {{ $booking->status === 'ongoing' ? 'bg-yellow-400' : ($booking->status === 'waiting for deposit' ? 'bg-blue-400' : ($booking->status === 'completed' ? 'bg-green-500' : 'bg-red-500')) }}"></div>
        
<div class="p-6">
    <!-- Booking Status -->
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
        <div>
            <span class="text-sm text-gray-500">Booking Status</span>
            <div class="mt-1">
                @if($booking->status === 'ongoing')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Ongoing
                    </span>
                @elseif($booking->status === 'waiting for deposit')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        Waiting for Deposit
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Completed
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        Cancelled
                    </span>
                @endif
            </div>
        </div>
        <div class="text-right">
            <span class="text-sm text-gray-500">Booking Reference</span>
            <p class="text-lg font-semibold text-gray-800">B-{{ $booking->id }}</p>
        </div>
    </div>
                
                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Event Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                                <dd class="text-sm text-gray-900 col-span-2 capitalize">{{ $booking->type }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->booking_date->format('l, F d, Y') }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Session</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->session === 'morning' ? 'Morning Session' : 'Evening Session' }}</dd>
                            </div>
                            @if($booking->expiry_date)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                                <dd class="text-sm text-gray-900 col-span-2">
                                    {{ $booking->expiry_date->format('F d, Y') }}
                                    @if(now()->gt($booking->expiry_date) && $booking->status === 'ongoing')
                                        <span class="text-red-600 text-xs">(Expired)</span>
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    
                    <!-- Venue Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Venue Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Venue Name</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->venue->name }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->venue->full_address }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Package Information -->
                    @if($booking->package)
                    <div class="md:col-span-2 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Information</h3>
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Package Name</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->package->name }}</dd>
                            </div>
                            @if($booking->package->description)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $booking->package->description }}</dd>
                            </div>
                            @endif
                            
                            <!-- Selected Guest Count (Pax) -->
                            @if($booking->price_id)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Guest Count</dt>
                                <dd class="text-sm font-medium text-gray-900 col-span-2">
                                    @php
                                        $price = \App\Models\Price::find($booking->price_id);
                                    @endphp
                                    @if($price)
                                        {{ $price->pax }} guests - RM {{ number_format($price->price, 0, ',', '.') }}
                                    @else
                                        Not specified
                                    @endif
                                </dd>
                            </div>
                            @endif
                            
                            @if($booking->package->prices->count() > 0)
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Price Range</dt>
                                <dd class="text-sm font-medium text-primary col-span-2">
                                    RM {{ number_format($booking->package->min_price, 0, ',', '.') }}
                                    @if($booking->package->min_price != $booking->package->max_price)
                                        - RM {{ number_format($booking->package->max_price, 0, ',', '.') }}
                                    @endif
                                </dd>
                            </div>
                            @endif

                            @if($booking->type === 'wedding' && $booking->status === 'ongoing' && $booking->package && $booking->package->packageItems->count() > 0)
<div class="md:col-span-2 border-t border-gray-200 pt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Package Items Customization</h3>
        <div>
            <a href="{{ route('user.customizations.index', $booking) }}" class="text-sm text-primary hover:underline">
                View All Customization Requests
            </a>
        </div>
    </div>
    
    <p class="text-gray-600 mb-4">Click on any item below to request customization for your wedding package:</p>
    
    <div class="space-y-4 mt-2">
        @php
            $packageItemsByCategory = $booking->package->packageItems->groupBy(function($item) {
                return $item->item->category->name;
            });
        @endphp
        
        @foreach($packageItemsByCategory as $categoryName => $packageItems)
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-2">{{ $categoryName }}</h4>
                <ul class="space-y-2">
                    @foreach($packageItems as $packageItem)
                        @php
                            $customization = $booking->customizations()
                                ->where('package_item_id', $packageItem->id)
                                ->first();
                        @endphp
                        <li class="flex justify-between items-center p-2 hover:bg-gray-100 rounded-md transition">
                            <div>
                                <span class="font-medium text-gray-800">{{ $packageItem->item->name }}</span>
                                @if($packageItem->description)
                                    <p class="text-sm text-gray-600">{{ $packageItem->description }}</p>
                                @endif
                            </div>
                            <div>
                                @if($customization)
                                    @if($customization->status === 'pending')
                                        <a href="{{ route('user.customizations.edit', [$booking, $customization]) }}" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                            Pending
                                        </a>
                                    @elseif($customization->status === 'approved')
                                        <a href="{{ route('user.customizations.show', [$booking, $customization]) }}" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                            Approved
                                        </a>
                                    @elseif($customization->status === 'rejected')
                                        <a href="{{ route('user.customizations.show', [$booking, $customization]) }}" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                            Rejected
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('user.customizations.create', [$booking, $packageItem]) }}" class="px-3 py-1 bg-primary text-white rounded text-xs hover:bg-opacity-90 transition">
                                        Customize
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endif
                            
                            <!-- Package Items -->
                            @if($booking->package->packageItems->count() > 0)
                            <div class="mt-6 col-span-3">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Included Items</dt>
                                <dd class="text-sm text-gray-900">
                                    @php
                                        $packageItemsByCategory = $booking->package->packageItems->groupBy(function($item) {
                                            return $item->item->category->name;
                                        });
                                    @endphp
                                    
                                    <div class="space-y-4 mt-2">
                                        @foreach($packageItemsByCategory as $categoryName => $packageItems)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="font-medium text-gray-700 mb-2">{{ $categoryName }}</h4>
                                                <ul class="pl-5 list-disc space-y-1">
                                                    @foreach($packageItems as $packageItem)
                                                        <li class="text-gray-600 text-sm">
                                                            <span class="font-medium">{{ $packageItem->item->name }}</span>
                                                            @if($packageItem->description)
                                                                - {{ $packageItem->description }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                    
                    <!-- Booking Contact Information -->
                    <div class="md:col-span-2 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>
                        <p class="text-gray-600 mb-4">If you have any questions about your booking, please contact us:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Phone</p>
                                    <p class="text-gray-600">+60 123 456 789</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Email</p>
                                    <p class="text-gray-600">info@enakrasa.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <!-- Add this section to the user booking-show.blade.php -->
@if($booking->type === 'wedding' && in_array($booking->status, ['waiting for deposit', 'ongoing']))
    <!-- Payment Information Section -->
    <div class="md:col-span-2 border-t border-gray-200 pt-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Payment Information</h3>
            <a href="{{ route('user.invoices.create', $booking) }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">
                Submit Payment Proof
            </a>
        </div>
        
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
            $paidAmount = $booking->total_paid;
            
            // Calculate percentage
            $percentage = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
            
            // Get invoices
            $invoices = $booking->invoice()->get();
        @endphp
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">Payment Progress</span>
                <span class="text-sm font-medium">{{ number_format($percentage, 0) }}%</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
            
            <div class="flex justify-between text-sm mb-6">
                <span class="text-gray-600">Paid: RM {{ number_format($paidAmount, 2) }}</span>
                <span class="text-gray-600">Total: RM {{ number_format($totalAmount, 2) }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-2">Payment History</h4>
                    @if($invoices->count() > 0)
                        <div class="space-y-2">
                            @foreach($invoices as $invoice)
                                <div class="flex justify-between items-center p-2 bg-white rounded border border-gray-200 text-sm">
                                    <div>
                                        <span class="font-medium">
                                            @if($invoice->type === 'deposit')
                                                Deposit
                                            @elseif($invoice->type === 'payment_1')
                                                First Installment
                                            @elseif($invoice->type === 'payment_2')
                                                Second Installment
                                            @elseif($invoice->type === 'final_payment')
                                                Final Payment
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                                            @endif
                                        </span>
                                        <span class="text-gray-500 ml-2">RM {{ number_format($invoice->amount, 2) }}</span>
                                    </div>
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
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No payment records found.</p>
                    @endif
                </div>
                
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-2">Payment Details</h4>
                    <div class="text-sm">
                        <p><span class="font-medium">Bank Name:</span> Bank Negara Malaysia</p>
                        <p><span class="font-medium">Account Name:</span> Enak Rasa Wedding Hall Sdn Bhd</p>
                        <p><span class="font-medium">Account Number:</span> 1234-5678-9012</p>
                        <p><span class="font-medium">Reference:</span> BOOKING-{{ $booking->id }}</p>
                        <p class="mt-2 text-xs text-red-600">Please include your booking reference in the payment description</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
            </div>
        </div>
    </div>
</div>
@endsection