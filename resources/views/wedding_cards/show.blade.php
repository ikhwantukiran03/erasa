@extends('layouts.app')

@section('title', $card->bride_name . ' & ' . $card->groom_name . ' Wedding Invitation')

@section('content')
<div class="min-h-screen pb-16">
    <!-- Template specific styling -->
    @if($card->template_id == 1)
    <div class="bg-cover bg-center py-10" style="background-image: url('{{ $imageUrls['backgrounds']['floral-bg'] ?? asset('assets/wedding-backgrounds/floral-bg.jpg') }}');">
    @elseif($card->template_id == 2)
    <div class="bg-gray-50 py-10" style="background-image: url('{{ $imageUrls['backgrounds']['default-bg'] ?? '' }}'); background-size: cover; background-position: center;">
    @elseif($card->template_id == 3)
    <div class="bg-cover bg-center py-10" style="background-image: url('{{ $imageUrls['backgrounds']['rustic-bg'] ?? asset('assets/wedding-backgrounds/rustic-bg.jpg') }}');">
    @elseif($card->template_id == 4)
    <div class="bg-cover bg-center py-10" style="background-image: url('{{ $imageUrls['backgrounds']['gold-bg'] ?? asset('assets/wedding-backgrounds/gold-bg.jpg') }}');">
    @elseif($card->template_id == 5)
    <div class="bg-cover bg-center py-10" style="background-image: url('{{ $imageUrls['backgrounds']['vintage-bg'] ?? asset('assets/wedding-backgrounds/vintage-bg.jpg') }}');">
    @else
    <div class="bg-light py-10" style="background-image: url('{{ $imageUrls['backgrounds']['default-bg'] ?? '' }}'); background-size: cover; background-position: center;">
    @endif
        <div class="container mx-auto px-4">
            @if(Auth::check() && Auth::id() == $card->user_id)
            <div class="max-w-5xl mx-auto mb-8 bg-white bg-opacity-90 rounded-lg shadow-soft p-4">
                <div class="flex justify-between items-center">
                    <a href="{{ route('wedding-cards.index') }}" class="text-primary hover:text-primary-dark font-medium inline-flex items-center transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to My Cards
                    </a>
                    <div class="flex space-x-4">
                        <a href="{{ route('wedding-cards.edit', $card->uuid) }}" class="btn-primary inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Card
                        </a>
                        <div class="relative inline-block text-left" id="shareDropdown">
                            <button type="button" class="bg-primary hover:bg-primary-dark text-white inline-flex items-center px-4 py-2 rounded-lg transition-colors duration-300" id="shareDropdownButton">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                Share
                            </button>
                            <div class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="shareMenu">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <div class="px-4 py-2 text-sm text-gray-700">
                                        <p class="mb-2 font-medium">Share this invitation:</p>
                                        <div class="flex items-center bg-gray-100 rounded-lg p-2">
                                            <input type="text" id="share-link" value="{{ route('wedding-cards.show', $card->uuid) }}" class="w-full bg-transparent border-none focus:outline-none text-sm" readonly>
                                            <button onclick="copyShareLink()" class="ml-2 text-primary hover:text-primary-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 border-t border-gray-100">
                                        <p class="text-sm text-gray-500 mb-2">Share on social media:</p>
                                        <div class="flex justify-center space-x-4">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('wedding-cards.show', $card->uuid)) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                                </svg>
                                            </a>
                                            <a href="https://wa.me/?text={{ urlencode('You are invited to our wedding! ' . route('wedding-cards.show', $card->uuid)) }}" target="_blank" class="text-green-600 hover:text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                </svg>
                                            </a>
                                            <a href="mailto:?subject={{ urlencode('Wedding Invitation: ' . $card->bride_name . ' & ' . $card->groom_name) }}&body={{ urlencode('You are invited to our wedding! View the invitation here: ' . route('wedding-cards.show', $card->uuid)) }}" class="text-red-600 hover:text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Wedding Card Display -->
            <div class="max-w-3xl mx-auto">
                @if($card->template_id == 1)
                <!-- Elegant Floral Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden">
                    <div class="bg-cover bg-center h-32 relative" style="background-image: url('{{ $imageUrls['headers']['1-header'] ?? asset('assets/wedding-templates/1-header.jpg') }}');">
                        <div class="absolute inset-0 bg-primary bg-opacity-30 flex items-center justify-center">
                            <div class="text-white text-center">
                                <div class="text-sm uppercase tracking-wider">We are getting married</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <div class="font-display text-4xl mb-4 text-primary">{{ $card->bride_name }} <span class="text-2xl">&</span> {{ $card->groom_name }}</div>
                        <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
                        
                        <div class="mb-8 text-lg">
                            <div class="mb-1">Request the pleasure of your company</div>
                            <div class="mb-1">on their wedding day</div>
                            <div class="font-semibold text-xl my-3">{{ $card->wedding_date->format('l, F j, Y') }}</div>
                            <div>at {{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="font-display text-2xl mb-2">{{ $card->venue_name }}</div>
                            <div class="text-gray-600">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 italic text-gray-700 border-t border-b border-gray-200 py-4">
                            "{{ $card->custom_message }}"
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <div class="mb-1 text-lg">Please RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-600">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                
                @elseif($card->template_id == 2)
                <!-- Modern Minimalist Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="uppercase tracking-widest text-xs mb-6 text-gray-500">Wedding Invitation</div>
                        <div class="font-display text-5xl mb-4">{{ $card->bride_name }} & {{ $card->groom_name }}</div>
                        <div class="w-16 h-0.5 bg-gray-300 mx-auto mb-6"></div>
                        
                        <div class="mb-8">
                            <div class="uppercase tracking-wider text-sm mb-2">{{ $card->wedding_date->format('F j, Y') }}</div>
                            <div>{{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="font-medium uppercase tracking-wider text-sm mb-2">Venue</div>
                            <div class="text-xl mb-1">{{ $card->venue_name }}</div>
                            <div class="text-gray-600 text-sm">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 text-gray-700 max-w-md mx-auto">
                            {{ $card->custom_message }}
                        </div>
                        @endif
                        
                        <div class="border-t border-gray-100 pt-6 mt-8">
                            <div class="uppercase tracking-wider text-xs mb-2 text-gray-500">RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-600">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                
                @elseif($card->template_id == 3)
                <!-- Rustic Romance Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden border border-gray-200">
                    <div class="bg-cover bg-center h-40" style="background-image: url('{{ $imageUrls['headers']['3-header'] ?? asset('assets/wedding-templates/3-header.jpg') }}');">
                    </div>
                    <div class="p-8 text-center">
                        <div class="font-display text-3xl mb-2 text-primary">Together with their families</div>
                        <div class="font-display text-4xl mb-4">{{ $card->bride_name }} & {{ $card->groom_name }}</div>
                        <div class="text-gray-700 mb-6">invite you to celebrate their marriage</div>
                        
                        <div class="mb-8">
                            <div class="font-display text-2xl mb-2">{{ $card->wedding_date->format('l, F j, Y') }}</div>
                            <div class="text-xl">{{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                        </div>
                        
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg inline-block">
                            <div class="font-display text-2xl mb-2">{{ $card->venue_name }}</div>
                            <div class="text-gray-600">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 italic text-gray-700 max-w-lg mx-auto border-t border-b border-gray-200 py-4">
                            {{ $card->custom_message }}
                        </div>
                        @endif
                        
                        <div class="mb-4 bg-primary bg-opacity-10 rounded-lg p-4 inline-block">
                            <div class="mb-1 font-display text-lg">RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-600">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                
                @elseif($card->template_id == 4)
                <!-- Classic Gold Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden" style="background-image: url('{{ $imageUrls['headers']['4-bg'] ?? asset('assets/wedding-templates/4-bg.jpg') }}'); background-size: 100% 100%;">
                    <div class="p-10 text-center">
                        <div class="text-sm uppercase tracking-widest mb-6 text-gray-700">Wedding Invitation</div>
                        <div class="font-display text-4xl mb-2 text-primary">{{ $card->bride_name }}</div>
                        <div class="font-display text-2xl mb-2">&</div>
                        <div class="font-display text-4xl mb-4 text-primary">{{ $card->groom_name }}</div>
                        <div class="w-24 h-0.5 bg-primary mx-auto mb-6"></div>
                        
                        <div class="mb-8 text-lg">
                            <div class="mb-2">We request the honor of your presence</div>
                            <div class="mb-1">at the celebration of our marriage</div>
                            <div class="font-semibold text-xl my-3">{{ $card->wedding_date->format('l, F j, Y') }}</div>
                            <div>at {{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="font-display text-2xl mb-2 text-primary">{{ $card->venue_name }}</div>
                            <div class="text-gray-700">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 italic text-gray-700 max-w-lg mx-auto border-t border-b border-gray-200 py-4">
                            {{ $card->custom_message }}
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <div class="mb-1 text-lg text-primary">RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-700">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                
                @elseif($card->template_id == 5)
                <!-- Vintage Lace Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden">
                    <div class="bg-cover bg-center h-24" style="background-image: url('{{ $imageUrls['headers']['5-header'] ?? asset('assets/wedding-templates/5-header.jpg') }}');">
                    </div>
                    <div class="p-8 text-center">
                        <div class="font-display text-4xl mb-2">{{ $card->bride_name }} & {{ $card->groom_name }}</div>
                        <div class="text-gray-600 italic mb-6">are getting married</div>
                        
                        <div class="border-4 border-gray-100 p-6 mb-8">
                            <div class="font-display text-2xl mb-2">{{ $card->wedding_date->format('F j, Y') }}</div>
                            <div class="text-lg mb-4">{{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                            
                            <div class="font-display text-xl mb-1">{{ $card->venue_name }}</div>
                            <div class="text-gray-600 text-sm">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 italic text-gray-700">
                            "{{ $card->custom_message }}"
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 p-4 rounded-lg inline-block">
                            <div class="mb-1 font-display">RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-600">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                
                @else
                <!-- Default Template -->
                <div class="bg-white rounded-lg shadow-medium overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="font-display text-4xl mb-4">{{ $card->bride_name }} & {{ $card->groom_name }}</div>
                        <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
                        
                        <div class="mb-8">
                            <div class="text-lg mb-2">We're getting married!</div>
                            <div class="font-semibold text-xl my-3">{{ $card->wedding_date->format('l, F j, Y') }}</div>
                            <div>at {{ date('g:i A', strtotime($card->ceremony_time)) }}</div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="font-display text-2xl mb-2">{{ $card->venue_name }}</div>
                            <div class="text-gray-600">{{ $card->venue_address }}</div>
                        </div>
                        
                        @if($card->custom_message)
                        <div class="mb-8 italic text-gray-700">
                            "{{ $card->custom_message }}"
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <div class="mb-1 text-lg">Please RSVP</div>
                            <div class="font-medium">{{ $card->rsvp_contact_name }}</div>
                            <div class="text-gray-600">{{ $card->rsvp_contact_info }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-display font-bold text-dark mb-6">Wishes & Comments</h2>
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-soft p-6 mb-8">
                <h3 class="text-lg font-medium text-dark mb-4">Leave a Comment</h3>
                <form action="{{ route('wedding-cards.comment', $card->uuid) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="font-medium text-gray-700 mb-2 block">Your Name</label>
                            <input type="text" name="commenter_name" value="{{ old('commenter_name') }}" class="form-input" placeholder="Enter your name">
                            @error('commenter_name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="font-medium text-gray-700 mb-2 block">Your Email (Optional)</label>
                            <input type="email" name="commenter_email" value="{{ old('commenter_email') }}" class="form-input" placeholder="Enter your email">
                            @error('commenter_email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="font-medium text-gray-700 mb-2 block">Your Wishes</label>
                        <textarea name="comment" class="form-input" rows="3" placeholder="Write your wishes or comment here">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="btn-primary">
                            Submit Comment
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Comments List -->
            @if($comments->count() > 0)
                <div class="space-y-4">
                    @foreach($comments as $comment)
                    <div class="bg-white rounded-lg shadow-soft p-4 border-l-4 border-primary">
                        <div class="flex justify-between items-start mb-2">
                            <div class="font-medium text-dark">{{ $comment->commenter_name }}</div>
                            <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-gray-600">{{ $comment->comment }}</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No comments yet. Be the first to leave wishes for the couple!</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Share dropdown
        const shareDropdownButton = document.getElementById('shareDropdownButton');
        const shareMenu = document.getElementById('shareMenu');
        
        if (shareDropdownButton && shareMenu) {
            shareDropdownButton.addEventListener('click', function() {
                shareMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!shareDropdownButton.contains(event.target) && !shareMenu.contains(event.target)) {
                    shareMenu.classList.add('hidden');
                }
            });
        }
    });
    
    function copyShareLink() {
        const shareInput = document.getElementById('share-link');
        shareInput.select();
        document.execCommand('copy');
        
        // Show feedback
        const originalText = shareInput.nextElementSibling.innerHTML;
        shareInput.nextElementSibling.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        `;
        
        setTimeout(function() {
            shareInput.nextElementSibling.innerHTML = originalText;
        }, 2000);
    }
</script>
@endpush
@endsection 