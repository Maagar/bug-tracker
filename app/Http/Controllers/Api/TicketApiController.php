<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketApiController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->get();

        return TicketResource::collection($tickets);
    }
}
