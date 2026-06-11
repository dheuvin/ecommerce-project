<?php

namespace App\Http\Controllers;

use App\Mail\TicketReplyMail;
use App\Mail\TicketStatusMail;
use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['category'])
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('subject', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function adminIndex(Request $request)
    {
        $query = Ticket::with(['user', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('ticket_category_id', $request->category);
        }

        $tickets = $query->latest()->paginate(10);

        $categories = TicketCategory::orderBy('name')->get();

        $totalCount = Ticket::count();
        $openCount = Ticket::where('status', 'open')->count();
        $pendingCount = Ticket::where('status', 'pending')->count();
        $closedCount = Ticket::where('status', 'closed')->count();

        return view('tickets.admin.index', compact(
            'tickets',
            'categories',
            'totalCount',
            'openCount',
            'pendingCount',
            'closedCount'
        ));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,pending,closed',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        try {
            Mail::to($ticket->user->email)->send(new TicketStatusMail($ticket));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    public function create()
    {
        $categories = TicketCategory::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string',
            'attachment' => 'nullable|image|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')
                ->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'ticket_category_id' => $request->ticket_category_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open',
            'attachment' => $filePath,
        ]);
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            try {
                Mail::to($admin->email)
                    ->send(new TicketCreatedMail($ticket));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if (
            auth()->user()->role !== 'admin'
            && $ticket->user_id != auth()->id()
        ) {
            abort(403);
        }

        $ticket->load([
            'user',
            'category',
            'replies.user',
        ]);

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if (
            auth()->user()->role !== 'admin'
            && $ticket->user_id !== auth()->id()
        ) {
            abort(403);
        }

        

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $isAdmin = auth()->user()->role === 'admin';

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => $isAdmin,
        ]);

         $ticket->update([
                'status' => 'pending',
            ]);

       try {

            // USER replies → send to ADMIN
            if (!$isAdmin) {

                $adminEmails = User::where('role', 'admin')
                    ->pluck('email')
                    ->toArray();

                Mail::to($adminEmails)
                    ->send(new TicketReplyMail($ticket, $reply));
            }

            // ADMIN replies → send to TICKET OWNER
            else {

                Mail::to($ticket->user->email)
                    ->send(new TicketReplyMail($ticket, $reply));
            }

        }
        catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update([
            'status' => 'closed',
        ]);

        return back()->with(
            'success',
            'Ticket closed successfully.'
        );
    }

    public function destroy(Ticket $ticket)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->delete();

        return back()->with('success', 'Ticket deleted successfully.');
    }
}
