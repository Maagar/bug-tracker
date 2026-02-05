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
            'tickets' => Ticket::latest()->filter(request(['search', 'status']))->get(),

            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;

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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,closed,in_progress',
        ]);

        $ticket->update($validated);

        return to_route('tickets.index');
    }
}
