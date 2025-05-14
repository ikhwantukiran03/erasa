<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;

class BookingCalendarController extends Controller
{
    /**
     * Display the booking calendar.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $venues = Venue::all();
        return view('booking-calendar.index', compact('venues'));
    }
} 