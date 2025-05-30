<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Services\CloudinaryService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    protected $cloudinaryService;
    protected $emailService;

    /**
     * Create a new controller instance.
     *
     * @param CloudinaryService $cloudinaryService
     * @param EmailService $emailService
     * @return void
     */
    public function __construct(CloudinaryService $cloudinaryService, EmailService $emailService)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the invoices for staff.
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

        // Build query for pending invoices
        $pendingQuery = Invoice::with(['booking.user', 'booking.venue'])
            ->pending()
            ->orderBy('created_at', 'desc');

        // Build query for verified invoices
        $verifiedQuery = Invoice::with(['booking.user', 'booking.venue', 'verifiedBy'])
            ->verified()
            ->orderBy('invoice_verified_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $verifiedQuery = $verifiedQuery->whereRaw('1 = 0'); // No results
            } elseif ($request->status === 'verified') {
                $pendingQuery = $pendingQuery->whereRaw('1 = 0'); // No results
            } elseif ($request->status === 'rejected') {
                $pendingQuery = $pendingQuery->whereRaw('1 = 0'); // No results
                $verifiedQuery = $verifiedQuery->whereRaw('1 = 0'); // No results
                // Add rejected query if needed
            }
        }

        if ($request->filled('type')) {
            $pendingQuery->where('type', $request->type);
            $verifiedQuery->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $pendingQuery->whereHas('booking', function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
            
            $verifiedQuery->whereHas('booking', function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $pendingInvoices = $pendingQuery->paginate(10, ['*'], 'pending_page');
        $verifiedInvoices = $verifiedQuery->paginate(10, ['*'], 'verified_page');

        return view('staff.invoices.index', compact('pendingInvoices', 'verifiedInvoices'));
    }

    /**
     * Show the form for verification (staff view).
     *
     * @param \App\Models\Booking $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerificationForm(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $invoices = $booking->invoice()->get();
        
        return view('staff.invoices.verify', compact('booking', 'invoices'));
    }

    /**
     * Show the form for submitting invoice (user view).
     *
     * @param \App\Models\Booking $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSubmitForm(Booking $booking)
    {
        if (Auth::id() !== $booking->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        // Calculate payment schedule
        $paymentSchedule = $this->calculatePaymentSchedule($booking);
        
        // Determine which invoice types are available for submission
        $availableTypes = $this->getAvailableInvoiceTypes($booking, $paymentSchedule);
        
        // Get existing invoices
        $invoices = $booking->invoice()->get();

        return view('user.invoices.create', compact('booking', 'paymentSchedule', 'availableTypes', 'invoices'));
    }

    /**
     * Submit invoice (user).
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request, Booking $booking)
    {
        if (Auth::id() !== $booking->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'in:deposit,second_deposit,balance,full_payment'],
            'invoice_file' => ['required', 'file', 'max:5120', 'mimes:jpeg,png,jpg,pdf'], // 5MB max
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if invoice type already exists for this booking
        $existingInvoice = $booking->invoice()->where('type', $request->type)->first();
        if ($existingInvoice) {
            // Don't allow resubmission if already verified
            if ($existingInvoice->status === 'verified') {
                return redirect()->back()
                    ->with('error', 'This payment has already been verified and cannot be resubmitted.');
            }
            
            // Only check if event has already passed - remove deadline restrictions
            $today = Carbon::today();
            $eventDate = Carbon::parse($booking->booking_date);
            
            if ($today->greaterThan($eventDate)) {
                return redirect()->back()
                    ->with('error', 'Cannot submit payments after the event date has passed.');
            }
            
            // Allow resubmission - delete old file from Cloudinary if it exists
            if ($existingInvoice->invoice_public_id) {
                $this->cloudinaryService->deleteFile($existingInvoice->invoice_public_id);
            }
        }

        // Only check if event has already passed - remove all other deadline restrictions
        $today = Carbon::today();
        $eventDate = Carbon::parse($booking->booking_date);
        
        if ($today->greaterThan($eventDate)) {
            return redirect()->back()
                ->with('error', 'Cannot submit payments after the event date has passed.');
        }

        // Upload file to Cloudinary
        $uploadResult = $this->cloudinaryService->uploadFile($request->file('invoice_file'));
        
        if (!$uploadResult) {
            return redirect()->back()
                ->with('error', 'Failed to upload invoice. Please try again.');
        }

        // Calculate amount based on invoice type
        $amount = $this->calculateAmount($booking, $request->type);

        // Create or update invoice
        if ($existingInvoice) {
            $existingInvoice->update([
                'invoice_path' => $uploadResult['url'],
                'invoice_public_id' => $uploadResult['public_id'],
                'invoice_notes' => $request->notes,
                'amount' => $amount,
                'invoice_submitted_at' => now(),
                'status' => 'pending',
            ]);
            
            $message = 'Your payment proof has been resubmitted successfully.';
        } else {
            // Calculate due date
            $paymentSchedule = $this->calculatePaymentSchedule($booking);
            $dueDate = $paymentSchedule[$request->type] ?? null;
            
            Invoice::create([
                'booking_id' => $booking->id,
                'type' => $request->type,
                'invoice_path' => $uploadResult['url'],
                'invoice_public_id' => $uploadResult['public_id'],
                'invoice_notes' => $request->notes,
                'amount' => $amount,
                'due_date' => $dueDate,
                'invoice_submitted_at' => now(),
                'status' => 'pending',
            ]);
            
            $message = 'Your payment proof has been submitted successfully.';
        }

        // If this is a deposit payment, set status to ongoing immediately
        if ($request->type === 'deposit') {
            $booking->update(['status' => 'ongoing']);
        }

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', $message);
    }

    /**
     * Verify invoice (staff).
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $validator = Validator::make($request->all(), [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'action' => ['required', 'in:verify,reject'],
            'staff_notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        // Check if invoice belongs to this booking
        if ($invoice->booking_id !== $booking->id) {
            return redirect()->back()
                ->with('error', 'This invoice does not belong to the specified booking.');
        }

        // Update invoice status
        $invoice->update([
            'status' => $request->action === 'verify' ? 'verified' : 'rejected',
            'invoice_verified_at' => $request->action === 'verify' ? now() : null,
            'invoice_verified_by' => $request->action === 'verify' ? Auth::id() : null,
            'invoice_notes' => $request->staff_notes,
        ]);

        // Send email notification if payment is verified
        if ($request->action === 'verify') {
            try {
                // Prepare email data
                $emailData = [
                    'customer_name' => $booking->user->name,
                    'booking_id' => $booking->id,
                    'payment_type' => $invoice->type,
                    'booking_type' => $booking->type,
                    'amount' => $invoice->amount,
                    'verified_date' => now()->format('M d, Y H:i'),
                    'staff_notes' => $request->staff_notes,
                ];

                // Send email notification
                $this->emailService->sendInvoiceVerificationEmail($booking->user->email, $emailData);
                
            } catch (\Exception $e) {
                // Log the error but don't fail the verification process
                \Log::error('Failed to send invoice verification email: ' . $e->getMessage());
            }
        } else {
            // Send rejection email
            try {
                // Prepare email data for rejection
                $emailData = [
                    'customer_name' => $booking->user->name,
                    'booking_id' => $booking->id,
                    'payment_type' => $invoice->type,
                    'booking_type' => $booking->type,
                    'amount' => $invoice->amount,
                    'rejected_date' => now()->format('M d, Y H:i'),
                    'staff_notes' => $request->staff_notes,
                ];

                // Send rejection email notification
                $this->emailService->sendInvoiceRejectionEmail($booking->user->email, $emailData);
                
            } catch (\Exception $e) {
                // Log the error but don't fail the rejection process
                \Log::error('Failed to send invoice rejection email: ' . $e->getMessage());
            }
        }

        // If this is a deposit payment and it's rejected, we don't change the status
        // Status will remain 'ongoing' as set during submission

        $message = $request->action === 'verify' 
            ? 'Payment has been verified successfully and email notification sent.' 
            : 'Payment has been rejected and email notification sent.';

        return redirect()->route('staff.invoices.index')
            ->with('success', $message);
    }

    /**
     * Calculate the payment schedule for a booking.
     *
     * @param \App\Models\Booking $booking
     * @return array
     */
    private function calculatePaymentSchedule(Booking $booking)
    {
        $eventDate = Carbon::parse($booking->booking_date);
        
        if ($booking->type === 'wedding') {
            // Wedding Event Payment Schedule
            return [
                'deposit' => Carbon::today(), // RM 3000 deposit - no deadline
                'second_deposit' => $eventDate->copy()->subMonths(6), // 50% of total - 6 months before event
                'balance' => $eventDate->copy()->subMonth(1), // Remaining amount - 1 month before event
            ];
        } else {
            // Other Event Payment Schedule
            return [
                'deposit' => Carbon::today(), // 50% of total - no deadline
                'balance' => $eventDate->copy()->subWeek(1), // Remaining amount - 1 week before event
            ];
        }
    }

    /**
     * Determine which invoice types are available for submission.
     *
     * @param \App\Models\Booking $booking
     * @param array $paymentSchedule
     * @return array
     */
    private function getAvailableInvoiceTypes(Booking $booking, $paymentSchedule)
    {
        $availableTypes = [];
        $today = Carbon::today();
        
        // Get all existing invoices for this booking
        $existingInvoices = $booking->invoice()->get();
        $verifiedTypes = $existingInvoices->where('status', 'verified')->pluck('type')->toArray();
        $pendingTypes = $existingInvoices->where('status', 'pending')->pluck('type')->toArray();
        $rejectedTypes = $existingInvoices->where('status', 'rejected')->pluck('type')->toArray();
        
        // Check if full payment has been verified
        if (in_array('full_payment', $verifiedTypes)) {
            return []; // No more payments needed
        }
        
        // Special handling for "waiting for full payment" status
        if ($booking->status === 'waiting for full payment') {
            if (!in_array('full_payment', $verifiedTypes)) {
                // Allow full payment submission if not verified yet and event hasn't passed
                $eventDate = Carbon::parse($booking->booking_date);
                if ($today->lessThan($eventDate)) {
                    $availableTypes[] = 'full_payment';
                }
            }
            return $availableTypes;
        }
        
        // Check if event has already passed - only restriction we keep
        $eventDate = Carbon::parse($booking->booking_date);
        if ($today->greaterThan($eventDate)) {
            return []; // No payments allowed after event date
        }
        
        // For regular payment flow - remove all deadline restrictions
        if ($booking->type === 'wedding') {
            // Wedding Event Payment Flow
            
            // 1. Deposit (RM 3000) - always available if not verified
            if (!in_array('deposit', $verifiedTypes)) {
                $availableTypes[] = 'deposit';
            }
            // 2. Second Deposit (50% of total) - available if deposit is verified and not already submitted
            elseif (in_array('deposit', $verifiedTypes) && !in_array('second_deposit', $verifiedTypes)) {
                $availableTypes[] = 'second_deposit';
            }
            // 3. Balance Payment - available if second deposit is verified and not already submitted
            elseif (in_array('second_deposit', $verifiedTypes) && !in_array('balance', $verifiedTypes)) {
                $availableTypes[] = 'balance';
            }
            
            // Full payment option - always available as alternative if no verified payments made yet
            if (empty($verifiedTypes)) {
                $availableTypes[] = 'full_payment';
            }
            
        } else {
            // Other Event Payment Flow (50% deposit, 50% balance)
            
            // 1. Deposit (50%) - always available if not verified
            if (!in_array('deposit', $verifiedTypes)) {
                $availableTypes[] = 'deposit';
            }
            // 2. Balance (50%) - available if deposit is verified and not already submitted
            elseif (in_array('deposit', $verifiedTypes) && !in_array('balance', $verifiedTypes)) {
                $availableTypes[] = 'balance';
            }
            
            // Full payment option - always available as alternative if no verified payments made yet
            if (empty($verifiedTypes)) {
                $availableTypes[] = 'full_payment';
            }
        }
        
        return $availableTypes;
    }

    /**
     * Calculate the amount for an invoice.
     *
     * @param \App\Models\Booking $booking
     * @param string $type
     * @return float
     */
    private function calculateAmount(Booking $booking, $type)
    {
        // Get total package price
        $totalPrice = 0;
        
        if ($booking->price_id) {
            $price = \App\Models\Price::find($booking->price_id);
            if ($price) {
                $totalPrice = $price->price;
            }
        } elseif ($booking->package) {
            $totalPrice = $booking->package->min_price;
        }
        
        if ($booking->type === 'wedding') {
            // Wedding Event Payment Amounts
            switch ($type) {
                case 'deposit':
                    return 3000; // Fixed RM 3000 deposit
                case 'second_deposit':
                    return $totalPrice * 0.50; // 50% of total
                case 'balance':
                    // Remaining amount after deposit and second deposit
                    return $totalPrice - 3000 - ($totalPrice * 0.50);
                case 'full_payment':
                    return $totalPrice; // 100% full payment
                default:
                    return 0;
            }
        } else {
            // Other Event Payment Amounts
            switch ($type) {
                case 'deposit':
                    return $totalPrice * 0.50; // 50% of total
                case 'balance':
                    return $totalPrice * 0.50; // Remaining 50%
                case 'full_payment':
                    return $totalPrice; // 100% full payment
                default:
                    return 0;
            }
        }
    }

    /**
     * Quick view for invoice details (AJAX).
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function quickView(Invoice $invoice)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return view('staff.invoices.quick-view', compact('invoice'));
    }

    /**
     * Bulk verify invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkVerify(Request $request)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'invoice_ids' => ['required', 'array'],
            'invoice_ids.*' => ['exists:invoices,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid invoice IDs'], 400);
        }

        try {
            $invoices = Invoice::with(['booking.user'])
                              ->whereIn('id', $request->invoice_ids)
                              ->where('status', 'pending')
                              ->get();

            $verifiedCount = 0;
            foreach ($invoices as $invoice) {
                $invoice->update([
                    'status' => 'verified',
                    'invoice_verified_at' => now(),
                    'verified_by' => auth()->id(),
                ]);

                // Update booking status if this is a deposit
                if ($invoice->type === 'deposit') {
                    $invoice->booking->update(['status' => 'ongoing']);
                }

                // Send email notification
                try {
                    $emailData = [
                        'customer_name' => $invoice->booking->user->name,
                        'booking_id' => $invoice->booking->id,
                        'payment_type' => $invoice->type,
                        'booking_type' => $invoice->booking->type,
                        'amount' => $invoice->amount,
                        'verified_date' => now()->format('M d, Y H:i'),
                        'staff_notes' => null, // No staff notes in bulk verification
                    ];

                    $this->emailService->sendInvoiceVerificationEmail($invoice->booking->user->email, $emailData);
                    
                } catch (\Exception $e) {
                    // Log the error but don't fail the verification process
                    \Log::error('Failed to send bulk invoice verification email for invoice ' . $invoice->id . ': ' . $e->getMessage());
                }

                $verifiedCount++;
            }

            return response()->json([
                'success' => true, 
                'message' => "Successfully verified {$verifiedCount} payment(s) and sent email notifications"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while verifying payments'
            ], 500);
        }
    }

    /**
     * Bulk reject invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkReject(Request $request)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'invoice_ids' => ['required', 'array'],
            'invoice_ids.*' => ['exists:invoices,id'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided'], 400);
        }

        try {
            $invoices = Invoice::with(['booking.user'])
                              ->whereIn('id', $request->invoice_ids)
                              ->where('status', 'pending')
                              ->get();

            $rejectedCount = 0;
            foreach ($invoices as $invoice) {
                $invoice->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->reason,
                    'invoice_verified_at' => now(),
                    'verified_by' => auth()->id(),
                ]);

                // Send email notification
                try {
                    $emailData = [
                        'customer_name' => $invoice->booking->user->name,
                        'booking_id' => $invoice->booking->id,
                        'payment_type' => $invoice->type,
                        'booking_type' => $invoice->booking->type,
                        'amount' => $invoice->amount,
                        'rejected_date' => now()->format('M d, Y H:i'),
                        'staff_notes' => $request->reason,
                    ];

                    $this->emailService->sendInvoiceRejectionEmail($invoice->booking->user->email, $emailData);
                    
                } catch (\Exception $e) {
                    // Log the error but don't fail the rejection process
                    \Log::error('Failed to send bulk invoice rejection email for invoice ' . $invoice->id . ': ' . $e->getMessage());
                }

                $rejectedCount++;
            }

            return response()->json([
                'success' => true, 
                'message' => "Successfully rejected {$rejectedCount} payment(s) and sent email notifications"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while rejecting payments'
            ], 500);
        }
    }
}