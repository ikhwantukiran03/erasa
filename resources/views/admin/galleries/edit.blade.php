@extends('layouts.app')

@section('title', 'Edit Gallery Image - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Gallery Image</h1>
                <p class="text-gray-600 mt-2">Update gallery image information</p>
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

                <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data" id="galleryForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Image Preview -->
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Current Image</h3>
                            <div class="flex items-center justify-center bg-white border border-gray-200 rounded-lg p-4">
                                @if($gallery->source === 'local' && $gallery->image_path)
                                    <img 
                                        src="{{ asset('storage/' . $gallery->image_path) }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="max-h-60 rounded"
                                    >
                                @elseif($gallery->source === 'external' && $gallery->image_url)
                                    <img 
                                        src="{{ $gallery->image_url }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="max-h-60 rounded"
                                        onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                                    >
                                @else
                                    <div class="text-gray-400">No image available</div>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                Current source: <span class="font-medium">{{ $gallery->source === 'local' ? 'Uploaded file' : 'External URL' }}</span>
                            </p>
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
                                    <option value="{{ $venue->id }}" {{ old('venue_id', $gallery->venue_id) == $venue->id ? 'selected' : '' }}>
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
                                value="{{ old('title', $gallery->title) }}" 
                                required 
                                class="form-input @error('title') border-red-500 @enderror" 
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="description" class="block text-dark font-medium mb-1">Description (Optional)</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3" 
                                class="form-input @error('description') border-red-500 @enderror" 
                            >{{ old('description', $gallery->description) }}</textarea>
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
                                        {{ old('source', $gallery->source) === 'local' ? 'checked' : '' }}
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
                                        {{ old('source', $gallery->source) === 'external' ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                    >
                                    <label for="source_external" class="ml-2 text-gray-700">Use External URL</label>
                                </div>
                            </div>
                            
                            <div id="local_upload" class="{{ old('source', $gallery->source) === 'external' ? 'hidden' : '' }}">
                                <label for="image" class="block text-dark font-medium mb-1">Upload New Image</label>
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    class="form-input @error('image') border-red-500 @enderror" 
                                >
                                <p class="text-sm text-gray-500 mt-1">
                                    @if($gallery->source === 'local')
                                        Leave empty to keep current image. Max file size: 5MB.
                                    @else
                                        Required when switching from external URL to local image. Max file size: 5MB.
                                    @endif
                                </p>
                                
                                <div class="mt-3 hidden" id="image_preview_container">
                                    <img id="image_preview" src="#" alt="Image Preview" class="max-h-40 rounded-lg">
                                </div>
                            </div>
                            
                            <div id="external_url" class="{{ old('source', $gallery->source) === 'external' ? '' : 'hidden' }}">
                                <label for="image_url" class="block text-dark font-medium mb-1">External Image URL</label>
                                <input 
                                    type="url" 
                                    id="image_url" 
                                    name="image_url" 
                                    value="{{ old('image_url', $gallery->image_url) }}" 
                                    class="form-input @error('image_url') border-red-500 @enderror" 
                                    placeholder="https://example.com/image.jpg"
                                >
                                <p class="text-sm text-gray-500 mt-1">Enter the URL of an image from social media or other websites.</p>
                                
                                <div class="mt-3 hidden" id="url_preview_container">
                                    <img id="url_preview" src="#" alt="URL Preview" class="max-h-40 rounded-lg">
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 flex items-center mt-2">
                            <input 
                                type="checkbox" 
                                id="is_featured" 
                                name="is_featured" 
                                value="1"
                                {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                            >
                            <label for="is_featured" class="ml-2 text-gray-700">Mark as featured image</label>
                        </div>
                        
                        <div>
                            <label for="display_order" class="block text-dark font-medium mb-1">Display Order (Optional)</label>
                            <input 
                                type="number" 
                                id="display_order" 
                                name="display_order" 
                                value="{{ old('display_order', $gallery->display_order) }}" 
                                min="0"
                                class="form-input @error('display_order') border-red-500 @enderror" 
                            >
                            <p class="text-sm text-gray-500 mt-1">Lower numbers will be displayed first.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.galleries.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                            Update Gallery Image
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
        const sourceLocalRadio = document.getElementById('source_local');
        const sourceExternalRadio = document.getElementById('source_external');
        const localUploadDiv = document.getElementById('local_upload');
        const externalUrlDiv = document.getElementById('external_url');
        const imageInput = document.getElementById('image');
        const imageUrlInput = document.getElementById('image_url');
        const imagePreviewContainer = document.getElementById('image_preview_container');
        const imagePreview = document.getElementById('image_preview');
        const urlPreviewContainer = document.getElementById('url_preview_container');
        const urlPreview = document.getElementById('url_preview');
        const galleryForm = document.getElementById('galleryForm');
        
        // Toggle between local and external source
        sourceLocalRadio.addEventListener('change', function() {
            if (this.checked) {
                localUploadDiv.classList.remove('hidden');
                externalUrlDiv.classList.add('hidden');
            }
        });
        
        sourceExternalRadio.addEventListener('change', function() {
            if (this.checked) {
                localUploadDiv.classList.add('hidden');
                externalUrlDiv.classList.remove('hidden');
            }
        });
        
        // Preview local image
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreviewContainer.classList.add('hidden');
            }
        });
        
        // Preview external URL image
        imageUrlInput.addEventListener('input', function() {
            if (this.value) {
                urlPreview.src = this.value;
                urlPreviewContainer.classList.remove('hidden');
                
                // Handle error for invalid URLs
                urlPreview.onerror = function() {
                    urlPreviewContainer.classList.add('hidden');
                };
            } else {
                urlPreviewContainer.classList.add('hidden');
            }
        });
        
        // Show preview for external URL on page load if present
        if (imageUrlInput.value) {
            urlPreview.src = imageUrlInput.value;
            urlPreviewContainer.classList.remove('hidden');
            
            // Handle error for invalid URLs
            urlPreview.onerror = function() {
                urlPreviewContainer.classList.add('hidden');
            };
        }
        
        // Form validation before submit
        galleryForm.addEventListener('submit', function(e) {
            const currentSource = "{{ $gallery->source }}";
            
            if (sourceLocalRadio.checked && currentSource === 'external' && !imageInput.files.length) {
                e.preventDefault();
                alert('Please select an image to upload when switching from external URL to local image.');
                return false;
            }
            
            if (sourceExternalRadio.checked && !imageUrlInput.value) {
                e.preventDefault();
                alert('Please enter an external image URL.');
                return false;
            }
        });
    });
</script>
@endpush
@endsection