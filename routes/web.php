<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Staff\StaffBookingRequestController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\Staff\BookingController;
use App\Http\Controllers\Api\BookingCalendarApiController;
use App\Http\Controllers\BoookingController;

// Home page
Route::get('/', [HomeController::class, 'index']);

//Public Routes
Route::get('/wedding-venues', [PublicController::class, 'showVenues'])->name('public.venues');
Route::get('/wedding-package/{package}', [PublicController::class, 'showPackage'])->name('public.package');

// Booking Request Routes - Public
Route::get('/booking-request', [BookingRequestController::class, 'create'])->name('booking-requests.create');
Route::post('/booking-request', [BookingRequestController::class, 'store'])->name('booking-requests.store');
Route::get('/booking-request/confirmation', [BookingRequestController::class, 'confirmation'])->name('booking-requests.confirmation');
Route::get('/my-requests', [BookingRequestController::class, 'myRequests'])->name('booking-requests.my-requests');

// Booking Calendar Route 
Route::get('/booking-calendar', [App\Http\Controllers\BookingCalendarController::class, 'index'])->name('booking.calendar');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // User Dashboard - common for all authenticated users
    Route::get('/dashboard', function () {
        // Redirect based on role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isStaff()) {
            return redirect()->route('staff.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    
    // Profile Routes - common for all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes for regular users - temporarily disable the role middleware
    Route::get('/bookings', function () {
        return view('bookings.index');
    })->name('bookings.index');
});

// Admin Routes - temporarily remove the role middleware
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // Check role directly in the route to maintain security
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Venue Management Routes
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [VenueController::class, 'create'])->name('venues.create');
    Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{venue}', [VenueController::class, 'show'])->name('venues.show');
    Route::get('/venues/{venue}/edit', [VenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{venue}', [VenueController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');
    
    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Item Management Routes
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // Package Management Routes
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
    Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::post('/packages/{package}/duplicate', [PackageController::class, 'duplicate'])->name('packages.duplicate');

    // Gallery Management Routes
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::get('/galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');
    Route::get('/galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
    Route::patch('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])->name('galleries.toggleFeatured');
    Route::post('/galleries/update-order', [GalleryController::class, 'updateOrder'])->name('galleries.updateOrder');

    Route::get('/bookings', function () {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return view('admin.bookings.index');
    })->name('bookings.index');
});

// Staff Routes 
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return view('staff.dashboard');
    })->name('dashboard');
    
    Route::get('/bookings', function () {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return view('staff.bookings.index');
    })->name('bookings.index');

    // Booking Request Management Routes
    Route::get('/requests', [StaffBookingRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{bookingRequest}', [StaffBookingRequestController::class, 'show'])->name('requests.show');
    Route::get('/requests/{bookingRequest}/edit', [StaffBookingRequestController::class, 'edit'])->name('requests.edit');
    Route::post('/requests/{bookingRequest}/approve', [StaffBookingRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{bookingRequest}/reject', [StaffBookingRequestController::class, 'reject'])->name('requests.reject');

    // Booking Management Routes
    Route::get('/bookings', [App\Http\Controllers\Staff\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [App\Http\Controllers\Staff\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [App\Http\Controllers\Staff\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Staff\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [App\Http\Controllers\Staff\BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [App\Http\Controllers\Staff\BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [App\Http\Controllers\Staff\BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/cancel', [App\Http\Controllers\Staff\BookingController::class, 'cancel'])->name('bookings.cancel');
});

// User Routes for Bookings and Booking Requests
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Booking Requests
    Route::get('/booking-requests', [BookingRequestController::class, 'myRequests'])->name('booking-requests');
    Route::get('/booking-requests/{bookingRequest}', [BookingRequestController::class, 'show'])->name('booking-requests.show');
    
    // Bookings
    Route::get('/bookings', function () {
        return view('user.bookings');
    })->name('bookings');
    Route::get('/bookings/{booking}', function ($booking) {
        $booking = \App\Models\Booking::findOrFail($booking);
        return view('user.booking-show', compact('booking'));
    })->name('bookings.show');
});