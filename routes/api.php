<?php

// Calendar API Routes
Route::get('/calendar-data', [App\Http\Controllers\Api\BookingCalendarApiController::class, 'getCalendarData']);
Route::get('/upcoming-bookings', [App\Http\Controllers\Api\BookingCalendarApiController::class, 'getUpcomingBookings']);
Route::get('/venues', [App\Http\Controllers\Api\BookingCalendarApiController::class, 'getVenues']);