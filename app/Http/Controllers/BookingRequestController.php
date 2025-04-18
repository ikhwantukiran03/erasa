<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingRequestController extends Controller
{
    /**
     * Show the form for creating a new booking request.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();

        return view('booking-requests.create', compact('venues', 'packages'));
    }

    /**
     * Store a newly created booking request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'whatsapp_no' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'type' => ['required', 'in:reservation,booking,viewing,appointment'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'event_date' => ['nullable', 'date', 'after:today'],
            'message' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the booking request
        $bookingRequest = new BookingRequest($request->all());
        
        // If user is logged in, associate the request with the user
        if (Auth::check()) {
            $bookingRequest->user_id = Auth::id();
        }
        
        $bookingRequest->save();

        return redirect()->route('booking-requests.confirmation')
            ->with('success', 'Your booking request has been submitted successfully! Our team will contact you soon via WhatsApp.');
    }

    /**
     * Display a confirmation page after submitting a booking request.
     *
     * @return \Illuminate\View\View
     */
    public function confirmation()
    {
        return view('booking-requests.confirmation');
    }

    /**
     * Display booking requests for the authenticated user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function myRequests()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You need to be logged in to view your booking requests.');
        }

        $bookingRequests = BookingRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking-requests.my-requests', compact('bookingRequests'));
    }

    /**
     * Display the specified booking request.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(BookingRequest $bookingRequest)
    {
        if (!Auth::check() || $bookingRequest->user_id !== Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this booking request.');
        }

        return view('user.show', compact('bookingRequest'));
    }
}