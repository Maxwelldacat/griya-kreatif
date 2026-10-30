<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display list of all customer messages
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start query with user relationship
        $query = Message::with('user');

        // Filter by read status
        if ($request->has('read_status') && $request->read_status != '') {
            if ($request->read_status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->read_status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by reply status
        if ($request->has('reply_status') && $request->reply_status != '') {
            if ($request->reply_status === 'unanswered') {
                $query->whereNull('admin_reply');
            } elseif ($request->reply_status === 'answered') {
                $query->whereNotNull('admin_reply');
            }
        }

        // Search by subject or customer name
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'LIKE', '%' . $request->search . '%');
                  });
            });
        }

        // Paginate results (newest first)
        $messages = $query->latest()->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display single message and mark as read
     * 
     * @param Message $message
     * @return \Illuminate\View\View
     */
    public function show(Message $message)
    {
        // Mark message as read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        // Load user relationship
        $message->load('user');

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Reply to customer message
     * 
     * @param Request $request
     * @param Message $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, Message $message)
    {
        // Validate reply
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        // Update message with admin reply
        $message->update([
            'admin_reply' => $validated['admin_reply'],
            'is_read' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Balasan berhasil dikirim!');
    }

    /**
     * Delete message
     * 
     * @param Message $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message)
    {
        // Delete message
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Pesan berhasil dihapus!');
    }
}