<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.messages', compact('messages'));
    }

    public function create()
    {
        return view('customer.message-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Pesan berhasil dikirim!');
    }

    public function show(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('customer.message-detail', compact('message'));
    }
}