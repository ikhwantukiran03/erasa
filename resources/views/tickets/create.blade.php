@extends('layouts.app')

@section('title', 'Create Support Ticket - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-r from-primary/10 to-secondary/20 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div data-aos="fade-up">
                <h1 class="text-3xl font-display font-bold text-primary mb-1">Create Support Ticket</h1>
                <p class="text-gray-600">Submit a new support ticket</p>
            </div>
            <div class="mt-4 md:mt-0" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center text-primary hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Tickets
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">New Ticket</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('user.tickets.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                placeholder="Brief description of your issue"
                                value="{{ old('title') }}"
                                required
                            >
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                name="category"
                                id="category"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                required
                            >
                                <option value="">Select a category</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="booking" {{ old('category') === 'booking' ? 'selected' : '' }}>Booking Related</option>
                                <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Payment Issue</option>
                                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical Support</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="6"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                placeholder="Please provide detailed information about your issue..."
                                required
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 