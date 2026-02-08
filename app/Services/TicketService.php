<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;

class TicketService
{
    public function createTicket(array $data, User $author): Ticket
    {
        return Ticket::create(array_merge($data, ['user_id' => $author->id]));
    }

    public function updateTicket(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }
}