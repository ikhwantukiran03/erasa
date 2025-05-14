@extends('layouts.app')

@section('title', 'My Wedding Cards')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-display font-bold text-dark">My Wedding Cards</h1>
            <a href="{{ route('wedding-cards.create') }}" class="btn-primary inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Card
            </a>
        </div>

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

        @if($cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cards as $card)
                <div class="bg-white rounded-lg shadow-soft overflow-hidden border border-gray-100 hover:shadow-medium transition-shadow duration-300">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-display font-semibold text-dark">{{ $card->bride_name }} & {{ $card->groom_name }}</h3>
                            <span class="text-xs font-medium px-2 py-1 bg-secondary rounded-full text-primary-dark">
                                Template #{{ $card->template_id }}
                            </span>
                        </div>
                        
                        <div class="text-gray-600 mb-4">
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $card->wedding_date->format('F j, Y') }}</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ date('g:i A', strtotime($card->ceremony_time)) }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $card->venue_name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 bg-gray-50 px-5 py-3 flex justify-between">
                        <a href="{{ route('wedding-cards.show', $card->uuid) }}" class="text-primary hover:text-primary-dark font-medium text-sm transition-colors duration-300">
                            View Card
                        </a>
                        <div class="flex space-x-3">
                            <a href="{{ route('wedding-cards.edit', $card->uuid) }}" class="text-gray-600 hover:text-dark text-sm transition-colors duration-300">
                                Edit
                            </a>
                            <form action="{{ route('wedding-cards.destroy', $card->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wedding card?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm transition-colors duration-300">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $cards->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-soft p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                </svg>
                <h3 class="text-xl font-display font-semibold text-dark mb-2">No Wedding Cards Yet</h3>
                <p class="text-gray-600 mb-6">Create your first wedding card invitation to share with your guests.</p>
                <a href="{{ route('wedding-cards.create') }}" class="btn-primary inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Card
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 