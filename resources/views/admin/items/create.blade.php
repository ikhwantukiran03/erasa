@extends('layouts.app')

@section('title', 'Add New Items - Enak Rasa Wedding Hall')

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
                        <a href="{{ route('admin.items.index') }}" class="ml-1 text-gray-700 hover:text-primary md:ml-2">Items</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Add New</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Add New Items</h1>
                <p class="text-gray-600 mt-2">Create multiple items for a category at once</p>
            </div>
            <a href="{{ route('admin.items.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Items
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

                <form action="{{ route('admin.items.store') }}" method="POST">
                    @csrf
                    
                    <div class="max-w-4xl">
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md">
                            <div class="flex">
                                <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Efficiency Tip</p>
                                    <p class="text-sm mt-1">You can add multiple items at once. All items will be assigned to the selected category.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="category_id" class="block text-dark font-medium mb-1">Select Category <span class="text-red-500">*</span></label>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                required 
                                class="form-input w-full @error('category_id') border-red-500 @enderror"
                                autofocus
                            >
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose the category these items belong to</p>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Add Items</h3>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm" id="item-counter">1 item</span>
                            </div>
                            
                            <div id="items-container" class="space-y-4">
                                <div class="item-row bg-gray-50 border border-gray-200 rounded-lg p-5">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-medium text-gray-700">Item #1</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-dark font-medium mb-1">Name <span class="text-red-500">*</span></label>
                                            <input 
                                                type="text" 
                                                name="items[0][name]" 
                                                class="form-input w-full" 
                                                required 
                                                placeholder="Item name"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-dark font-medium mb-1">Description</label>
                                            <textarea 
                                                name="items[0][description]" 
                                                class="form-input w-full" 
                                                rows="1" 
                                                placeholder="Optional description"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center mt-6">
                                <button type="button" id="add-item" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Another Item
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.items.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save All Items
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
        let itemCount = 1;
        const addItemButton = document.getElementById('add-item');
        const itemsContainer = document.getElementById('items-container');
        const itemCounter = document.getElementById('item-counter');
        
        // Update counter initially
        updateItemCounter();
        
        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('div');
            itemRow.className = 'item-row bg-gray-50 border border-gray-200 rounded-lg p-5';
            
            itemRow.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-700">Item #${itemCount + 1}</h4>
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-dark font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="items[${itemCount}][name]" 
                            class="form-input w-full" 
                            required 
                            placeholder="Item name"
                        >
                    </div>
                    <div>
                        <label class="block text-dark font-medium mb-1">Description</label>
                        <textarea 
                            name="items[${itemCount}][description]" 
                            class="form-input w-full" 
                            rows="1" 
                            placeholder="Optional description"
                        ></textarea>
                    </div>
                </div>
            `;
            
            itemsContainer.appendChild(itemRow);
            
            // Add event listener to the remove button
            const removeButton = itemRow.querySelector('.remove-item');
            removeButton.addEventListener('click', function() {
                itemRow.classList.add('scale-95', 'opacity-0');
                itemRow.style.transition = 'all 0.2s ease-out';
                
                setTimeout(() => {
                    itemRow.remove();
                    updateItemCounter();
                    renumberItems();
                }, 200);
            });
            
            itemCount++;
            updateItemCounter();
        });
        
        // Function to update the item counter
        function updateItemCounter() {
            const itemCount = document.querySelectorAll('.item-row').length;
            itemCounter.textContent = itemCount === 1 ? '1 item' : `${itemCount} items`;
        }
        
        // Function to renumber items after deletion
        function renumberItems() {
            const items = document.querySelectorAll('.item-row');
            items.forEach((item, index) => {
                // Update the item number in the title
                const title = item.querySelector('h4');
                if (title) {
                    title.textContent = `Item #${index + 1}`;
                }
                
                // Update the input name attributes
                const nameInput = item.querySelector('input[name^="items["]');
                const descInput = item.querySelector('textarea[name^="items["]');
                
                if (nameInput) {
                    nameInput.name = `items[${index}][name]`;
                }
                
                if (descInput) {
                    descInput.name = `items[${index}][description]`;
                }
            });
            
            itemCount = items.length;
        }
    });
</script>
@endpush
@endsection