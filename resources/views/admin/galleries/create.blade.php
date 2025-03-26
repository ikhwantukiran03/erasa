@extends('layouts.app')

@section('title', 'Add Gallery Image - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Add Gallery Image</h1>
                <p class="text-gray-600 mt-2">Upload a new image to the venue gallery</p>
            </div>
            <a href="{{ route('admin.galleries.index') }}" class="text-primary hover:underline">Back to Gallery</a>
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

                <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        
                        <div>
                            <label for="title" class="block text-dark font-medium mb-1">Image Title</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}" 
                                required 
                                class="form-input @error('title') border-red-500 @enderror" 
                                placeholder="Enter image title"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="description" class="block text-dark font-medium mb-1">Description (Optional)</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3" 
                                class="form-input @error('description') border-red-500 @enderror" 
                                placeholder="Enter image description"
                            >{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-dark font-medium mb-3">Image Source</label>
                            
                            <div class="flex flex-wrap gap-6 mb-3">
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="source_local" 
                                        name="source" 
                                        value="local"
                                        {{ old('source', 'local') === 'local' ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                    >
                                    <label for="source_local" class="ml-2 text-gray-700">Upload from Computer</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="source_external" 
                                        name="source" 
                                        value="external"
                                        {{ old('source') === 'external' ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                    >
                                    <label for="source_external" class="ml-2 text-gray-700">Use External URL</label>
                                </div>
                            </div>
                            
                            <div id="local_upload" class="{{ old('source') === 'external' ? 'hidden' : '' }}">
                                <label for="image" class="block text-dark font-medium mb-1">Upload Image</label>
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    class="form-input @error('image') border-red-500 @enderror" 
                                >
                                <p class="text-sm text-gray-500 mt-1">Max file size: 5MB. Recommended dimensions: 1200x800px.</p>
                                
                                <div class="mt-3 hidden" id="image_preview_container">
                                    <img id="image_preview" src="#" alt="Image Preview" class="max-h-40 rounded-lg">
                                </div>
                            </div>
                            
                            <div id="external_url" class="{{ old('source') === 'external' ? '' : 'hidden' }}">
                                <label for="image_url" class="block text-dark font-medium mb-1">External Image URL</label>
                                <input 
                                    type="url" 
                                    id="image_url" 
                                    name="image_url" 
                                    value="{{ old('image_url') }}" 
                                    class="form-input @error('image_url') border-red-500 @enderror" 
                                    placeholder="https://example.com/image.jpg"
                                >
                                <p class="text-sm text-gray-500 mt-1">Enter the URL of an image from social media or other websites.</p>
                                
                                <div class="mt-3 hidden" id="url_preview_container">
                                    <img id="url_preview" src="#" alt="URL Preview" class="max-h-40 rounded-lg">
                                </div>