@extends('layouts.app')

@section('title', 'Edit Venue - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Venue</h1>
                <p class="text-gray-600 mt-2">Update venue information</p>
            </div>
            <a href="{{ route('admin.venues.index') }}" class="text-primary hover:underline">Back to Venues</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
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

                <form action="{{ route('admin.venues.update', $venue) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-dark font-medium mb-1">Venue Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $venue->name) }}" 
                                required 
                                class="form-input @error('name') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="description" class="block text-dark font-medium mb-1">Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="form-input @error('description') border-red-500 @enderror" 
                            >{{ old('description', $venue->description) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="address_line_1" class="block text-dark font-medium mb-1">Address Line 1</label>
                            <input 
                                type="text" 
                                id="address_line_1" 
                                name="address_line_1" 
                                value="{{ old('address_line_1', $venue->address_line_1) }}" 
                                required 
                                class="form-input @error('address_line_1') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div>
                            <label for="address_line_2" class="block text-dark font-medium mb-1">Address Line 2 (Optional)</label>
                            <input 
                                type="text" 
                                id="address_line_2" 
                                name="address_line_2" 
                                value="{{ old('address_line_2', $venue->address_line_2) }}" 
                                class="form-input @error('address_line_2') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div>
                            <label for="city" class="block text-dark font-medium mb-1">City</label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                value="{{ old('city', $venue->city) }}" 
                                required 
                                class="form-input @error('city') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div>
                            <label for="state" class="block text-dark font-medium mb-1">State/Province</label>
                            <input 
                                type="text" 
                                id="state" 
                                name="state" 
                                value="{{ old('state', $venue->state) }}" 
                                required 
                                class="form-input @error('state') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-dark font-medium mb-1">Postal Code</label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                value="{{ old('postal_code', $venue->postal_code) }}" 
                                required 
                                class="form-input @error('postal_code') border-red-500 @enderror" 
                            >
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Update Venue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection