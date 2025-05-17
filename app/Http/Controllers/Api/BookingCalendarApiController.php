<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingCalendarApiController extends Controller
{
    /**
     * Get calendar data for the given date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(Request $request)
    {
        $start = $request->query('start', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end = $request->query('end', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $venueId = $request->query('venue_id');

        $query = Booking::with(['venue', 'user', 'package'])
            ->whereBetween('booking_date', [$start, $end])
            ->where('type', '!=', 'viewing')
            ->where('status', '!=', 'cancelled');
            
        if ($venueId) {
            $query->where('venue_id', $venueId);
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            $title = ucfirst($booking->type);
            $color = $this->getColorByBookingType($booking->type);
            
            return [
                'id' => $booking->id,
                'title' => $title,
                'start' => $booking->booking_date->format('Y-m-d'),
                'allDay' => true,
                'color' => $color,
                'extendedProps' => [
                    'session' => $booking->session,
                    'venue' => $booking->venue->name,
                    'type' => $booking->type,
                    'user' => $booking->user->name,
                    'package' => $booking->package ? $booking->package->name : '-'
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Get upcoming bookings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingBookings()
    {
        $bookings = Booking::with(['venue', 'user'])
            ->where('booking_date', '>=', Carbon::now())
            ->where('type', '!=', 'viewing')
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        return response()->json($bookings);
    }

    /**
     * Get list of venues.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVenues()
    {
        $venues = Venue::all();
        return response()->json($venues);
    }

    /**
     * Check if a venue is available on a specific date and session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');
        $session = $request->query('session', 'evening');
        $venueId = $request->query('venue_id');
        
        if (!$date || !$venueId) {
            return response()->json(['available' => true]);
        }
        
        // Check if there's already a booking (wedding or reservation) for this venue, date and session
        $existingBooking = Booking::where('booking_date', $date)
            ->where('venue_id', $venueId)
            ->where('session', $session)
            ->whereIn('type', ['wedding', 'reservation']) // Only check wedding and reservation bookings
            ->where('status', '!=', 'cancelled')
            ->exists();
        
        return response()->json(['available' => !$existingBooking]);
    }

    /**
     * Get color based on booking type.
     *
     * @param  string  $type
     * @return string
     */
    private function getColorByBookingType($type)
    {
        return match($type) {
            'wedding' => '#4CAF50',
            'viewing' => '#2196F3',
            'reservation' => '#FF9800',
            default => '#9E9E9E',
        };
    }
} 