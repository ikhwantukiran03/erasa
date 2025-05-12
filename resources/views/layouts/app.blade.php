<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Enak Rasa Wedding Hall')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D4A373',
                        'primary-dark': '#C69C6D',
                        secondary: '#FAEDCD',
                        dark: '#333333',
                        light: '#FEFAE0',
                        accent: '#CCD5AE',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 15px rgba(0, 0, 0, 0.05)',
                        'medium': '0 6px 25px rgba(0, 0, 0, 0.1)',
                    }
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
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }
        
        .btn-primary {
            background-color: #D4A373;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #C69C6D;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 163, 115, 0.25);
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #D4A373;
            box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.15);
        }
        
        .error-message {
            color: #EF4444;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        /* Dropdown animation */
        .dropdown-menu {
            opacity: 0;
            transform: translateY(-10px);
            visibility: hidden;
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
        }
        
        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }
        
        /* Mobile menu animation */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        #mobile-menu.show {
            max-height: 400px;
        }
        
        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #D4A373;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="bg-white shadow-soft sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center">
                    <span class="text-2xl font-display font-bold text-primary">Enak Rasa</span>
                </a>
                
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/" class="nav-link text-dark hover:text-primary transition-colors duration-300 py-2 {{ request()->is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('public.venues') }}" class="nav-link text-dark hover:text-primary transition-colors duration-300 py-2 {{ request()->routeIs('public.venues') ? 'active' : '' }}">Venues</a>
                    <a href="{{ route('booking.calendar') }}" class="nav-link text-dark hover:text-primary transition-colors duration-300 py-2 {{ request()->routeIs('booking.calendar') ? 'active' : '' }}">Calendar</a>
                    
                    @guest
                        <div class="flex items-center space-x-4 ml-4">
                            <a href="{{ route('login') }}" class="text-dark hover:text-primary transition-colors duration-300 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-300 hover:shadow-md">Register</a>
                        </div>
                    @else
                        <div class="relative" id="userDropdown">
                            <button class="flex items-center space-x-2 text-dark hover:text-primary transition-colors px-4 py-2 rounded-lg hover:bg-gray-50" id="dropdownToggle">
                                <span>{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="chevron-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-medium py-2 dropdown-menu z-50" id="userMenu">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm text-gray-500">Signed in as</p>
                                    <p class="font-medium text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </nav>
                
                <button class="md:hidden text-dark p-2 focus:outline-none rounded-lg hover:bg-gray-100" id="mobile-menu-button" aria-label="Toggle mobile menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div class="md:hidden" id="mobile-menu">
                <nav class="mt-4 border-t border-gray-100 pt-4 pb-2 space-y-1">
                    <a href="/" class="block px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">Home</a>
                    <a href="{{ route('public.venues') }}" class="block px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">Venues</a>
                    <a href="{{ route('booking.calendar') }}" class="block px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">Calendar</a>
                    
                    @guest
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">Login</a>
                            <a href="{{ route('register') }}" class="block mx-4 my-2 px-4 py-3 text-base font-medium text-center bg-primary text-white hover:bg-primary-dark rounded-lg">Register</a>
                        </div>
                    @else
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <div class="px-4 py-2">
                                <p class="text-sm text-gray-500">Signed in as</p>
                                <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-base font-medium text-dark hover:bg-gray-50 hover:text-primary rounded-lg">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white mt-12 pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative inline-block pb-2">
                        Enak Rasa
                        <span class="absolute bottom-0 left-0 w-16 h-0.5 bg-primary"></span>
                    </h3>
                    <p class="text-gray-300 leading-relaxed">Making your wedding dreams come true with our exquisite venue, exceptional catering, and dedicated service.</p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative inline-block pb-2">
                        Quick Links
                        <span class="absolute bottom-0 left-0 w-16 h-0.5 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Home</a>
                        </li>
                        <li><a href="{{ route('public.venues') }}" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Venues</a>
                        </li>
                        <li><a href="{{ route('booking.calendar') }}" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Calendar</a>
                        </li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Login</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative inline-block pb-2">
                        Services
                        <span class="absolute bottom-0 left-0 w-16 h-0.5 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Wedding Venue</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Catering</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Event Planning</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white hover:translate-x-1 transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Decoration</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-display font-semibold mb-6 relative inline-block pb-2">
                        Contact Us
                        <span class="absolute bottom-0 left-0 w-16 h-0.5 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            123 Wedding Street, Kuala Lumpur, Malaysia
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +60 123 456 789
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@enakrasa.com
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle with animation
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
        });

        // User dropdown toggle with animation
        const dropdownToggle = document.getElementById('dropdownToggle');
        const userMenu = document.getElementById('userMenu');
        const chevronIcon = document.getElementById('chevron-icon');
        
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', (e) => {
                e.preventDefault();
                userMenu.classList.toggle('show');
                chevronIcon.classList.toggle('rotate-180');
            });
            
            // Close the dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const userDropdown = document.getElementById('userDropdown');
                if (userDropdown && !userDropdown.contains(e.target)) {
                    userMenu.classList.remove('show');
                    chevronIcon.classList.remove('rotate-180');
                }
            });
        }
        
        // Active link highlighting based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>