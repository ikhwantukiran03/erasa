<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\NewMessage;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        return view('user.chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_staff_reply' => 0,
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return back();
    }
} 