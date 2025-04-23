<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingCalendarController extends Controller
{
    /**
     * Display the booking calendar view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

     public function index(Request $request)
     {
        // This controller only loads the view
        // The actual data is loaded via AJAX from the API endpoint
        return view('booking-calendar');
     }
         
}
