<?php

namespace App\Http\Controllers;

use App\Models\Request as BookingRequest;
use App\Models\Venue;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RequestController extends Controller
{
    /**
     * Store a newly created booking request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'event_date' => ['nullable', 'date', 'after:today'],
            'message' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        BookingRequest::create($request->all());

        return redirect()->back()->with('success', 'Your booking request has been submitted. We will contact you shortly!');
    }

    /**
     * Display the form for creating a new booking request.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $venues = Venue::orderBy('name')->get();
        $packages = Package::orderBy('name')->get();
        
        return view('requests.create', compact('venues', 'packages'));
    }
    
    /**
     * Staff: Display a listing of booking requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $query = BookingRequest::with(['venue', 'package'])->latest();
        
        // Filter by status if requested
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $requests = $query->paginate(15);
        
        return view('staff.requests.index', compact('requests'));
    }
    
    /**
     * Staff: Update the status of a booking request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request  $bookingRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, BookingRequest $bookingRequest): RedirectResponse
    {
        if (!auth()->user()->isStaff()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,contacted,confirmed,canceled'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bookingRequest->update([
            'status' => $request->status,
        ]);

        return redirect()->route('staff.requests.index')
            ->with('success', 'Booking request status updated successfully.');
    }
}