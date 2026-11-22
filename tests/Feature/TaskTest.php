<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_task_pages(): void
    {
        $this->get(route('tasks.index'))->assertRedirect(route('login'));
        $this->get(route('tasks.create'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_task_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('tasks.index'))->assertOk();
    }

    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Tugas Baru',
            'description' => 'Deskripsi',
            'deadline' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'difficulty' => 'sedang',
            'status' => 'todo',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Tugas Baru',
            'difficulty' => 'sedang',
            'status' => 'todo',
        ]);
    }

    public function test_creating_task_with_done_status_sets_completed_at(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Sudah Selesai',
            'deadline' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'difficulty' => 'mudah',
            'status' => 'done',
        ]);

        $task = Task::where('title', 'Sudah Selesai')->first();
        $this->assertNotNull($task->completed_at);
    }

    public function test_task_validation_requires_title_deadline_difficulty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => '',
            'deadline' => '',
            'difficulty' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'deadline', 'difficulty']);
    }

    public function test_task_rejects_invalid_difficulty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'X',
            'deadline' => now()->addDay()->format('Y-m-d H:i:s'),
            'difficulty' => 'super',
        ]);

        $response->assertSessionHasErrors('difficulty');
    }

    public function test_task_course_must_belong_to_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseB = Course::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->post(route('tasks.store'), [
            'title' => 'Tugas',
            'deadline' => now()->addDay()->format('Y-m-d H:i:s'),
            'difficulty' => 'mudah',
            'course_id' => $courseB->id,
        ]);

        $response->assertSessionHasErrors('course_id');
    }

    public function test_user_can_view_own_task_detail(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get(route('tasks.show', $task))->assertOk();
    }

    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'Lama', 'status' => 'todo']);

        $response = $this->actingAs($user)->put(route('tasks.update', $task), [
            'title' => 'Baru',
            'deadline' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'difficulty' => 'sulit',
            'status' => 'in_progress',
        ]);

        $response->assertRedirect(route('tasks.show', $task));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Baru',
            'difficulty' => 'sulit',
            'status' => 'in_progress',
        ]);
    }

    public function test_updating_task_to_done_sets_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'todo', 'completed_at' => null]);

        $this->actingAs($user)->put(route('tasks.update', $task), [
            'title' => $task->title,
            'deadline' => $task->deadline->format('Y-m-d H:i:s'),
            'difficulty' => $task->difficulty->value,
            'status' => 'done',
        ]);

        $task->refresh();
        $this->assertNotNull($task->completed_at);
    }

    public function test_updating_task_away_from_done_clears_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->done()->create(['user_id' => $user->id]);

        $this->actingAs($user)->put(route('tasks.update', $task), [
            'title' => $task->title,
            'deadline' => $task->deadline->format('Y-m-d H:i:s'),
            'difficulty' => $task->difficulty->value,
            'status' => 'todo',
        ]);

        $task->refresh();
        $this->assertNull($task->completed_at);
    }

    public function test_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_view_other_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id]);

        $this->actingAs($userB)->get(route('tasks.show', $taskA))->assertForbidden();
    }

    public function test_user_cannot_edit_other_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id]);

        $this->actingAs($userB)->get(route('tasks.edit', $taskA))->assertForbidden();
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id, 'title' => 'A']);

        $response = $this->actingAs($userB)->put(route('tasks.update', $taskA), [
            'title' => 'Hacked',
            'deadline' => now()->addDay()->format('Y-m-d H:i:s'),
            'difficulty' => 'mudah',
            'status' => 'todo',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $taskA->id, 'title' => 'A']);
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id]);

        $response = $this->actingAs($userB)->delete(route('tasks.destroy', $taskA));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $taskA->id]);
    }

    public function test_user_can_update_task_status_via_patch(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'todo']);

        $response = $this->actingAs($user)->patch(route('tasks.status', $task), [
            'status' => 'done',
        ]);

        $response->assertRedirect();
        $task->refresh();
        $this->assertSame('done', $task->status->value);
        $this->assertNotNull($task->completed_at);
    }

    public function test_task_status_patch_json(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'todo']);

        $response = $this->actingAs($user)->patchJson(route('tasks.status', $task), [
            'status' => 'in_progress',
        ]);

        $response->assertOk()->assertJsonPath('status', 'in_progress');
    }

    public function test_task_index_only_shows_own_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        Task::factory()->create(['user_id' => $userA->id, 'title' => 'Task A']);
        Task::factory()->create(['user_id' => $userB->id, 'title' => 'Task B']);

        $response = $this->actingAs($userA)->get(route('tasks.index'));

        $response->assertSee('Task A');
        $response->assertDontSee('Task B');
    }

    public function test_task_index_eager_loads_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id, 'name' => 'MK Test']);
        Task::factory()->create(['user_id' => $user->id, 'course_id' => $course->id, 'title' => 'Tugas Course']);

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee('MK Test');
    }
}
