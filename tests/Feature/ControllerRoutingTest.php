<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_protected_controller_pages(): void
    {
        $urls = [
            route('dashboard'),
            route('tasks.index'),
            route('courses.index'),
            route('kanban.index'),
            route('calendar.index'),
            route('notifications.index'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_authenticated_user_can_open_controller_pages(): void
    {
        $user = User::factory()->create();

        $urls = [
            route('dashboard'),
            route('tasks.index'),
            route('tasks.create'),
            route('tasks.show', ['task' => 1]),
            route('tasks.edit', ['task' => 1]),
            route('courses.index'),
            route('courses.create'),
            route('courses.edit', ['course' => 1]),
            route('kanban.index'),
            route('calendar.index'),
            route('notifications.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_write_actions_are_not_implemented_yet(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tasks.store'))->assertStatus(501);
        $this->actingAs($user)->put(route('tasks.update', ['task' => 1]))->assertStatus(501);
        $this->actingAs($user)->delete(route('tasks.destroy', ['task' => 1]))->assertStatus(501);
        $this->actingAs($user)->patch(route('tasks.status', ['task' => 1]))->assertStatus(501);

        $this->actingAs($user)->post(route('courses.store'))->assertStatus(501);
        $this->actingAs($user)->put(route('courses.update', ['course' => 1]))->assertStatus(501);
        $this->actingAs($user)->delete(route('courses.destroy', ['course' => 1]))->assertStatus(501);

        $this->actingAs($user)->post(route('agendas.store'))->assertStatus(501);
        $this->actingAs($user)->put(route('agendas.update', ['agenda' => 1]))->assertStatus(501);
        $this->actingAs($user)->delete(route('agendas.destroy', ['agenda' => 1]))->assertStatus(501);
    }
}
