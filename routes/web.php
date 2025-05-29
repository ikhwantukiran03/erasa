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
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Staff\StaffCustomizationController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\PackageRecommendationController;
use App\Http\Controllers\WeddingCardController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Staff\TicketController as StaffTicketController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ChatbotController;

// Home page
Route::get('/', [HomeController::class, 'index']);

//Public Routes
Route::get('/wedding-venues', [PublicController::class, 'showVenues'])->name('public.venues');
Route::get('/wedding-package/{package}', [PublicController::class, 'showPackage'])->name('public.package');
Route::get('/feedback', [FeedbackController::class, 'publicIndex'])->name('public.feedback');

// Package Recommendation Routes
Route::get('/package-recommendation', [PackageRecommendationController::class, 'index'])->name('package-recommendation.index');
Route::post('/package-recommendation/recommend', [PackageRecommendationController::class, 'recommend'])->name('package-recommendation.recommend');

// Booking Request Routes - Public
Route::get('/booking-request', [BookingRequestController::class, 'create'])->name('booking-requests.create');
Route::post('/booking-request', [BookingRequestController::class, 'store'])->name('booking-requests.store');
Route::get('/booking-request/confirmation', [BookingRequestController::class, 'confirmation'])->name('booking-requests.confirmation');
Route::get('/my-requests', [BookingRequestController::class, 'myRequests'])->name('booking-requests.my-requests');

// Booking Calendar Route 
Route::get('/booking-calendar', [App\Http\Controllers\BookingCalendarController::class, 'index'])->name('booking.calendar');

Route::get('/api/calendar-data', [BookingCalendarApiController::class, 'getCalendarData']);
Route::get('/api/upcoming-bookings', [BookingCalendarApiController::class, 'getUpcomingBookings']);
Route::get('/api/venues', [BookingCalendarApiController::class, 'getVenues']);

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

    // Wedding Card Routes - Protected
Route::get('/wedding-cards', [WeddingCardController::class, 'index'])->name('wedding-cards.index');
Route::get('/wedding-cards/create', [WeddingCardController::class, 'create'])->name('wedding-cards.create');
Route::post('/wedding-cards', [WeddingCardController::class, 'store'])->name('wedding-cards.store');
Route::get('/wedding-cards/{uuid}/edit', [WeddingCardController::class, 'edit'])->name('wedding-cards.edit');
Route::put('/wedding-cards/{uuid}', [WeddingCardController::class, 'update'])->name('wedding-cards.update');
Route::delete('/wedding-cards/{uuid}', [WeddingCardController::class, 'destroy'])->name('wedding-cards.destroy');
});

// Public wedding card routes - Not protected
Route::get('/wedding-cards/{uuid}', [WeddingCardController::class, 'show'])->name('wedding-cards.show');
Route::post('/wedding-cards/{uuid}/comment', [WeddingCardController::class, 'addComment'])->name('wedding-cards.comment');

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
    
    // Reports Route
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    
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
    
    // AJAX routes for creating categories and items from package creation page
    Route::post('/packages/store-category', [PackageController::class, 'storeCategory'])->name('packages.storeCategory');
    Route::post('/packages/store-item', [PackageController::class, 'storeItem'])->name('packages.storeItem');
    Route::get('/packages/get-items-by-category', [PackageController::class, 'getItemsByCategory'])->name('packages.getItemsByCategory');

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
    Route::post('/galleries/bulk-feature', [GalleryController::class, 'bulkFeature'])->name('galleries.bulkFeature');

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

    // Ticket Routes
    Route::get('/tickets', [StaffTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [StaffTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [StaffTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [StaffTicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [StaffTicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [StaffTicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{ticket}/reply', [StaffTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/status', [StaffTicketController::class, 'updateStatus'])->name('tickets.update-status');

    
    // Invoice verification routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{booking}', [InvoiceController::class, 'showVerificationForm'])->name('invoices.show');
    Route::post('/invoices/{booking}/verify', [InvoiceController::class, 'verify'])->name('invoices.verify');
    
    // New invoice management routes
    Route::get('/invoices/{invoice}/quick-view', [InvoiceController::class, 'quickView'])->name('invoices.quick-view');
    Route::post('/invoices/bulk-verify', [InvoiceController::class, 'bulkVerify'])->name('invoices.bulk-verify');
    Route::post('/invoices/bulk-reject', [InvoiceController::class, 'bulkReject'])->name('invoices.bulk-reject');
    
    // Customization Management Routes
    Route::get('/customizations', [StaffCustomizationController::class, 'index'])->name('customizations.index');
    Route::get('/customizations/{customization}', [StaffCustomizationController::class, 'show'])->name('customizations.show');
    Route::post('/customizations/{customization}/process', [StaffCustomizationController::class, 'process'])->name('customizations.process');

    // Feedback routes
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::patch('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');

    // Promotion routes
    Route::get('/promotions', function () {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->index();
    })->name('promotions.index');

    Route::get('/promotions/create', function () {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->create();
    })->name('promotions.create');

    Route::post('/promotions', function () {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->store(request());
    })->name('promotions.store');

    Route::get('/promotions/{promotion}/edit', function (App\Models\Promotion $promotion) {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->edit($promotion);
    })->name('promotions.edit');

    Route::put('/promotions/{promotion}', function (App\Models\Promotion $promotion) {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->update(request(), $promotion);
    })->name('promotions.update');

    Route::delete('/promotions/{promotion}', function (App\Models\Promotion $promotion) {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return app()->make(App\Http\Controllers\Staff\PromotionController::class)->destroy($promotion);
    })->name('promotions.destroy');
});

// User Routes for Bookings and Booking Requests
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Booking Requests
    Route::get('/booking-requests', [BookingRequestController::class, 'myRequests'])->name('booking-requests');
    Route::get('/booking-requests/{bookingRequest}', [BookingRequestController::class, 'show'])->name('booking-requests.show');
    
    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
    
    // Booking Actions
    Route::get('/bookings/{booking}/confirm', [UserBookingController::class, 'confirmReservationForm'])->name('bookings.confirm.form');
    Route::post('/bookings/{booking}/confirm', [UserBookingController::class, 'confirmReservation'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancelBooking'])->name('bookings.cancel');

    // Feedback routes
    Route::get('/bookings/{booking}/feedback', [FeedbackController::class, 'create'])->name('bookings.feedback');
    Route::post('/bookings/{booking}/feedback', [FeedbackController::class, 'store'])->name('bookings.feedback.store');

    // Invoice management
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'showSubmitForm'])->name('invoices.create');
    Route::post('/bookings/{booking}/invoice', [InvoiceController::class, 'submit'])->name('invoices.store');

    Route::get('/bookings/{booking}/customizations', function ($booking) {
        $booking = \App\Models\Booking::findOrFail($booking);
        return view('user.customizations.index', compact('booking'));
    })->name('customizations.index');
    
    Route::get('/bookings/{booking}/customizations/create/{packageItem}', [CustomizationController::class, 'create'])->name('customizations.create');
    Route::post('/bookings/{booking}/customizations/{packageItem}', [CustomizationController::class, 'store'])->name('customizations.store');
    Route::get('/bookings/{booking}/customizations/{customization}', [CustomizationController::class, 'show'])->name('customizations.show');
    Route::get('/bookings/{booking}/customizations/{customization}/edit', [CustomizationController::class, 'edit'])->name('customizations.edit');
    Route::put('/bookings/{booking}/customizations/{customization}', [CustomizationController::class, 'update'])->name('customizations.update');
    Route::delete('/bookings/{booking}/customizations/{customization}', [CustomizationController::class, 'destroy'])->name('customizations.destroy');

    // User Ticket Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::patch('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('update-status');
    });
});

// Promotion routes for users
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/promotions/{promotion}', [PromotionController::class, 'show'])->name('promotions.show');

// Chatbot Routes
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/query', [ChatbotController::class, 'query'])->name('chatbot.query');
Route::post('/chatbot/clear-conversation', [ChatbotController::class, 'clearConversation'])->name('chatbot.clear');
Route::get('/chatbot/conversation-history', [ChatbotController::class, 'getConversationHistory'])->name('chatbot.history');


    

    



    




    



