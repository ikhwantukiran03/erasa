<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookingCalendarApiController extends Controller
{
    /**
     * Get calendar data for the specified month.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(Request $request): JsonResponse
    {
        // Get current month and year from request or use current date
        $month = $request->input('month') ?? date('m');
        $year = $request->input('year') ?? date('Y');
        
        // Create a Carbon instance for the first day of the month
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
        
        // Get start and end dates for the calendar (including days from prev/next months)
        $startDate = $firstDayOfMonth->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endDate = $firstDayOfMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
        
        // Build the query for bookings
        $bookingQuery = Booking::where('status', 'ongoing')
            ->where('type', 'wedding')
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        
        // Filter by venue if requested
        if ($request->has('venue_id') && $request->venue_id) {
            $bookingQuery->where('venue_id', $request->venue_id);
        }
        
        // Get bookings for the date range
        $bookings = $bookingQuery->with(['venue'])->get();
        
        // Group bookings by date and session
        $bookingsByDate = [];
        foreach ($bookings as $booking) {
            $dateKey = $booking->booking_date->format('Y-m-d');
            $session = $booking->session;
            
            if (!isset($bookingsByDate[$dateKey])) {
                $bookingsByDate[$dateKey] = [
                    'morning' => null,
                    'evening' => null,
                ];
            }
            
            $bookingsByDate[$dateKey][$session] = [
                'id' => $booking->id,
                'venue' => [
                    'id' => $booking->venue->id,
                    'name' => $booking->venue->name
                ]
            ];
        }
        
        // Build the calendar days
        $calendarDays = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            $isCurrentMonth = $currentDate->format('m') == $month;
            $isToday = $currentDate->isToday();
            
            // Check if there are bookings for this date
            $hasMorningBooking = isset($bookingsByDate[$dateKey]) && $bookingsByDate[$dateKey]['morning'];
            $hasEveningBooking = isset($bookingsByDate[$dateKey]) && $bookingsByDate[$dateKey]['evening'];
            
            $calendarDays[] = [
                'day' => $currentDate->format('j'),
                'date' => $dateKey,
                'isCurrentMonth' => $isCurrentMonth,
                'isToday' => $isToday,
                'bookings' => [
                    'morning' => $hasMorningBooking,
                    'evening' => $hasEveningBooking,
                ],
                'bookingDetails' => isset($bookingsByDate[$dateKey]) ? $bookingsByDate[$dateKey] : [
                    'morning' => null,
                    'evening' => null,
                ],
            ];
            
            $currentDate->addDay();
        }
        
        // Prepare calendar metadata
        $calendarMeta = [
            'currentMonth' => $firstDayOfMonth->format('F'),
            'currentYear' => $firstDayOfMonth->format('Y'),
            'prevMonth' => $firstDayOfMonth->copy()->subMonth()->format('m'),
            'prevYear' => $firstDayOfMonth->copy()->subMonth()->format('Y'),
            'nextMonth' => $firstDayOfMonth->copy()->addMonth()->format('m'),
            'nextYear' => $firstDayOfMonth->copy()->addMonth()->format('Y'),
        ];
        
        return response()->json([
            'meta' => $calendarMeta,
            'days' => $calendarDays
        ]);
    }

    /**
     * Get upcoming bookings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingBookings(Request $request): JsonResponse
    {
        // Get upcoming bookings for the table
        $upcomingBookingsQuery = Booking::where('status', 'ongoing')
            ->where('type', 'wedding')
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->orderBy('booking_date')
            ->orderBy('session')
            ->with(['venue', 'user']);
            
        // Filter by venue if requested
        if ($request->has('venue_id') && $request->venue_id) {
            $upcomingBookingsQuery->where('venue_id', $request->venue_id);
        }
        
        // Limit the results and get the data
        $upcomingBookings = $upcomingBookingsQuery->take(10)->get();
        
        // Format the data for the API response
        $formattedBookings = $upcomingBookings->map(function($booking) {
            return [
                'id' => $booking->id,
                'date' => $booking->booking_date->format('Y-m-d'),
                'formattedDate' => $booking->booking_date->format('M d, Y'),
                'session' => $booking->session,
                'venue' => [
                    'id' => $booking->venue->id,
                    'name' => $booking->venue->name
                ],
                'customer' => [
                    'id' => $booking->user->id,
                    'name' => $booking->user->name,
                    'email' => $booking->user->email
                ],
                'type' => $booking->type
            ];
        });
        
        return response()->json($formattedBookings);
    }

    /**
     * Get all venues for filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVenues(): JsonResponse
    {
        $venues = Venue::orderBy('name')->get(['id', 'name']);
        return response()->json($venues);
    }
}