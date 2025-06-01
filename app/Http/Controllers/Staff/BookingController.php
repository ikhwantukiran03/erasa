<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\Venue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $status = $request->get('status', '');
        $search = $request->get('search', '');
        
        $query = Booking::with(['venue', 'package', 'user', 'handler']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%')
                               ->orWhere('whatsapp', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('venue', function($venueQuery) use ($search) {
                      $venueQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('package', function($packageQuery) use ($search) {
                      $packageQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('staff.bookings.index', compact('bookings', 'status', 'search'));
    }

    /**
     * Show the form for creating a new booking.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        
        return view('staff.bookings.create', compact('venues', 'packages', 'users'));
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Base validation rules
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'booking_date' => ['required', 'date'],
            'session' => ['required', 'in:morning,evening'],
            'type' => ['required', 'in:wedding,viewing,reservation'],
            'status' => ['required', 'in:waiting for deposit,ongoing,completed,cancelled,pending_verification'],
            'booking_request_id' => ['nullable', 'exists:booking_requests,id'],
        ];
        
        // Add expiry_date validation only for reservation bookings
        if ($request->type === 'reservation') {
            $rules['expiry_date'] = ['required', 'date', 'after:today'];
        } else {
            $rules['expiry_date'] = ['nullable', 'date', 'after:today'];
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional validation for booking date based on booking type
        $bookingDate = \Carbon\Carbon::parse($request->booking_date);
        $today = \Carbon\Carbon::today();
        
        if (in_array($request->type, ['reservation', 'wedding'])) {
            // For reservations and wedding bookings, require at least 6 months advance booking
            $minimumDate = $today->copy()->addMonths(6);
            if ($bookingDate->lessThan($minimumDate)) {
                return redirect()->back()
                    ->withErrors(['booking_date' => 'For reservations and wedding bookings, the booking date must be at least 6 months from today.'])
                    ->withInput();
            }
        } else {
            // For viewing, require at least 1 day advance booking
            if ($bookingDate->lessThanOrEqualTo($today)) {
                return redirect()->back()
                    ->withErrors(['booking_date' => 'The booking date must be after today.'])
                    ->withInput();
            }
        }

        try {
            // Check for existing bookings on the same date and session
            $existingBooking = Booking::where('venue_id', $request->venue_id)
                ->where('booking_date', $request->booking_date)
                ->where('session', $request->session)
                ->where('status', '!=', 'cancelled')
                ->first();
                
            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'This venue is already booked for the selected date and session.')
                    ->withInput();
            }
            
            $booking = new Booking();
            $booking->user_id = $request->user_id;
            $booking->venue_id = $request->venue_id;
            $booking->package_id = $request->package_id;
            $booking->booking_date = $request->booking_date;
            $booking->session = $request->session;
            $booking->type = $request->type;
            $booking->status = $request->status;
            // Set expiry date to 7 days from today (booking creation date)
            $booking->expiry_date = $request->expiry_date ?: \Carbon\Carbon::now()->addDays(7);
            $booking->handled_by = auth()->id();
            $booking->save();
            
            // Auto-reject conflicting booking requests
            $this->autoRejectConflictingRequests($booking);
            
            // Handle booking request completion and email notification
            $bookingRequestData = session('booking_request_data');
            if ($request->booking_request_id && $bookingRequestData) {
                $this->handleBookingRequestCompletion($booking, $bookingRequestData);
                
                // Clear the session data
                session()->forget('booking_request_data');
            }
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle completion of booking request and send email notification.
     *
     * @param  \App\Models\Booking  $booking
     * @param  array  $bookingRequestData
     * @return void
     */
    private function handleBookingRequestCompletion(Booking $booking, array $bookingRequestData)
    {
        try {
            // Get the booking request
            $bookingRequest = \App\Models\BookingRequest::find($bookingRequestData['booking_request_id']);
            if (!$bookingRequest) {
                return;
            }

            // Get email service
            $emailService = app(\App\Services\EmailService::class);
            
            // Prepare email data
            $emailData = [
                'name' => $bookingRequestData['customer_name'],
                'email' => $bookingRequestData['customer_email'],
                'type' => $bookingRequest->type,
                'package' => $booking->package,
                'venue' => $booking->venue,
                'event_date' => $booking->booking_date,
                'session' => $booking->session,
                'admin_notes' => $bookingRequest->admin_notes,
                'booking_id' => $booking->id,
                'booking_type' => $booking->type,
                'account_created' => $bookingRequestData['account_created'],
                'password' => $bookingRequestData['password'],
            ];

            // Send email notification
            $emailService->sendBookingApprovalEmail($bookingRequestData['customer_email'], $emailData);
            
        } catch (\Exception $e) {
            // Log the error but don't fail the booking creation
            \Log::error('Failed to send booking approval email: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Load necessary relationships
        $booking->load(['user', 'venue', 'package.packageItems.item.category', 'handler']);
        
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Load necessary relationships including nested ones
        $booking->load(['user', 'venue', 'package.packageItems.item.category', 'handler']);
        
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        
        return view('staff.bookings.edit', compact('booking', 'venues', 'packages', 'users'));
    }

    /**
     * Update the specified booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Base validation rules
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'booking_date' => ['required', 'date'],
            'session' => ['required', 'in:morning,evening'],
            'type' => ['required', 'in:wedding,viewing,reservation'],
            'status' => ['required', 'in:waiting for deposit,ongoing,completed,cancelled,pending_verification'],
        ];
        
        // Add expiry_date validation only for reservation bookings
        if ($request->type === 'reservation') {
            $rules['expiry_date'] = ['required', 'date', 'after:today'];
        } else {
            $rules['expiry_date'] = ['nullable', 'date', 'after:today'];
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional validation for booking date based on booking type
        $bookingDate = \Carbon\Carbon::parse($request->booking_date);
        $today = \Carbon\Carbon::today();
        
        if (in_array($request->type, ['reservation', 'wedding'])) {
            // For reservations and wedding bookings, require at least 6 months advance booking
            $minimumDate = $today->copy()->addMonths(6);
            if ($bookingDate->lessThan($minimumDate)) {
                return redirect()->back()
                    ->withErrors(['booking_date' => 'For reservations and wedding bookings, the booking date must be at least 6 months from today.'])
                    ->withInput();
            }
        } else {
            // For viewing, require at least 1 day advance booking
            if ($bookingDate->lessThanOrEqualTo($today)) {
                return redirect()->back()
                    ->withErrors(['booking_date' => 'The booking date must be after today.'])
                    ->withInput();
            }
        }

        try {
            // Check for existing bookings on the same date and session
            $existingBooking = Booking::where('venue_id', $request->venue_id)
                ->where('booking_date', $request->booking_date)
                ->where('session', $request->session)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $booking->id)
                ->first();
                
            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'This venue is already booked for the selected date and session.')
                    ->withInput();
            }
            
            $booking->user_id = $request->user_id;
            $booking->venue_id = $request->venue_id;
            $booking->package_id = $request->package_id;
            $booking->booking_date = $request->booking_date;
            $booking->session = $request->session;
            $booking->type = $request->type;
            $booking->status = $request->status;
            // Only set automatic expiry date if not provided and booking doesn't already have one
            if ($request->expiry_date) {
                $booking->expiry_date = $request->expiry_date;
            } elseif (!$booking->expiry_date) {
                $booking->expiry_date = \Carbon\Carbon::now()->addDays(7);
            }
            $booking->save();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified booking from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            $booking->delete();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the booking: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            $booking->status = 'cancelled';
            $booking->save();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking cancelled successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while cancelling the booking: ' . $e->getMessage());
        }
    }

    /**
     * Auto-reject conflicting booking requests.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    private function autoRejectConflictingRequests(Booking $booking)
    {
        try {
            // Only auto-reject for wedding and reservation bookings
            if (!in_array($booking->type, ['wedding', 'reservation'])) {
                return;
            }
            
            // Find conflicting booking requests
            $conflictingRequests = BookingRequest::where('venue_id', $booking->venue_id)
                ->where('event_date', $booking->booking_date)
                ->where('session', $booking->session)
                ->whereIn('type', ['wedding', 'booking', 'reservation'])
                ->where('status', 'pending')
                ->get();
            
            if ($conflictingRequests->isEmpty()) {
                return;
            }
            
            // Get email service
            $emailService = app(\App\Services\EmailService::class);
            
            foreach ($conflictingRequests as $request) {
                // Update the booking request status
                $request->status = 'rejected';
                $request->admin_notes = 'This date and session has been booked by another customer. Please select a different date or session for your event.';
                $request->handled_by = auth()->id();
                $request->handled_at = now();
                $request->save();
                
                // Send rejection email
                $emailData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'type' => $request->type,
                    'venue' => $request->venue,
                    'event_date' => $request->event_date,
                    'session' => $request->session,
                    'admin_notes' => $request->admin_notes,
                    'reason' => 'conflict',
                    'booking_date' => $booking->booking_date,
                    'booking_session' => $booking->session,
                ];
                
                try {
                    $emailService->sendBookingRejectionEmail($request->email, $emailData);
                } catch (\Exception $e) {
                    \Log::error('Failed to send auto-rejection email for booking request ' . $request->id . ': ' . $e->getMessage());
                }
            }
            
            \Log::info('Auto-rejected ' . $conflictingRequests->count() . ' conflicting booking requests for booking ' . $booking->id);
            
        } catch (\Exception $e) {
            \Log::error('Failed to auto-reject conflicting booking requests: ' . $e->getMessage());
        }
    }
}