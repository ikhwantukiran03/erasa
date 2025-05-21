<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Venue;
use App\Models\Category;
use App\Models\Item;
use App\Models\Price;
use App\Models\PackageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Start with a base query
        $packagesQuery = Package::with(['venue', 'prices']);
        
        // Apply search filter
        if ($request->has('search') && $request->search) {
            $packagesQuery->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Apply venue filter
        if ($request->has('venue') && $request->venue) {
            $packagesQuery->where('venue_id', $request->venue);
        }
        
        // Apply price range filter
        if ($request->has('price_range') && $request->price_range) {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];
                
                $packagesQuery->whereHas('prices', function($query) use ($minPrice, $maxPrice) {
                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                });
            }
        }
        
        // Get all venues for the filter dropdown
        $venues = \App\Models\Venue::all();
        
        // Paginate the results
        $packages = $packagesQuery->paginate(9)->withQueryString();
        
        return view('admin.packages.index', compact('packages', 'venues'));
    }

    /**
     * Show the form for creating a new package.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::all();
        $categories = Category::with('items')->get();
        
        return view('admin.packages.create', compact('venues', 'categories'));
    }

    /**
     * Store a newly created package in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue_id' => ['required', 'exists:venues,id'],
            'prices' => ['required', 'array', 'min:1'],
            'prices.*.pax' => ['required', 'integer', 'min:1'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create the package
            $package = Package::create([
                'name' => $request->name,
                'description' => $request->description,
                'venue_id' => $request->venue_id,
            ]);
            
            // Add prices
            foreach ($request->prices as $priceData) {
                Price::create([
                    'package_id' => $package->id,
                    'pax' => $priceData['pax'],
                    'price' => $priceData['price'],
                ]);
            }
            
            // Add items
            foreach ($request->items as $itemId => $itemData) {
                if (isset($itemData['item_id']) && $itemData['selected'] ?? false) {
                    PackageItem::create([
                        'package_id' => $package->id,
                        'item_id' => $itemData['item_id'],
                        'description' => $itemData['description'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.packages.index')
                ->with('success', 'Package created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while creating the package: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified package.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Package $package)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $package->load(['venue', 'prices', 'packageItems.item.category']);
        
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Package $package)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::all();
        $categories = Category::with('items')->get();
        
        // Get the currently selected items
        $package->load(['prices', 'packageItems']);
        $selectedItems = $package->packageItems->pluck('description', 'item_id')->toArray();
        
        return view('admin.packages.edit', compact('package', 'venues', 'categories', 'selectedItems'));
    }

    /**
     * Update the specified package in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Package $package)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue_id' => ['required', 'exists:venues,id'],
            'prices' => ['required', 'array', 'min:1'],
            'prices.*.pax' => ['required', 'integer', 'min:1'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Update the package
            $package->update([
                'name' => $request->name,
                'description' => $request->description,
                'venue_id' => $request->venue_id,
            ]);
            
            // Update prices
            // First, delete all existing prices
            $package->prices()->delete();
            
            // Then add the new ones
            foreach ($request->prices as $priceData) {
                Price::create([
                    'package_id' => $package->id,
                    'pax' => $priceData['pax'],
                    'price' => $priceData['price'],
                ]);
            }
            
            // Update items
            // First, delete all existing package items
            $package->packageItems()->delete();
            
            // Then add the new ones
            foreach ($request->items as $itemId => $itemData) {
                if (isset($itemData['item_id']) && $itemData['selected'] ?? false) {
                    PackageItem::create([
                        'package_id' => $package->id,
                        'item_id' => $itemData['item_id'],
                        'description' => $itemData['description'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.packages.index')
                ->with('success', 'Package updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while updating the package: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified package from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Package $package)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete related prices
            $package->prices()->delete();
            
            // Delete related package items
            $package->packageItems()->delete();
            
            // Delete the package
            $package->delete();
            
            DB::commit();
            
            return redirect()->route('admin.packages.index')
                ->with('success', 'Package deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the package: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate the specified package.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Package $package)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        try {
            DB::beginTransaction();
            
            // Create a new package with same data but append "(Copy)" to the name
            $newPackage = Package::create([
                'name' => $package->name . ' (Copy)',
                'description' => $package->description,
                'venue_id' => $package->venue_id,
            ]);
            
            // Duplicate prices
            foreach ($package->prices as $price) {
                Price::create([
                    'package_id' => $newPackage->id,
                    'pax' => $price->pax,
                    'price' => $price->price,
                ]);
            }
            
            // Duplicate package items
            foreach ($package->packageItems as $packageItem) {
                PackageItem::create([
                    'package_id' => $newPackage->id,
                    'item_id' => $packageItem->item_id,
                    'description' => $packageItem->description,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.packages.index')
                ->with('success', 'Package duplicated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while duplicating the package: ' . $e->getMessage());
        }
    }

    /**
     * Display a public listing of packages organized by venues.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function publicIndex(Request $request)
    {
        // Get all venues for the filter
        $venues = Venue::all();
        
        // If venue_id is provided, show packages for that specific venue
        if ($request->has('venue_id')) {
            $venueWithPackages = Venue::with(['packages.prices', 'packages.packageItems.item', 'galleries'])
                ->findOrFail($request->venue_id);
            
            return view('packages.index', compact('venues', 'venueWithPackages'));
        }
        
        // Otherwise, show all venues with their packages
        $venuesWithPackages = Venue::with(['packages.prices', 'packages.packageItems.item'])
            ->has('packages')
            ->get();
        
        return view('packages.index', compact('venues', 'venuesWithPackages'));
    }

    /**
     * Display the public view of the specified package.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\View\View
     */
    public function publicShow(Package $package)
    {
        $package->load(['venue', 'prices', 'packageItems.item.category']);
        
        // Get related packages from the same venue
        $relatedPackages = Package::where('venue_id', $package->venue_id)
            ->where('id', '!=', $package->id)
            ->with(['prices', 'venue'])
            ->take(3)
            ->get();
        
        return view('packages.show', compact('package', 'relatedPackages'));
    }

    /**
     * Get a formatted pax string for display.
     *
     * @param \App\Models\Package $package
     * @return string
     */
    protected function getPaxString(Package $package) 
    {
        if ($package->prices->isEmpty()) {
            return 'No pax information';
        }
        
        $minPax = $package->prices->min('pax');
        $maxPax = $package->prices->max('pax');
        
        if ($minPax == $maxPax) {
            return "$minPax pax";
        }
        
        return "$minPax - $maxPax pax";
    }
}