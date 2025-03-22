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
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $packages = Package::with(['venue', 'prices'])->get();
        return view('admin.packages.index', compact('packages'));
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
}