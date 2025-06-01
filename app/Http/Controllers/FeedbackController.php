<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->route('dashboard')->with('error', 'You can only submit feedback for completed bookings.');
        }

        // Check if user already submitted feedback
        if ($booking->feedback()->exists()) {
            return redirect()->route('dashboard')->with('error', 'You have already submitted feedback for this booking.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $feedback = $booking->feedback()->create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'Thank you for your feedback! It will be reviewed by our team.');
    }

    public function create(Booking $booking)
    {
        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->route('dashboard')->with('error', 'You can only submit feedback for completed bookings.');
        }

        // Check if user already submitted feedback
        if ($booking->feedback()->exists()) {
            return redirect()->route('dashboard')->with('error', 'You have already submitted feedback for this booking.');
        }

        return view('bookings.feedback', compact('booking'));
    }

    public function index(Request $request)
    {
        $query = Feedback::with(['user', 'booking'])
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest();

        $feedback = $query->paginate(10);
        $pendingCount = Feedback::pending()->count();
        $publishedCount = Feedback::published()->count();
        $rejectedCount = Feedback::rejected()->count();

        return view('staff.feedback.index', compact('feedback', 'pendingCount', 'publishedCount', 'rejectedCount'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,rejected',
        ]);

        $feedback->update([
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return back()->with('success', 'Feedback status updated successfully.');
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['user', 'booking']);
        return view('staff.feedback.show', compact('feedback'));
    }

    public function publicIndex()
    {
        $feedbacks = Feedback::where('status', 'published')
            ->with(['booking.user'])
            ->latest()
            ->get();

        return view('feedback.index', compact('feedbacks'));
    }
} 