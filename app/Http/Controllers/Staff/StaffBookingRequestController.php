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
        
        $bookingRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('staff.requests.index', compact('bookingRequests', 'status'));
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
}