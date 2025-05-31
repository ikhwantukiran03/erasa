<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffCustomizationController extends Controller
{
    /**
     * Display a listing of the customization requests.
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

        $status = $request->get('status', 'pending');
        $search = $request->get('search', '');
        
        $query = Customization::with(['booking.user', 'packageItem.item.category']);
        
        switch ($status) {
            case 'pending':
                $query->pending();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            default:
                // No filter, show all
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customization', 'like', '%' . $search . '%')
                  ->orWhereHas('booking.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('packageItem.item', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('packageItem.item.category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $customizations = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('staff.customizations.index', compact('customizations', 'status', 'search'));
    }

    /**
     * Display the specified customization request.
     *
     * @param  \App\Models\Customization  $customization
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Customization $customization)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return view('staff.customizations.show', compact('customization'));
    }

    /**
     * Process the customization request - approve or reject.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customization  $customization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request, Customization $customization)
    {
        if (!auth()->user()->isStaff() && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:approved,rejected'],
            'staff_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if the customization request is still pending
        if ($customization->status !== 'pending') {
            return redirect()->route('staff.customizations.index')
                ->with('error', 'This customization request has already been processed.');
        }

        // Update the customization request
        $customization->update([
            'status' => $request->status,
            'staff_notes' => $request->staff_notes,
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        return redirect()->route('staff.customizations.index')
            ->with('success', 'The customization request has been ' . $request->status . '.');
    }
}