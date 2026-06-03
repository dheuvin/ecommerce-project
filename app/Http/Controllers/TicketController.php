<?php

namespace App\Http\Controllers;

use App\Mail\TicketReplyMail;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function adminIndex()
    {
        $tickets = Ticket::with('user')
            ->latest()
            ->get();

        return view('tickets.admin.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'attachment' => 'nullable|image|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')
                ->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment' => $filePath,
        ]);

        return redirect()->route('tickets.index');
    }

    public function show(Ticket $ticket)
    {
        if (
            auth()->user()->role !== 'admin'
            && $ticket->user_id != auth()->id()
        ) {
            abort(403);
        }

        $ticket->load('replies');

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return back()->with('error', 'This ticket is closed.');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => auth()->user()->role === 'admin',
        ]);

        // Send mail only if OPEN
        if ($ticket->status !== 'closed') {

            if (auth()->user()->role === 'admin') {
                Mail::to($ticket->user->email)
                    ->send(new TicketReplyMail($ticket, $reply));
            } else {
                $admin = User::where('role', 'admin')->first();

                Mail::to($admin->email)
                    ->send(new TicketReplyMail($ticket, $reply));
            }
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        // only admin can close
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update([
            'status' => 'closed',
        ]);

        return back()->with('success', 'Ticket closed successfully.');
    }
}
