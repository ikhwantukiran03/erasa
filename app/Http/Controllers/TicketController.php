<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['replies'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:general,technical,billing,other',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'status' => 'open',
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Support ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['replies.user', 'user']);
        
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        if ($ticket->status === 'closed') {
            return redirect()->route('tickets.show', $ticket)
                ->with('error', 'Cannot reply to a closed ticket.');
        }

        return view('tickets.edit', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        if ($ticket->status === 'closed') {
            return redirect()->route('tickets.show', $ticket)
                ->with('error', 'Cannot reply to a closed ticket.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => false,
        ]);

        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Reply added successfully.');
    }
} 