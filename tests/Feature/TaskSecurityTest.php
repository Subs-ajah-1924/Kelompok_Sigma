<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_other_users_task(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->get(route('tasks.show', $task))->assertForbidden();
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id, 'title' => 'Asli']);

        $this->actingAs($other)->put(route('tasks.update', $task), [
            'title' => 'Diubah',
            'deadline' => $task->deadline->format('Y-m-d H:i:s'),
            'difficulty' => $task->difficulty->value,
            'status' => $task->status->value,
        ])->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Asli']);
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->delete(route('tasks.destroy', $task))->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
