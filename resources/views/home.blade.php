@extends('layouts.app')

@section('title', 'Enak Rasa Wedding Hall - Memorable Celebrations')

@section('content')
<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1>Create Unforgettable Wedding Memories</h1>
            <p>Enak Rasa Wedding Hall offers an elegant setting for your perfect day. Our dedicated team will transform your dreams into a celebration to remember.</p>
            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                <a href="#booking" class="cta-btn">Book Your Date</a>
                
                @guest
                    <a href="{{ route('login') }}" class="cta-btn bg-transparent border-white text-white hover:bg-white hover:text-primary">Login</a>
                    <a href="{{ route('register') }}" class="cta-btn bg-white text-primary border-white hover:bg-transparent hover:text-white">Register</a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>About Our Venue</h2>
        </div>
        <div class="about-content">
            <div class="about-image" data-aos="fade-right" data-aos-duration="1000">
                <img src="https://scontent.fkul8-2.fna.fbcdn.net/v/t39.30808-6/477277295_471515166032449_6589999265876936820_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeEyi4LM_FIQ3jxeHJwFDPuuEpMpTz5j_DUSkylPPmP8NR88oAt4ljhfJ4s_-gFpPI3pJXDP1K8ZcX6jMgyjVp9f&_nc_ohc=X7j4VqL4lMcQ7kNvgFoNnjd&_nc_oc=AdlPIDpWZiK9mwye0jzGX9Mn_7ojN5H9k-CDY-jSjHWe0DlTL7holE0Jblt5PMyjlvQ&_nc_zt=23&_nc_ht=scontent.fkul8-2.fna&_nc_gid=e0FVTbDIhDREkjNUPq9kjQ&oh=00_AYEydLSibMtvCh4hjO4rg0Dy9JiZ60TpedBmssY4VDkbdg&oe=67E03D76" alt="Enak Rasa Wedding Hall">
            </div>
            <div class="about-text" data-aos="fade-left" data-aos-duration="1000">
                <h3>The Perfect Setting for Your Special Day</h3>
                <p>Enak Rasa Wedding Hall is a premier wedding venue located in the heart of the city. With our exquisite architecture, stunning gardens, and versatile spaces, we provide the perfect backdrop for your celebration.</p>
                <p>Our experienced team of event planners, chefs, and service staff work tirelessly to ensure that your wedding day exceeds all expectations. From intimate ceremonies to grand receptions, we can accommodate events of all sizes with our customizable packages.</p>
                <p>At Enak Rasa, we believe that your wedding day should be as unique as your love story. Let us help you create the wedding of your dreams.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Services</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                    </svg>
                </div>
                <h3>Exquisite Catering</h3>
                <p>Our culinary team creates delicious, customized menus featuring both traditional and international cuisines to delight your guests.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3>Versatile Venues</h3>
                <p>Choose from our elegant ballroom, intimate garden setting, or stunning rooftop terrace for your ceremony and reception.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3>Customizable Packages</h3>
                <p>We offer flexible wedding packages that can be tailored to match your vision, style, and budget requirements.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3>Event Planning</h3>
                <p>Our experienced event planners will guide you through every step of the planning process to ensure a flawless event.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3>Decoration Services</h3>
                <p>From elegant floral arrangements to custom lighting designs, we'll transform our venue to match your wedding theme.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3>Photography & Videography</h3>
                <p>We partner with top photographers and videographers to capture every beautiful moment of your special day.</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery" id="gallery">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Wedding Gallery</h2>
        </div>
        <div class="gallery-container">
            <div class="gallery-item wide" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2669&auto=format&fit=crop" alt="Wedding Reception">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Elegant Receptions</h3>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1507504031003-b417219a0fde?q=80&w=2670&auto=format&fit=crop" alt="Wedding Ceremony">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Beautiful Ceremonies</h3>
                    </div>
                </div>
            </div>
            <div class="gallery-item tall" data-aos="fade-up" data-aos-delay="300">
                <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=2728&auto=format&fit=crop" alt="Wedding Dinner">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Exquisite Dining</h3>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                <img src="https://images.unsplash.com/photo-1506836467174-27f1042aa48c?q=80&w=2787&auto=format&fit=crop" alt="Wedding Decorations">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Stunning Decorations</h3>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2670&auto=format&fit=crop" alt="Wedding Hall">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Spacious Venues</h3>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="600">
                <img src="https://images.unsplash.com/photo-1537633552985-df8429e8048b?q=80&w=2670&auto=format&fit=crop" alt="Wedding Cake">
                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h3>Delicious Catering</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Happy Couples</h2>
        </div>
        <div class="testimonial-slider" data-aos="fade-up" data-aos-delay="200">
            <div class="testimonial-item">
                <div class="testimonial-text">
                    "Our wedding day at Enak Rasa was absolutely perfect! The venue was stunning, the food was incredible, and the staff made sure everything ran smoothly. We couldn't have asked for a better experience. Our guests are still talking about how beautiful everything was!"
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="https://images.unsplash.com/photo-1520423465871-0866049020b7?q=80&w=2787&auto=format&fit=crop" alt="Sarah & Michael">
                    </div>
                    <div class="author-name">Sarah & Michael</div>
                    <div class="wedding-date">June 12, 2024</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Form Section - INLINE FORM INSTEAD OF COMPONENT -->
<section class="booking" id="booking">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Book Your Special Day</h2>
        </div>
        
        <div class="form-container">
            <p data-aos="fade-up" data-aos-delay="100" class="text-center">
                Ready to start planning your dream wedding? Fill out the form below to check availability for your preferred date and learn more about our customizable wedding packages.
            </p>
            
            <div data-aos="fade-up" data-aos-delay="200" class="form-box">
                <!-- INLINE BOOKING FORM -->
                <div class="booking-form-container bg-white rounded-lg shadow p-6">
                    <form action="{{ route('requests.store') }}" method="POST">
                        @csrf
                        
                        @if(session('success'))
                            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                                <p class="font-bold">Please fix the following errors:</p>
                                <ul class="list-disc ml-4 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-dark font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    required 
                                    class="form-input w-full"
                                    placeholder="Enter your full name"
                                >
                            </div>
                            
                            <div>
                                <label for="whatsapp" class="block text-dark font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="whatsapp" 
                                    name="whatsapp" 
                                    value="{{ old('whatsapp') }}"
                                    required 
                                    class="form-input w-full"
                                    placeholder="+62 812 3456 7890"
                                >
                            </div>
                            
                            <div>
                                <label for="email" class="block text-dark font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required 
                                    class="form-input w-full"
                                    placeholder="your@email.com"
                                >
                            </div>
                            
                            <div>
                                <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                                <input 
                                    type="date" 
                                    id="event_date" 
                                    name="event_date" 
                                    value="{{ old('event_date') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="form-input w-full"
                                >
                            </div>
                            
                            <div>
                                <label for="venue_id" class="block text-dark font-medium mb-1">Preferred Venue</label>
                                <select id="venue_id" name="venue_id" class="form-input w-full">
                                    <option value="">-- Select Venue --</option>
                                    @foreach(\App\Models\Venue::orderBy('name')->get() as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="package_id" class="block text-dark font-medium mb-1">Interested Package</label>
                                <select id="package_id" name="package_id" class="form-input w-full">
                                    <option value="">-- Select Package --</option>
                                    @foreach(\App\Models\Package::orderBy('name')->get() as $package)
                                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }} ({{ $package->venue->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="message" class="block text-dark font-medium mb-1">Your Message <span class="text-red-500">*</span></label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    required 
                                    class="form-input w-full"
                                    placeholder="Please share details about your event, guest count, and any special requirements"
                                >{{ old('message') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="bg-primary text-white px-6 py-3 rounded hover:bg-opacity-90 transition w-full md:w-auto">
                                Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
                <!-- END INLINE BOOKING FORM -->
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Initialize AOS (Animate on Scroll)
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init();
        
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
@endpush
@endsection