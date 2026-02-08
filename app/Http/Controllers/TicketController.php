<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicketCreated;
use App\Services\TicketService;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

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

    public function store(StoreTicketRequest $request)
    {
        $this->ticketService->createTicket(
            $request->validated(),
            $request->user()
        );

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

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->ticketService->updateTicket($ticket, $request->validated());

        return to_route('tickets.index');
    }
}
