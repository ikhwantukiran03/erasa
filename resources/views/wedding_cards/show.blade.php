@extends('layouts.app')

@section('title', $card->groom_name . ' & ' . $card->bride_name)

@section('content')
<div class="container mx-auto px-4 py-8 bg-gray-50 min-h-screen">
    <div class="max-w-lg mx-auto relative bg-white rounded-2xl shadow-lg p-0 overflow-hidden" style="background: #fff;">
        <div class="relative" style="background: #fff;">
            <img src="{{ $imageUrls['templates'][$card->template_id] }}" alt="Template" class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none" style="z-index:1; opacity:1;">
            <div class="relative z-10 px-6 py-16 text-center flex flex-col justify-center" style="min-height:750px;">
                <div class="mb-4 text-base tracking-widest text-gray-700 font-semibold uppercase" style="letter-spacing:2px; font-family: 'Montserrat', 'Arial', sans-serif;">JEMPUTAN KE<br>MAJLIS PERKAHWINAN</div>
                <div class="mb-2 text-4xl md:text-5xl font-display font-bold text-black leading-tight" style="font-family: 'Caveat', cursive; word-break: break-word;">{{ $card->bride_name }}</div>
                <div class="mb-2 text-2xl md:text-3xl font-display text-black" style="font-family: 'Caveat', cursive;">&amp;</div>
                <div class="mb-6 text-4xl md:text-5xl font-display font-bold text-black leading-tight" style="font-family: 'Caveat', cursive; word-break: break-word;">{{ $card->groom_name }}</div>
                <div class="flex justify-center items-center gap-8 mb-6 text-gray-700 font-medium text-lg md:text-xl">
                    <div>
                        <div>SATURDAY</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold tracking-widest">{{ strtoupper(\Carbon\Carbon::parse($card->wedding_date)->format('M')) }}</div>
                        <div class="text-3xl font-bold">{{ \Carbon\Carbon::parse($card->wedding_date)->format('d') }}</div>
                        <div>{{ \Carbon\Carbon::parse($card->wedding_date)->format('Y') }}</div>
                    </div>
                    <div>
                        <div>{{ \Carbon\Carbon::parse($card->ceremony_time)->format('h.i A') }}</div>
                    </div>
                </div>
                <div class="mb-6 text-gray-700 mx-auto" style="font-size:1rem; max-width: 98%; word-break: break-word; line-height: 1.6; font-family: 'Montserrat', 'Arial', sans-serif;">
                    {{ $card->venue_name }}<br>
                    @php
                        // Try to extract city and state if possible
                        $address = $card->venue_address;
                        $cityState = '';
                        if (preg_match('/,\s*([^,]+),\s*([^,]+)$/', $address, $matches)) {
                            $mainAddress = trim(str_replace($matches[0], '', $address), ', ');
                            $cityState = $matches[1] . ', ' . $matches[2];
                        } else {
                            $mainAddress = $address;
                        }
                    @endphp
                    {{ $mainAddress }}<br>
                    @if($cityState)
                        <span>{{ $cityState }}</span>
                        @endif
                </div>
                        @if($card->custom_message)
                <div class="mb-4 italic text-gray-600 text-base" style="font-family: 'Montserrat', 'Arial', sans-serif;">{{ $card->custom_message }}</div>
                @endif
                <div class="mb-2 text-gray-700 text-sm" style="font-family: 'Montserrat', 'Arial', sans-serif;">RSVP: {{ $card->rsvp_contact_name }} ({{ $card->rsvp_contact_info }})</div>
            </div>
        </div>
    </div>
    @if(Auth::check() && Auth::id() === $card->user_id)
    <div class="max-w-lg mx-auto mt-8 flex flex-wrap gap-2 justify-center">
        <a href="{{ route('wedding-cards.index') }}" class="btn-secondary">Back to My Cards</a>
        <a href="{{ route('wedding-cards.edit', $card->uuid) }}" class="btn-secondary">Edit Card</a>
        <button onclick="window.print()" class="btn-primary">Print</button>
        <button id="shareBtn" class="btn-primary">Share</button>
        <a id="waShareBtn" class="btn-primary flex items-center gap-1" target="_blank" rel="noopener">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.97L0 24l6.22-1.62A11.93 11.93 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.2-1.25-6.21-3.48-8.52zM12 22c-1.85 0-3.67-.5-5.24-1.44l-.37-.22-3.69.96.99-3.59-.24-.37A9.94 9.94 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.6c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.12-.12.28-.32.42-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.34-.01-.52-.01-.18 0-.48.07-.73.34-.25.27-.97.95-.97 2.3s.99 2.67 1.13 2.85c.14.18 1.95 2.98 4.74 4.06.66.23 1.18.37 1.58.47.66.17 1.26.15 1.73.09.53-.08 1.65-.67 1.89-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/></svg>
            WhatsApp
        </a>
    </div>
    @endif
    <!-- Comments Section -->
    <div class="max-w-lg mx-auto mt-12 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-center">Guest Messages</h2>
        @foreach($comments as $comment)
            <div class="mb-4 p-4 bg-gray-50 rounded shadow-sm">
                <div class="font-semibold text-primary">{{ $comment->commenter_name }}</div>
                <div class="text-gray-700">{{ $comment->comment }}</div>
            </div>
        @endforeach
        <form action="{{ route('wedding-cards.add-comment', $card->uuid) }}" method="POST" class="mt-6">
            @csrf
            <div class="mb-2">
                <input type="text" name="commenter_name" class="form-input w-full" placeholder="Your Name" required>
            </div>
            <div class="mb-2">
                <input type="email" name="commenter_email" class="form-input w-full" placeholder="Email (optional)">
                        </div>
            <div class="mb-2">
                <textarea name="comment" class="form-input w-full" rows="2" placeholder="Your message..." required></textarea>
            </div>
            <button type="submit" class="btn-primary w-full">Send Message</button>
        </form>
    </div>
</div>

<script>
document.getElementById('shareBtn').addEventListener('click', function() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(function() {
            alert('Card link copied to clipboard!');
            });
        }
    });
    
document.getElementById('waShareBtn').addEventListener('click', function(e) {
    e.preventDefault();
    const url = window.location.href;
    const text = encodeURIComponent('You are invited! View our wedding card: ' + url);
    window.open('https://wa.me/?text=' + text, '_blank');
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap');

body { background: #f9fafb; }
.btn-primary, .btn-secondary {
    border-radius: 0.5rem;
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    transition: background 0.2s, color 0.2s;
}
.btn-primary {
    background: #3b82f6;
    color: #fff;
    border: none;
}
.btn-primary:hover {
    background: #2563eb;
}
.btn-secondary {
    background: #fff;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}
.btn-secondary:hover {
    background: #3b82f6;
    color: #fff;
}
.form-input {
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    padding: 0.5rem 0.75rem;
    width: 100%;
    font-size: 1rem;
    margin-top: 0.25rem;
    margin-bottom: 0.25rem;
}
@media print {
    .btn-primary, .btn-secondary, nav, .mt-8, .mt-12 { display: none !important; }
    body { background: #fff !important; }
    .container { box-shadow: none !important; }
}
</style>
@endsection 