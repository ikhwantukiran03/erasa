<!-- resources/views/admin/galleries/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add Gallery Images - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Add Gallery Images</h1>
                <p class="text-gray-600 mt-2">Upload new images to the venue gallery</p>
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
                    
                    <div class="mb-6">
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
                        @error('venue_id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Method</h3>
                        
                        <div class="flex flex-wrap gap-6 mb-4">
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    id="source_local" 
                                    name="source_type" 
                                    value="local"
                                    checked
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                >
                                <label for="source_local" class="ml-2 text-gray-700">Upload from Computer</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    id="source_external" 
                                    name="source_type" 
                                    value="external"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                >
                                <label for="source_external" class="ml-2 text-gray-700">Use External URLs</label>
                            </div>
                        </div>
                        
                        <!-- Local Upload Section -->
                        <div id="local_upload_section" class="mb-6">
                            <div class="mb-4">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center" id="dropzone">
                                    <label for="images" class="block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Drag and drop images here or click to browse</p>
                                        <p class="mt-1 text-xs text-gray-500">Max file size: 5MB per image. Accepted formats: JPG, PNG, GIF</p>
                                        <input 
                                            type="file" 
                                            id="images" 
                                            name="images[]" 
                                            multiple 
                                            accept="image/*"
                                            class="hidden" 
                                        >
                                    </label>
                                </div>
                            </div>
                            
                            <div id="image_previews" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Image previews will be added here -->
                            </div>
                        </div>
                        
                        <!-- External URL Section -->
                        <div id="external_url_section" class="hidden mb-6">
                            <div id="external_urls_container">
                                <div class="external-url-item mb-4 p-4 border border-gray-200 rounded-lg">
                                    <div class="mb-3">
                                        <label class="block text-dark font-medium mb-1">Image URL</label>
                                        <input 
                                            type="url" 
                                            name="image_urls[]" 
                                            class="form-input image-url-input" 
                                            placeholder="https://example.com/image.jpg"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-dark font-medium mb-1">Image Title</label>
                                        <input 
                                            type="text" 
                                            name="url_titles[]" 
                                            class="form-input" 
                                            placeholder="Enter image title"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-dark font-medium mb-1">Description (Optional)</label>
                                        <textarea 
                                            name="url_descriptions[]" 
                                            rows="2" 
                                            class="form-input" 
                                            placeholder="Enter image description"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <button type="button" id="add_url_btn" class="text-primary hover:text-primary-dark">
                                    <i class="fas fa-plus-circle"></i> Add Another URL
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6" id="image_details_section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Image Details</h3>
                        <p class="text-gray-600 mb-4">These details will be applied to all uploaded images. You can edit individual image details later.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="default_title" class="block text-dark font-medium mb-1">Default Title</label>
                                <input 
                                    type="text" 
                                    id="default_title" 
                                    name="default_title" 
                                    class="form-input" 
                                    placeholder="Enter default title for all images"
                                >
                            </div>
                            
                            <div>
                                <label for="display_order" class="block text-dark font-medium mb-1">Starting Display Order</label>
                                <input 
                                    type="number" 
                                    id="display_order" 
                                    name="display_order" 
                                    value="{{ old('display_order', 0) }}" 
                                    min="0"
                                    class="form-input" 
                                >
                                <p class="text-sm text-gray-500 mt-1">Images will be numbered starting from this value</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="default_description" class="block text-dark font-medium mb-1">Default Description (Optional)</label>
                                <textarea 
                                    id="default_description" 
                                    name="default_description" 
                                    rows="3" 
                                    class="form-input" 
                                    placeholder="Enter default description for all images"
                                ></textarea>
                            </div>
                            
                            <div class="md:col-span-2 flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="is_featured" 
                                    name="is_featured" 
                                    value="1"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                >
                                <label for="is_featured" class="ml-2 text-gray-700">Mark first image as featured</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.galleries.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition">
                            Upload Images
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
        // Toggle between local and external URL sections
        const sourceLocalRadio = document.getElementById('source_local');
        const sourceExternalRadio = document.getElementById('source_external');
        const localUploadSection = document.getElementById('local_upload_section');
        const externalUrlSection = document.getElementById('external_url_section');
        
        sourceLocalRadio.addEventListener('change', function() {
            if (this.checked) {
                localUploadSection.classList.remove('hidden');
                externalUrlSection.classList.add('hidden');
            }
        });
        
        sourceExternalRadio.addEventListener('change', function() {
            if (this.checked) {
                localUploadSection.classList.add('hidden');
                externalUrlSection.classList.remove('hidden');
            }
        });
        
        // Local image upload and preview
        const imagesInput = document.getElementById('images');
        const imagePreviewsContainer = document.getElementById('image_previews');
        const dropzone = document.getElementById('dropzone');
        
        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropzone.classList.add('border-primary', 'bg-primary/5');
        }
        
        function unhighlight() {
            dropzone.classList.remove('border-primary', 'bg-primary/5');
        }
        
        dropzone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            imagesInput.files = files;
            handleFiles(files);
        }
        
        imagesInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        function handleFiles(files) {
            imagePreviewsContainer.innerHTML = '';
            
            if (files.length === 0) {
                return;
            }
            
            // Create form data to store files
            const formData = new FormData();
            
            // Loop through all files
            Array.from(files).forEach((file, index) => {
                // Check file type
                if (!file.type.match('image.*')) {
                    return;
                }
                
                // Check file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                    return;
                }
                
                // Add file to form data
                formData.append(`images[${index}]`, file);
                
                // Create preview container
                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-item relative border border-gray-200 rounded-lg overflow-hidden';
                
                // Create preview image
                const img = document.createElement('img');
                img.classList.add('w-full', 'h-40', 'object-cover');
                
                // Create reader to load image
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Create title input for this image
                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.name = `titles[${index}]`;
                titleInput.placeholder = 'Image title';
                titleInput.className = 'form-input mt-2 text-sm w-full';
                titleInput.setAttribute('data-index', index);
                
                // Create description input for this image
                const descInput = document.createElement('textarea');
                descInput.name = `descriptions[${index}]`;
                descInput.placeholder = 'Image description (optional)';
                descInput.className = 'form-input mt-2 text-sm w-full';
                descInput.rows = 2;
                descInput.setAttribute('data-index', index);
                
                // Create remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition';
                removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                
                // Add event listener to remove this preview
                removeBtn.addEventListener('click', function() {
                    previewContainer.remove();
                    
                    // Create a new FileList without this file
                    updateFileInput(index);
                });
                
                // Add elements to preview container
                previewContainer.appendChild(img);
                previewContainer.appendChild(removeBtn);
                
                // Add a div for the inputs
                const inputsDiv = document.createElement('div');
                inputsDiv.className = 'p-2';
                inputsDiv.appendChild(titleInput);
                inputsDiv.appendChild(descInput);
                
                previewContainer.appendChild(inputsDiv);
                
                // Add preview container to previews container
                imagePreviewsContainer.appendChild(previewContainer);
                
                // Add a hidden input to keep track of the original file name
                const fileNameInput = document.createElement('input');
                fileNameInput.type = 'hidden';
                fileNameInput.name = `file_names[${index}]`;
                fileNameInput.value = file.name;
                previewContainer.appendChild(fileNameInput);
            });
        }
        
        // Helper function to update FileInput when a file is removed
        function updateFileInput(indexToRemove) {
            const currentFiles = imagesInput.files;
            
            // FileList is immutable, so we need to create a DataTransfer
            // and then add all files except the one we want to remove
            const dt = new DataTransfer();
            
            for (let i = 0; i < currentFiles.length; i++) {
                if (i !== indexToRemove) {
                    dt.items.add(currentFiles[i]);
                }
            }
            
            // Set the new FileList
            imagesInput.files = dt.files;
            
            // Update all data-index attributes and input names
            const previewItems = imagePreviewsContainer.querySelectorAll('.preview-item');
            previewItems.forEach((item, newIndex) => {
                const titleInput = item.querySelector(`input[name^="titles["]`);
                const descInput = item.querySelector(`textarea[name^="descriptions["]`);
                const fileNameInput = item.querySelector(`input[name^="file_names["]`);
                
                if (titleInput) {
                    titleInput.name = `titles[${newIndex}]`;
                    titleInput.setAttribute('data-index', newIndex);
                }
                
                if (descInput) {
                    descInput.name = `descriptions[${newIndex}]`;
                    descInput.setAttribute('data-index', newIndex);
                }
                
                if (fileNameInput) {
                    fileNameInput.name = `file_names[${newIndex}]`;
                }
            });
        }
        
        // External URL section
        const addUrlBtn = document.getElementById('add_url_btn');
        const externalUrlsContainer = document.getElementById('external_urls_container');
        
        // Add another URL input
        addUrlBtn.addEventListener('click', function() {
            const urlIndex = externalUrlsContainer.children.length;
            
            const urlItem = document.createElement('div');
            urlItem.className = 'external-url-item mb-4 p-4 border border-gray-200 rounded-lg relative';
            
            urlItem.innerHTML = `
                <button type="button" class="remove-url-btn absolute top-2 right-2 text-red-500 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="mb-3">
                    <label class="block text-dark font-medium mb-1">Image URL</label>
                    <input 
                        type="url" 
                        name="image_urls[]" 
                        class="form-input image-url-input" 
                        placeholder="https://example.com/image.jpg"
                        required
                    >
                </div>
                <div class="mb-3">
                    <label class="block text-dark font-medium mb-1">Image Title</label>
                    <input 
                        type="text" 
                        name="url_titles[]" 
                        class="form-input" 
                        placeholder="Enter image title"
                        required
                    >
                </div>
                <div>
                    <label class="block text-dark font-medium mb-1">Description (Optional)</label>
                    <textarea 
                        name="url_descriptions[]" 
                        rows="2" 
                        class="form-input" 
                        placeholder="Enter image description"
                    ></textarea>
                </div>
                <div class="mt-2">
                    <img class="url-preview hidden max-h-40 rounded mt-2" src="" alt="URL Preview">
                </div>
            `;
            
            // Add event listener to remove button
            const removeBtn = urlItem.querySelector('.remove-url-btn');
            removeBtn.addEventListener('click', function() {
                urlItem.remove();
            });
            
            externalUrlsContainer.appendChild(urlItem);
            
            // Add event listener to URL input for preview
            const urlInput = urlItem.querySelector('.image-url-input');
            const urlPreview = urlItem.querySelector('.url-preview');
            
            urlInput.addEventListener('blur', function() {
                if (this.value) {
                    urlPreview.src = this.value;
                    urlPreview.classList.remove('hidden');
                    urlPreview.onerror = function() {
                        urlPreview.classList.add('hidden');
                        alert('Unable to load image from URL. Please check the URL and ensure it points directly to an image file.');
                    };
                } else {
                    urlPreview.classList.add('hidden');
                }
            });
        });
        
        // Add preview functionality to first URL input
        const firstUrlInput = document.querySelector('.image-url-input');
        if (firstUrlInput) {
            const firstUrlPreview = document.createElement('img');
            firstUrlPreview.className = 'url-preview hidden max-h-40 rounded mt-2';
            firstUrlInput.parentElement.parentElement.appendChild(firstUrlPreview);
            
            firstUrlInput.addEventListener('blur', function() {
                if (this.value) {
                    firstUrlPreview.src = this.value;
                    firstUrlPreview.classList.remove('hidden');
                    firstUrlPreview.onerror = function() {
                        firstUrlPreview.classList.add('hidden');
                        alert('Unable to load image from URL. Please check the URL and ensure it points directly to an image file.');
                    };
                } else {
                    firstUrlPreview.classList.add('hidden');
                }
            });
        }
        
        // Apply default title and description to all images
        const defaultTitleInput = document.getElementById('default_title');
        const defaultDescInput = document.getElementById('default_description');
        
        defaultTitleInput.addEventListener('input', function() {
            const titleInputs = document.querySelectorAll('input[name^="titles["]');
            titleInputs.forEach(input => {
                if (!input.value) {
                    input.value = this.value;
                }
            });
        });
        
        defaultDescInput.addEventListener('input', function() {
            const descInputs = document.querySelectorAll('textarea[name^="descriptions["]');
            descInputs.forEach(textarea => {
                if (!textarea.value) {
                    textarea.value = this.value;
                }
            });
        });
        
        // Form validation
        const galleryForm = document.getElementById('galleryForm');
        galleryForm.addEventListener('submit', function(e) {
            const venueId = document.getElementById('venue_id').value;
            if (!venueId) {
                e.preventDefault();
                alert('Please select a venue');
                return;
            }
            
            if (sourceLocalRadio.checked) {
                // Check if any images are selected
                if (imagesInput.files.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one image to upload');
                    return;
                }
            } else if (sourceExternalRadio.checked) {
                // Check if any URLs are entered
                const urlInputs = document.querySelectorAll('.image-url-input');
                let hasValidUrl = false;
                
                urlInputs.forEach(input => {
                    if (input.value) {
                        hasValidUrl = true;
                    }
                });
                
                if (!hasValidUrl) {
                    e.preventDefault();
                    alert('Please enter at least one image URL');
                    return;
                }
            }
        });
    });
</script>
@endpush
@endsection