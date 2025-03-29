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
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }
        
        .btn-primary {
            background-color: #D4A373;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #C69C6D;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #D4A373;
            box-shadow: 0 0 0 2px rgba(212, 163, 115, 0.2);
        }
        
        .error-message {
            color: #EF4444;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        /* Dropdown styles */
        .dropdown-menu {
            display: none; /* Hidden by default */
        }
        
        .dropdown-menu.show {
            display: block; /* Show when active */
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-display font-bold text-primary">Enak Rasa</a>
            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-dark hover:text-primary transition">Home</a>
                <a href="#about" class="text-dark hover:text-primary transition">About</a>
                <a href="#gallery" class="text-dark hover:text-primary transition">Gallery</a>
                <a href="{{ route('public.venues') }}" class="text-dark hover:text-primary transition">Packages</a>
                @guest
                    <a href="{{ route('login') }}" class="text-dark hover:text-primary transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">Register</a>
                @else
                    <div class="relative" id="userDropdown">
                        <button class="flex items-center text-dark hover:text-primary transition" id="dropdownToggle">
                            {{ Auth::user()->name }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 dropdown-menu z-50" id="userMenu">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </nav>
            <button class="md:hidden text-dark" id="mobile-menu-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">Home</a>
                <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">About</a>
                <a href="#gallery" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">Gallery</a>
                <a href="{{ route('public.venues') }}" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">Packages</a>
                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-primary text-white hover:bg-opacity-90">Register</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-dark hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white mt-12 py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-display mb-4">Enak Rasa</h3>
                    <p class="text-gray-300">Making your wedding dreams come true with our exquisite venue, exceptional catering, and dedicated service.</p>
                </div>
                <div>
                    <h3 class="text-xl font-display mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="#about" class="text-gray-300 hover:text-white">About</a></li>
                        <li><a href="#gallery" class="text-gray-300 hover:text-white">Gallery</a></li>
                        <li><a href="#pricing" class="text-gray-300 hover:text-white">Packages</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-display mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Wedding Venue</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Catering</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Event Planning</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Decoration</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-display mb-4">Operating Hours</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Monday - Friday: 9AM - 6PM</li>
                        <li>Saturday: 9AM - 4PM</li>
                        <li>Sunday: By Appointment</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                &copy; {{ date('Y') }} Enak Rasa Wedding Hall. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // User dropdown toggle
        const dropdownToggle = document.getElementById('dropdownToggle');
        const userMenu = document.getElementById('userMenu');
        
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', (e) => {
                e.preventDefault();
                userMenu.classList.toggle('show');
            });
            
            // Close the dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const userDropdown = document.getElementById('userDropdown');
                if (userDropdown && !userDropdown.contains(e.target)) {
                    userMenu.classList.remove('show');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>