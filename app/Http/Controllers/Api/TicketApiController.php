<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class TicketApiController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->get();

        return TicketResource::collection($tickets);
    }

    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);
        
        $ticket->update($validated);
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);
        $ticket->delete();
        return response()->noContent();
    }
}
