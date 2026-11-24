<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_agenda(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('agendas.store'), [
            'title' => 'Rapat Lab',
            'date' => '2026-11-24',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('agendas', [
            'user_id' => $user->id,
            'title' => 'Rapat Lab',
        ]);
    }

    public function test_user_cannot_update_others_agenda(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $agenda = Agenda::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->put(route('agendas.update', $agenda), [
            'title' => 'Hacked',
            'date' => $agenda->date->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('agendas', ['id' => $agenda->id, 'title' => $agenda->title]);
    }

    public function test_user_can_delete_own_agenda(): void
    {
        $user = User::factory()->create();
        $agenda = Agenda::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('agendas.destroy', $agenda));

        $response->assertRedirect();
        $this->assertSoftDeleted('agendas', ['id' => $agenda->id]);
    }
}
