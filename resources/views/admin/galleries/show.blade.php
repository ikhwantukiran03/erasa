@extends('layouts.app')

@section('title', $gallery->title . ' - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $gallery->title }}</h1>
                <p class="text-gray-600 mt-2">Gallery image details</p>
            </div>
            <a href="{{ route('admin.galleries.index') }}" class="text-primary hover:underline">Back to Gallery</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <!-- Image Preview -->
                    <div class="lg:col-span-3">
                        <div class="bg-gray-100 rounded-lg flex items-center justify-center p-4 h-full">
                            @if($gallery->source === 'local' && $gallery->image_path)
                                <img 
                                    src="{{ $gallery->image_path }}" 
                                    alt="{{ $gallery->title }}" 
                                    class="max-w-full max-h-96 rounded shadow"
                                    onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                                >
                            @elseif($gallery->source === 'external' && $gallery->image_url)
                                <img 
                                    src="{{ $gallery->image_url }}" 
                                    alt="{{ $gallery->title }}" 
                                    class="max-w-full max-h-96 rounded shadow"
                                    onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                                >
                            @else
                                <div class="text-gray-400 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p>No image available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Image Details -->
                    <div class="lg:col-span-2">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Image Information</h2>
                        
                        <div class="border-t border-gray-200 -mx-6"></div>
                        
                        <dl class="mt-4 space-y-4">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Venue</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->venue->name }}</dd>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->title }}</dd>
                            </div>
                            
                            @if($gallery->description)
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->description }}</dd>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Image Source</dt>
                                <dd class="text-sm text-gray-900 col-span-2">
                                    {{ $gallery->source === 'local' ? 'Uploaded file' : 'External URL' }}
                                </dd>
                            </div>
                            
                            @if($gallery->source === 'local' && $gallery->image_path)
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">Image URL</dt>
                                    <dd class="text-sm text-gray-900 col-span-2 break-all">{{ $gallery->image_path }}</dd>
                                </div>
                            @endif
                            
                            @if($gallery->source === 'external' && $gallery->image_url)
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">External URL</dt>
                                    <dd class="text-sm text-gray-900 col-span-2 break-all">
                                        <a href="{{ $gallery->image_url }}" target="_blank" class="text-primary hover:underline">
                                            {{ $gallery->image_url }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Featured</dt>
                                <dd class="text-sm text-gray-900 col-span-2">
                                    @if($gallery->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            No
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Display Order</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->display_order }}</dd>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->created_at->format('M d, Y H:i:s') }}</dd>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900 col-span-2">{{ $gallery->updated_at->format('M d, Y H:i:s') }}</dd>
                            </div>
                        </dl>
                        
                        <div class="mt-8 flex gap-3">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                                Edit Image
                            </a>
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                    Delete Image
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection