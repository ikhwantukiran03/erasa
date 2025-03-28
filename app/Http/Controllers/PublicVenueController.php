<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class PublicVenueController extends Controller
{
    /**
     * Display a listing of venues for the public.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $venues = Venue::all();
        return view('venues.index', compact('venues'));
    }

    /**
     * Display the specified venue for the public.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\View\View
     */
    public function show(Venue $venue)
    {
        // Load related data for the venue page
        $venue->load(['galleries', 'packages.prices']);
        
        return view('venues.show', compact('venue'));
    }

    /**
     * Process a venue booking inquiry
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitInquiry(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'event_date' => 'required|date|after:today',
            'guests' => 'required|integer|min:1',
            'package_id' => 'nullable|exists:packages,id',
            'message' => 'nullable|string',
        ]);

        // Here you would typically:
        // 1. Save the inquiry to the database
        // 2. Send notification emails
        // 3. Create a booking record if needed
        
        // For now, we'll just redirect with a success message
        return redirect()->back()->with('success', 'Thank you for your inquiry! Our team will contact you shortly.');
    }

    /**
     * Display the venue search page and results
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = Venue::query();
        
        // Apply filters
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('state', 'like', "%{$location}%")
                  ->orWhere('postal_code', 'like', "%{$location}%");
            });
        }
        
        // Capacity filter would need a relationship with a 'capacity' field
        // This is just a placeholder example
        if ($request->filled('capacity')) {
            $capacity = $request->capacity;
            // Assuming venues have a capacity field or relationship
            $query->where('capacity', '>=', $capacity);
        }
        
        $venues = $query->get();
        
        return view('venues.search', compact('venues'));
    }
}