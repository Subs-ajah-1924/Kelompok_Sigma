<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Task;
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
        $task = Task::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create(['user_id' => $user->id]);

        $urls = [
            route('dashboard'),
            route('tasks.index'),
            route('tasks.create'),
            route('tasks.show', $task),
            route('tasks.edit', $task),
            route('courses.index'),
            route('courses.create'),
            route('courses.edit', $course),
            route('kanban.index'),
            route('calendar.index'),
            route('notifications.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
