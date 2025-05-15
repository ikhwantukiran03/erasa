<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    /**
     * Show form to confirm a reservation with package selection.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showConfirmForm(Booking $booking)
    {
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

        // Get available packages for selection
        $packages = \App\Models\Package::orderBy('name')->get();
        
        return view('user.bookings.confirm-reservation', compact('booking', 'packages'));
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

        // Validate inputs
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'price_id' => 'required|exists:prices,id',
        ]);

        // Update the booking type to wedding, status to waiting for deposit, and add package details
        $booking->type = 'wedding';
        $booking->status = 'waiting for deposit';
        $booking->package_id = $request->package_id;
        $booking->price_id = $request->price_id;
        $booking->save();

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your reservation has been confirmed and converted to a wedding booking. Please proceed with the deposit payment to secure your date.');
    }

    /**
     * Cancel a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelBooking(Booking $booking)
    {
        // Check if user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to cancel this booking.');
        }

        // Check if booking can be cancelled (not already cancelled or completed)
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This booking cannot be cancelled because it is already ' . $booking->status . '.');
        }

        // Cancel the booking
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your booking has been successfully cancelled.');
    }
} 