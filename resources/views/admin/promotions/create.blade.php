@extends('layouts.app')

@section('title', 'Create Promotion - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-4xl font-display font-bold text-primary mb-2">Create New Promotion</h1>
                    <p class="text-gray-600 text-lg">Design an attractive promotion for your wedding packages</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Promotions
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="font-medium text-red-800">Please fix the following errors:</p>
                </div>
                <ul class="list-disc ml-8 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-gradient-to-r from-primary/10 to-primary/5 px-8 py-6 border-b border-gray-100">
                    <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Promotion Details</h2>
                    <p class="text-gray-600">Create an attractive promotion for your wedding packages</p>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Promotion Title *</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('title') border-red-500 @enderror" 
                                placeholder="e.g., Summer Wedding Special Offer"
                            >
                            <p class="mt-1 text-sm text-gray-500">Choose a catchy title that highlights your promotion</p>
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('description') border-red-500 @enderror" 
                                placeholder="Describe your promotion details and benefits..."
                            >{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Provide detailed information about the promotion</p>
                        </div>

                        <div>
                            <label for="package_id" class="block text-sm font-semibold text-gray-700 mb-2">Package *</label>
                            <select 
                                id="package_id" 
                                name="package_id" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('package_id') border-red-500 @enderror"
                            >
                                <option value="">Select a package...</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose the package this promotion applies to</p>
                        </div>

                        <div>
                            <label for="discount" class="block text-sm font-semibold text-gray-700 mb-2">Discount Amount (RM) *</label>
                            <input 
                                type="number" 
                                id="discount" 
                                name="discount" 
                                value="{{ old('discount') }}" 
                                required 
                                min="0"
                                step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('discount') border-red-500 @enderror" 
                                placeholder="e.g., 1000"
                            >
                            <p class="mt-1 text-sm text-gray-500">Enter the discount amount in Ringgit Malaysia</p>
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                            <input 
                                type="date" 
                                id="start_date" 
                                name="start_date" 
                                value="{{ old('start_date') }}" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('start_date') border-red-500 @enderror"
                            >
                            <p class="mt-1 text-sm text-gray-500">When should this promotion start?</p>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
                            <input 
                                type="date" 
                                id="end_date" 
                                name="end_date" 
                                value="{{ old('end_date') }}" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('end_date') border-red-500 @enderror"
                            >
                            <p class="mt-1 text-sm text-gray-500">When should this promotion end?</p>
                        </div>

                        <div class="lg:col-span-2">
                            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Promotion Image *</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" class="sr-only" required accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Upload an attractive image for your promotion</p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Promotion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 