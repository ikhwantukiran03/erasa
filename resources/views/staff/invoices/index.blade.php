<!-- resources/views/staff/invoices/index.blade.php -->
@extends('layouts.app')

@section('title', 'Manage Payment Proofs - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Payment Proofs</h1>
                <p class="text-gray-600 mt-2">Verify and manage customer payment proofs</p>
            </div>
            <div>
                <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Pending Verifications</h2>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="p-6">
                @if($pendingInvoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingInvoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Booking #{{ $invoice->booking->id }}</div>
                                                <div class="text-xs text-gray-500">{{ $invoice->booking->venue->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $invoice->booking->booking_date->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $invoice->booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $invoice->booking->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->type === 'deposit' ? 'bg-blue-100 text-blue-800' : ($invoice->type === 'final_payment' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        RM {{ number_format($invoice->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $invoice->invoice_submitted_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('staff.invoices.show', $invoice->booking) }}" class="text-indigo-600 hover:text-indigo-900">
                                            View & Verify
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4">No pending payment verifications found.</p>
                    </div>
                @endif
                
                {{-- Pagination Links for Pending Invoices --}}
                @if(method_exists($pendingInvoices, 'links'))
                <div class="mt-4">
                    {{ $pendingInvoices->links() }}
                </div>
                @endif
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Recent Verifications</h2>
            </div>
            
            <div class="p-6">
                @if($verifiedInvoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($verifiedInvoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Booking #{{ $invoice->booking->id }}</div>
                                                <div class="text-xs text-gray-500">{{ $invoice->booking->venue->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $invoice->booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $invoice->booking->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->type === 'deposit' ? 'bg-blue-100 text-blue-800' : ($invoice->type === 'final_payment' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        RM {{ number_format($invoice->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $invoice->invoice_verified_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Verified
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        <p>No verified payments found.</p>
                    </div>
                @endif
                
                {{-- Pagination Links for Verified Invoices --}}
                @if(method_exists($verifiedInvoices, 'links'))
                <div class="mt-4">
                    {{ $verifiedInvoices->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection