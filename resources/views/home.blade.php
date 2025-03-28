
</style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">Enak Rasa</div>
                <nav class="nav-links">
                    <a href="#home">Home</a>
                    <a href="#about">About</a>
                    <a href="#gallery">Gallery</a>
                    <a href="{{ route('publicvenues.index') }}">Packages</a>
                    <a href="#booking" class="cta-btn">Book Now</a>
                </nav>
                <button class="mobile-menu-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1>Create Unforgettable Wedding Memories</h1>
            <p>Enak Rasa Wedding Hall offers an elegant setting for your perfect day. Our dedicated team will transform your dreams into a celebration to remember.</p>
            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                <a href="#booking" class="cta-btn">Book Your Date</a>
                
                    <a href="{{ route('login') }}" class="cta-btn bg-transparent border-white text-white hover:bg-white hover:text-primary">Login</a>
                    <a href="{{ route('register') }}" class="cta-btn bg-white text-primary border-white hover:bg-transparent hover:text-white">Register</a>
                
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

    <!-- Booking CTA Section -->
    <section class="booking" id="booking">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Book Your Special Day</h2>
            </div>
            <p data-aos="fade-up" data-aos-delay="100">
                Ready to start planning your dream wedding? Check availability for your preferred date and learn more about our customizable wedding packages.
            </p>
            <a href="#pricing" class="cta-btn" data-aos="fade-up" data-aos-delay="200">View Packages</a>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Wedding Packages</h2>
            </div>
            <div class="pricing-container">
                <div class="pricing-card" data-aos="fade-up" data-aos-delay="100">
                    <h3>Intimate Elegance</h3>
                    <p class="pricing-description">Perfect for smaller, intimate celebrations</p>
                    <div class="pricing-price">
                        Rp 25,000,000 <span>/ package</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 100 guests</li>
                        <li>5-hour venue rental</li>
                        <li>Basic decoration setup</li>
                        <li>Standard catering menu</li>
                        <li>Sound system</li>
                        <li>1 photographer</li>
                        <li>Wedding coordinator</li>
                    </ul>
                    <a href="#booking" class="cta-btn">Book Now</a>
                </div>
                <div class="pricing-card featured" data-aos="fade-up" data-aos-delay="200">
                    <div class="featured-label">Most Popular</div>
                    <h3>Classic Celebration</h3>
                    <p class="pricing-description">Our most popular comprehensive package</p>
                    <div class="pricing-price">
                        Rp 45,000,000 <span>/ package</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 200 guests</li>
                        <li>8-hour venue rental</li>
                        <li>Premium decoration setup</li>
                        <li>Deluxe catering menu</li>
                        <li>Full sound & lighting system</li>
                        <li>Photography & videography</li>
                        <li>Wedding planner</li>
                        <li>Bridal suite access</li>
                        <li>Wedding cake</li>
                    </ul>
                    <a href="#booking" class="cta-btn">Book Now</a>
                </div>
                <div class="pricing-card" data-aos="fade-up" data-aos-delay="300">
                    <h3>Grand Luxury</h3>
                    <p class="pricing-description">The ultimate wedding experience</p>
                    <div class="pricing-price">
                        Rp 75,000,000 <span>/ package</span>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 500 guests</li>
                        <li>Full-day venue exclusive use</li>
                        <li>Luxury decoration & florals</li>
                        <li>Premium catering & beverages</li>
                        <li>Complete entertainment system</li>
                        <li>Full photography & cinematography</li>
                        <li>Dedicated wedding planner</li>
                        <li>Bridal & groom suite access</li>
                        <li>Custom wedding cake</li>
                        <li>Honeymoon suite (1 night)</li>
                    </ul>
                    <a href="#booking" class="cta-btn">Book Now</a>
                </div>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Enak Rasa</h3>
                    <p>Making your wedding dreams come true with our exquisite venue, exceptional catering, and dedicated service.</p>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#pricing">Packages</a></li>

                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="#">Wedding Venue</a></li>
                        <li><a href="#">Catering</a></li>
                        <li><a href="#">Event Planning</a></li>
                        <li><a href="#">Decoration</a></li>
                        <li><a href="#">Photography</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Operating Hours</h3>
                    <ul class="footer-links">
                        <li>Monday - Friday: 9AM - 6PM</li>
                        <li>Saturday: 9AM - 4PM</li>
                        <li>Sunday: By Appointment</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} Enak Rasa Wedding Hall. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        // Initialize AOS (Animate on Scroll)
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init();
            
            // Header Scroll Effect
            const header = document.getElementById('header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            
            // Mobile Menu Toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
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
                        
                        // Close mobile menu if open
                        if (window.innerWidth < 768) {
                            navLinks.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html><!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enak Rasa Wedding Hall - Memorable Celebrations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #D4A373;
            --secondary: #FAEDCD;
            --dark: #333333;
            --light: #FEFAE0;
            --accent: #CCD5AE;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: #FFFDF7;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: background-color 0.3s ease;
            padding: 1rem 0;
        }
        
        header.scrolled {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        
        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover:after {
            width: 100%;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://scontent.fkul8-3.fna.fbcdn.net/v/t39.30808-6/476350610_468705386313427_3344429432169983636_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeHm8_RZ-ThOy3uAa7CEoebdOUMqw389l1g5QyrDfz2XWAvhKAwGhW1Jrq9DIOOaCOBjYxJTyVu4KPgeff7p2in0&_nc_ohc=p1C2qEJKv90Q7kNvgHiVKXW&_nc_oc=AdnU4cq_1P4w5s95ePs7mDurBnYuO6gDg3UOUnBfvup7o4VhYa9cKSDQzNYnEAyNmgM&_nc_zt=23&_nc_ht=scontent.fkul8-3.fna&_nc_gid=WD9DVxSPjdf0w98eszH7mw&oh=00_AYFSbqzZ13OeyohJeiP--nLCirIE5kHhESjMzm5lgJy40A&oe=67E0271D&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .hero-content {
            max-width: 800px;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .cta-btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }
        
        .cta-btn:hover {
            background-color: transparent;
            color: white;
        }
        
        /* About Section */
        .about {
            padding: 6rem 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            display: inline-block;
            position: relative;
            z-index: 1;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary);
        }
        
        .about-content {
            display: flex;
            gap: 4rem;
            align-items: center;
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            height: 400px;
        }
        
        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }
        
        .about-text p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }
        
        /* Features Section */
        .features {
            padding: 6rem 0;
            background-color: var(--light);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .feature-card {
            background-color: white;
            padding: 2.5rem 2rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background-color: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            color: var(--primary);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            line-height: 1.6;
            color: #666;
        }
        
        /* Gallery Section */
        .gallery {
            padding: 6rem 0;
            background-color: white;
        }
        
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 250px;
            gap: 1.5rem;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .gallery-item.wide {
            grid-column: span 2;
        }
        
        .gallery-item.tall {
            grid-row: span 2;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-overlay-content {
            color: white;
            text-align: center;
        }
        
        .gallery-overlay-content h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        /* Testimonials Section */
        .testimonials {
            padding: 6rem 0;
            background-color: var(--secondary);
        }
        
        .testimonial-slider {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-item {
            text-align: center;
            padding: 0 2rem;
        }
        
        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            font-style: italic;
            position: relative;
        }
        
        .testimonial-text::before,
        .testimonial-text::after {
            content: '"';
            font-size: 3rem;
            color: var(--primary);
            position: absolute;
            opacity: 0.3;
        }
        
        .testimonial-text::before {
            top: -20px;
            left: -20px;
        }
        
        .testimonial-text::after {
            bottom: -40px;
            right: -20px;
        }
        
        .testimonial-author {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .author-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 1rem;
            border: 3px solid var(--primary);
        }
        
        .author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        
        .wedding-date {
            font-size: 0.9rem;
            color: #777;
        }
        
        /* Booking Section */
        .booking {
            padding: 6rem 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2670&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            text-align: center;
        }
        
        .booking h2 {
            color: white;
        }
        
        .booking p {
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.8;
        }
        
        /* Pricing Section */
        .pricing {
            padding: 6rem 0;
            background-color: white;
        }
        
        .pricing-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .pricing-card {
            background-color: white;
            border-radius: 10px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .pricing-card.featured {
            transform: scale(1.05);
            border: 2px solid var(--primary);
        }
        
        .featured-label {
            position: absolute;
            top: 20px;
            right: -30px;
            background-color: var(--primary);
            color: white;
            padding: 0.3rem 2rem;
            transform: rotate(45deg);
        }
        
        .pricing-card h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .pricing-description {
            color: #777;
            margin-bottom: 1.5rem;
        }
        
        .pricing-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }
        
        .pricing-price span {
            font-size: 1rem;
            color: #777;
        }
        
        .pricing-features {
            list-style: none;
            margin-bottom: 2rem;
        }
        
        .pricing-features li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        /* Contact Section */
        .contact {
            padding: 6rem 0;
            background-color: var(--light);
        }
        
        .contact-container {
            display: flex;
            gap: 4rem;
        }
        
        .contact-info {
            flex: 1;
        }
        
        .contact-info h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }
        
        .contact-details {
            margin-bottom: 2rem;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .contact-icon {
            margin-right: 1rem;
            color: var(--primary);
        }
        
        .contact-text {
            line-height: 1.6;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .social-link:hover {
            background-color: var(--dark);
        }
        
        .contact-form {
            flex: 1;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        textarea.form-control {
            height: 150px;
            resize: none;
        }
        
        .submit-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        
        .submit-btn:hover {
            background-color: var(--dark);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .footer-column h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary);
        }
        
        .footer-column p {
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary);
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .about-content {
                flex-direction: column;
                gap: 2rem;
            }
            
            .features-grid,
            .pricing-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .gallery-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .contact-container {
                flex-direction: column;
            }
            
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .features-grid,
            .pricing-container {
                grid-template-columns: 1fr;
            }
            
            .gallery-container {
                grid-template-columns: 1fr;
            }
            
            .gallery-item.wide,
            .gallery-item.tall {
                grid-column: auto;
                grid-row: auto;
            }
        }
        
        @media (max-width: 576px) {
            .section-title h2 {
                font-size: 2rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }