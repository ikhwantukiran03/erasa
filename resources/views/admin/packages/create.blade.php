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
                            <div class="price-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
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
                                    <p class="text-xs text-gray-500 mt-1">Minimum number of guests</p>
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
                                        placeholder="e.g., 25000"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">Total package price for this number of guests</p>
                                </div>
                                <div class="flex items-end justify-between">
                                    <div class="bg-blue-50 p-3 rounded-md flex-grow">
                                        <p class="text-xs text-blue-700 font-medium">This pricing tier will appear in your package details.</p>
                                    </div>
                                    <button type="button" class="remove-price ml-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 h-10 w-10 rounded-md flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" id="add-price" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mr-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Another Price Tier
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-6 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Package Items</h2>
                        <p class="text-gray-600 mb-4">Select items to include in this package</p>
                        
                        <!-- Search functionality for items -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <label for="item-search" class="block text-dark font-medium mb-1">Search Items</label>
                            <div class="flex">
                                <input 
                                    type="text" 
                                    id="item-search" 
                                    class="form-input flex-grow" 
                                    placeholder="Search for items by name..."
                                >
                                <button type="button" id="search-items-btn" class="ml-2 bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                    Search
                                </button>
                            </div>
                        </div>
                        
                        <!-- Container for items sorted by categories -->
                        <div id="categories-container" class="mb-6 space-y-4">
                            <!-- Items will be grouped by categories here -->
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg bg-white shadow-sm">
                            <div>
                                <label for="category-select" class="block text-dark font-medium mb-1">Select Category</label>
                                <select id="category-select" class="form-input">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-category-name="{{ $category->name }}">{{ $category->name }}</option>
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
                                    rows="2" 
                                    class="form-input" 
                                    placeholder="Add specific details about this item..."
                                    disabled
                                ></textarea>
                            </div>
                            
                            <div class="md:col-span-2 flex justify-end">
                                <button type="button" id="add-item-btn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mr-2 flex items-center" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Item to Package
                                </button>
                            </div>
                        </div>
                        
                        <!-- Selected items preview -->
                        <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <h3 class="font-medium text-dark mb-2">Selected Items</h3>
                            <div id="selected-items-container" class="space-y-2">
                                <p id="no-items-message" class="text-gray-500 italic">No items selected yet</p>
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
        const categoriesContainer = document.getElementById('categories-container');
        const selectedItemsContainer = document.getElementById('selected-items-container');
        const noItemsMessage = document.getElementById('no-items-message');
        const itemSearchInput = document.getElementById('item-search');
        const searchItemsBtn = document.getElementById('search-items-btn');
        
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
        
        // Category names mapping
        const categoryNames = {
            @foreach($categories as $category)
                {{ $category->id }}: "{{ $category->name }}",
            @endforeach
        };
        
        // Flatten all items for search
        const allItems = [];
        Object.keys(itemsByCategory).forEach(categoryId => {
            itemsByCategory[categoryId].forEach(item => {
                allItems.push({
                    ...item,
                    categoryId: categoryId,
                    categoryName: categoryNames[categoryId]
                });
            });
        });
        
        // Track selected items to prevent duplicates
        const selectedItems = new Set();
        
        // Track selected items by category
        const itemsBySelectedCategory = {};
        
        // Add price tier button
        addPriceButton.addEventListener('click', function() {
            const priceRow = document.createElement('div');
            priceRow.className = 'price-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
            
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
                    <p class="text-xs text-gray-500 mt-1">Minimum number of guests</p>
                </div>
                <div>
                    <label class="block text-dark font-medium mb-1">Price (RM)</label>
                    <input 
                        type="number" 
                        name="prices[${priceCount}][price]" 
                        class="form-input" 
                        required 
                        min="0"
                        step="0.01"
                        placeholder="e.g., 25000"
                    >
                    <p class="text-xs text-gray-500 mt-1">Total package price for this number of guests</p>
                </div>
                <div class="flex items-end justify-between">
                    <div class="bg-blue-50 p-3 rounded-md flex-grow">
                        <p class="text-xs text-blue-700 font-medium">This pricing tier will appear in your package details.</p>
                    </div>
                    <button type="button" class="remove-price ml-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 h-10 w-10 rounded-md flex items-center justify-center">
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
        
        // Handle category selection
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Reset item select
            itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
            itemSelect.disabled = !categoryId;
            itemDescription.disabled = !categoryId;
            addItemButton.disabled = true;
            
            if (categoryId) {
                // Populate items for the selected category
                const items = itemsByCategory[categoryId] || [];
                items.forEach(item => {
                    // Only add items that haven't been selected yet
                    if (!selectedItems.has(item.id)) {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        option.dataset.description = item.description || '';
                        itemSelect.appendChild(option);
                    }
                });
            }
        });
        
        // Handle item selection
        itemSelect.addEventListener('change', function() {
            const selectedItemId = this.value;
            addItemButton.disabled = !selectedItemId;
            
            if (selectedItemId) {
                const selectedOption = this.options[this.selectedIndex];
                itemDescription.value = selectedOption.dataset.description || '';
            } else {
                itemDescription.value = '';
            }
        });
        
        // Search items functionality
        searchItemsBtn.addEventListener('click', searchItems);
        itemSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchItems();
            }
        });
        
        function searchItems() {
            const searchQuery = itemSearchInput.value.toLowerCase().trim();
            
            if (!searchQuery) {
                alert('Please enter a search term');
                return;
            }
            
            // Filter items that match the search query
            const matchedItems = allItems.filter(item => 
                item.name.toLowerCase().includes(searchQuery) || 
                (item.description && item.description.toLowerCase().includes(searchQuery))
            );
            
            // Create a modal or popup to show search results
            const resultsContainer = document.createElement('div');
            resultsContainer.className = 'fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50';
            resultsContainer.innerHTML = `
                <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[80vh] overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Search Results: "${searchQuery}"</h3>
                        <button type="button" id="close-search-results" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto" style="max-height: calc(80vh - 60px);">
                        ${
                            matchedItems.length > 0 
                            ? `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${matchedItems.map(item => `
                                    <div class="border rounded-lg p-3 ${selectedItems.has(item.id) ? 'bg-gray-100 opacity-60' : 'bg-white'}">
                                        <div class="flex justify-between">
                                            <p class="font-medium">${item.name}</p>
                                            <span class="text-xs text-gray-500">${item.categoryName}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">${item.description || 'No description'}</p>
                                        ${!selectedItems.has(item.id) ? 
                                            `<button type="button" class="quick-add-item mt-2 px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-sm" 
                                                    data-item-id="${item.id}" 
                                                    data-item-name="${item.name}" 
                                                    data-category-id="${item.categoryId}"
                                                    data-description="${item.description || ''}">
                                                Add to Package
                                            </button>` 
                                            : '<p class="mt-2 text-xs text-gray-500 italic">Already added</p>'
                                        }
                                    </div>
                                `).join('')}
                              </div>`
                            : '<p class="text-gray-500">No items found matching your search criteria.</p>'
                        }
                    </div>
                </div>
            `;
            
            document.body.appendChild(resultsContainer);
            
            // Close button event
            document.getElementById('close-search-results').addEventListener('click', function() {
                resultsContainer.remove();
            });
            
            // Quick add buttons
            resultsContainer.querySelectorAll('.quick-add-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = parseInt(this.dataset.itemId);
                    const itemName = this.dataset.itemName;
                    const categoryId = this.dataset.categoryId;
                    const description = this.dataset.description;
                    
                    addItemToPackage(itemId, itemName, categoryId, description);
                    this.closest('div').classList.add('bg-gray-100', 'opacity-60');
                    this.outerHTML = '<p class="mt-2 text-xs text-gray-500 italic">Added to package</p>';
                });
            });
        }
        
        // Add item to package
        addItemButton.addEventListener('click', function() {
            const itemId = parseInt(itemSelect.value);
            if (!itemId) return;
            
            const itemName = itemSelect.options[itemSelect.selectedIndex].text;
            const categoryId = categorySelect.value;
            const description = itemDescription.value;
            
            addItemToPackage(itemId, itemName, categoryId, description);
            
            // Reset item selection
            itemSelect.value = '';
            itemDescription.value = '';
            addItemButton.disabled = true;
            
            // Re-trigger category change to refresh item list
            categorySelect.dispatchEvent(new Event('change'));
        });
        
        function addItemToPackage(itemId, itemName, categoryId, description) {
            // Skip if item already selected
            if (selectedItems.has(itemId)) return;
            
            // Add to selected items set
            selectedItems.add(itemId);
            
            // Add to selected items by category
            if (!itemsBySelectedCategory[categoryId]) {
                itemsBySelectedCategory[categoryId] = [];
            }
            itemsBySelectedCategory[categoryId].push({
                id: itemId,
                name: itemName,
                description: description
            });
            
            // Update categories container
            updateCategoriesDisplay();
            
            // Update selected items preview
            updateSelectedItemsPreview();
            
            // Add hidden input for form submission
            const hiddenInput = `
                <input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">
                <input type="hidden" name="items[${itemId}][selected]" value="1">
                <input type="hidden" name="items[${itemId}][description]" value="${description.replace(/"/g, '&quot;')}">
            `;
            document.getElementById('packageForm').insertAdjacentHTML('beforeend', hiddenInput);
        }
        
        function updateCategoriesDisplay() {
            categoriesContainer.innerHTML = '';
            
            Object.keys(itemsBySelectedCategory).forEach(categoryId => {
                const items = itemsBySelectedCategory[categoryId];
                if (items.length === 0) return;
                
                const categoryName = categoryNames[categoryId];
                const categoryElement = document.createElement('div');
                categoryElement.className = 'p-4 border border-gray-200 rounded-lg';
                categoryElement.innerHTML = `
                    <h3 class="font-semibold text-dark mb-2">${categoryName}</h3>
                    <div class="space-y-2">
                        ${items.map(item => `
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <div>
                                    <p class="font-medium">${item.name}</p>
                                    ${item.description ? `<p class="text-sm text-gray-600">${item.description}</p>` : ''}
                                </div>
                                <button type="button" class="remove-item text-red-500 hover:text-red-700" data-item-id="${item.id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                `;
                
                // Add event listeners to remove buttons
                categoryElement.querySelectorAll('.remove-item').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = parseInt(this.dataset.itemId);
                        removeItemFromPackage(itemId, categoryId);
                    });
                });
                
                categoriesContainer.appendChild(categoryElement);
            });
        }
        
        function updateSelectedItemsPreview() {
            // Count total selected items
            const totalItems = Array.from(selectedItems).length;
            
            if (totalItems === 0) {
                selectedItemsContainer.innerHTML = '<p class="text-gray-500 italic">No items selected yet</p>';
                return;
            }
            
            // Group by category for the preview
            const previewHTML = Object.keys(itemsBySelectedCategory)
                .map(categoryId => {
                    const items = itemsBySelectedCategory[categoryId];
                    if (items.length === 0) return '';
                    
                    return `
                        <div class="mb-2">
                            <span class="text-sm font-medium">${categoryNames[categoryId]}: </span>
                            <span class="text-sm text-gray-600">${items.map(i => i.name).join(', ')}</span>
                        </div>
                    `;
                })
                .join('');
            
            selectedItemsContainer.innerHTML = `
                <div>
                    <p class="text-sm mb-2"><span class="font-medium">${totalItems}</span> item(s) selected</p>
                    ${previewHTML}
                </div>
            `;
        }
        
        function removeItemFromPackage(itemId, categoryId) {
            // Remove from selected items set
            selectedItems.delete(itemId);
            
            // Remove from selected items by category
            itemsBySelectedCategory[categoryId] = itemsBySelectedCategory[categoryId].filter(item => item.id !== itemId);
            
            // Remove hidden inputs
            document.querySelector(`input[name="items[${itemId}][item_id]"]`)?.remove();
            document.querySelector(`input[name="items[${itemId}][selected]"]`)?.remove();
            document.querySelector(`input[name="items[${itemId}][description]"]`)?.remove();
            
            // Update displays
            updateCategoriesDisplay();
            updateSelectedItemsPreview();
            
            // Re-trigger category change if the current category is the one we modified
            if (categorySelect.value === categoryId) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endpush
@endsection