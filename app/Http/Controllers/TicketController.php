<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicketCreated;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::with('user:id,name', 'assignee:id,name')
                ->latest()
                ->filter(request(['search', 'status']))
                ->get(),

            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['user_id'] = $request->author()->id;

        $ticket = Ticket::create($validated);

        Mail::to('admin@bugtracker.com')->send(new NewTicketCreated($ticket));

        return to_route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);
        $ticket->delete();
        return to_route('tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:open,closed,in_progress',
            'assignee_id' => 'sometimes|nullable|exists:users,id'
        ]);

        $ticket->update($validated);

        if ($ticket->wasChanged('assignee_id') && $ticket->assignee_id != null) {
            $ticket->update(['status' => 'in_progress']);
        }

        return to_route('tickets.index');
    }
}
