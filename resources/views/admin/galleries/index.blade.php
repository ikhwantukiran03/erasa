@extends('layouts.app')

@section('title', 'Gallery Management - Enak Rasa Wedding Hall')

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
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Galleries</span>
                    </div>
                </li>
            </ol>
        </nav>
        <!-- Simple Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gallery Management</h1>
            <a href="{{ route('admin.galleries.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Images
            </a>
        </div>
        
        <!-- Simple Filter -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-5">
            <form action="{{ route('admin.galleries.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-grow max-w-xs">
                    <label for="venue_filter" class="block text-sm font-medium text-gray-600 mb-1">Filter by Venue</label>
                    <select id="venue_filter" name="venue_id" class="w-full rounded-md border-gray-300 focus:border-primary">
                        <option value="">All Venues</option>
                        @foreach(\App\Models\Venue::orderBy('name')->get() as $venue)
                            <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow max-w-xs">
                    <label for="featured_filter" class="block text-sm font-medium text-gray-600 mb-1">Featured Status</label>
                    <select id="featured_filter" name="featured" class="w-full rounded-md border-gray-300 focus:border-primary">
                        <option value="">All Images</option>
                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    
                    @if(request('venue_id') || request('featured'))
                        <a href="{{ route('admin.galleries.index') }}" class="text-primary hover:underline py-2">Clear Filter</a>
                    @endif
                </div>
            </form>
        </div>
        
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Gallery Grid -->
        <div class="bg-white rounded-lg shadow-sm p-5">
            @if($galleries->count() > 0)
                <!-- Bulk Actions -->
                <form action="{{ route('admin.galleries.bulkFeature') }}" method="POST" id="bulkActionsForm" class="mb-4">
                    @csrf
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700">Select All</label>
                        </div>
                        <div class="flex-grow">
                            <select name="action" id="bulkAction" class="text-sm rounded-md border-gray-300 focus:border-primary py-1 px-3">
                                <option value="">-- Choose Action --</option>
                                <option value="feature">Mark as Featured</option>
                                <option value="unfeature">Remove from Featured</option>
                            </select>
                            <button type="submit" id="applyAction" class="bg-gray-200 text-gray-700 text-sm rounded py-1 px-3 hover:bg-gray-300 transition ml-2" disabled>
                                Apply
                            </button>
                        </div>
                    </div>
                </form>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($galleries as $gallery)
                        <div class="group relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                            <!-- Checkbox for bulk actions -->
                            <div class="absolute top-2 right-2 z-10">
                                <input type="checkbox" name="gallery_ids[]" value="{{ $gallery->id }}" form="bulkActionsForm"
                                    class="gallery-checkbox form-checkbox h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                            </div>
                            
                            <!-- Image -->
                            <div class="aspect-w-16 aspect-h-10 w-full overflow-hidden bg-gray-100">
                                @if(($gallery->source === 'local' && $gallery->image_path) || ($gallery->source === 'external' && $gallery->image_url))
                                    <img 
                                        src="{{ $gallery->source === 'local' ? $gallery->image_path : $gallery->image_url }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="w-full h-full object-cover"
                                        onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                                    >
                                    <!-- Overlay with actions -->
                                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-4">
                                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="bg-white text-blue-500 p-2 rounded-full hover:bg-blue-500 hover:text-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.galleries.toggleFeatured', $gallery) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-white {{ $gallery->is_featured ? 'text-yellow-500' : 'text-gray-400' }} p-2 rounded-full hover:bg-yellow-500 hover:text-white transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-white text-red-500 p-2 rounded-full hover:bg-red-500 hover:text-white transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span>No image</span>
                                    </div>
                                @endif
                                
                                <!-- Featured badge -->
                                @if($gallery->is_featured)
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-yellow-400 text-xs text-white px-2 py-1 rounded shadow-sm">
                                            Featured
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Simple Info -->
                            <div class="p-3">
                                <h3 class="font-medium text-gray-800 truncate" title="{{ $gallery->title }}">
                                    {{ $gallery->title ?: 'Untitled' }}
                                </h3>
                                <p class="text-xs text-gray-500 truncate mt-1" title="{{ $gallery->venue->name }}">
                                    {{ $gallery->venue->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($galleries instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">
                    {{ $galleries->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-500">No images found</h3>
                    <p class="text-gray-400 mt-1">{{ request('venue_id') ? 'Try a different filter' : 'Add your first gallery image' }}</p>
                    
                    <a href="{{ route('admin.galleries.create') }}" class="inline-block mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Add New Images
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Quick Summary -->
        <div class="mt-6 flex justify-end">
            <div class="text-sm text-gray-500">
                Showing {{ $galleries->count() }} {{ Str::plural('image', $galleries->count()) }}
                @if($galleries->where('is_featured', true)->count() > 0)
                ({{ $galleries->where('is_featured', true)->count() }} featured)
                @endif
            </div>
        </div>
        
        
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form when venue filter changes
        document.getElementById('venue_filter').addEventListener('change', function() {
            this.form.submit();
        });
        
        // Auto-submit the form when featured filter changes
        document.getElementById('featured_filter').addEventListener('change', function() {
            this.form.submit();
        });
        
        // Bulk actions functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const galleryCheckboxes = document.querySelectorAll('.gallery-checkbox');
        const bulkActionSelect = document.getElementById('bulkAction');
        const applyActionBtn = document.getElementById('applyAction');
        
        // Handle select all checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                galleryCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateApplyButton();
            });
        }
        
        // Handle individual checkboxes
        galleryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateApplyButton();
                
                // Update select all checkbox
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Check if all checkboxes are checked
                    const allChecked = [...galleryCheckboxes].every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
        
        // Handle bulk action select
        bulkActionSelect.addEventListener('change', updateApplyButton);
        
        // Function to enable/disable the apply button
        function updateApplyButton() {
            const checkedCount = [...galleryCheckboxes].filter(cb => cb.checked).length;
            const hasAction = bulkActionSelect.value !== '';
            
            applyActionBtn.disabled = !(checkedCount > 0 && hasAction);
            
            // Update button state visually
            if (applyActionBtn.disabled) {
                applyActionBtn.classList.add('bg-gray-200', 'text-gray-700');
                applyActionBtn.classList.remove('bg-primary', 'text-white');
            } else {
                applyActionBtn.classList.remove('bg-gray-200', 'text-gray-700');
                applyActionBtn.classList.add('bg-primary', 'text-white');
            }
        }
        
        // Prevent form submission if no checkboxes are selected
        document.getElementById('bulkActionsForm').addEventListener('submit', function(e) {
            const checkedCount = [...galleryCheckboxes].filter(cb => cb.checked).length;
            if (checkedCount === 0 || bulkActionSelect.value === '') {
                e.preventDefault();
                alert('Please select at least one image and an action to apply.');
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Fix aspect ratio for image containers */
    .aspect-w-16 {
        position: relative;
        padding-bottom: 62.5%; /* 10/16 = 0.625 */
    }
    
    .aspect-w-16 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    
    /* Pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
    }
    
    .pagination > * {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        background-color: #f3f4f6;
        color: #4b5563;
    }
    
    .pagination a:hover {
        background-color: #e5e7eb;
    }
    
    .pagination .active {
        background-color: #4f46e5;
        color: white;
    }
</style>
@endpush
@endsection