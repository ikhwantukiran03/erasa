<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Show the form for submitting an invoice.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSubmitForm(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to access this booking.');
        }
        
        // Check if the booking type and status are appropriate for invoice submission
        if ($booking->type !== 'wedding' || $booking->status !== 'waiting for deposit') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This booking does not require an invoice submission.');
        }
        
        return view('user.invoices.submit', compact('booking'));
    }
    
    /**
     * Process the invoice submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request, Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to access this booking.');
        }
        
        // Check if the booking type and status are appropriate for invoice submission
        if ($booking->type !== 'wedding' || $booking->status !== 'waiting for deposit') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This booking does not require an invoice submission.');
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'invoice' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Delete old invoice if exists
            if ($booking->invoice_path) {
                Storage::disk('public')->delete($booking->invoice_path);
            }
            
            // Store the new invoice
            $path = $request->file('invoice')->store('invoices', 'public');
            
            // Update the booking
            $booking->invoice_path = $path;
            $booking->invoice_submitted_at = now();
            $booking->invoice_notes = $request->notes;
            $booking->save();
            
            return redirect()->route('user.bookings.show', $booking)
                ->with('success', 'Your payment proof has been submitted successfully. Our staff will verify it shortly.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your payment proof: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Show all bookings with submitted invoices awaiting verification (Staff/Admin only).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $bookings = Booking::where('status', 'waiting for deposit')
            ->whereNotNull('invoice_path')
            ->whereNull('invoice_verified_at')
            ->with(['user', 'venue', 'package'])
            ->orderBy('invoice_submitted_at', 'desc')
            ->get();
            
        return view('staff.invoices.index', compact('bookings'));
    }
    
    /**
     * Show the invoice verification form (Staff/Admin only).
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerificationForm(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Check if booking has a submitted invoice
        if (!$booking->invoice_path) {
            return redirect()->route('staff.invoices.index')
                ->with('error', 'This booking does not have a submitted payment proof.');
        }
        
        return view('staff.invoices.verify', compact('booking'));
    }
    
    /**
     * Process the invoice verification (Staff/Admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Check if booking has a submitted invoice
        if (!$booking->invoice_path) {
            return redirect()->route('staff.invoices.index')
                ->with('error', 'This booking does not have a submitted payment proof.');
        }
        
        $validator = Validator::make($request->all(), [
            'verification_result' => ['required', 'in:approve,reject'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            if ($request->verification_result === 'approve') {
                // If approved, update booking status
                $booking->status = 'ongoing';
                $booking->invoice_verified_at = now();
                $booking->invoice_verified_by = Auth::id();
                $booking->invoice_notes = $request->notes ? 
                    ($booking->invoice_notes . "\n\nStaff Notes: " . $request->notes) : 
                    $booking->invoice_notes;
                $booking->save();
                
                // TODO: Send notification to user about approved payment
                
                return redirect()->route('staff.invoices.index')
                    ->with('success', 'Payment proof has been verified and booking status updated to Ongoing.');
            } else {
                // If rejected, add notes but leave status as is
                $booking->invoice_notes = $request->notes ? 
                    ($booking->invoice_notes . "\n\nStaff Notes: " . $request->notes . "\n[REJECTED]") : 
                    ($booking->invoice_notes . "\n[REJECTED]");
                $booking->save();
                
                // TODO: Send notification to user about rejected payment
                
                return redirect()->route('staff.invoices.index')
                    ->with('success', 'Payment proof has been rejected. The customer will need to resubmit.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }
}