<!-- resources/views/user/customizations/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Customization Request - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Customization Request</h1>
                <p class="text-gray-600 mt-2">Update your customization request</p>
            </div>
            <a href="{{ route('user.bookings.show', $booking) }}" class="text-primary hover:underline">Back to Booking</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-1 bg-yellow-400"></div>
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Edit Customization Request</h2>
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
                
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Item Information</h3>
                    <p><span class="font-medium">Item:</span> {{ $packageItem->item->name }}</p>
                    <p><span class="font-medium">Category:</span> {{ $packageItem->item->category->name }}</p>
                    @if($packageItem->description)
                        <p><span class="font-medium">Description:</span> {{ $packageItem->description }}</p>
                    @endif
                </div>
                
                <form action="{{ route('user.customizations.update', [$booking, $customization]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="customization" class="block text-dark font-medium mb-1">
                            Describe your customization request <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="customization" 
                            name="customization" 
                            rows="6" 
                            required 
                            class="form-input w-full @error('customization') border-red-500 @enderror"
                            placeholder="Please provide details about how you would like this item customized for your wedding..."
                        >{{ old('customization', $customization->customization) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Be as specific as possible about your requirements. Our staff will review your request and let you know if it's possible.</p>
                    </div>
                    
                    <div class="flex justify-end">
                        <form action="{{ route('user.customizations.destroy', [$booking, $customization]) }}" method="POST" class="inline mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to delete this customization request?')">
                                Delete Request
                            </button>
                        </form>
                        
                        <a href="{{ route('user.bookings.show', $booking) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition mr-2">
                            Cancel
                        </a>
                        
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                            Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection