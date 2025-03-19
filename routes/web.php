<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;

// Home page
Route::get('/', [HomeController::class, 'index']);

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
    Route::get('/venues', [App\Http\Controllers\Admin\VenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [App\Http\Controllers\Admin\VenueController::class, 'create'])->name('venues.create');
    Route::post('/venues', [App\Http\Controllers\Admin\VenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{venue}', [App\Http\Controllers\Admin\VenueController::class, 'show'])->name('venues.show');
    Route::get('/venues/{venue}/edit', [App\Http\Controllers\Admin\VenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{venue}', [App\Http\Controllers\Admin\VenueController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{venue}', [App\Http\Controllers\Admin\VenueController::class, 'destroy'])->name('venues.destroy');
    
    Route::get('/bookings', function () {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        return view('admin.bookings.index');
    })->name('bookings.index');
});

// Staff Routes - temporarily remove the role middleware
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
});