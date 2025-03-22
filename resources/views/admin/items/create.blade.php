@extends('layouts.app')

@section('title', 'Add New Items - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Add New Items</h1>
                <p class="text-gray-600 mt-2">Create multiple items for a category</p>
            </div>
            <a href="{{ route('admin.items.index') }}" class="text-primary hover:underline">Back to Items</a>
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

                <form action="{{ route('admin.items.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="category_id" class="block text-dark font-medium mb-1">Select Category</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required 
                            class="form-input @error('category_id') border-red-500 @enderror"
                        >
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Items</h3>
                        <p class="text-gray-600 mb-4">You can add multiple items at once.</p>
                        
                        <div id="items-container">
                            <div class="item-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <label class="block text-dark font-medium mb-1">Name</label>
                                    <input 
                                        type="text" 
                                        name="items[0][name]" 
                                        class="form-input" 
                                        required 
                                        placeholder="Item name"
                                    >
                                </div>
                                <div>
                                    <label class="block text-dark font-medium mb-1">Description</label>
                                    <textarea 
                                        name="items[0][description]" 
                                        class="form-input" 
                                        rows="1" 
                                        placeholder="Optional description"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" id="add-item" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mr-2">
                                Add Another Item
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
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
        
        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('div');
            itemRow.className = 'item-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded-lg';
            
            itemRow.innerHTML = `
                <div>
                    <label class="block text-dark font-medium mb-1">Name</label>
                    <input 
                        type="text" 
                        name="items[${itemCount}][name]" 
                        class="form-input" 
                        required 
                        placeholder="Item name"
                    >
                </div>
                <div class="relative">
                    <label class="block text-dark font-medium mb-1">Description</label>
                    <textarea 
                        name="items[${itemCount}][description]" 
                        class="form-input" 
                        rows="1" 
                        placeholder="Optional description"
                    ></textarea>
                    <button type="button" class="remove-item absolute top-0 right-0 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            itemsContainer.appendChild(itemRow);
            
            // Add event listener to the remove button
            const removeButton = itemRow.querySelector('.remove-item');
            removeButton.addEventListener('click', function() {
                itemRow.remove();
            });
            
            itemCount++;
        });
    });
</script>
@endpush
@endsection