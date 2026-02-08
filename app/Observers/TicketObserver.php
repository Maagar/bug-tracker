<?php

namespace App\Observers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicketCreated;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        Mail::to('admin@bugtracker.com')->send(new NewTicketCreated($ticket));
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updating(Ticket $ticket): void
    {
        if ($ticket->isDirty('assignee_id') && $ticket->assignee_id !== null) {
            $ticket->status = 'in_progress';
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
