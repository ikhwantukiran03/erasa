<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customization;
use App\Models\PackageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomizationController extends Controller
{
    /**
     * Display the form for creating a new customization request.
     *
     * @param  \App\Models\Booking  $booking
     * @param  \App\Models\PackageItem  $packageItem
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Booking $booking, PackageItem $packageItem)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to customize this booking.');
        }
        
        // Check if the booking is a wedding booking and ongoing
        if ($booking->type !== 'wedding' || $booking->status !== 'ongoing') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'Only ongoing wedding bookings can be customized.');
        }
        
        // Check if the package item belongs to the booking's package
        $isValidPackageItem = $booking->package->packageItems()->where('id', $packageItem->id)->exists();
        if (!$isValidPackageItem) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This item does not belong to your booking package.');
        }
        
        // Check if there's already a pending or approved customization request for this item
        $existingCustomization = Customization::where('booking_id', $booking->id)
            ->where('package_item_id', $packageItem->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        if ($existingCustomization) {
            return redirect()->route('user.customizations.edit', [$booking, $existingCustomization])
                ->with('info', 'You already have a customization request for this item.');
        }
        
        return view('user.customizations.create', compact('booking', 'packageItem'));
    }
    
    /**
     * Store a newly created customization request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @param  \App\Models\PackageItem  $packageItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Booking $booking, PackageItem $packageItem)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to customize this booking.');
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'customization' => ['required', 'string', 'min:10', 'max:1000'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create the customization request
        Customization::create([
            'booking_id' => $booking->id,
            'package_item_id' => $packageItem->id,
            'customization' => $request->customization,
            'status' => 'pending',
        ]);
        
        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your customization request has been submitted. Our staff will review it shortly.');
    }
    
    /**
     * Display the form for editing an existing customization request.
     *
     * @param  \App\Models\Booking  $booking
     * @param  \App\Models\Customization  $customization
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Booking $booking, Customization $customization)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to customize this booking.');
        }
        
        // Check if the customization belongs to the booking
        if ($customization->booking_id !== $booking->id) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request does not belong to this booking.');
        }
        
        // Check if the customization request is still pending
        if ($customization->status !== 'pending') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request cannot be edited because it has already been ' . $customization->status . '.');
        }
        
        $packageItem = $customization->packageItem;
        
        return view('user.customizations.edit', compact('booking', 'customization', 'packageItem'));
    }
    
    /**
     * Update the specified customization request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @param  \App\Models\Customization  $customization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking, Customization $customization)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to customize this booking.');
        }
        
        // Check if the customization belongs to the booking
        if ($customization->booking_id !== $booking->id) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request does not belong to this booking.');
        }
        
        // Check if the customization request is still pending
        if ($customization->status !== 'pending') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request cannot be edited because it has already been ' . $customization->status . '.');
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'customization' => ['required', 'string', 'min:10', 'max:1000'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update the customization request
        $customization->update([
            'customization' => $request->customization,
            'status' => 'pending', // Reset status if it was previously rejected
        ]);
        
        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your customization request has been updated. Our staff will review it shortly.');
    }
    
    /**
     * Remove the specified customization request from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @param  \App\Models\Customization  $customization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking, Customization $customization)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.bookings')
                ->with('error', 'You do not have permission to customize this booking.');
        }
        
        // Check if the customization belongs to the booking
        if ($customization->booking_id !== $booking->id) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request does not belong to this booking.');
        }
        
        // Check if the customization request is still pending
        if ($customization->status !== 'pending') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'This customization request cannot be deleted because it has already been ' . $customization->status . '.');
        }
        
        // Delete the customization request
        $customization->delete();
        
        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Your customization request has been deleted.');
    }
}