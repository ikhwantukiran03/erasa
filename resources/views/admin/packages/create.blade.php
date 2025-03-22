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

                <form action="{{ route('admin.packages.store') }}" method="POST" id="packageForm">
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
                                    rows="2" 
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
                                    <label class="block text-dark font-medium mb-1">Price (RM)</label>
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
                        
                        <div id="selected-items-container" class="mb-6 space-y-4">
                            <!-- Selected items will appear here -->
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                            <div>
                                <label for="category-select" class="block text-dark font-medium mb-1">Select Category</label>
                                <select id="category-select" class="form-input">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="item-select" class="block text-dark font-medium mb-1">Select Item</label>
                                <select id="item-select" class="form-input" disabled>
                                    <option value="">-- Select Category First --</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="item-description" class="block text-dark font-medium mb-1">Custom Description (Optional)</label>
                                <textarea 
                                    id="item-description" 
                                    rows="1" 
                                    class="form-input" 
                                    placeholder="Add specific details about this item..."
                                    disabled
                                ></textarea>
                            </div>
                            
                            <div class="md:col-span-2 flex justify-end">
                                <button type="button" id="add-item-btn" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition" disabled>
                                    Add Item to Package
                                </button>
                            </div>
                        </div>
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
        // Price tier management
        let priceCount = 1;
        const addPriceButton = document.getElementById('add-price');
        const pricesContainer = document.getElementById('prices-container');
        
        // Category and item selection
        const categorySelect = document.getElementById('category-select');
        const itemSelect = document.getElementById('item-select');
        const itemDescription = document.getElementById('item-description');
        const addItemButton = document.getElementById('add-item-btn');
        const selectedItemsContainer = document.getElementById('selected-items-container');
        
        // Items data by category
        const itemsByCategory = {
            @foreach($categories as $category)
                {{ $category->id }}: [
                    @foreach($category->items as $item)
                        {
                            id: {{ $item->id }},
                            name: "{{ $item->name }}",
                            description: "{{ addslashes($item->description ?? '') }}"
                        },
                    @endforeach
                ],
            @endforeach
        };
        
        // Track selected items to prevent duplicates
        const selectedItems = new Set();
        
        // Add price tier button
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
            
            // Add event listener to the remove button
            const removeButton = priceRow.querySelector('.remove-price');
            removeButton.addEventListener('click', function() {
                priceRow.remove();
            });
            
            pricesContainer.appendChild(priceRow);
            priceCount++;
        });
        
        // Category select change event
        categorySelect.addEventListener('change', function() {
            // Clear the item select
            itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
            
            // Disable item select if no category is selected
            const categoryId = this.value;
            if (!categoryId) {
                itemSelect.disabled = true;
                itemDescription.disabled = true;
                addItemButton.disabled = true;
                return;
            }
            
            // Populate items for selected category
            const items = itemsByCategory[categoryId] || [];
            items.forEach(item => {
                // Skip if already selected
                if (selectedItems.has(parseInt(item.id))) {
                    return;
                }
                
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                option.dataset.description = item.description || '';
                itemSelect.appendChild(option);
            });
            
            // Enable item select
            itemSelect.disabled = false;
            itemDescription.disabled = true;
            addItemButton.disabled = true;
        });
        
        // Item select change event
        itemSelect.addEventListener('change', function() {
            const itemId = this.value;
            
            if (!itemId) {
                itemDescription.disabled = true;
                addItemButton.disabled = true;
                return;
            }
            
            // Enable description and add button
            itemDescription.disabled = false;
            addItemButton.disabled = false;
            
            // Populate description from selected item
            const selectedOption = this.options[this.selectedIndex];
            itemDescription.value = selectedOption.dataset.description || '';
        });
        
        // Add item button click
        addItemButton.addEventListener('click', function() {
            const itemId = itemSelect.value;
            const itemName = itemSelect.options[itemSelect.selectedIndex].text;
            const description = itemDescription.value;
            
            // Add to selected items
            selectedItems.add(parseInt(itemId));
            
            // Create selected item element
            const itemElement = document.createElement('div');
            itemElement.className = 'selected-item grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-lg';
            itemElement.dataset.itemId = itemId;
            
            itemElement.innerHTML = `
                <div class="md:col-span-1">
                    <p class="font-medium text-gray-800">${itemName}</p>
                    <p class="text-sm text-gray-500">
                        ${categorySelect.options[categorySelect.selectedIndex].text}
                    </p>
                    <input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">
                    <input type="hidden" name="items[${itemId}][selected]" value="1">
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Custom Description:</p>
                    <textarea name="items[${itemId}][description]" rows="1" class="form-input text-sm">${description}</textarea>
                </div>
                <div class="md:col-span-1 flex justify-end items-start">
                    <button type="button" class="remove-item text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            // Add event listener to remove button
            const removeButton = itemElement.querySelector('.remove-item');
            removeButton.addEventListener('click', function() {
                // Remove from selected items set
                selectedItems.delete(parseInt(itemId));
                
                // Remove element
                itemElement.remove();
                
                // Reset category select to refresh available items
                const currentCategoryId = categorySelect.value;
                if (currentCategoryId) {
                    categorySelect.dispatchEvent(new Event('change'));
                }
            });
            
            // Add to selected items container
            selectedItemsContainer.appendChild(itemElement);
            
            // Reset item selection fields
            itemSelect.value = '';
            itemDescription.value = '';
            itemDescription.disabled = true;
            addItemButton.disabled = true;
            
            // Reset category select to refresh available items
            categorySelect.dispatchEvent(new Event('change'));
        });
        
        // Form validation
        document.getElementById('packageForm').addEventListener('submit', function(event) {
            // Check if at least one price tier is provided
            if (pricesContainer.querySelectorAll('.price-row').length === 0) {
                event.preventDefault();
                alert('Please add at least one price tier.');
                return false;
            }
            
            // Check if at least one item is selected
            if (selectedItemsContainer.childElementCount === 0) {
                event.preventDefault();
                alert('Please select at least one item for the package.');
                return false;
            }
        });
    });
</script>
@endpush
@endsection