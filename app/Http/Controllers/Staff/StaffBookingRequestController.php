<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffBookingRequestController extends Controller
{
    protected $emailService;

    /**
     * Create a new controller instance.
     *
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the booking requests.
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

        $status = $request->get('status', 'pending');
        $search = $request->get('search', '');
        
        $query = BookingRequest::with(['venue', 'package', 'user', 'handler']);
        
        switch ($status) {
            case 'pending':
                $query->pending();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            case 'cancelled':
                $query->cancelled();
                break;
            default:
                // No filter, show all
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('whatsapp_no', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%')
                  ->orWhereHas('venue', function($venueQuery) use ($search) {
                      $venueQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('package', function($packageQuery) use ($search) {
                      $packageQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $bookingRequests = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('staff.requests.index', compact('bookingRequests', 'status', 'search'));
    }

    /**
     * Show the form for editing the specified booking request.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(BookingRequest $bookingRequest)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return view('staff.requests.edit', compact('bookingRequest'));
    }

    /**
     * Approve the specified booking request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, BookingRequest $bookingRequest)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if the booking request is already processed
        if ($bookingRequest->status !== 'pending') {
            return redirect()->route('staff.requests.index')
                ->with('error', 'This booking request has already been processed.');
        }

        // For booking requests with type = 'booking', redirect to create booking form
        if ($bookingRequest->type === 'booking') {
            return $this->redirectToCreateBookingForm($bookingRequest, $request->admin_notes);
        }

        try {
            // Begin transaction
            \DB::beginTransaction();

            // Create user account if the requester doesn't have one
            $user = null;
            $accountCreated = false;
            $password = '';
            
            if ($bookingRequest->user_id) {
                $user = User::find($bookingRequest->user_id);
            } else {
                // Check if a user with this email already exists
                $user = User::where('email', $bookingRequest->email)->first();
                
                if (!$user) {
                    // Create a new user with a secure random password
                    $password = Str::random(10);
                    
                    $user = User::create([
                        'name' => $bookingRequest->name,
                        'email' => $bookingRequest->email,
                        'whatsapp' => $bookingRequest->whatsapp_no,
                        'password' => Hash::make($password),
                        'role' => 'user',
                    ]);
                    
                    $accountCreated = true;
                }
                
                // Associate the booking request with the user
                $bookingRequest->user_id = $user->id;
            }

            // Update the booking request
            $bookingRequest->status = 'approved';
            $bookingRequest->admin_notes = $request->admin_notes;
            $bookingRequest->handled_by = Auth::id();
            $bookingRequest->handled_at = now();
            $bookingRequest->save();

            // Create a new booking record
            $booking = new Booking();
            $booking->user_id = $user->id;
            $booking->venue_id = $bookingRequest->venue_id;
            
            // Only set package and price for non-reservation types
            if (!in_array($bookingRequest->type, ['reservation', 'appointment'])) {
                $booking->package_id = $bookingRequest->package_id;
                $booking->price_id = $bookingRequest->price_id;
            }
            
            $booking->booking_date = $bookingRequest->event_date;
            $booking->session = $bookingRequest->session ? $bookingRequest->session : 'evening'; // Use session from request or default
            $booking->type = $this->mapRequestTypeToBookingType($bookingRequest->type);
            $booking->status = $booking->type === 'wedding' ? 'waiting for deposit' : 'ongoing';
            $booking->handled_by = Auth::id();
            $booking->save();

            // Auto-reject conflicting booking requests
            $this->autoRejectConflictingRequests($booking, $bookingRequest->id);

            // Commit transaction
            \DB::commit();

            // Send email notification
            $emailData = [
                'name' => $bookingRequest->name,
                'email' => $bookingRequest->email,
                'type' => $bookingRequest->type,
                'package' => $bookingRequest->package,
                'venue' => $bookingRequest->venue,
                'event_date' => $bookingRequest->event_date,
                'session' => $booking->session,
                'admin_notes' => $request->admin_notes,
                'booking_id' => $booking->id,
                'booking_type' => $booking->type,
                'account_created' => $accountCreated,
                'password' => $password,
            ];

            $this->emailService->sendBookingApprovalEmail($bookingRequest->email, $emailData);

            return redirect()->route('staff.requests.index')
                ->with('success', 'Booking request approved successfully, booking created, and email notification sent.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            \DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Redirect to create booking form with pre-filled data from booking request.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @param  string|null  $adminNotes
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToCreateBookingForm(BookingRequest $bookingRequest, $adminNotes = null)
    {
        try {
            // Create user account if the requester doesn't have one
            $user = null;
            $accountCreated = false;
            $password = '';
            
            if ($bookingRequest->user_id) {
                $user = User::find($bookingRequest->user_id);
            } else {
                // Check if a user with this email already exists
                $user = User::where('email', $bookingRequest->email)->first();
                
                if (!$user) {
                    // Create a new user with a secure random password
                    $password = Str::random(10);
                    
                    $user = User::create([
                        'name' => $bookingRequest->name,
                        'email' => $bookingRequest->email,
                        'whatsapp' => $bookingRequest->whatsapp_no,
                        'password' => Hash::make($password),
                        'role' => 'user',
                    ]);
                    
                    $accountCreated = true;
                    
                    // Associate the booking request with the user
                    $bookingRequest->user_id = $user->id;
                    $bookingRequest->save();
                }
            }

            // Update the booking request status to approved
            $bookingRequest->status = 'approved';
            $bookingRequest->admin_notes = $adminNotes;
            $bookingRequest->handled_by = Auth::id();
            $bookingRequest->handled_at = now();
            $bookingRequest->save();

            // Store booking request data in session for pre-filling the form
            session([
                'booking_request_data' => [
                    'booking_request_id' => $bookingRequest->id,
                    'user_id' => $user->id,
                    'venue_id' => $bookingRequest->venue_id,
                    'package_id' => $bookingRequest->package_id,
                    'price_id' => $bookingRequest->price_id,
                    'booking_date' => $bookingRequest->event_date ? $bookingRequest->event_date->format('Y-m-d') : null,
                    'session' => $bookingRequest->session ?: 'evening',
                    'type' => 'wedding', // booking request type 'booking' maps to 'wedding'
                    'status' => 'waiting for deposit', // Default status for wedding bookings
                    'account_created' => $accountCreated,
                    'password' => $password,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                ]
            ]);

            return redirect()->route('staff.bookings.create')
                ->with('success', 'Booking request approved. Please review and finalize the booking details below.')
                ->with('info', $accountCreated ? 'A new user account has been created for the customer.' : null);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while processing the booking request: ' . $e->getMessage());
        }
    }

    /**
     * Map request type to booking type.
     *
     * @param string $requestType
     * @return string
     */
    private function mapRequestTypeToBookingType($requestType)
    {
        switch ($requestType) {
            case 'booking':
                return 'wedding';
            case 'viewing':
                return 'viewing';
            case 'reservation':
                return 'reservation';
            case 'appointment':
                return 'reservation';
            default:
                return 'wedding';
        }
    }

    /**
     * Reject the specified booking request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, BookingRequest $bookingRequest)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if the booking request is already processed
        if ($bookingRequest->status !== 'pending') {
            return redirect()->route('staff.requests.index')
                ->with('error', 'This booking request has already been processed.');
        }

        // Create user account if the requester doesn't have one
        $user = null;
        $accountCreated = false;
        $password = '';
        
        if ($bookingRequest->user_id) {
            $user = User::find($bookingRequest->user_id);
        } else {
            // Check if a user with this email already exists
            $user = User::where('email', $bookingRequest->email)->first();
            
            if (!$user) {
                // Create a new user
                $password = Str::random(10); // Generate a secure random password
                
                $user = User::create([
                    'name' => $bookingRequest->name,
                    'email' => $bookingRequest->email,
                    'whatsapp' => $bookingRequest->whatsapp_no,
                    'password' => Hash::make($password),
                    'role' => 'user',
                ]);
                
                $accountCreated = true;
            }
            
            // Associate the booking request with the user
            $bookingRequest->user_id = $user->id;
        }

        // Update the booking request
        $bookingRequest->status = 'rejected';
        $bookingRequest->admin_notes = $request->admin_notes;
        $bookingRequest->handled_by = Auth::id();
        $bookingRequest->handled_at = now();
        $bookingRequest->save();

        // Send email notification
        $emailData = [
            'name' => $bookingRequest->name,
            'email' => $bookingRequest->email,
            'package' => $bookingRequest->package,
            'venue' => $bookingRequest->venue,
            'event_date' => $bookingRequest->event_date,
            'admin_notes' => $request->admin_notes,
            'account_created' => $accountCreated,
            'password' => $password,
        ];

        $this->emailService->sendBookingRejectionEmail($bookingRequest->email, $emailData);

        return redirect()->route('staff.requests.index')
            ->with('success', 'Booking request rejected successfully and email notification sent.');
    }

    /**
     * Display the specified booking request.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(BookingRequest $bookingRequest)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return view('staff.requests.show', compact('bookingRequest'));
    }

    /**
     * Auto-reject conflicting booking requests.
     *
     * @param  \App\Models\Booking  $booking
     * @param  int  $bookingRequestId
     * @return void
     */
    private function autoRejectConflictingRequests(Booking $booking, $bookingRequestId)
    {
        try {
            // Only auto-reject for wedding and reservation bookings
            if (!in_array($booking->type, ['wedding', 'reservation'])) {
                return;
            }
            
            // Find conflicting booking requests (exclude the one that was just approved)
            $conflictingRequests = BookingRequest::where('venue_id', $booking->venue_id)
                ->where('event_date', $booking->booking_date)
                ->where('session', $booking->session)
                ->whereIn('type', ['wedding', 'booking', 'reservation'])
                ->where('status', 'pending')
                ->where('id', '!=', $bookingRequestId) // Exclude the approved request
                ->get();
            
            if ($conflictingRequests->isEmpty()) {
                return;
            }
            
            foreach ($conflictingRequests as $request) {
                // Update the booking request status
                $request->status = 'rejected';
                $request->admin_notes = 'This date and session has been booked by another customer. Please select a different date or session for your event.';
                $request->handled_by = Auth::id();
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
                    $this->emailService->sendBookingRejectionEmail($request->email, $emailData);
                } catch (\Exception $e) {
                    \Log::error('Failed to send auto-rejection email for booking request ' . $request->id . ': ' . $e->getMessage());
                }
            }
            
            \Log::info('Auto-rejected ' . $conflictingRequests->count() . ' conflicting booking requests when approving request ' . $bookingRequestId);
            
        } catch (\Exception $e) {
            \Log::error('Failed to auto-reject conflicting booking requests: ' . $e->getMessage());
        }
    }
}