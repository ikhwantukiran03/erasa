@extends('layouts.app')

@section('title', 'Create Package - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-4xl font-display font-bold text-primary mb-2">Create New Package</h1>
                    <p class="text-gray-600 text-lg">Design a comprehensive wedding package for your venue</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Packages
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center text-primary step-indicator" data-step="1">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                        <span class="ml-2 font-medium">Basic Info</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center text-gray-400 step-indicator" data-step="2">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                        <span class="ml-2">Pricing</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center text-gray-400 step-indicator" data-step="3">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                        <span class="ml-2">Items</span>
                    </div>
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
            <form action="{{ route('admin.packages.store') }}" method="POST" id="packageForm">
                @csrf
                
                <!-- Step 1: Package Information -->
                <div class="step-section active" id="step-1">
                    <div class="bg-gradient-to-r from-primary/10 to-primary/5 px-8 py-6 border-b border-gray-100">
                        <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Package Information</h2>
                        <p class="text-gray-600">Start by defining the basic details of your wedding package</p>
                    </div>
                    
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="lg:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Package Name *</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('name') border-red-500 @enderror" 
                                    placeholder="e.g., Elegant Garden Wedding Package"
                                >
                                <p class="mt-1 text-sm text-gray-500">Choose a descriptive name that highlights the package's unique features</p>
                            </div>
                            
                            <div class="lg:col-span-1">
                                <label for="venue_id" class="block text-sm font-semibold text-gray-700 mb-2">Venue *</label>
                                <select 
                                    id="venue_id" 
                                    name="venue_id" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('venue_id') border-red-500 @enderror"
                                >
                                    <option value="">Select a venue...</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="lg:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Package Description</label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('description') border-red-500 @enderror" 
                                    placeholder="Describe what makes this package special and what couples can expect..."
                                >{{ old('description') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Help couples understand the value and experience this package provides</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-8">
                            <button type="button" class="next-step px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                Continue to Pricing
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Pricing Tiers -->
                <div class="step-section hidden" id="step-2">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
                        <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Pricing Structure</h2>
                        <p class="text-gray-600">Set up flexible pricing tiers based on guest count</p>
                    </div>
                    
                    <div class="p-8">
                        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-800">Pricing Strategy Tip</p>
                                    <p class="text-sm text-blue-700 mt-1">Create multiple pricing tiers to accommodate different group sizes and provide better value for larger events.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="prices-container" class="space-y-4">
                            <div class="price-row bg-gray-50 border border-gray-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-gray-800">Pricing Tier #1</h3>
                                    <span class="text-sm text-gray-500">Default tier</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Guest Count (Pax)</label>
                                        <input 
                                            type="number" 
                                            name="prices[0][pax]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                                            required 
                                            min="1"
                                            placeholder="100"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Minimum number of guests</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Price (RM)</label>
                                        <input 
                                            type="number" 
                                            name="prices[0][price]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                                            required 
                                            min="0"
                                            step="0.01"
                                            placeholder="25000"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Total package cost</p>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 w-full">
                                            <div class="text-center">
                                                <p class="text-sm font-medium text-green-800">Per Person Cost</p>
                                                <p class="text-lg font-bold text-green-600 price-per-person">RM 0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <button type="button" id="add-price" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Another Pricing Tier
                            </button>
                        </div>
                        
                        <div class="flex justify-between mt-8">
                            <button type="button" class="prev-step px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Back to Basic Info
                            </button>
                            <button type="button" class="next-step px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                Continue to Items
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Package Items -->
                <div class="step-section hidden" id="step-3">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-8 py-6 border-b border-gray-100">
                        <h2 class="text-2xl font-display font-bold text-gray-800 mb-2">Package Inclusions</h2>
                        <p class="text-gray-600">Choose what's included in this wedding package</p>
                    </div>
                    
                    <div class="p-8">
                        <!-- Quick Actions Toggle Buttons -->
                        <div class="mb-6 flex flex-wrap gap-3">
                            <button type="button" id="toggle-category-form" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span id="category-toggle-text">Show Category Form</span>
                            </button>
                            <button type="button" id="toggle-item-form" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span id="item-toggle-text">Show Item Form</span>
                            </button>
                        </div>
                        
                        <!-- Quick Actions Forms -->
                        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Create Category -->
                            <div id="category-form-container" class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 hidden">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-gray-800">Create New Category</h3>
                                    <button type="button" id="close-category-form" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="new-category-name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                                        <input 
                                            type="text" 
                                            id="new-category-name" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" 
                                            placeholder="e.g., Photography, Catering, Decoration"
                                        >
                                    </div>
                                    <div>
                                        <label for="new-category-description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                        <textarea 
                                            id="new-category-description" 
                                            rows="2" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" 
                                            placeholder="Brief description of this category..."
                                        ></textarea>
                                    </div>
                                    <button type="button" id="create-category-btn" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Create Category
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Create Item -->
                            <div id="item-form-container" class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 hidden">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-gray-800">Create New Item</h3>
                                    <button type="button" id="close-item-form" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="new-item-category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                        <select id="new-item-category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500">
                                            <option value="">Select category...</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="new-item-name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                                        <input 
                                            type="text" 
                                            id="new-item-name" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500" 
                                            placeholder="e.g., Wedding Photography, Buffet Catering"
                                        >
                                    </div>
                                    <div>
                                        <label for="new-item-description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                        <textarea 
                                            id="new-item-description" 
                                            rows="2" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500" 
                                            placeholder="Details about this item..."
                                        ></textarea>
                                    </div>
                                    <button type="button" id="create-item-btn" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Create Item
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Items Display -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-gray-800 mb-4">Selected Items</h3>
                            <div id="selected-items-summary" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="font-medium">No items selected yet</p>
                                <p class="text-sm">Start by selecting a category and item below, or create new ones above</p>
                            </div>
                        </div>
                        
                        <!-- Categories Display -->
                        <div id="categories-container" class="space-y-6">
                            <!-- Categories will be populated by JavaScript -->
                        </div>
                        
                        <!-- Item Selection Interface -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-800">Add Items to Package</h3>
                                <div id="category-memory-indicator" class="hidden text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="remembered-category-name">Category remembered</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="category-select" class="block text-sm font-medium text-gray-700 mb-2">Select Category</label>
                                    <select id="category-select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        <option value="">Choose a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" data-category-name="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="item-select" class="block text-sm font-medium text-gray-700 mb-2">Select Item</label>
                                    <select id="item-select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" disabled>
                                        <option value="">Select category first...</option>
                                    </select>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="item-description" class="block text-sm font-medium text-gray-700 mb-2">Custom Description (Optional)</label>
                                    <textarea 
                                        id="item-description" 
                                        rows="3" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                                        placeholder="Add specific details about this item for this package..."
                                        disabled
                                    ></textarea>
                                </div>
                                
                                <div class="md:col-span-2 text-right">
                                    <button type="button" id="add-item-btn" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add to Package
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between mt-8">
                            <button type="button" class="prev-step px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Back to Pricing
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Create Package
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Multi-step form functionality
        let currentStep = 1;
        const totalSteps = 3;
        
        // Step navigation
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', function() {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        showStep(currentStep + 1);
                    }
                }
            });
        });
        
        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', function() {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });
        });
        
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-section').forEach(section => {
                section.classList.add('hidden');
                section.classList.remove('active');
            });
            
            // Show current step
            document.getElementById(`step-${step}`).classList.remove('hidden');
            document.getElementById(`step-${step}`).classList.add('active');
            
            // Update progress indicator
            updateProgressIndicator(step);
            
            currentStep = step;
        }
        
        function updateProgressIndicator(step) {
            for (let i = 1; i <= totalSteps; i++) {
                const stepElement = document.querySelector(`.step-indicator[data-step="${i}"]`);
                if (stepElement) {
                    const circleElement = stepElement.querySelector('.w-8');
                    if (i <= step) {
                        stepElement.classList.remove('text-gray-400');
                        stepElement.classList.add('text-primary');
                        circleElement.classList.remove('bg-gray-300', 'text-gray-600');
                        circleElement.classList.add('bg-primary', 'text-white');
                    } else {
                        stepElement.classList.remove('text-primary');
                        stepElement.classList.add('text-gray-400');
                        circleElement.classList.remove('bg-primary', 'text-white');
                        circleElement.classList.add('bg-gray-300', 'text-gray-600');
                    }
                }
            }
        }
        
        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            // Special validation for step 3 (items)
            if (currentStep === 3) {
                const selectedItems = document.querySelectorAll('input[name*="[selected]"]');
                if (selectedItems.length === 0) {
                    alert('Please add at least one item to the package before continuing.');
                    return false;
                }
            }
            
            if (!isValid && currentStep === 1) {
                alert('Please fill in all required fields before continuing.');
            } else if (!isValid && currentStep === 2) {
                alert('Please add at least one pricing tier before continuing.');
            }
            
            return isValid;
        }
        
        // Price calculation functionality
        function calculatePricePerPerson() {
            document.querySelectorAll('.price-row').forEach(row => {
                const paxInput = row.querySelector('input[name*="[pax]"]');
                const priceInput = row.querySelector('input[name*="[price]"]');
                const perPersonElement = row.querySelector('.price-per-person');
                
                if (paxInput && priceInput && perPersonElement) {
                    const pax = parseFloat(paxInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const perPerson = pax > 0 ? (price / pax).toFixed(0) : 0;
                    perPersonElement.textContent = `RM ${perPerson}`;
                }
            });
        }
        
        // Add event listeners for price calculation
        document.addEventListener('input', function(e) {
            if (e.target.name && (e.target.name.includes('[pax]') || e.target.name.includes('[price]'))) {
                calculatePricePerPerson();
            }
        });
        
        // Pricing tier management
        let priceCount = 1;
        const addPriceButton = document.getElementById('add-price');
        const pricesContainer = document.getElementById('prices-container');
        
        addPriceButton.addEventListener('click', function() {
            const priceRow = document.createElement('div');
            priceRow.className = 'price-row bg-gray-50 border border-gray-200 rounded-xl p-6';
            
            priceRow.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Pricing Tier #${priceCount + 1}</h3>
                    <button type="button" class="remove-price text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guest Count (Pax)</label>
                        <input 
                            type="number" 
                            name="prices[${priceCount}][pax]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                            required 
                            min="1"
                            placeholder="${100 + (priceCount * 50)}"
                        >
                        <p class="text-xs text-gray-500 mt-1">Minimum number of guests</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Price (RM)</label>
                        <input 
                            type="number" 
                            name="prices[${priceCount}][price]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                            required 
                            min="0"
                            step="0.01"
                            placeholder="${25000 + (priceCount * 5000)}"
                        >
                        <p class="text-xs text-gray-500 mt-1">Total package cost</p>
                    </div>
                    
                    <div class="flex items-end">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 w-full">
                            <div class="text-center">
                                <p class="text-sm font-medium text-green-800">Per Person Cost</p>
                                <p class="text-lg font-bold text-green-600 price-per-person">RM 0</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            pricesContainer.appendChild(priceRow);
            priceCount++;
            
            // Add remove functionality
            priceRow.querySelector('.remove-price').addEventListener('click', function() {
                priceRow.remove();
            });
        });
        
        // Item management
        const categorySelect = document.getElementById('category-select');
        const itemSelect = document.getElementById('item-select');
        const itemDescription = document.getElementById('item-description');
        const addItemBtn = document.getElementById('add-item-btn');
        const selectedItemsSummary = document.getElementById('selected-items-summary');
        const categoriesContainer = document.getElementById('categories-container');
        
        // Categories data from PHP
        const categories = @json($categories);
        let selectedItems = {};
        let itemsByCategory = {};
        let lastSelectedCategory = null; // Remember last selected category
        
        // Populate items by category
        categories.forEach(category => {
            itemsByCategory[category.id] = category.items || [];
        });
        
        // Category selection handler
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            itemSelect.innerHTML = '<option value="">Select an item...</option>';
            itemDescription.value = '';
            
            if (categoryId) {
                // Remember the selected category
                lastSelectedCategory = categoryId;
                
                // Show category memory indicator
                const indicator = document.getElementById('category-memory-indicator');
                const categoryName = this.options[this.selectedIndex].text;
                document.getElementById('remembered-category-name').textContent = `${categoryName} selected`;
                indicator.classList.remove('hidden');
                
                const items = itemsByCategory[categoryId] || [];
                let availableItems = 0;
                items.forEach(item => {
                    // Only show items that haven't been selected yet
                    if (!selectedItems[item.id]) {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        option.dataset.description = item.description || '';
                        itemSelect.appendChild(option);
                        availableItems++;
                    }
                });
                
                // Show message if no items are available
                if (availableItems === 0 && items.length > 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'All items from this category have been added';
                    option.disabled = true;
                    option.style.fontStyle = 'italic';
                    option.style.color = '#6B7280';
                    itemSelect.appendChild(option);
                }
                
                itemSelect.disabled = false;
                itemDescription.disabled = false;
                addItemBtn.disabled = false;
            } else {
                // Hide category memory indicator when no category selected
                document.getElementById('category-memory-indicator').classList.add('hidden');
                lastSelectedCategory = null;
                
                itemSelect.disabled = true;
                itemDescription.disabled = true;
                addItemBtn.disabled = true;
            }
        });
        
        // Item selection handler
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.description) {
                itemDescription.value = selectedOption.dataset.description;
            }
        });
        
        // Add item to package
        addItemBtn.addEventListener('click', function() {
            const categoryId = categorySelect.value;
            const itemId = itemSelect.value;
            const categoryName = categorySelect.options[categorySelect.selectedIndex].text;
            const itemName = itemSelect.options[itemSelect.selectedIndex].text;
            const description = itemDescription.value;
            
            if (!categoryId || !itemId) {
                alert('Please select both category and item.');
                return;
            }
            
            // Check if item already selected
            if (selectedItems[itemId]) {
                alert('This item is already added to the package.');
                return;
            }
            
            // Add to selected items
            selectedItems[itemId] = {
                categoryId: categoryId,
                categoryName: categoryName,
                itemName: itemName,
                description: description
            };
            
            // Update display
            updateSelectedItemsDisplay();
            
            // Reset form but remember category
            if (lastSelectedCategory) {
                // Keep the last selected category
                categorySelect.value = lastSelectedCategory;
                
                // Repopulate items for the selected category
                const items = itemsByCategory[lastSelectedCategory] || [];
                itemSelect.innerHTML = '<option value="">Select an item...</option>';
                let availableItems = 0;
                items.forEach(item => {
                    // Only show items that haven't been selected yet
                    if (!selectedItems[item.id]) {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        option.dataset.description = item.description || '';
                        itemSelect.appendChild(option);
                        availableItems++;
                    }
                });
                
                // Show message if no items are available
                if (availableItems === 0 && items.length > 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'All items from this category have been added';
                    option.disabled = true;
                    option.style.fontStyle = 'italic';
                    option.style.color = '#6B7280';
                    itemSelect.appendChild(option);
                }
                
                itemSelect.disabled = false;
                itemDescription.disabled = false;
                addItemBtn.disabled = false;
            } else {
                // No category remembered, reset everything
                categorySelect.value = '';
                itemSelect.innerHTML = '<option value="">Select category first...</option>';
                itemSelect.disabled = true;
                itemDescription.disabled = true;
                addItemBtn.disabled = true;
            }
            
            // Always clear item selection and description
            itemSelect.value = '';
            itemDescription.value = '';
        });
        
        function updateSelectedItemsDisplay() {
            // Clear existing display
            selectedItemsSummary.innerHTML = '';
            categoriesContainer.innerHTML = '';
            
            if (Object.keys(selectedItems).length === 0) {
                selectedItemsSummary.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="font-medium">No items selected yet</p>
                    <p class="text-sm">Start by selecting a category and item below, or create new ones above</p>
                `;
                return;
            }
            
            // Group items by category
            const itemsByCategory = {};
            Object.keys(selectedItems).forEach(itemId => {
                const item = selectedItems[itemId];
                if (!itemsByCategory[item.categoryId]) {
                    itemsByCategory[item.categoryId] = [];
                }
                itemsByCategory[item.categoryId].push({
                    id: itemId,
                    ...item
                });
            });
            
            // Update summary
            const totalItems = Object.keys(selectedItems).length;
            selectedItemsSummary.innerHTML = `
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium text-green-800">${totalItems} item${totalItems > 1 ? 's' : ''} selected</p>
                    <p class="text-sm text-gray-600">Review and modify your selections below</p>
                </div>
            `;
            
            // Create category sections
            Object.keys(itemsByCategory).forEach(categoryId => {
                const items = itemsByCategory[categoryId];
                const categoryName = items[0].categoryName;
                
                const categorySection = document.createElement('div');
                categorySection.className = 'bg-white border border-gray-200 rounded-xl p-6';
                categorySection.innerHTML = `
                    <h4 class="font-semibold text-gray-800 mb-4">${categoryName}</h4>
                    <div class="space-y-3" id="category-${categoryId}-items"></div>
                `;
                
                const itemsContainer = categorySection.querySelector(`#category-${categoryId}-items`);
                
                items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                    itemElement.innerHTML = `
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">${item.itemName}</p>
                            ${item.description ? `<p class="text-sm text-gray-600">${item.description}</p>` : ''}
                            <input type="hidden" name="items[${item.id}][item_id]" value="${item.id}">
                            <input type="hidden" name="items[${item.id}][selected]" value="1">
                            <input type="hidden" name="items[${item.id}][description]" value="${item.description}">
                        </div>
                        <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" data-item-id="${item.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    `;
                    
                    // Add remove functionality
                    itemElement.querySelector('.remove-item').addEventListener('click', function() {
                        const itemId = this.dataset.itemId;
                        delete selectedItems[itemId];
                        updateSelectedItemsDisplay();
                        
                        // Refresh the item dropdown if the removed item's category is currently selected
                        if (categorySelect.value && lastSelectedCategory) {
                            const changeEvent = new Event('change');
                            categorySelect.dispatchEvent(changeEvent);
                        }
                    });
                    
                    itemsContainer.appendChild(itemElement);
                });
                
                categoriesContainer.appendChild(categorySection);
            });
        }
        
        // Create Category functionality
        document.getElementById('create-category-btn').addEventListener('click', function() {
            const name = document.getElementById('new-category-name').value.trim();
            const description = document.getElementById('new-category-description').value.trim();
            
            if (!name) {
                alert('Please enter a category name.');
                return;
            }
            
            // Disable button and show loading
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...';
            
            // Send AJAX request
            fetch('{{ route("admin.packages.storeCategory") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add to categories array
                    categories.push(data.category);
                    itemsByCategory[data.category.id] = [];
                    
                    // Update category selects
                    updateCategorySelects();
                    
                    // Clear form
                    document.getElementById('new-category-name').value = '';
                    document.getElementById('new-category-description').value = '';
                    
                    // Hide form after successful creation
                    categoryFormContainer.classList.add('hidden');
                    categoryToggleText.textContent = 'Show Category Form';
                    toggleCategoryBtn.querySelector('svg').style.transform = 'rotate(0deg)';
                    
                    // Show success message
                    showNotification('Category created successfully!', 'success');
                } else {
                    alert('Error creating category: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating category. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Create Category';
            });
        });
        
        // Create Item functionality
        document.getElementById('create-item-btn').addEventListener('click', function() {
            const categoryId = document.getElementById('new-item-category').value;
            const name = document.getElementById('new-item-name').value.trim();
            const description = document.getElementById('new-item-description').value.trim();
            
            if (!categoryId) {
                alert('Please select a category.');
                return;
            }
            
            if (!name) {
                alert('Please enter an item name.');
                return;
            }
            
            // Disable button and show loading
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...';
            
            // Send AJAX request
            fetch('{{ route("admin.packages.storeItem") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    category_id: categoryId,
                    name: name,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add to items array
                    if (!itemsByCategory[categoryId]) {
                        itemsByCategory[categoryId] = [];
                    }
                    itemsByCategory[categoryId].push(data.item);
                    
                    // Update item select if this category is currently selected
                    if (categorySelect.value === categoryId) {
                        // Only add to dropdown if not already selected
                        if (!selectedItems[data.item.id]) {
                            const option = document.createElement('option');
                            option.value = data.item.id;
                            option.textContent = data.item.name;
                            option.dataset.description = data.item.description || '';
                            itemSelect.appendChild(option);
                        }
                    }
                    
                    // Clear form
                    document.getElementById('new-item-category').value = '';
                    document.getElementById('new-item-name').value = '';
                    document.getElementById('new-item-description').value = '';
                    
                    // Hide form after successful creation
                    itemFormContainer.classList.add('hidden');
                    itemToggleText.textContent = 'Show Item Form';
                    toggleItemBtn.querySelector('svg').style.transform = 'rotate(0deg)';
                    
                    // Show success message
                    showNotification('Item created successfully!', 'success');
                } else {
                    alert('Error creating item: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating item. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Create Item';
            });
        });
        
        // Helper function to update category selects
        function updateCategorySelects() {
            const selects = [categorySelect, document.getElementById('new-item-category')];
            
            selects.forEach(select => {
                const currentValue = select.value;
                
                // Clear existing options (except first one)
                while (select.children.length > 1) {
                    select.removeChild(select.lastChild);
                }
                
                // Add updated categories
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (select === categorySelect) {
                        option.dataset.categoryName = category.name;
                    }
                    select.appendChild(option);
                });
                
                // Restore selection if it still exists, or use last selected category for main select
                if (select === categorySelect && lastSelectedCategory) {
                    select.value = lastSelectedCategory;
                    // Trigger change event to populate items
                    const changeEvent = new Event('change');
                    select.dispatchEvent(changeEvent);
                } else if (currentValue) {
                    select.value = currentValue;
                }
            });
        }
        
        // Helper function to show notifications
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition-all duration-300 transform translate-x-full`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
        
        // Toggle form functionality
        const categoryFormContainer = document.getElementById('category-form-container');
        const itemFormContainer = document.getElementById('item-form-container');
        const toggleCategoryBtn = document.getElementById('toggle-category-form');
        const toggleItemBtn = document.getElementById('toggle-item-form');
        const closeCategoryBtn = document.getElementById('close-category-form');
        const closeItemBtn = document.getElementById('close-item-form');
        const categoryToggleText = document.getElementById('category-toggle-text');
        const itemToggleText = document.getElementById('item-toggle-text');
        
        // Toggle category form
        toggleCategoryBtn.addEventListener('click', function() {
            const isHidden = categoryFormContainer.classList.contains('hidden');
            
            if (isHidden) {
                categoryFormContainer.classList.remove('hidden');
                categoryToggleText.textContent = 'Hide Category Form';
                toggleCategoryBtn.querySelector('svg').style.transform = 'rotate(45deg)';
            } else {
                categoryFormContainer.classList.add('hidden');
                categoryToggleText.textContent = 'Show Category Form';
                toggleCategoryBtn.querySelector('svg').style.transform = 'rotate(0deg)';
                // Clear form when hiding
                document.getElementById('new-category-name').value = '';
                document.getElementById('new-category-description').value = '';
            }
        });
        
        // Close category form
        closeCategoryBtn.addEventListener('click', function() {
            categoryFormContainer.classList.add('hidden');
            categoryToggleText.textContent = 'Show Category Form';
            toggleCategoryBtn.querySelector('svg').style.transform = 'rotate(0deg)';
            // Clear form when closing
            document.getElementById('new-category-name').value = '';
            document.getElementById('new-category-description').value = '';
        });
        
        // Toggle item form
        toggleItemBtn.addEventListener('click', function() {
            const isHidden = itemFormContainer.classList.contains('hidden');
            
            if (isHidden) {
                itemFormContainer.classList.remove('hidden');
                itemToggleText.textContent = 'Hide Item Form';
                toggleItemBtn.querySelector('svg').style.transform = 'rotate(45deg)';
            } else {
                itemFormContainer.classList.add('hidden');
                itemToggleText.textContent = 'Show Item Form';
                toggleItemBtn.querySelector('svg').style.transform = 'rotate(0deg)';
                // Clear form when hiding
                document.getElementById('new-item-category').value = '';
                document.getElementById('new-item-name').value = '';
                document.getElementById('new-item-description').value = '';
            }
        });
        
        // Close item form
        closeItemBtn.addEventListener('click', function() {
            itemFormContainer.classList.add('hidden');
            itemToggleText.textContent = 'Show Item Form';
            toggleItemBtn.querySelector('svg').style.transform = 'rotate(0deg)';
            // Clear form when closing
            document.getElementById('new-item-category').value = '';
            document.getElementById('new-item-name').value = '';
            document.getElementById('new-item-description').value = '';
        });
        
        // Form submission validation
        document.getElementById('packageForm').addEventListener('submit', function(e) {
            if (Object.keys(selectedItems).length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the package before submitting.');
                return false;
            }
        });
    });
</script>
@endpush

@endsection