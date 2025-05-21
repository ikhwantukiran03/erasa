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
        // Start with a base query
        $venuesQuery = \App\Models\Venue::query();
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $venuesQuery->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Apply capacity filter if provided
        if ($request->has('capacity') && $request->capacity) {
            $capacityRanges = [
                'small' => [0, 100],
                'medium' => [100, 300],
                'large' => [300, 1000],
                'xl' => [1000, 99999]
            ];
            
            if (isset($capacityRanges[$request->capacity])) {
                $range = $capacityRanges[$request->capacity];
                $venuesQuery->whereBetween('capacity', [$range[0], $range[1]]);
            }
        }
        
        // Apply city filter if provided
        if ($request->has('city') && $request->city) {
            $venuesQuery->where('city', $request->city);
        }
        
        // Paginate results
        $venues = $venuesQuery->paginate(6)->withQueryString();
        
        // Get all cities for the filter dropdown
        $cities = \App\Models\Venue::select('city')->distinct()->pluck('city');
        
        // If venue_id is provided, load that venue with its packages and galleries
        $selectedVenue = null;
        $packages = collect();
        $galleries = collect();
        
        if ($request->has('venue_id')) {
            $selectedVenue = \App\Models\Venue::findOrFail($request->venue_id);
            $packages = \App\Models\Package::where('venue_id', $request->venue_id)
                ->with(['prices', 'packageItems.item'])
                ->get();
            $galleries = \App\Models\Gallery::where('venue_id', $request->venue_id)->get();
        }
        
        return view('public.venues', compact('venues', 'cities', 'selectedVenue', 'packages', 'galleries'));
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