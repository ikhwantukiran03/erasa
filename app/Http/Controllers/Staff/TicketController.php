<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['user', 'replies'])
            ->latest()
            ->paginate(15);

        return view('staff.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['replies.user', 'user']);
        
        return view('staff.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return redirect()->route('staff.tickets.show', $ticket)
                ->with('error', 'Cannot reply to a closed ticket.');
        }

        return view('staff.tickets.edit', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return redirect()->route('staff.tickets.show', $ticket)
                ->with('error', 'Cannot reply to a closed ticket.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => true,
        ]);

        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->route('staff.tickets.show', $ticket)
            ->with('success', 'Reply added successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket->update($validated);

        return redirect()->route('staff.tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully.');
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:general,technical,billing,other',
            'status' => 'required|string|in:open,in_progress,closed',
        ]);

        $ticket->update($validated);

        return redirect()->route('staff.tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }
} 