@extends('layouts.app')

@section('title', 'Current Promotions - Enak Rasa Wedding Hall')

@section('content')
<!-- Promotions Hero Section -->
<div class="bg-gradient-to-r from-primary/20 to-secondary/30 py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="/" class="hover:text-primary transition">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-primary">Current Promotions</span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-display font-bold text-primary" data-aos="fade-up">Special Offers & Promotions</h1>
        <p class="text-gray-600 mt-2" data-aos="fade-up" data-aos-delay="100">Exclusive deals for your special day</p>
    </div>
</div>

<!-- Promotions Content -->
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($promotions as $promotion)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 border border-gray-100 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <!-- Promotion Image -->
                    <div class="h-48 overflow-hidden relative">
                        <img src="{{ $promotion->cloudinary_image_url }}" 
                             alt="{{ $promotion->title }}" 
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 hover:opacity-100 transition-opacity"></div>
                        
                        <!-- Discount Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-primary text-white text-sm px-4 py-1.5 rounded-full font-medium shadow-md">
                                RM{{ number_format($promotion->discount, 2) }} OFF
                            </span>
                        </div>
                    </div>

                    <!-- Promotion Details -->
                    <div class="p-6">
                        <h2 class="text-xl font-display font-bold text-dark mb-2">{{ $promotion->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($promotion->description, 100) }}</p>
                        
                        <!-- Package Info -->
                        @if($promotion->package)
                            <div class="flex items-center text-gray-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                                <span>{{ $promotion->package->name }}</span>
                            </div>
                        @endif

                        <!-- Validity Period -->
                        <div class="flex items-center text-gray-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Valid until {{ $promotion->end_date->format('M d, Y') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @auth
                                <a href="{{ route('booking-requests.create', ['promotion' => $promotion->id]) }}" 
                                   class="block w-full bg-primary text-white text-center py-3 rounded-lg hover:bg-opacity-90 transition text-sm font-medium flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Claim Promo
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full bg-gray-500 text-white text-center py-3 rounded-lg hover:bg-opacity-90 transition text-sm font-medium flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login to Claim
                                </a>
                            @endauth

                            @if($promotion->package)
                                <a href="{{ route('public.package', $promotion->package_id) }}" 
                                   class="block w-full bg-white border border-primary text-primary text-center py-3 rounded-lg hover:bg-primary hover:text-white transition text-sm font-medium flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Package Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-md p-8 text-center" data-aos="fade-up">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Promotions</h3>
                        <p class="text-gray-600 mb-4">Check back soon for our latest offers and special deals.</p>
                        <a href="{{ route('public.venues') }}" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                            <span>View Our Packages</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Contact Section -->
        <div class="mt-16 pt-6 border-t border-gray-100">
            <div class="bg-gradient-to-r from-primary/10 to-secondary/20 rounded-xl p-8 text-center" data-aos="fade-up">
                <h2 class="text-2xl font-display font-bold text-primary mb-4">Need Help?</h2>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Our team is here to help you find the perfect package and make your special day unforgettable.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="tel:+60133314389" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Call Us: 013-331 4389
                    </a>
                    <a href="https://wa.me/60133314389" target="_blank" class="inline-flex items-center bg-green-500 text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg>
                        WhatsApp Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 