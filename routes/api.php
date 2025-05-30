<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingCalendarApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Calendar API Routes
Route::get('/calendar-data', [BookingCalendarApiController::class, 'getCalendarData']);
Route::get('/upcoming-bookings', [BookingCalendarApiController::class, 'getUpcomingBookings']);
Route::get('/venues', [BookingCalendarApiController::class, 'getVenues']);
Route::get('/customers', [BookingCalendarApiController::class, 'getCustomers']);
Route::get('/packages', [BookingCalendarApiController::class, 'getPackages']);
Route::get('/check-availability', [BookingCalendarApiController::class, 'checkAvailability']);
Route::post('/quick-booking', [BookingCalendarApiController::class, 'createQuickBooking']);