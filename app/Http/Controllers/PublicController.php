<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Package;
use App\Models\Gallery;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display all venues and handle venue selection
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showVenues(Request $request)
    {
        // Get all venues for the buttons
        $venues = Venue::all();
        
        // If venue_id is provided in the request, load that venue with its packages and galleries
        $selectedVenue = null;
        $packages = collect();
        $galleries = collect();
        
        if ($request->has('venue_id')) {
            $selectedVenue = Venue::findOrFail($request->venue_id);
            $packages = Package::where('venue_id', $request->venue_id)
                ->with(['prices', 'packageItems.item'])
                ->get();
            $galleries = Gallery::where('venue_id', $request->venue_id)->get();
        }
        
        return view('public.venues', compact('venues', 'selectedVenue', 'packages', 'galleries'));
    }
    
    /**
     * Display details for a specific package
     * 
     * @param Package $package
     * @return \Illuminate\View\View
     */
    public function showPackage(Package $package)
    {
        $package->load(['venue', 'prices', 'packageItems.item.category']);
        
        // Get related packages from the same venue
        $relatedPackages = Package::where('venue_id', $package->venue_id)
            ->where('id', '!=', $package->id)
            ->with(['prices', 'venue'])
            ->take(3)
            ->get();
        
        return view('public.package-details', compact('package', 'relatedPackages'));
    }
}