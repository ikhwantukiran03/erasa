<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Venue;
use Illuminate\Http\Request;

class PackageRecommendationController extends Controller
{
    /**
     * Show the package recommendation form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $venues = Venue::all();
        return view('package-recommendation.index', compact('venues'));
    }

    /**
     * Get package recommendations based on user preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function recommend(Request $request)
    {
        $request->validate([
            'budget' => 'required|numeric|min:0',
            'guest_count' => 'required|integer|min:1',
            'venue_preference' => 'nullable|exists:venues,id',
            'preferences' => 'nullable|array',
        ]);

        $budget = $request->budget;
        $guestCount = $request->guest_count;
        $venueId = $request->venue_preference;
        $preferences = $request->preferences ?? [];

        // Start with all packages
        $query = Package::with(['venue', 'prices', 'items']);
        
        // Filter by venue if specified
        if ($venueId) {
            $query->where('venue_id', $venueId);
        }

        // Get all packages
        $packages = $query->get();
        
        // Score each package based on criteria
        $scoredPackages = $packages->map(function ($package) use ($budget, $guestCount, $preferences) {
            // Base score starts at 100
            $score = 100;
            
            // Budget match (higher score if closer to budget)
            $minPrice = $package->min_price;
            if ($minPrice > $budget) {
                // Reduce score if over budget
                $percentOver = (($minPrice - $budget) / $budget) * 100;
                $score -= min(50, $percentOver * 2); // Max 50 point reduction
            } else {
                // Slight reduction if way under budget (might be missing features)
                $percentUnder = ($budget - $minPrice) / $budget * 100;
                if ($percentUnder > 30) {
                    $score -= min(20, ($percentUnder - 30)); // Max 20 point reduction
                }
            }
            
            // Check if package has items that match user preferences
            $packageItems = $package->items->pluck('name')->map(function($name) {
                return strtolower($name);
            })->toArray();
            
            foreach ($preferences as $preference) {
                $found = false;
                foreach ($packageItems as $item) {
                    if (str_contains($item, strtolower($preference))) {
                        $found = true;
                        break;
                    }
                }
                
                if ($found) {
                    $score += 10; // Add 10 points for each preference match
                }
            }
            
            // Check venue capacity (if we had that data)
            // For now, just do a basic calculation based on venue size
            // This is a placeholder - you'd want to use actual venue capacity from the database
            
            return [
                'package' => $package,
                'score' => max(0, $score),
                'price' => $minPrice,
                'matches_budget' => $minPrice <= $budget,
                'preference_matches' => collect($preferences)->filter(function($preference) use ($packageItems) {
                    foreach ($packageItems as $item) {
                        if (str_contains($item, strtolower($preference))) {
                            return true;
                        }
                    }
                    return false;
                })->count()
            ];
        });
        
        // Sort by score (highest first)
        $recommendations = $scoredPackages->sortByDesc('score')->values();
        
        // Get the venue if selected
        $selectedVenue = $venueId ? Venue::find($venueId) : null;
        
        return view('package-recommendation.results', compact('recommendations', 'budget', 'guestCount', 'selectedVenue', 'preferences'));
    }
} 