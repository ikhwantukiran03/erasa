@extends('layouts.app')

@section('title', 'Edit Item - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Item</h1>
                <p class="text-gray-600 mt-2">Update item information</p>
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

                <form action="{{ route('admin.items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-dark font-medium mb-1">Item Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $item->name) }}" 
                                required 
                                class="form-input @error('name') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div>
                            <label for="category_id" class="block text-dark font-medium mb-1">Category</label>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                required 
                                class="form-input @error('category_id') border-red-500 @enderror"
                            >
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
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
                            >{{ old('description', $item->description) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection