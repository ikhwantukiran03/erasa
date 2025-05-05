@extends('layouts.app')

@section('title', 'Payment Verification - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Payment Verification</h1>
                <p class="text-gray-600 mt-2">Review and verify customer payment proofs</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Pending Payment Verifications</h2>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue/Package</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Submitted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->venue->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if($booking->package)
                                                {{ $booking->package->name }}
                                            @else
                                                No package selected
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->session === 'morning' ? 'Morning' : 'Evening' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->invoice->invoice_submitted_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('staff.invoices.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Verify Payment
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4">No pending payment verifications found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection