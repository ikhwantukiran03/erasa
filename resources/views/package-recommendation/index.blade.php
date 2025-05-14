@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Find Your Perfect Package</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Answer a few questions and we'll recommend the best wedding packages for your needs</p>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 md:p-8">
                <form action="{{ route('package-recommendation.recommend') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Budget -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">What's your budget?</h2>
                        <div class="mt-2">
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Your budget (RM)</label>
                            <input type="number" name="budget" id="budget" min="0" step="100" 
                                class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('budget') border-red-500 @enderror" 
                                value="{{ old('budget', 5000) }}" required>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Guest Count -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">How many guests are you expecting?</h2>
                        <div class="mt-2">
                            <label for="guest_count" class="block text-sm font-medium text-gray-700 mb-1">Number of guests</label>
                            <input type="number" name="guest_count" id="guest_count" min="1" 
                                class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('guest_count') border-red-500 @enderror" 
                                value="{{ old('guest_count', 100) }}" required>
                            @error('guest_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Venue Preference -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Do you have a venue preference?</h2>
                        <div class="mt-2">
                            <label for="venue_preference" class="block text-sm font-medium text-gray-700 mb-1">Select venue (optional)</label>
                            <select name="venue_preference" id="venue_preference" 
                                class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">Any venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_preference') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Special Preferences -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">What features are most important to you?</h2>
                        <p class="text-sm text-gray-600 mb-3">Select all that apply</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="catering" name="preferences[]" value="catering" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('catering', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="catering" class="font-medium text-gray-700">Premium Catering</label>
                                    <p class="text-gray-500">High-quality food and beverages</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="decoration" name="preferences[]" value="decoration" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('decoration', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="decoration" class="font-medium text-gray-700">Decoration & Styling</label>
                                    <p class="text-gray-500">Professional decoration services</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="photography" name="preferences[]" value="photography" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('photography', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="photography" class="font-medium text-gray-700">Photography & Video</label>
                                    <p class="text-gray-500">Professional photo and video services</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="entertainment" name="preferences[]" value="entertainment" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('entertainment', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="entertainment" class="font-medium text-gray-700">Entertainment</label>
                                    <p class="text-gray-500">Music, performances, and activities</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="planning" name="preferences[]" value="planning" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('planning', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="planning" class="font-medium text-gray-700">Full Planning Service</label>
                                    <p class="text-gray-500">Professional wedding planners</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="accommodation" name="preferences[]" value="accommodation" type="checkbox" 
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                        {{ in_array('accommodation', old('preferences', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="accommodation" class="font-medium text-gray-700">Accommodation</label>
                                    <p class="text-gray-500">Rooms for wedding party or guests</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Get Recommendations
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 