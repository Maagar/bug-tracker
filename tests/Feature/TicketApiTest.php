<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_tickets_list()
    {
        $user = User::factory()->create();
        
        Ticket::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/tickets');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
}