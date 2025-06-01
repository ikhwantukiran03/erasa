@extends('layouts.app')

@section('title', 'Create Support Ticket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-primary rounded-lg hover:bg-primary hover:text-white transition font-medium shadow">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tickets
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-2xl mx-auto">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-primary/10 to-secondary/20">
            <h1 class="text-2xl font-display font-bold text-primary mb-1">Create Support Ticket</h1>
            <p class="text-gray-600 text-sm">Need help? Fill out the form below and our team will get back to you soon.</p>
        </div>

        <form action="{{ route('user.tickets.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- Ticket Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/30 focus:ring-opacity-50"
                    placeholder="E.g. Unable to access my booking" required>
                <p class="text-xs text-gray-500 mt-1">Briefly describe your issue in a few words.</p>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ticket Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category" id="category"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/30 focus:ring-opacity-50" required>
                    <option value="">Select a category</option>
                    <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                    <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical Support</option>
                    <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>Billing Issue</option>
                    <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Choose the category that best fits your issue.</p>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ticket Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="6"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/30 focus:ring-opacity-50"
                    placeholder="Please provide detailed information about your issue" required>{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Include as much detail as possible to help us assist you quickly.</p>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-2">
                <a href="{{ route('user.tickets.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 