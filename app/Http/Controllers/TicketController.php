<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\TicketService;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

    public function index(Request $request)
    {
        $tickets = Ticket::with('user:id,name', 'assignee:id,name')
                ->latest()
                ->filter(request(['search', 'status']))
                ->get();
                
        return Inertia::render('Tickets/Index', [
            'tickets' => TicketResource::collection($tickets),
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
        Gate::authorize('update', $ticket);
        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $this->ticketService->updateTicket($ticket, $request->validated());

        return to_route('tickets.index');
    }
}
