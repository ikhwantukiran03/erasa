@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Your Personalized Recommendations</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Based on your budget of RM{{ number_format($budget) }} for {{ $guestCount }} guests
                @if($selectedVenue)
                    at {{ $selectedVenue->name }}
                @endif
            </p>
            <div class="mt-4">
                <a href="{{ route('package-recommendation.index') }}" class="text-primary hover:text-primary-dark font-medium flex items-center justify-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Change preferences</span>
                </a>
            </div>
        </div>

        @if(count($recommendations) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach($recommendations as $index => $recommendation)
                    @php
                        $package = $recommendation['package'];
                        $isTopPick = $index === 0;
                        $matchesBudget = $recommendation['matches_budget'];
                    @endphp
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg border {{ $isTopPick ? 'border-primary' : 'border-transparent' }} transform transition-all hover:-translate-y-1 hover:shadow-xl">
                        @if($isTopPick)
                            <div class="bg-primary text-white text-center py-2 font-medium">
                                Top Recommendation
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-bold text-gray-800">{{ $package->name }}</h2>
                                <div class="text-xs px-2 py-1 rounded-full {{ $matchesBudget ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $matchesBudget ? 'Within Budget' : 'Over Budget' }}
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="text-2xl font-bold text-primary">
                                    RM{{ number_format($recommendation['price']) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $package->venue->name }}
                                </div>
                            </div>
                            
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-gray-700">Match score: {{ $recommendation['score'] }}%</span>
                                </div>
                                
                                @if(count($preferences) > 0 && $recommendation['preference_matches'] > 0)
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-gray-700">
                                            Matches {{ $recommendation['preference_matches'] }} of your {{ count($preferences) }} preferences
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-gray-700 text-sm line-clamp-3">
                                    {{ $package->description }}
                                </p>
                            </div>
                            
                            <div class="mt-6 flex flex-wrap gap-2">
                                @foreach($package->items->take(4) as $item)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item->name }}
                                    </span>
                                @endforeach
                                @if($package->items->count() > 4)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        +{{ $package->items->count() - 4 }} more
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mt-6">
                                <a href="{{ route('public.package', $package->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-primary text-sm font-medium rounded-md shadow-sm text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    View Package Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($recommendations) > 3)
                <div class="text-center">
                    <p class="text-gray-600 mb-4">We found {{ count($recommendations) }} packages that might suit your needs. The top matches are shown above.</p>
                </div>
            @endif
            
        @else
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800 mb-2">No packages found</h2>
                <p class="text-gray-600 mb-6">We couldn't find any packages matching your criteria. Try adjusting your preferences or budget.</p>
                <a href="{{ route('package-recommendation.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Modify Your Preferences
                </a>
            </div>
        @endif
        
        <div class="mt-12 bg-white overflow-hidden shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Need More Help?</h2>
            <p class="text-gray-600 mb-6">Our wedding specialists can help you find the perfect package for your special day.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Contact a Wedding Specialist
                </a>
                <a href="{{ route('booking-requests.create') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Book a Venue Tour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 