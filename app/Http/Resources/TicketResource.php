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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at_human' => $this->created_at->diffForHumans(),
            'is_urgent' => $this->status === 'critical',
            'user' => $this->user,
            'assignee' => $this->assignee,
            'can' => [
                'delete' => $request->user()->can('delete', $this->resource),
                'update' => $request->user()->can('update', $this->resource),
            ]
        ];
    }
}
