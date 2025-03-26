@extends('layouts.app')

@section('title', 'Manage Gallery - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Gallery</h1>
                <p class="text-gray-600 mt-2">View and manage venue gallery images</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-primary hover:underline">Back to Admin Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Gallery Images</h2>
                <a href="{{ route('admin.galleries.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Add New Image</a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Filter by venue -->
            <div class="px-6 py-4 border-b border-gray-200">
                <form action="{{ route('admin.galleries.index') }}" method="GET" class="flex items-center">
                    <label for="venue_filter" class="mr-2">Filter by Venue:</label>
                    <select id="venue_filter" name="venue_id" class="form-input mr-2 w-64">
                        <option value="">All Venues</option>
                        @foreach(\App\Models\Venue::all() as $venue)
                            <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Filter</button>
                    @if(request('venue_id'))
                        <a href="{{ route('admin.galleries.index') }}" class="ml-2 text-primary hover:underline">Clear Filter</a>
                    @endif
                </form>
            </div>
            
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($galleries as $gallery)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                            <div class="relative h-64 overflow-hidden bg-gray-200">
                                @if($gallery->source === 'local' && $gallery->image_path)
                                    <img 
                                        src="{{ asset('storage/' . $gallery->image_path) }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="w-full h-full object-cover"
                                    >
                                @elseif($gallery->source === 'external' && $gallery->image_url)
                                    <img 
                                        src="{{ $gallery->image_url }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="w-full h-full object-cover"
                                        onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                                    >
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-gray-400">No image available</span>
                                    </div>
                                @endif
                                
                                @if($gallery->is_featured)
                                    <div class="absolute top-2 left-2 bg-primary text-white px-2 py-1 text-xs rounded">
                                        Featured
                                    </div>
                                @endif
                                
                                <div class="absolute top-2 right-2 flex space-x-1">
                                    <form action="{{ route('admin.galleries.toggleFeatured', $gallery) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-white p-1 rounded shadow hover:bg-gray-100" title="{{ $gallery->is_featured ? 'Remove from featured' : 'Mark as featured' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $gallery->is_featured ? 'text-yellow-500' : 'text-gray-400' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-800 truncate">{{ $gallery->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Venue:</span> {{ $gallery->venue->name }}
                                </p>
                                @if($gallery->description)
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $gallery->description }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mb-3">
                                    Source: {{ $gallery->source === 'local' ? 'Uploaded file' : 'External URL' }}
                                </p>
                                
                                <div class="flex justify-between mt-2">
                                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                    <a href="{{ route('admin.galleries.show', $gallery) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4">No gallery images have been added yet.</p>
                    <a href="{{ route('admin.galleries.create') }}" class="mt-4 inline-block text-primary hover:underline">Add your first gallery image</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form when venue filter changes
        document.getElementById('venue_filter').addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush