<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\Venue;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingRequestController extends Controller
{
    /**
     * Show the form for creating a new booking request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Get venues with their featured gallery images
        $venues = Venue::with(['galleries' => function($query) {
            $query->where('is_featured', '1')->orWhere(function($q) {
                $q->whereRaw('id = (SELECT MIN(id) FROM galleries g2 WHERE g2.venue_id = galleries.venue_id)');
            });
        }])->orderBy('name')->get();

        // Get packages with their venue, prices, and venue gallery images
        $packages = Package::with([
            'venue.galleries' => function($query) {
                $query->where('is_featured', '1')->orWhere(function($q) {
                    $q->whereRaw('id = (SELECT MIN(id) FROM galleries g2 WHERE g2.venue_id = galleries.venue_id)');
                });
            },
            'prices'
        ])->orderBy('name')->get();

        // Get pre-filled data from URL parameters
        $prefilledData = [
            'package_id' => $request->get('package_id'),
            'venue_id' => $request->get('venue_id'),
            'event_date' => $request->get('event_date'),
        ];

        // If package_id is provided, get the package and set the venue_id
        if ($prefilledData['package_id']) {
            $selectedPackage = Package::find($prefilledData['package_id']);
            if ($selectedPackage) {
                $prefilledData['venue_id'] = $selectedPackage->venue_id;
            }
        }

        return view('booking-requests.create', compact('venues', 'packages', 'prefilledData'));
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
            'price_id' => ['nullable', 'exists:prices,id'],
            'event_date' => ['nullable', 'date', 'after:today'],
            'session' => ['required', 'in:morning,evening'],
            'message' => ['required', 'string'],
            'skip_package' => ['sometimes', 'in:1,0,true,false'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional validation for event date based on booking type
        if (in_array($request->type, ['booking', 'reservation']) && $request->event_date) {
            $sixMonthsFromNow = now()->addMonths(6);
            $selectedDate = \Carbon\Carbon::parse($request->event_date);
            
            if ($selectedDate->lt($sixMonthsFromNow)) {
                return redirect()->back()
                    ->withErrors([
                        'event_date' => 'Booking and reservation requests require at least 6 months advance notice. Please select a date 6 months or more from today.'
                    ])
                    ->withInput();
            }
        }

        // Additional validation for event date based on booking type
        if (in_array($request->type, ['reservation', 'booking']) && $request->venue_id && $request->event_date && $request->session) {
            // Check existing confirmed bookings
            $existingBooking = \App\Models\Booking::where('booking_date', $request->event_date)
                ->where('venue_id', $request->venue_id)
                ->where('session', $request->session)
                ->whereIn('type', ['wedding', 'reservation'])
                ->where('status', '!=', 'cancelled')
                ->exists();

            // Check existing booking requests
            $existingRequest = BookingRequest::where('event_date', $request->event_date)
                ->where('venue_id', $request->venue_id)
                ->where('session', $request->session)
                ->whereIn('type', ['reservation', 'booking'])
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($existingBooking || $existingRequest) {
                $venue = Venue::find($request->venue_id);
                $sessionTiming = $request->session === 'morning' ? '11:00 AM - 4:00 PM' : '7:00 PM - 11:00 PM';
                $formattedDate = \Carbon\Carbon::parse($request->event_date)->format('F d, Y');
                
                return redirect()->back()
                    ->withErrors([
                        'session' => "The {$venue->name} venue is already booked for {$request->session} session ({$sessionTiming}) on {$formattedDate}. Please select a different session, date, or venue."
                    ])
                    ->withInput();
            }
        }

        // Validate package belongs to selected venue if both are provided
        if ($request->package_id && $request->venue_id) {
            $package = Package::find($request->package_id);
            if ($package && $package->venue_id != $request->venue_id) {
                return redirect()->back()
                    ->withErrors([
                        'package_id' => 'The selected package does not belong to the selected venue.'
                    ])
                    ->withInput();
            }
        }

        // Validate price belongs to selected package if both are provided
        if ($request->price_id && $request->package_id) {
            $price = Price::find($request->price_id);
            if ($price && $price->package_id != $request->package_id) {
                return redirect()->back()
                    ->withErrors([
                        'price_id' => 'The selected pricing option does not belong to the selected package.'
                    ])
                    ->withInput();
            }
        }

        // If package is skipped, remove package_id
        if ($request->has('skip_package') && ($request->skip_package == '1' || $request->skip_package === true)) {
            $request->merge(['package_id' => null, 'price_id' => null]);
        }

        // Create the booking request
        $bookingRequest = new BookingRequest($request->except(['skip_package']));
        
        // If user is logged in, associate the request with the user
        if (Auth::check()) {
            $bookingRequest->user_id = Auth::id();
        }
        
        $bookingRequest->save();

        // Send email notification (if email service is configured)
        try {
            // You can add email notification logic here
        } catch (\Exception $e) {
            // Log email error but don't fail the request
            \Log::warning('Failed to send booking request email notification: ' . $e->getMessage());
        }

        return redirect()->route('booking-requests.confirmation')
            ->with('success', 'Your booking request has been submitted successfully! We will contact you within 24 hours to confirm the details.');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function myRequests(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You need to be logged in to view your booking requests.');
        }

        $query = BookingRequest::where('user_id', Auth::id())
            ->with(['venue', 'package']);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Search by request ID (remove # if present)
                $requestId = str_replace('#', '', $searchTerm);
                if (is_numeric($requestId)) {
                    $q->orWhere('id', $requestId);
                }
                
                // Search by request type
                $q->orWhere('type', 'ILIKE', '%' . $searchTerm . '%');
                
                // Search by venue name
                $q->orWhereHas('venue', function($venueQuery) use ($searchTerm) {
                    $venueQuery->where('name', 'ILIKE', '%' . $searchTerm . '%');
                });
                
                // Search by package name
                $q->orWhereHas('package', function($packageQuery) use ($searchTerm) {
                    $packageQuery->where('name', 'ILIKE', '%' . $searchTerm . '%');
                });
                
                // Search by event date (flexible date search)
                if ($searchTerm) {
                    $q->orWhere('event_date', 'ILIKE', '%' . $searchTerm . '%');
                    
                    // Search by formatted date (e.g., "Dec 2024", "December", etc.)
                    $q->orWhereRaw("TO_CHAR(event_date, 'Mon YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
                    $q->orWhereRaw("TO_CHAR(event_date, 'Month YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
                    $q->orWhereRaw("TO_CHAR(event_date, 'Mon DD, YYYY') ILIKE ?", ['%' . $searchTerm . '%']);
                }
                
                // Search by status
                $q->orWhere('status', 'ILIKE', '%' . $searchTerm . '%');
            });
        }

        $bookingRequests = $query->orderBy('created_at', 'desc')->get();

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

    /**
     * Check session availability for a venue on a specific date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $venueId = $request->venue_id;
        $eventDate = $request->event_date;
        $requestType = $request->type;

        if (!$venueId || !$eventDate || !in_array($requestType, ['booking', 'reservation'])) {
            return response()->json(['unavailable_sessions' => []]);
        }

        $unavailableSessions = [];

        // Check both morning and evening sessions
        foreach (['morning', 'evening'] as $session) {
            // Check existing confirmed bookings
            $existingBooking = \App\Models\Booking::where('booking_date', $eventDate)
                ->where('venue_id', $venueId)
                ->where('session', $session)
                ->whereIn('type', ['wedding', 'reservation'])
                ->where('status', '!=', 'cancelled')
                ->exists();

            // Check existing booking requests
            $existingRequest = BookingRequest::where('event_date', $eventDate)
                ->where('venue_id', $venueId)
                ->where('session', $session)
                ->whereIn('type', ['reservation', 'booking'])
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($existingBooking || $existingRequest) {
                $unavailableSessions[] = $session;
            }
        }

        return response()->json(['unavailable_sessions' => $unavailableSessions]);
    }
}