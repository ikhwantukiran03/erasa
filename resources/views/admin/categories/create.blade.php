<!-- resources/views/admin/categories/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add New Category - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Add New Category</h1>
                <p class="text-gray-600 mt-2">Create a new category</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline">Back to Categories</a>
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

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-dark font-medium mb-1">Category Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                class="form-input @error('name') border-red-500 @enderror" 
                                placeholder="Enter category name"
                            >
                        </div>
                        
                        <div>
                            <label for="description" class="block text-dark font-medium mb-1">Description (Optional)</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="form-input @error('description') border-red-500 @enderror" 
                                placeholder="Provide a category description..."
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection