@extends('layouts.app')

@section('title', 'Edit Wedding Card')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('wedding-cards.index') }}" class="text-primary hover:text-primary-dark font-medium inline-flex items-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Cards
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-soft p-8">
            <h1 class="text-3xl font-display font-bold text-dark mb-6">Edit Wedding Card</h1>
            
            <form action="{{ route('wedding-cards.update', $card->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">Groom's Name</label>
                        <input type="text" name="groom_name" value="{{ old('groom_name', $card->groom_name) }}" class="form-input" placeholder="Enter groom's name">
                        @error('groom_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">Bride's Name</label>
                        <input type="text" name="bride_name" value="{{ old('bride_name', $card->bride_name) }}" class="form-input" placeholder="Enter bride's name">
                        @error('bride_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">Wedding Date</label>
                        <input type="date" name="wedding_date" value="{{ old('wedding_date', $card->wedding_date->format('Y-m-d')) }}" class="form-input">
                        @error('wedding_date')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="ceremony_time" class="block text-sm font-medium text-gray-700">Ceremony Time</label>
                        <input type="time" name="ceremony_time" id="ceremony_time" value="{{ old('ceremony_time', $card->ceremony_time) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">Venue</label>
                        <select name="venue_id" id="venue_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                            <option value="">Select a venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ old('venue_id', $card->venue_name == $venue->name ? $venue->id : '') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">RSVP Contact Name</label>
                        <input type="text" name="rsvp_contact_name" id="rsvp_contact_name" value="{{ old('rsvp_contact_name', $card->rsvp_contact_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="font-medium text-gray-700 mb-2 block">RSVP Contact Info</label>
                        <input type="text" name="rsvp_contact_info" value="{{ old('rsvp_contact_info', $card->rsvp_contact_info) }}" class="form-input" placeholder="Enter phone number or email">
                        @error('rsvp_contact_info')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-8">
                    <label class="font-medium text-gray-700 mb-2 block">Custom Message (Optional)</label>
                    <textarea name="custom_message" class="form-input" rows="4" placeholder="Enter a custom message for your invitation">{{ old('custom_message', $card->custom_message) }}</textarea>
                    @error('custom_message')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-8">
                    <label class="font-medium text-gray-700 mb-2 block">Select Template</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        @foreach($templates as $id => $name)
                        <div class="relative">
                            <input type="radio" name="template_id" value="{{ $id }}" id="template-{{ $id }}" class="sr-only" {{ old('template_id', $card->template_id) == $id ? 'checked' : '' }}>
                            <label for="template-{{ $id }}" class="block cursor-pointer">
                                <div class="border-2 rounded-lg p-2 overflow-hidden transition-all duration-200 hover:shadow-medium" id="template-container-{{ $id }}">
                                    <img src="{{ $imageUrls['templates'][$id] }}" alt="{{ $name }}" class="w-full h-48 object-cover rounded">
                                    <div class="mt-2 text-center py-2 font-medium">{{ $name }}</div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('template_id')
                        <p class="error-message mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-between">
                    <a href="{{ route('wedding-cards.show', $card->uuid) }}" class="text-primary hover:text-primary-dark font-medium">
                        Cancel and View Card
                    </a>
                    <button type="submit" class="btn-primary px-8 py-3">
                        Update Wedding Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateRadios = document.querySelectorAll('input[name="template_id"]');
        
        templateRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset all borders
                document.querySelectorAll('[id^="template-container-"]').forEach(container => {
                    container.classList.remove('border-primary');
                    container.classList.add('border-gray-200');
                });
                
                // Highlight selected
                if (this.checked) {
                    const container = document.getElementById('template-container-' + this.value);
                    container.classList.remove('border-gray-200');
                    container.classList.add('border-primary');
                }
            });
            
            // Initialize on load
            if (radio.checked) {
                const container = document.getElementById('template-container-' + radio.value);
                container.classList.remove('border-gray-200');
                container.classList.add('border-primary');
            }
        });
        
        // Initialize selected template
        const selectedTemplateId = {{ old('template_id', $card->template_id) }};
        const selectedContainer = document.getElementById('template-container-' + selectedTemplateId);
        if (selectedContainer) {
            selectedContainer.classList.remove('border-gray-200');
            selectedContainer.classList.add('border-primary');
        }
    });
</script>
@endpush
@endsection 