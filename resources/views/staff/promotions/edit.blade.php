@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Edit Promotion</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staff.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                       id="title" type="text" name="title" value="{{ old('title', $promotion->title) }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                          id="description" name="description" rows="4" required>{{ old('description', $promotion->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="discount">
                    Discount (%)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('discount') border-red-500 @enderror"
                       id="discount" type="number" name="discount" min="0" max="100" step="0.01" value="{{ old('discount', $promotion->discount) }}" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                        Start Date
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                           id="start_date" type="date" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror"
                           id="end_date" type="date" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Current Image
                </label>
                <img src="{{ $promotion->cloudinary_image_url }}" alt="{{ $promotion->title }}" class="h-32 w-32 object-cover rounded mb-2">
                
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    New Image (Optional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror"
                       id="image" type="file" name="image" accept="image/*">
                <p class="text-gray-600 text-xs mt-1">Leave empty to keep current image. Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</p>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Update Promotion
                </button>
                <a href="{{ route('staff.promotions.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 