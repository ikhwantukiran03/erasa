@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Current Promotions</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($promotions as $promotion)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ $promotion->cloudinary_image_url }}" alt="{{ $promotion->title }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $promotion->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($promotion->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-green-600">{{ $promotion->discount }}% OFF</span>
                        @auth
                            <a href="{{ route('booking-requests.create', ['promotion' => $promotion->id]) }}" 
                               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                Claim Promo
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                                Login to Claim
                            </a>
                        @endauth
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        Valid until: {{ $promotion->end_date->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No active promotions at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection 