<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->title,
            'description' => $this->description,
            'created_at_human' => $this->created_at->diffForHumans(),
            'is_urgent' => $this->status === 'critical',
        ];
    }
}
