<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    /**
     * Confirm a reservation booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmReservation(Booking $booking)
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

        // Update the booking type to wedding and status to waiting for deposit
        $booking->type = 'wedding';
        $booking->status = 'waiting for deposit';
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