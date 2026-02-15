<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('User can create a ticket', function () {
    $user = User::factory()->create();

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->post(route('tickets.store'), [
            'title' => "testowy ticket",
            'description' => 'opis problemu'

        ]);
    $response->assertRedirect(route('tickets.index'));

    $this->assertDatabaseHas('tickets', [
        'title' => 'testowy ticket',
        'user_id' => $user->id,
    ]);
});

test('number of tickets increases after creating a ticket', function () {
    $user = User::factory()->create();

    $initialCount = Ticket::count();

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)->post(route('tickets.store'), [
        'title' => 'nowy ticket',
        'description' => 'opis'
    ]);

    $this->assertDatabaseCount('tickets', $initialCount + 1);
});

test('user can\'t delete another user\'s ticket', function () {
    $user = User::factory()->create();
    $secondUser = User::factory()->create();

    $ticket = Ticket::factory()->create([
        'user_id' => $secondUser->id
    ]);
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->delete(route('tickets.destroy', $ticket));

    $response->assertForbidden();

    $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
});
