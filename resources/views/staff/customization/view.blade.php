
@extends('layouts.app')

@section('title', 'Process Customization Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Customization Request #{{ $customization->id }}</h1>
                <p class="text-gray-600 mt-2">Review and process this customization request</p>
            </div>
            <a href="{{ route('staff.customizations.index') }}" class="text-primary hover:underline">Back to All Requests</a>
        </div>
        
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Request Details -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-1 {{ $customization->status === 'pending' ? 'bg-yellow-400' : ($customization->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}"></div>
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Request Details</h2>
                    <div>
                        @if($customization->status === 'pending')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($customization->status === 'approved')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Approved
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Item Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><span class="font-medium">Item:</span> {{ $customization->packageItem->item->name }}</p>
                                <p><span class="font-medium">Category:</span> {{ $customization->packageItem->item->category->name }}</p>
                                @if($customization->packageItem->description)
                                    <p><span class="font-medium">Default Description:</span> {{ $customization->packageItem->description }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Booking Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><span class="font-medium">Booking ID:</span> {{ $customization->booking->id }}</p>
                                <p><span class="font-medium">Venue:</span> {{ $customization->booking->venue->name }}</p>
                                <p><span class="font-medium">Date:</span> {{ $customization->booking->booking_date->format('M d, Y') }}</p>
                                <p><span class="font-medium">Session:</span> {{ ucfirst($customization->booking->session) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Customer Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Name:</span> {{ $customization->booking->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $customization->booking->user->email }}</p>
                                <p><span class="font-medium">WhatsApp:</span> {{ $customization->booking->user->whatsapp }}</p>
                            </div>
                            <div class="flex justify-end">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customization->booking->user->whatsapp) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition flex items-center h-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    Contact Customer
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Customization Request</h3>
                        <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap">
                            {{ $customization->customization }}
                        </div>
                    </div>
                    
                    @if($customization->status !== 'pending')
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3">Staff Response</h3>
                            <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap {{ $customization->status === 'approved' ? 'text-green-800' : 'text-red-800' }}">
                                {{ $customization->staff_notes ?: 'No notes provided.' }}
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-500 mt-4">
                            <p>Processed by: {{ $customization->handler ? $customization->handler->name : 'N/A' }}</p>
                            <p>Processed on: {{ $customization->handled_at ? $customization->handled_at->format('M d, Y H:i') : 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Process Form -->
            @if($customization->status === 'pending')
            <div class="lg:col-span-1 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Process Request</h2>
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
                    
                    <form action="{{ route('staff.customizations.process', $customization) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-dark font-medium mb-2">Your Decision <span class="text-red-500">*</span></label>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="approve" 
                                        name="status" 
                                        value="approved" 
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                        {{ old('status') == 'approved' ? 'checked' : '' }}
                                        required
                                    >
                                    <label for="approve" class="ml-2 block text-sm text-gray-900">
                                        Approve - We can accommodate this customization
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="reject" 
                                        name="status" 
                                        value="rejected" 
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                        {{ old('status') == 'rejected' ? 'checked' : '' }}
                                        required
                                        >
                                    <label for="reject" class="ml-2 block text-sm text-gray-900">
                                        Reject - We cannot accommodate this customization
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="staff_notes" class="block text-dark font-medium mb-1">
                                Notes for Customer
                                <span class="text-red-500">{{ old('status') == 'rejected' ? '*' : '' }}</span>
                            </label>
                            <textarea 
                                id="staff_notes" 
                                name="staff_notes" 
                                rows="6" 
                                class="form-input w-full @error('staff_notes') border-red-500 @enderror"
                                placeholder="Provide details about your decision (required for rejection)..."
                            >{{ old('staff_notes') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Explain your decision to the customer. This note will be visible to them.</p>
                        </div>
                        
                        <div class="flex flex-col space-y-3">
                            <button type="submit" name="status" value="approved" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                Approve Request
                            </button>
                            <button type="submit" name="status" value="rejected" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                Reject Request
                            </button>
                            <a href="{{ route('staff.customizations.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection