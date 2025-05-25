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
                    <div class="flex items-center text-primary">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                        <span class="ml-2 font-medium">Basic Info</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center text-gray-400">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                        <span class="ml-2">Pricing</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center text-gray-400">
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
                        <!-- Quick Search -->
                        <div class="mb-8 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Quick Item Search</h3>
                            <div class="flex gap-4">
                                <input 
                                    type="text" 
                                    id="item-search" 
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                                    placeholder="Search for items by name (e.g., 'flowers', 'catering', 'photography')..."
                                >
                                <button type="button" id="search-items-btn" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Search
                                </button>
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
                                <p class="text-sm">Start by selecting a category below or use the search function</p>
                            </div>
                        </div>
                        
                        <!-- Categories Display -->
                        <div id="categories-container" class="space-y-6">
                            <!-- Categories will be populated by JavaScript -->
                        </div>
                        
                        <!-- Item Selection Interface -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="font-semibold text-gray-800 mb-4">Add Items to Package</h3>
                            
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
                const stepElement = document.querySelector(`.flex:nth-child(${i * 2 - 1})`);
                if (stepElement) {
                    if (i <= step) {
                        stepElement.classList.remove('text-gray-400');
                        stepElement.classList.add('text-primary');
                        stepElement.querySelector('.w-8').classList.remove('bg-gray-300', 'text-gray-600');
                        stepElement.querySelector('.w-8').classList.add('bg-primary', 'text-white');
                    } else {
                        stepElement.classList.remove('text-primary');
                        stepElement.classList.add('text-gray-400');
                        stepElement.querySelector('.w-8').classList.remove('bg-primary', 'text-white');
                        stepElement.querySelector('.w-8').classList.add('bg-gray-300', 'text-gray-600');
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
                            placeholder="${25000 + (priceCount *