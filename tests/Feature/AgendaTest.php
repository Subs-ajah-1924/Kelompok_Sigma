<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_calendar(): void
    {
        $this->get(route('calendar.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_create_agenda(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('agendas.store'), [
            'date' => '2026-11-25',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'title' => 'Rapat',
        ]);

        $response->assertRedirect(route('calendar.index', ['month' => '2026-11']));
        $this->assertDatabaseHas('agendas', ['user_id' => $user->id, 'title' => 'Rapat']);
    }

    public function test_user_can_update_and_delete_own_agenda(): void
    {
        $user = User::factory()->create();
        $agenda = Agenda::factory()->create(['user_id' => $user->id, 'title' => 'Lama']);

        $this->actingAs($user)->put(route('agendas.update', $agenda), [
            'date' => $agenda->date->format('Y-m-d'),
            'title' => 'Baru',
        ])->assertRedirect();

        $this->assertDatabaseHas('agendas', ['id' => $agenda->id, 'title' => 'Baru']);

        $this->actingAs($user)->delete(route('agendas.destroy', $agenda))->assertRedirect();
        $this->assertSoftDeleted('agendas', ['id' => $agenda->id]);
    }

    public function test_user_cannot_update_or_delete_other_users_agenda(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $agenda = Agenda::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->put(route('agendas.update', $agenda), [
            'date' => $agenda->date->format('Y-m-d'),
            'title' => 'Hack',
        ])->assertForbidden();

        $this->actingAs($other)->delete(route('agendas.destroy', $agenda))->assertForbidden();
    }

    public function test_calendar_renders_agenda_ui(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('calendar.index'));

        $response->assertOk();
        $response->assertSee('Tambah Agenda');
    }
}
