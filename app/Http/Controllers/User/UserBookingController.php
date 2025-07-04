<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['venue', 'package', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.bookings', compact('bookings'));
    }
    
    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Booking $booking)
    {
        // Load necessary relationships
        $booking->load(['user', 'venue', 'package', 'handler']);
        
        // Check if user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to view this booking.');
        }
        
        return view('user.booking-show', compact('booking'));
    }

    /**
     * Show the confirmation form for a reservation booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirmReservationForm(Booking $booking)
    {
        // Load necessary relationships
        $booking->load(['user', 'venue', 'package']);
        
        // Check if user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to confirm this reservation.');
        }

        // Check if booking is a reservation and can be confirmed
        if ($booking->type !== 'reservation') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'Only reservations can be confirmed.');
        }
        
        // Get packages available for this venue
        $packages = Package::where('venue_id', $booking->venue_id)
            ->with(['prices', 'packageItems.item.category'])
            ->get();
            
        return view('user.bookings.confirm', compact('booking', 'packages'));
    }

    /**
     * Confirm a reservation booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmReservation(Request $request, Booking $booking)
    {
        // Load necessary relationships
        $booking->load(['user', 'venue', 'package']);
        
        // Check if user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to confirm this reservation.');
        }

        // Check if booking is a reservation and can be confirmed
        if ($booking->type !== 'reservation') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'Only reservations can be confirmed.');
        }
        
        // Validate package selection and payment option
        $validator = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'price_id' => 'nullable|exists:prices,id',
            'payment_option' => 'required|in:deposit,full',
        ]);

        // Update the booking type to wedding and status based on payment option
        $booking->type = 'wedding';
        $booking->package_id = $request->package_id;
        
        // Save the price_id if provided
        if ($request->has('price_id')) {
            $booking->price_id = $request->price_id;
        }
        
        // Set status based on payment option
        if ($request->payment_option === 'full') {
            $booking->status = 'waiting for full payment';
        } else {
            $booking->status = 'waiting for deposit';
        }
        
        $booking->save();

        $message = $request->payment_option === 'full' 
            ? 'Your reservation has been confirmed and converted to a wedding booking. Please proceed with the full payment to secure your date.'
            : 'Your reservation has been confirmed and converted to a wedding booking. Please proceed with the deposit payment to secure your date.';

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', $message);
    }

    /**
     * Cancel a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelBooking(Booking $booking)
    {
        // Load necessary relationships
        $booking->load(['user', 'venue', 'package']);
        
        // Check if user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to cancel this booking.');
        }

        // Update booking status to cancelled
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your booking has been cancelled successfully.');
    }
} 