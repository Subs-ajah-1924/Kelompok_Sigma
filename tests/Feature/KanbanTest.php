<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KanbanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_kanban(): void
    {
        $this->get(route('kanban.index'))->assertRedirect(route('login'));
    }

    public function test_kanban_renders_columns(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create(['status' => 'todo']);
        Task::factory()->for($user)->create(['status' => 'in_progress']);
        Task::factory()->for($user)->create(['status' => 'done']);

        $response = $this->actingAs($user)->get(route('kanban.index'));

        $response->assertOk();
        $columns = $response->viewData('columns');
        $this->assertCount(1, $columns['todo']);
        $this->assertCount(1, $columns['in_progress']);
        $this->assertCount(1, $columns['done']);
    }
}
