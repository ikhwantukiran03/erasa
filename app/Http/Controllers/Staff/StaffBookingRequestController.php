<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
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
                $password = Str::random(8); // Generate a random password
                
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

        // Prepare WhatsApp message
        $message = "Hello {$bookingRequest->name},\n\n";
        $message .= "Your booking request has been APPROVED.\n";
        
        if ($bookingRequest->package) {
            $message .= "Package: {$bookingRequest->package->name}\n";
        }
        
        if ($bookingRequest->venue) {
            $message .= "Venue: {$bookingRequest->venue->name}\n";
        }
        
        if ($bookingRequest->event_date) {
            $message .= "Event Date: {$bookingRequest->event_date->format('d M Y')}\n";
        }
        
        if ($request->admin_notes) {
            $message .= "\nAdditional information:\n{$request->admin_notes}\n";
        }
        
        // Add account information if a new account was created
        if ($accountCreated) {
            $message .= "\nWe've created an account for you on our website:\n";
            $message .= "Email: {$bookingRequest->email}\n";
            $message .= "Password: {$password}\n";
            $message .= "You can log in at: " . route('login') . "\n";
        }
        
        $message .= "\nThank you for choosing Enak Rasa Wedding Hall.";

        // Send WhatsApp message
        $this->whatsAppService->sendMessage($bookingRequest->whatsapp_no, $message);

        return redirect()->route('staff.requests.index')
            ->with('success', 'Booking request approved successfully and WhatsApp notification sent.');
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
                $password = Str::random(8); // Generate a random password
                
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
        $message .= "We regret to inform you that your booking request has been DECLINED.\n";
        
        if ($bookingRequest->package) {
            $message .= "Package: {$bookingRequest->package->name}\n";
        }
        
        if ($bookingRequest->venue) {
            $message .= "Venue: {$bookingRequest->venue->name}\n";
        }
        
        if ($bookingRequest->event_date) {
            $message .= "Event Date: {$bookingRequest->event_date->format('d M Y')}\n";
        }
        
        $message .= "\nReason:\n{$request->admin_notes}\n";
        
        // Add account information if a new account was created
        if ($accountCreated) {
            $message .= "\nWe've created an account for you on our website:\n";
            $message .= "Email: {$bookingRequest->email}\n";
            $message .= "Password: {$password}\n";
            $message .= "You can log in at: " . route('login') . "\n";
            $message .= "You can explore other packages and venues that might suit your needs.\n";
        }
        
        $message .= "\nThank you for considering Enak Rasa Wedding Hall.";

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