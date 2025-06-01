<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enak Rasa Wedding Hall - Memorable Celebrations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D4A373',
                        secondary: '#FAEDCD',
                        dark: '#333333',
                        light: '#FEFAE0',
                        accent: '#CCD5AE',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333333;
            background-color: #FFFDF7;
        }
        
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Hero Image with Overlay */
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
            url('{{ asset('assets/Home.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .hero-bg {
                background-attachment: scroll;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="fixed w-full z-50 transition-all duration-300" id="header">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="flex items-center">
                <img src="{{ asset('assets/logo.jpg') }}" class="h-10 w-auto" alt="Logo">
                    <span class="text-2xl font-display font-bold text-primary transition-colors duration-300">Enak Rasa</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-white hover:text-primary transition-colors duration-300 text-sm font-medium py-2">Home</a>
                    <a href="#gallery" class="text-white hover:text-primary transition-colors duration-300 text-sm font-medium py-2">Gallery</a>
                    <a href="{{ route('booking.calendar') }}" class="text-white hover:text-primary transition-colors duration-300 text-sm font-medium py-2">Calendar</a>
                    <a href="{{ route('public.venues') }}" class="text-white hover:text-primary transition-colors duration-300 text-sm font-medium py-2">Packages</a>
                    <a href="{{ route('promotions.index') }}" class="text-white hover:text-primary transition-colors duration-300 text-sm font-medium py-2">Promotions</a>
                    
                    @guest
                        <div class="flex items-center space-x-3 ml-2">
                            <a href="{{ route('login') }}" class="text-white hover:text-primary transition-colors duration-300 font-medium text-sm">Login</a>
                            <a href="{{ route('register') }}" class="bg-primary hover:bg-opacity-90 text-white px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 hover:shadow-lg">Register</a>
                        </div>
                    @else
                        <div class="relative group ml-2">
                            <button class="flex items-center text-white hover:text-primary transition-colors duration-300 bg-opacity-20 bg-white px-4 py-2 rounded-full">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 invisible opacity-0 transform translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-in-out">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </nav>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white p-2 focus:outline-none rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors" id="mobile-menu-button" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default) -->
        <div class="md:hidden hidden bg-white rounded-lg shadow-xl mt-2 mx-4 overflow-hidden transition-all duration-300 transform origin-top scale-95 opacity-0" id="mobile-menu">
            <nav class="flex flex-col divide-y divide-gray-100">
                <a href="#home" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Home</a>
                <a href="#gallery" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Gallery</a>
                
                <a href="{{ route('booking.calendar') }}" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Calendar</a>
                <a href="{{ route('public.venues') }}" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Packages</a>
                
                <a href="{{ route('promotions.index') }}" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Promotions</a>
                
                @guest
                    <div class="flex flex-col space-y-2 p-4">
                        <a href="{{ route('login') }}" class="text-dark hover:text-primary transition-colors py-2 font-medium text-center border border-gray-200 rounded-lg">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white py-2.5 rounded-lg text-center transition-colors font-medium">Register</a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="text-dark hover:text-primary hover:bg-gray-50 transition-colors px-4 py-3 font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-3">
                        @csrf
                        <button type="submit" class="w-full text-left text-dark hover:text-primary font-medium">
                            Logout
                        </button>
                    </form>
                @endguest
            </nav>
        </div>
    </header>

    <!-- Promotional Banner -->
    @if($activePromotions->isNotEmpty())
    <div class="bg-gradient-to-r from-primary to-primary-dark text-white py-3 relative overflow-hidden" id="promo-banner">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 flex-1">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        <span class="font-semibold text-sm">Special Offer:</span>
                    </div>
                    
                    <!-- Promotion Carousel -->
                    <div class="flex-1 overflow-hidden">
                        <div class="promotion-slider flex transition-transform duration-500 ease-in-out" id="promotion-slider">
                            @foreach($activePromotions as $promotion)
                                <div class="promotion-slide flex-shrink-0 w-full flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm">{{ $promotion->title }}</span>
                                        <span class="bg-white text-primary px-2 py-1 rounded-full text-xs font-bold">
                                            RM{{ number_format($promotion->discount, 2) }} OFF
                                        </span>
                                        <span class="text-xs opacity-90">Valid until {{ $promotion->end_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('promotions.show', $promotion) }}" 
                                           class="text-white hover:text-gray-200 text-xs underline">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Navigation arrows (only show if more than 1 promotion) -->
                @if($activePromotions->count() > 1)
                <div class="flex items-center space-x-2 ml-4">
                    <button onclick="previousPromotion()" class="text-white hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button onclick="nextPromotion()" class="text-white hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                @endif
                
                <!-- Close button -->
                <button onclick="closeBanner()" class="text-white hover:text-gray-200 transition-colors ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-4 -right-4 w-8 h-8 bg-white opacity-10 rounded-full animate-bounce"></div>
            <div class="absolute top-2 left-1/4 w-2 h-2 bg-white opacity-20 rounded-full animate-pulse"></div>
            <div class="absolute bottom-1 right-1/3 w-3 h-3 bg-white opacity-15 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center justify-center text-white" id="home">
        <div class="container mx-auto px-4">
       

            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold mb-6 leading-tight">Create Unforgettable Wedding Memories</h1>
                <p class="text-lg md:text-xl mb-8 mx-auto max-w-2xl">Enak Rasa Wedding Hall offers an elegant setting for your perfect day. Our dedicated team will transform your dreams into a celebration to remember.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('booking-requests.create') }}" class="bg-primary hover:bg-opacity-90 text-white px-6 py-3 rounded-full font-medium transition-colors duration-300">Book Your Date</a>
                    <a href="{{ route('booking.calendar') }}" class="bg-white text-primary hover:bg-gray-100 px-6 py-3 rounded-full font-medium transition-colors duration-300">View Calendar</a>
                    
                    
                </div>
            </div>
        </div>
        
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#gallery" class="text-white flex flex-col items-center">
                <span class="text-sm mb-2">Scroll Down</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Featured Gallery Section -->
    <section class="py-20 bg-white" id="gallery">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark mb-4">Our Wedding Gallery</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Browse through stunning highlights from weddings and events hosted at our exquisite venues.</p>
            </div>
            
            @if($galleryImages->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Featured Gallery Items - First Row (Large Images) -->
                    <div class="lg:col-span-2 overflow-hidden rounded-lg shadow-lg group" data-aos="fade-up" data-aos-delay="100">
                        <div class="relative h-96 w-full">
                            <img src="{{ isset($galleryImages[0]) ? ($galleryImages[0]->image_path ?? $galleryImages[0]->image_url) : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=3270&auto=format&fit=crop' }}" 
                                alt="{{ isset($galleryImages[0]) ? $galleryImages[0]->title : 'Wedding Venue' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="text-white text-center p-6 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="text-2xl font-display font-semibold mb-2">{{ isset($galleryImages[0]) ? $galleryImages[0]->title : 'Elegant Wedding Venue' }}</h3>
                                    @if(isset($galleryImages[0]) && $galleryImages[0]->description)
                                        <p class="text-sm md:text-base">{{ $galleryImages[0]->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden rounded-lg shadow-lg group" data-aos="fade-up" data-aos-delay="200">
                        <div class="relative h-96 w-full">
                            <img src="{{ isset($galleryImages[1]) ? ($galleryImages[1]->image_path ?? $galleryImages[1]->image_url) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=3269&auto=format&fit=crop' }}" 
                                alt="{{ isset($galleryImages[1]) ? $galleryImages[1]->title : 'Wedding Ceremony' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="text-white text-center p-6 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="text-2xl font-display font-semibold mb-2">{{ isset($galleryImages[1]) ? $galleryImages[1]->title : 'Beautiful Ceremonies' }}</h3>
                                    @if(isset($galleryImages[1]) && $galleryImages[1]->description)
                                        <p class="text-sm md:text-base">{{ $galleryImages[1]->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Row -->
                    <div class="overflow-hidden rounded-lg shadow-lg group" data-aos="fade-up" data-aos-delay="300">
                        <div class="relative h-80 w-full">
                            <img src="{{ isset($galleryImages[2]) ? ($galleryImages[2]->image_path ?? $galleryImages[2]->image_url) : 'https://images.unsplash.com/photo-1529636798458-92182e662485?q=80&w=3269&auto=format&fit=crop' }}" 
                                alt="{{ isset($galleryImages[2]) ? $galleryImages[2]->title : 'Wedding Decorations' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="text-white text-center p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="text-xl font-display font-semibold mb-1">{{ isset($galleryImages[2]) ? $galleryImages[2]->title : 'Stunning Decorations' }}</h3>
                                    @if(isset($galleryImages[2]) && $galleryImages[2]->description)
                                        <p class="text-sm">{{ $galleryImages[2]->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden rounded-lg shadow-lg group" data-aos="fade-up" data-aos-delay="400">
                        <div class="relative h-80 w-full">
                            <img src="{{ isset($galleryImages[3]) ? ($galleryImages[3]->image_path ?? $galleryImages[3]->image_url) : 'https://images.unsplash.com/photo-1470204639138-9b335f10beec?q=80&w=3270&auto=format&fit=crop' }}" 
                                alt="{{ isset($galleryImages[3]) ? $galleryImages[3]->title : 'Wedding Cake' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="text-white text-center p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="text-xl font-display font-semibold mb-1">{{ isset($galleryImages[3]) ? $galleryImages[3]->title : 'Catering Excellence' }}</h3>
                                    @if(isset($galleryImages[3]) && $galleryImages[3]->description)
                                        <p class="text-sm">{{ $galleryImages[3]->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-3 overflow-hidden rounded-lg shadow-lg group" data-aos="fade-up" data-aos-delay="500">
                        <div class="relative h-96 w-full">
                            <img src="{{ isset($galleryImages[4]) ? ($galleryImages[4]->image_path ?? $galleryImages[4]->image_url) : 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=2940&auto=format&fit=crop' }}" 
                                alt="{{ isset($galleryImages[4]) ? $galleryImages[4]->title : 'Happy Couples' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-primary bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="text-white text-center p-6 max-w-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="text-3xl font-display font-semibold mb-3">Make Your Wedding Dreams Come True</h3>
                                    <p class="text-base md:text-lg mb-4">Enak Rasa Wedding Hall offers the perfect backdrop for your special day. Contact us today to schedule a viewing.</p>
                                    <a href="{{ route('booking-requests.create') }}" class="inline-block bg-white text-primary hover:bg-gray-100 px-6 py-3 rounded-full font-medium transition-colors">Book Your Event</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-10 bg-gray-100 rounded-lg" data-aos="fade-up">
                    <p class="text-gray-600">Gallery images will be displayed here. Please check back soon!</p>
                </div>
            @endif
            
            <div class="text-center mt-8">
                <a href="#" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                    <span>View Full Gallery</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Customer Feedback Section -->
    <section class="py-20 bg-gray-50" id="feedback">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark mb-4">What Our Couples Say</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Read testimonials from couples who celebrated their special day with us.</p>
            </div>
            
            @if($topFeedback->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                    @foreach($topFeedback as $feedback)
                        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <!-- Rating Stars -->
                            <div class="flex items-center mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ $feedback->rating }}/5)</span>
                            </div>
                            
                            <!-- Feedback Comment -->
                            <p class="text-gray-700 mb-4 italic">"{{ $feedback->comment }}"</p>
                            
                            <!-- Customer Info -->
                            <div class="border-t pt-4">
                                <div class="flex items-center">
                                    <div class="bg-primary rounded-full w-10 h-10 flex items-center justify-center text-white font-semibold">
                                        {{ substr($feedback->booking->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">{{ $feedback->booking->user->name }}</p>
                                        <p class="text-sm text-gray-500">
                                            @if($feedback->booking->venue)
                                                {{ $feedback->booking->venue->name }} • 
                                            @endif
                                            {{ $feedback->created_at->format('M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- View All Feedback Link -->
                <div class="text-center mt-10" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ route('public.feedback') }}" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                        <span>View All Feedback</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12" data-aos="fade-up">
                    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Be Our First Review!</h3>
                        <p class="text-gray-600 mb-4">We're excited to serve you and would love to hear about your experience.</p>
                        <a href="{{ route('booking-requests.create') }}" class="inline-flex items-center bg-primary text-white px-4 py-2 rounded-full font-medium hover:bg-opacity-90 transition-colors duration-300">
                            Book Your Event
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Booking CTA Section -->
    <section class="py-20 bg-cover bg-center bg-no-repeat text-white" id="booking" 
             style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2670&auto=format&fit=crop');">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-display font-bold mb-6">Book Your Special Day</h2>
                <p class="text-lg mb-8">Ready to start planning your dream wedding? Check availability for your preferred date and learn more about our customizable wedding packages.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('booking-requests.create') }}" class="bg-primary hover:bg-opacity-90 text-white px-8 py-4 rounded-full font-medium transition-colors duration-300 flex items-center justify-center">
                        <span>Book Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </a>
                    <a href="{{ route('booking.calendar') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary text-white px-8 py-4 rounded-full font-medium transition-colors duration-300 flex items-center justify-center">
                        <span>View Calendar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative pb-3">
                        <span class="text-primary">Enak Rasa</span>
                        <span class="absolute bottom-0 left-0 h-1 w-12 bg-primary"></span>
                    </h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Making your wedding dreams come true with our exquisite venue, exceptional catering, and dedicated service.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-white hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-white hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative pb-3">
                        <span>Quick Links</span>
                        <span class="absolute bottom-0 left-0 h-1 w-12 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-primary transition-colors">Home</a></li>
                        <li><a href="#gallery" class="text-gray-400 hover:text-primary transition-colors">Gallery</a></li>
                        <li><a href="#calendar" class="text-gray-400 hover:text-primary transition-colors">Calendar</a></li>
                        <li><a href="{{ route('public.venues') }}" class="text-gray-400 hover:text-primary transition-colors">Packages</a></li>
                        <li><a href="{{ route('booking.calendar') }}" class="text-gray-400 hover:text-primary transition-colors">Calendar</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative pb-3">
                        <span>Our Wedding Services</span>
                        <span class="absolute bottom-0 left-0 h-1 w-12 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('booking-requests.create') }}" class="text-gray-400 hover:text-primary transition-colors">Book a Venue</a></li>
                        <li><a href="{{ route('booking.calendar') }}" class="text-gray-400 hover:text-primary transition-colors">Check Availability</a></li>
                        <li><a href="{{ route('public.venues') }}" class="text-gray-400 hover:text-primary transition-colors">View Packages</a></li>
                        <li><a href="#gallery" class="text-gray-400 hover:text-primary transition-colors">Browse Gallery</a></li>
                        <li><a href="#booking" class="text-gray-400 hover:text-primary transition-colors">Make a Reservation</a></li>
                    </ul>
                </div>
                
                <!-- Operating Hours -->
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative pb-3">
                        <span>Operating Hours</span>
                        <span class="absolute bottom-0 left-0 h-1 w-12 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Monday - Friday: 9AM - 6PM
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Saturday: 9AM - 4PM
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Sunday: By Appointment
                        </li>
                        <li class="mt-6 flex items-center">
                            <svg class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <a href="tel:+60133314389" class="hover:text-primary transition-colors">013-331 4389</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize AOS (Animate on Scroll)
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: true,
                offset: 50,
            });
            
            // Header Scroll Effect
            const header = document.getElementById('header');
            const mobileMenu = document.getElementById('mobile-menu');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('bg-white', 'shadow-md');
                    header.classList.remove('bg-transparent');
                    
                    // Change nav link colors after scroll
                    const navLinks = header.querySelectorAll('nav a:not(.bg-primary)');
                    navLinks.forEach(link => {
                        if (link.parentElement.tagName !== 'DIV' || !link.parentElement.classList.contains('bg-white')) {
                            link.classList.remove('text-white');
                            link.classList.add('text-dark');
                        }
                    });
                    
                    // Change dropdown button text color
                    const dropdownButtons = header.querySelectorAll('button:not(#mobile-menu-button)');
                    dropdownButtons.forEach(button => {
                        button.classList.remove('text-white');
                        button.classList.add('text-dark');
                    });
                } else {
                    header.classList.remove('bg-white', 'shadow-md');
                    header.classList.add('bg-transparent');
                    
                    // Restore nav link colors
                    const desktopNavLinks = header.querySelector('.md\\:flex').querySelectorAll('a:not(.bg-primary):not([href="#"])');
                    desktopNavLinks.forEach(link => {
                        if (link.parentElement.tagName !== 'DIV' || !link.parentElement.classList.contains('bg-white')) {
                            link.classList.remove('text-dark');
                            link.classList.add('text-white');
                        }
                    });
                    
                    // Restore dropdown button text color
                    const dropdownButtons = header.querySelectorAll('button:not(#mobile-menu-button)');
                    dropdownButtons.forEach(button => {
                        button.classList.remove('text-dark');
                        button.classList.add('text-white');
                    });
                }
            });
            
            // Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-button');
            
            mobileMenuBtn.addEventListener('click', function() {
                if (mobileMenu.classList.contains('hidden')) {
                    // Open menu
                    mobileMenu.classList.remove('hidden', 'scale-95', 'opacity-0');
                    mobileMenu.classList.add('scale-100', 'opacity-100');
                    setTimeout(() => {
                        mobileMenu.classList.remove('scale-100');
                    }, 300);
                } else {
                    // Close menu
                    mobileMenu.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                }
            });
            
            // Close mobile menu when clicking a menu item
            const mobileMenuItems = mobileMenu.querySelectorAll('a[href^="#"]');
            mobileMenuItems.forEach(item => {
                item.addEventListener('click', function() {
                    mobileMenu.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                });
            });
            
            // Smooth Scrolling for Anchor Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
    
    <!-- Promotional Banner JavaScript -->
    <script>
        // Promotional Banner Functionality
        let currentPromotionIndex = 0;
        const promotionSlider = document.getElementById('promotion-slider');
        const totalPromotions = {{ $activePromotions->count() ?? 0 }};
        
        function nextPromotion() {
            if (totalPromotions <= 1) return;
            
            currentPromotionIndex = (currentPromotionIndex + 1) % totalPromotions;
            updatePromotionSlider();
        }
        
        function previousPromotion() {
            if (totalPromotions <= 1) return;
            
            currentPromotionIndex = (currentPromotionIndex - 1 + totalPromotions) % totalPromotions;
            updatePromotionSlider();
        }
        
        function updatePromotionSlider() {
            if (promotionSlider) {
                const translateX = -currentPromotionIndex * 100;
                promotionSlider.style.transform = `translateX(${translateX}%)`;
            }
        }
        
        function closeBanner() {
            const banner = document.getElementById('promo-banner');
            if (banner) {
                banner.style.transform = 'translateY(-100%)';
                banner.style.opacity = '0';
                setTimeout(() => {
                    banner.style.display = 'none';
                }, 300);
                
                // Store in localStorage to remember user preference
                localStorage.setItem('promoBannerClosed', 'true');
                localStorage.setItem('promoBannerClosedTime', new Date().getTime().toString());
            }
        }
        
        // Auto-rotate promotions every 5 seconds if there are multiple
        if (totalPromotions > 1) {
            setInterval(nextPromotion, 5000);
        }
        
        // Check if user previously closed the banner
        document.addEventListener('DOMContentLoaded', function() {
            const bannerClosed = localStorage.getItem('promoBannerClosed');
            const banner = document.getElementById('promo-banner');
            
            if (bannerClosed === 'true' && banner) {
                // Check if 24 hours have passed since closing
                const lastClosed = localStorage.getItem('promoBannerClosedTime');
                const now = new Date().getTime();
                
                if (lastClosed && (now - parseInt(lastClosed)) > 24 * 60 * 60 * 1000) {
                    // 24 hours have passed, show banner again
                    localStorage.removeItem('promoBannerClosed');
                    localStorage.removeItem('promoBannerClosedTime');
                } else {
                    // Still within 24 hours, keep banner hidden
                    banner.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>