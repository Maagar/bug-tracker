<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::latest()
            ->where('status', 'open')
            ->get() 
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Ticket::create($validated);

        return to_route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return to_route('tickets.index');
    }
}