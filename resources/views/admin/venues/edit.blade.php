@extends('layouts.app')

@section('title', 'Edit Venue - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('admin.venues.index') }}" class="ml-1 text-gray-700 hover:text-primary md:ml-2">Venues</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Edit Venue</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Venue</h1>
                <p class="text-gray-600 mt-2">Update venue information</p>
            </div>
            <a href="{{ route('admin.venues.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Venues
            </a>
        </div>
        
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                        <div class="flex items-center mb-2">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-bold">Please fix the following errors:</p>
                        </div>
                        <ul class="list-disc ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.venues.update', $venue) }}" method="POST" class="max-w-4xl">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 pb-3 mb-4 border-b border-gray-200">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Venue Information
                            </span>
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-dark font-medium mb-1">Venue Name <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $venue->name) }}" 
                                    required 
                                    class="form-input w-full @error('name') border-red-500 @enderror" 
                                    autofocus
                                >
                                <p class="mt-1 text-sm text-gray-500">Enter the official name of the venue</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-dark font-medium mb-1">Description <span class="text-red-500">*</span></label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    class="form-input w-full @error('description') border-red-500 @enderror" 
                                    required
                                >{{ old('description', $venue->description) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Describe the venue's features, capacity, and unique selling points</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 pb-3 mb-4 border-b border-gray-200">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Location Details
                            </span>
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address_line_1" class="block text-dark font-medium mb-1">Address Line 1 <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="address_line_1" 
                                    name="address_line_1" 
                                    value="{{ old('address_line_1', $venue->address_line_1) }}" 
                                    required 
                                    class="form-input w-full @error('address_line_1') border-red-500 @enderror" 
                                >
                                <p class="mt-1 text-sm text-gray-500">Street address, P.O. box, company name</p>
                            </div>
                            
                            <div>
                                <label for="address_line_2" class="block text-dark font-medium mb-1">Address Line 2 (Optional)</label>
                                <input 
                                    type="text" 
                                    id="address_line_2" 
                                    name="address_line_2" 
                                    value="{{ old('address_line_2', $venue->address_line_2) }}" 
                                    class="form-input w-full @error('address_line_2') border-red-500 @enderror" 
                                >
                                <p class="mt-1 text-sm text-gray-500">Apartment, suite, unit, building, floor, etc.</p>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-dark font-medium mb-1">City <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city', $venue->city) }}" 
                                    required 
                                    class="form-input w-full @error('city') border-red-500 @enderror" 
                                >
                            </div>
                            
                            <div>
                                <label for="state" class="block text-dark font-medium mb-1">State/Province <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="state" 
                                    name="state" 
                                    value="{{ old('state', $venue->state) }}" 
                                    required 
                                    class="form-input w-full @error('state') border-red-500 @enderror" 
                                >
                            </div>
                            
                            <div>
                                <label for="postal_code" class="block text-dark font-medium mb-1">Postal Code <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    value="{{ old('postal_code', $venue->postal_code) }}" 
                                    required 
                                    class="form-input w-full @error('postal_code') border-red-500 @enderror" 
                                >
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.venues.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Venue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection