@extends('layouts.app')

@section('title', 'Manage Customization Requests - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Customization Requests</h1>
                <p class="text-gray-600 mt-2">Review and process customer customization requests</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">All Customization Requests</h2>
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
                <!-- Search Form -->
                <div class="mb-6">
                    <form action="{{ route('staff.customizations.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $search ?? '' }}" 
                                    placeholder="Search by customer name, email, item, category, or customization text..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="{{ $status ?? '' }}">
                        <div class="flex gap-2">
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition flex items-center">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                            @if($search ?? false)
                                <a href="{{ route('staff.customizations.index', ['status' => $status ?? '']) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Filter tabs -->
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route('staff.customizations.index', ['status' => 'pending', 'search' => $search ?? '']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Pending
                    </a>
                    <a href="{{ route('staff.customizations.index', ['status' => 'approved', 'search' => $search ?? '']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Approved
                    </a>
                    <a href="{{ route('staff.customizations.index', ['status' => 'rejected', 'search' => $search ?? '']) }}" class="px-4 py-2 rounded-full text-sm {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        Rejected
                    </a>
                    <a href="{{ route('staff.customizations.index', ['search' => $search ?? '']) }}" class="px-4 py-2 rounded-full text-sm {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                        All Requests
                    </a>
                </div>
                
                @if($customizations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customization</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customizations as $customization)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customization->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $customization->booking->user ? $customization->booking->user->name : 'User not found' }}</div>
                                                <div class="text-sm text-gray-500">{{ $customization->booking->user ? $customization->booking->user->email : 'Email not available' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $customization->packageItem->item ? $customization->packageItem->item->name : 'Item not found' }}</div>
                                        <div class="text-sm text-gray-500">{{ $customization->packageItem->item && $customization->packageItem->item->category ? $customization->packageItem->item->category->name : 'Category not found' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                        {{ \Illuminate\Support\Str::limit($customization->customization, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $customization->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($customization->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($customization->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('staff.customizations.show', $customization) }}" class="text-primary hover:underline">
                                            {{ $customization->status === 'pending' ? 'Process' : 'View' }}
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
                        @if($search ?? false)
                            <p class="mt-4">No customization requests found matching "{{ $search }}".</p>
                            <a href="{{ route('staff.customizations.index', ['status' => $status ?? '']) }}" class="text-primary hover:underline">Clear search</a>
                        @else
                        <p class="mt-4">No customization requests found.</p>
                        @endif
                    </div>
                @endif
                
                @if($customizations->count() > 0 && method_exists($customizations, 'links'))
                <div class="mt-4">
                    {{ $customizations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection