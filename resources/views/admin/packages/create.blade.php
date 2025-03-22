@extends('layouts.app')

@section('title', 'Create Package - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Create New Package</h1>
                <p class="text-gray-600 mt-2">Add a new wedding package to the system</p>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="text-primary hover:underline">Back to Packages</a>
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

                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Package Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-dark font-medium mb-1">Package Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    class="form-input @error('name') border-red-500 @enderror" 
                                    placeholder="Enter package name"
                                >
                            </div>
                            
                            <div>
                                <label for="venue_id" class="block text-dark font-medium mb-1">Venue</label>
                                <select 
                                    id="venue_id" 
                                    name="venue_id" 
                                    required 
                                    class="form-input @error('venue_id') border-red-500 @enderror"
                                >
                                    <option value="">-- Select Venue --</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-dark font-medium mb-1">Description</label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    class="form-input @error('description') border-red-500 @enderror" 
                                    placeholder="Provide a package description..."
                                >{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Package Prices</h2>
                        <p class="text-gray-600 mb-4">Add pricing tiers based on the number of guests (pax)</p>
                        
                        <div id="prices-container">
                            <div class="price-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <label class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                                    <input 
                                        type="number" 
                                        name="prices[0][pax]" 
                                        class="form-input" 
                                        required 
                                        min="1"
                                        placeholder="e.g., 100"
                                    >
                                </div>
                                <div>
                                    <label class="block text-dark font-medium mb-1">Price (Rp)</label>
                                    <input 
                                        type="number" 
                                        name="prices[0][price]" 
                                        class="form-input" 
                                        required 
                                        min="0"
                                        step="0.01"
                                        placeholder="e.g., 25000000"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" id="add-price" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mr-2">
                                Add Another Price Tier
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-6 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Package Items</h2>
                        <p class="text-gray-600 mb-4">Select items to include in this package</p>
                        
                        @foreach($categories as $category)
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-700 mb-3">{{ $category->name }}</h3>
                                
                                @foreach($category->items as $item)
                                    <div class="item-selection mb-3 p-3 border border-gray-200 rounded-lg">
                                        <div class="flex items-start">
                                            <div class="mr-2 mt-1">
                                                <input 
                                                    type="checkbox" 
                                                    id="item_{{ $item->id }}" 
                                                    name="items[{{ $item->id }}][selected]" 
                                                    value="1"
                                                    class="rounded border-gray-300 text-primary focus:ring-primary"
                                                    {{ old("items.{$item->id}.selected") ? 'checked' : '' }}
                                                >
                                                <input type="hidden" name="items[{{ $item->id }}][item_id]" value="{{ $item->id }}">
                                            </div>
                                            <div class="flex-1">
                                                <label for="item_{{ $item->id }}" class="font-medium text-gray-800">{{ $item->name }}</label>
                                                @if($item->description)
                                                    <p class="text-sm text-gray-600">{{ $item->description }}</p>
                                                @endif
                                                
                                                <div class="mt-2">
                                                    <label for="item_desc_{{ $item->id }}" class="block text-sm text-gray-600 mb-1">Custom Description (Optional)</label>
                                                    <textarea 
                                                        id="item_desc_{{ $item->id }}" 
                                                        name="items[{{ $item->id }}][description]" 
                                                        rows="2" 
                                                        class="form-input text-sm" 
                                                        placeholder="Add specific details about this item..."
                                                    >{{ old("items.{$item->id}.description") }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                            Create Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let priceCount = 1;
        const addPriceButton = document.getElementById('add-price');
        const pricesContainer = document.getElementById('prices-container');
        
        addPriceButton.addEventListener('click', function() {
            const priceRow = document.createElement('div');
            priceRow.className = 'price-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded-lg';
            
            priceRow.innerHTML = `
                <div>
                    <label class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                    <input 
                        type="number" 
                        name="prices[${priceCount}][pax]" 
                        class="form-input" 
                        required 
                        min="1"
                        placeholder="e.g., 100"
                    >
                </div>
                <div class="relative">
                    <label class="block text-dark font-medium mb-1">Price (Rp)</label>
                    <input 
                        type="number" 
                        name="prices[${priceCount}][price]" 
                        class="form-input" 
                        required 
                        min="0"
                        step="0.01"
                        placeholder="e.g., 25000000"
                    >
                    <button type="button" class="remove-price absolute top-0 right-0 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            pricesContainer.appendChild(priceRow);
            
            // Add event listener to the remove button
            const removeButton = priceRow.querySelector('.remove-price');
            removeButton.addEventListener('click', function() {
                priceRow.remove();
            });
            
            priceCount++;
        });
    });
</script>
@endpush
@endsection