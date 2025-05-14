<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    protected $cloudinaryService;

    /**
     * Create a new controller instance.
     *
     * @param CloudinaryService $cloudinaryService
     * @return void
     */
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Display a listing of the invoices for verification (staff view).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        $pendingInvoices = Invoice::with(['booking.user', 'booking.venue'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $verifiedInvoices = Invoice::with(['booking.user', 'booking.venue'])
            ->verified()
            ->orderBy('invoice_verified_at', 'desc')
            ->paginate(10);

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
            'type' => ['required', 'string', 'in:deposit,payment_1,payment_2,final_payment'],
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
            // If rejected, allow resubmission
            if ($existingInvoice->status === 'rejected') {
                // Delete old file from Cloudinary if it exists
                if ($existingInvoice->invoice_public_id) {
                    $this->cloudinaryService->deleteFile($existingInvoice->invoice_public_id);
                }
            } else {
                return redirect()->back()
                    ->with('error', 'An invoice of this type has already been submitted.');
            }
        }

        // Check for final payment timing
        if ($request->type === 'final_payment') {
            $eventDate = Carbon::parse($booking->booking_date);
            $today = Carbon::today();
            
            if ($today->diffInDays($eventDate, false) < 30) {
                return redirect()->back()
                    ->with('error', 'Final payment must be made at least 30 days before the event date.');
            }
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

        // If this is a deposit and booking status is 'waiting for deposit', update to 'pending_verification'
        if ($request->type === 'deposit' && $booking->status === 'waiting for deposit') {
            $booking->update(['status' => 'pending_verification']);
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

        // If this is a deposit payment and it's verified, update booking status
        if ($invoice->type === 'deposit' && $request->action === 'verify') {
            $booking->update(['status' => 'ongoing']);
        }

        $message = $request->action === 'verify' 
            ? 'Payment has been verified successfully.' 
            : 'Payment has been rejected.';

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
        
        return [
            'deposit' => Carbon::today(),
            'payment_1' => Carbon::today()->addDays(30),
            'payment_2' => Carbon::today()->addDays(60),
            'final_payment' => $eventDate->copy()->subDays(30),
        ];
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
        $existingInvoices = $booking->invoice()->whereIn('status', ['pending', 'verified'])->pluck('type')->toArray();
        
        // Deposit is always first
        if (!in_array('deposit', $existingInvoices)) {
            $availableTypes[] = 'deposit';
        }
        
        // Other payments follow a sequence
        if (in_array('deposit', $existingInvoices) && !in_array('payment_1', $existingInvoices)) {
            $availableTypes[] = 'payment_1';
        }
        
        if (in_array('payment_1', $existingInvoices) && !in_array('payment_2', $existingInvoices)) {
            $availableTypes[] = 'payment_2';
        }
        
        if (in_array('payment_2', $existingInvoices) && !in_array('final_payment', $existingInvoices)) {
            // Check if it's not too late for final payment
            $eventDate = Carbon::parse($booking->booking_date);
            $today = Carbon::today();
            
            if ($today->diffInDays($eventDate, false) >= 30) {
                $availableTypes[] = 'final_payment';
            }
        }
        
        // Add rejected invoices back to available types
        $rejectedInvoices = $booking->invoice()->where('status', 'rejected')->get();
        foreach ($rejectedInvoices as $invoice) {
            if (!in_array($invoice->type, $availableTypes)) {
                $availableTypes[] = $invoice->type;
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
        // For simplicity, assuming a package price exists
        $totalPrice = 0;
        
        if ($booking->price_id) {
            $price = \App\Models\Price::find($booking->price_id);
            if ($price) {
                $totalPrice = $price->price;
            }
        } elseif ($booking->package) {
            $totalPrice = $booking->package->min_price;
        }
        
        // Calculate amount based on type
        switch ($type) {
            case 'deposit':
                return $totalPrice * 0.10; // 10% deposit
            case 'payment_1':
                return $totalPrice * 0.30; // 30% first payment
            case 'payment_2':
                return $totalPrice * 0.30; // 30% second payment
            case 'final_payment':
                return $totalPrice * 0.30; // 30% final payment
            default:
                return 0;
        }
    }
}