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
            route('statistics.index'),
            route('notifications.index'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_search_query_is_encoded_as_a_javascript_string(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('dashboard', ['q' => "'+alert(1)+'"]));

        $response->assertOk();
        $response->assertSee('query:', false);
        $response->assertSee('alert(1)', false);
        $response->assertDontSee("query: ''+alert(1)+' '", false);
    }

    public function test_search_query_with_apostrophe_is_rendered_safely(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('dashboard', ['q' => "don't"]));

        $response->assertOk();
        $response->assertSee('query:', false);
        $response->assertSee('don', false);
        $response->assertSee('t', false);
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
            route('statistics.index'),
            route('notifications.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
