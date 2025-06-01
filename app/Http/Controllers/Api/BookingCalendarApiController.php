<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\BookingRequest;

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
     * Get list of customers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers()
    {
        $customers = User::where('role', 'customer')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        return response()->json($customers);
    }

    /**
     * Get list of packages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackages()
    {
        $packages = Package::with('venue:id,name')
            ->select('id', 'name', 'venue_id')
            ->get();
        return response()->json($packages);
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
        $session = $request->query('session');
        $venueId = $request->query('venue_id');
        $excludeBookingId = $request->query('exclude_booking_id'); // For edit mode
        
        if (!$date || !$venueId || !$session) {
            return response()->json(['available' => true]);
        }
        
        // Check if there's already a confirmed booking (wedding or reservation) for this venue, date and session
        $bookingQuery = Booking::where('booking_date', $date)
            ->where('venue_id', $venueId)
            ->where('session', $session)
            ->whereIn('type', ['wedding', 'reservation']) // Only check wedding and reservation bookings
            ->where('status', '!=', 'cancelled');
            
        // Exclude current booking if editing
        if ($excludeBookingId) {
            $bookingQuery->where('id', '!=', $excludeBookingId);
        }
        
        $existingBooking = $bookingQuery->exists();

        // Check if there's already a pending booking request (reservation or booking) for this venue, date and session
        $existingRequest = BookingRequest::where('event_date', $date)
            ->where('venue_id', $venueId)
            ->where('session', $session)
            ->whereIn('type', ['reservation', 'booking']) // Only check reservation and booking requests
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        return response()->json(['available' => !($existingBooking || $existingRequest)]);
    }

    /**
     * Create a quick booking from calendar date selection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQuickBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:users,id',
            'venue_id' => 'required|exists:venues,id',
            'booking_date' => 'required|date|after:today',
            'session' => 'required|in:morning,evening',
            'type' => 'required|in:wedding,viewing,reservation,appointment',
            'package_id' => 'nullable|exists:packages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Double-check availability
        $existingBooking = Booking::where('booking_date', $request->booking_date)
            ->where('venue_id', $request->venue_id)
            ->where('session', $request->session)
            ->whereIn('type', ['wedding', 'reservation'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'This venue is no longer available for the selected date and session.'
            ], 409);
        }

        try {
            // Create the booking
            $booking = Booking::create([
                'user_id' => $request->customer_id,
                'venue_id' => $request->venue_id,
                'package_id' => $request->package_id,
                'booking_date' => $request->booking_date,
                'session' => $request->session,
                'type' => $request->type,
                'status' => $request->type === 'reservation' ? 'ongoing' : 'waiting for deposit',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking->load(['venue', 'user', 'package'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
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