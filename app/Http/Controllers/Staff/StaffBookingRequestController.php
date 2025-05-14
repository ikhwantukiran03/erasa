<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffBookingRequestController extends Controller
{
    protected $whatsAppService;

    /**
     * Create a new controller instance.
     *
     * @param WhatsAppService $whatsAppService
     */
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
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
        
        $bookingRequests = $query->orderBy('created_at', 'desc')->get();

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

            // Prepare WhatsApp message
            $message = "Hello {$bookingRequest->name},\n\n";
            $message .= "🎉 *Your booking request has been APPROVED!* 🎉\n\n";
            
            // Only include package information for non-reservation types
            if ($bookingRequest->type !== 'reservation' && $bookingRequest->type !== 'appointment' && $bookingRequest->package) {
                $message .= "*Package:* {$bookingRequest->package->name}\n";
            }
            
            if ($bookingRequest->venue) {
                $message .= "*Venue:* {$bookingRequest->venue->name}\n";
            }
            
            if ($bookingRequest->event_date) {
                $message .= "*Event Date:* {$bookingRequest->event_date->format('d M Y')}\n";
                $message .= "*Session:* " . ucfirst($booking->session) . "\n";
            }
            
            if ($request->admin_notes) {
                $message .= "\n*Additional information:*\n{$request->admin_notes}\n";
            }
            
            // Add booking confirmation
            $message .= "\n*🎫 BOOKING CONFIRMATION:*\n";
            $message .= "Your booking has been confirmed with reference number: B-{$booking->id}\n";

            // Add deposit information for wedding bookings
            if ($booking->type === 'wedding') {
                $message .= "*⚠️ IMPORTANT: Your booking requires a deposit payment.*\n";
                $message .= "Your booking status is 'Waiting for Deposit'. Please complete your payment to confirm your reservation.\n\n";
                $message .= "*Payment Details:*\n";
                $message .= "Bank Transfer to: Bank Negara Malaysia\n";
                $message .= "Account Number: 1234-5678-9012\n";
                $message .= "Reference: BOOKING-{$booking->id}\n\n";
                $message .= "Please reply to this message with your payment proof once completed.\n";
            }

            $message .= "You can view your booking details in your account.\n";
            
            // Add account information if a new account was created
            if ($accountCreated) {
                $message .= "\n*💻 YOUR ACCOUNT DETAILS:*\n";
                $message .= "We've created an account for you on our website:\n";
                $message .= "*Email:* {$bookingRequest->email}\n";
                $message .= "*Password:* {$password}\n\n";
                $message .= "*LOGIN LINK:* " . route('login') . "\n\n";
                $message .= "⚠️ *IMPORTANT:* For security purposes, please log in and change your password as soon as possible by visiting your profile page: " . route('profile.edit') . "\n";
            } else {
                $message .= "\n*🔑 ACCESS YOUR BOOKING:*\n";
                $message .= "You can view the details of your booking by logging into your account: " . route('login') . "\n";
                $message .= "Then navigate to My Bookings.\n";
            }
            
            $message .= "\nThank you for choosing Enak Rasa Wedding Hall! 🙏\n";
            $message .= "If you have any questions, please contact us at +60 123 456 789.";

            // Send WhatsApp message
            $this->whatsAppService->sendMessage($bookingRequest->whatsapp_no, $message);

            return redirect()->route('staff.requests.index')
                ->with('success', 'Booking request approved successfully, booking created, and WhatsApp notification sent.');
                
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

        // Prepare WhatsApp message
        $message = "Hello {$bookingRequest->name},\n\n";
        $message .= "We regret to inform you that your booking request has been *DECLINED*.\n\n";
        
        if ($bookingRequest->package) {
            $message .= "*Package:* {$bookingRequest->package->name}\n";
        }
        
        if ($bookingRequest->venue) {
            $message .= "*Venue:* {$bookingRequest->venue->name}\n";
        }
        
        if ($bookingRequest->event_date) {
            $message .= "*Event Date:* {$bookingRequest->event_date->format('d M Y')}\n";
        }
        
        $message .= "\n*Reason:*\n{$request->admin_notes}\n";
        
        // Add account information if a new account was created
        if ($accountCreated) {
            $message .= "\n*💻 YOUR ACCOUNT DETAILS:*\n";
            $message .= "Despite this rejection, we've created an account for you on our website so you can explore other options:\n";
            $message .= "*Email:* {$bookingRequest->email}\n";
            $message .= "*Password:* {$password}\n\n";
            $message .= "*LOGIN LINK:* " . route('login') . "\n\n";
            $message .= "⚠️ *IMPORTANT:* For security purposes, please log in and change your password as soon as possible by visiting your profile page: " . route('profile.edit') . "\n";
            $message .= "You can explore other packages and venues that might suit your needs.\n";
        } else {
            $message .= "\n*🔍 EXPLORE OTHER OPTIONS:*\n";
            $message .= "You can log into your account to explore other packages and venues that might better suit your needs: " . route('login') . "\n";
            $message .= "View all available venues: " . route('public.venues') . "\n";
        }
        
        $message .= "\nWe apologize for any inconvenience and thank you for considering Enak Rasa Wedding Hall. 🙏\n";
        $message .= "If you have any questions, please contact us at +60 123 456 789.";

        // Send WhatsApp message
        $this->whatsAppService->sendMessage($bookingRequest->whatsapp_no, $message);

        return redirect()->route('staff.requests.index')
            ->with('success', 'Booking request rejected successfully and WhatsApp notification sent.');
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