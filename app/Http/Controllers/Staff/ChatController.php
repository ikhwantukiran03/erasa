<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\NewMessage;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::whereHas('messages')
            ->with(['messages' => function ($query) {
                $query->latest();
            }])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_staff_reply', 0)
                    ->whereNull('read_at');
            }])
            ->get()
            ->map(function ($user) {
                $user->last_message = $user->messages->first();
                return $user;
            });

        return view('staff.chat.index', compact('users'));
    }

    public function show(User $user)
    {
        $messages = Message::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('user_id', $user->id)
            ->where('is_staff_reply', 0)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $users = User::whereHas('messages')
            ->with(['messages' => function ($query) {
                $query->latest();
            }])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_staff_reply', 0)
                    ->whereNull('read_at');
            }])
            ->get()
            ->map(function ($u) {
                $u->last_message = $u->messages->first();
                return $u;
            });

        return view('staff.chat.index', compact('user', 'messages', 'users'));
    }

    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'is_staff_reply' => true,
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return back();
    }
} 