<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
    
    Route::get('/users', function () {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        $users = \App\Models\User::all();
        return view('admin.users.index', compact('users'));
    })->name('users.index');
    
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