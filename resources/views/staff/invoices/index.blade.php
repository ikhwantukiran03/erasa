<!-- resources/views/staff/invoices/index.blade.php -->
@extends('layouts.app')

@section('title', 'Payment Management - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Payment Management</h1>
                <p class="text-gray-600 mt-2">Monitor and verify customer payment submissions</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center text-primary hover:underline transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Verification</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $pendingInvoices->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Verified Today</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Invoice::verified()->whereDate('invoice_verified_at', today())->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Amount Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            RM {{ number_format($pendingInvoices->sum('amount'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Overdue Payments</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Invoice::pending()->where('due_date', '<', now())->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('staff.invoices.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="form-input w-full">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                        <select name="type" id="type" class="form-input w-full">
                            <option value="">All Types</option>
                            <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="second_deposit" {{ request('type') === 'second_deposit' ? 'selected' : '' }}>Second Deposit</option>
                            <option value="balance" {{ request('type') === 'balance' ? 'selected' : '' }}>Balance</option>
                            <option value="full_payment" {{ request('type') === 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Customer name, email, booking ID..." class="form-input w-full">
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('staff.invoices.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
            </div>
            
            @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
                </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Pending Verifications Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">Pending Verifications</h2>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold bg-yellow-200 text-yellow-800 rounded-full">
                            {{ $pendingInvoices->total() }}
                        </span>
                    </div>
                    @if($pendingInvoices->count() > 0)
                    <div class="flex space-x-2">
                        <button onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                        <button onclick="clearSelection()" class="text-sm text-gray-600 hover:text-gray-800">Clear</button>
                </div>
            @endif
                </div>
            </div>
            
            <div class="p-6">
                @if($pendingInvoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all-pending" class="rounded border-gray-300 text-primary focus:ring-primary">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Details</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Info</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timeline</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingInvoices as $invoice)
                                <tr class="hover:bg-gray-50 {{ $invoice->isOverdue() ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_invoices[]" value="{{ $invoice->id }}" class="invoice-checkbox rounded border-gray-300 text-primary focus:ring-primary">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    Booking #{{ $invoice->booking->id }}
                                                    @if($invoice->isOverdue())
                                                        <span class="ml-1 px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">OVERDUE</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $invoice->booking->venue->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $invoice->booking->booking_date->format('M d, Y') }} ({{ ucfirst($invoice->booking->session) }})</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($invoice->booking->type) }} Event</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $invoice->booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $invoice->booking->user->email }}</div>
                                        @if($invoice->booking->user->whatsapp)
                                        <div class="text-xs text-gray-500 flex items-center mt-1">
                                            {{ $invoice->booking->user->whatsapp }}
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $invoice->booking->user->whatsapp) }}" target="_blank" class="ml-1 text-green-600 hover:text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
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
                                        </div>
                                        <div class="text-sm text-gray-900 font-semibold mt-1">RM {{ number_format($invoice->amount, 2) }}</div>
                                        @if($invoice->invoice_notes)
                                        <div class="text-xs text-gray-500 mt-1 max-w-xs truncate" title="{{ $invoice->invoice_notes }}">
                                            "{{ $invoice->invoice_notes }}"
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="space-y-1">
                                            <div class="text-xs">
                                                <span class="font-medium">Submitted:</span> {{ $invoice->invoice_submitted_at->format('M d, H:i') }}
                                            </div>
                                            @if($invoice->due_date)
                                            <div class="text-xs {{ $invoice->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                                <span class="font-medium">Due:</span> {{ $invoice->due_date->format('M d, Y') }}
                                                @if($invoice->isOverdue())
                                                    ({{ $invoice->due_date->diffForHumans() }})
                                                @endif
                                            </div>
                                            @endif
                                            <div class="text-xs">
                                                <span class="font-medium">Event:</span> {{ $invoice->booking->booking_date->diffForHumans() }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invoice->isOverdue())
                                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">HIGH</span>
                                        @elseif($invoice->due_date && $invoice->due_date->diffInDays() <= 7)
                                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">MEDIUM</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">NORMAL</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('staff.invoices.show', $invoice->booking) }}" 
                                               class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-opacity-90 transition">
                                                Verify
                                            </a>
                                            <button onclick="quickView({{ $invoice->id }})" 
                                                    class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 transition">
                                                Preview
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">With selected:</span>
                            <button onclick="bulkAction('verify')" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                Bulk Verify
                            </button>
                            <button onclick="bulkAction('reject')" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                Bulk Reject
                            </button>
                        </div>
                        
                        <!-- Pagination -->
                        <div>
                            {{ $pendingInvoices->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Verifications</h3>
                        <p class="text-gray-500">All payment proofs have been processed. Great job!</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Verifications Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                <h2 class="text-xl font-semibold text-gray-800">Recent Verifications</h2>
                    <span class="ml-2 px-2 py-1 text-xs font-semibold bg-green-200 text-green-800 rounded-full">
                        {{ $verifiedInvoices->total() }}
                    </span>
                </div>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified By</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($verifiedInvoices as $invoice)
                                <tr class="hover:bg-gray-50">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        RM {{ number_format($invoice->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $invoice->invoice_verified_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($invoice->verified_by)
                                            {{ $invoice->verifiedBy->name ?? 'Unknown' }}
                                        @else
                                            System
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('staff.invoices.show', $invoice->booking) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-xs">
                                                View Details
                                            </a>
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                ✓ Verified
                                        </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $verifiedInvoices->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        <p>No verified payments found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Payment Proof Preview</h3>
                <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                </div>
            <div id="quickViewContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select all functionality
function selectAll() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

// Select all pending checkbox
document.getElementById('select-all-pending').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

// Quick view functionality
function quickView(invoiceId) {
    // Show modal
    document.getElementById('quickViewModal').classList.remove('hidden');
    
    // Load content via AJAX
    fetch(`/staff/invoices/${invoiceId}/quick-view`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('quickViewContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('quickViewContent').innerHTML = '<p class="text-red-600">Error loading preview</p>';
        });
}

function closeQuickView() {
    document.getElementById('quickViewModal').classList.add('hidden');
}

// Bulk actions
function bulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.invoice-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one invoice');
        return;
    }
    
    const invoiceIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (action === 'verify') {
        if (confirm(`Are you sure you want to verify ${invoiceIds.length} payment(s)?`)) {
            // Implement bulk verify
            bulkVerify(invoiceIds);
        }
    } else if (action === 'reject') {
        const reason = prompt('Please provide a reason for rejection:');
        if (reason) {
            bulkReject(invoiceIds, reason);
        }
    }
}

function bulkVerify(invoiceIds) {
    fetch('/staff/invoices/bulk-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ invoice_ids: invoiceIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function bulkReject(invoiceIds, reason) {
    fetch('/staff/invoices/bulk-reject', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ invoice_ids: invoiceIds, reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// Close modal when clicking outside
document.getElementById('quickViewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickView();
    }
});
</script>
@endpush

@endsection