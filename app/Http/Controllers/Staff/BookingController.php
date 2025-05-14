<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\Venue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
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

        $status = $request->get('status', '');
        
        $query = Booking::with(['venue', 'package', 'user', 'handler']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('staff.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show the form for creating a new booking.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        
        return view('staff.bookings.create', compact('venues', 'packages', 'users'));
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'booking_date' => ['required', 'date', 'after:today'],
            'session' => ['required', 'in:morning,evening'],
            'type' => ['required', 'in:wedding,viewing,reservation'],
            'status' => ['required', 'in:ongoing,completed,cancelled,waiting for deposit'],
            'expiry_date' => ['nullable', 'date', 'after:booking_date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check for existing bookings on the same date and session
            $existingBooking = Booking::where('venue_id', $request->venue_id)
                ->where('booking_date', $request->booking_date)
                ->where('session', $request->session)
                ->where('status', '!=', 'cancelled')
                ->first();
                
            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'This venue is already booked for the selected date and session.')
                    ->withInput();
            }
            
            $booking = new Booking();
            $booking->user_id = $request->user_id;
            $booking->venue_id = $request->venue_id;
            $booking->package_id = $request->package_id;
            $booking->booking_date = $request->booking_date;
            $booking->session = $request->session;
            $booking->type = $request->type;
            $booking->status = $request->status;
            $booking->expiry_date = $request->expiry_date;
            $booking->handled_by = auth()->id();
            $booking->save();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        
        return view('staff.bookings.edit', compact('booking', 'venues', 'packages', 'users'));
    }

    /**
     * Update the specified booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'booking_date' => ['required', 'date'],
            'session' => ['required', 'in:morning,evening'],
            'type' => ['required', 'in:wedding,viewing,reservation'],
            'status' => ['required', 'in:ongoing,completed,cancelled'],
            'expiry_date' => ['nullable', 'date', 'after:booking_date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check for existing bookings on the same date and session
            $existingBooking = Booking::where('venue_id', $request->venue_id)
                ->where('booking_date', $request->booking_date)
                ->where('session', $request->session)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $booking->id)
                ->first();
                
            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'This venue is already booked for the selected date and session.')
                    ->withInput();
            }
            
            $booking->user_id = $request->user_id;
            $booking->venue_id = $request->venue_id;
            $booking->package_id = $request->package_id;
            $booking->booking_date = $request->booking_date;
            $booking->session = $request->session;
            $booking->type = $request->type;
            $booking->status = $request->status;
            $booking->expiry_date = $request->expiry_date;
            $booking->save();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified booking from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            $booking->delete();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the booking: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Booking $booking)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            $booking->status = 'cancelled';
            $booking->save();
            
            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking cancelled successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while cancelling the booking: ' . $e->getMessage());
        }
    }
}