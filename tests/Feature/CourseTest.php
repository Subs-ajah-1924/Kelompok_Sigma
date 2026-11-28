<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_course_pages(): void
    {
        $this->get(route('courses.index'))->assertRedirect(route('login'));
        $this->get(route('courses.create'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_course_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('courses.index'));

        $response->assertOk();
    }

    public function test_user_can_create_course(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'Pemrograman Web',
            'code' => 'PW001',
            'color' => '#4F46E5',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'user_id' => $user->id,
            'name' => 'Pemrograman Web',
            'code' => 'PW001',
            'color' => '#4F46E5',
        ]);
    }

    public function test_course_name_must_be_unique_per_user(): void
    {
        $user = User::factory()->create();
        Course::factory()->create(['user_id' => $user->id, 'name' => 'Basis Data']);

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'Basis Data',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('courses', 1);
    }

    public function test_same_course_name_allowed_for_different_users(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        Course::factory()->create(['user_id' => $userA->id, 'name' => 'Algoritma']);

        $response = $this->actingAs($userB)->post(route('courses.store'), [
            'name' => 'Algoritma',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseCount('courses', 2);
    }

    public function test_course_validation_requires_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_course_color_validation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'Warna Test',
            'color' => 'merah',
        ]);

        $response->assertSessionHasErrors('color');
    }

    public function test_user_can_update_own_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id, 'name' => 'Lama']);

        $response = $this->actingAs($user)->put(route('courses.update', $course), [
            'name' => 'Baru',
            'code' => 'NEW1',
            'color' => '#059669',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Baru',
            'code' => 'NEW1',
            'color' => '#059669',
        ]);
    }

    public function test_updating_course_name_to_duplicate_fails(): void
    {
        $user = User::factory()->create();
        Course::factory()->create(['user_id' => $user->id, 'name' => 'A']);
        $courseB = Course::factory()->create(['user_id' => $user->id, 'name' => 'B']);

        $response = $this->actingAs($user)->put(route('courses.update', $courseB), [
            'name' => 'A',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_delete_own_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('courses.destroy', $course));

        $response->assertRedirect(route('courses.index'));
        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }

    public function test_deleting_course_keeps_tasks_and_nulls_course_id(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'course_id' => $course->id]);

        $this->actingAs($user)->delete(route('courses.destroy', $course));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'course_id' => null]);
        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }

    public function test_course_index_only_shows_own_courses(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseA = Course::factory()->create(['user_id' => $userA->id, 'name' => 'Course A']);
        $courseB = Course::factory()->create(['user_id' => $userB->id, 'name' => 'Course B']);

        $response = $this->actingAs($userA)->get(route('courses.index'));

        $response->assertSee('Course A');
        $response->assertDontSee('Course B');
    }

    public function test_user_cannot_edit_other_users_course(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseA = Course::factory()->create(['user_id' => $userA->id]);

        $response = $this->actingAs($userB)->get(route('courses.edit', $courseA));

        $response->assertForbidden();
    }

    public function test_user_cannot_update_other_users_course(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseA = Course::factory()->create(['user_id' => $userA->id, 'name' => 'A']);

        $response = $this->actingAs($userB)->put(route('courses.update', $courseA), [
            'name' => 'Hacked',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('courses', ['id' => $courseA->id, 'name' => 'A']);
    }

    public function test_user_cannot_delete_other_users_course(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $courseA = Course::factory()->create(['user_id' => $userA->id]);

        $response = $this->actingAs($userB)->delete(route('courses.destroy', $courseA));

        $response->assertForbidden();
        $this->assertDatabaseHas('courses', ['id' => $courseA->id]);
    }

    public function test_course_index_uses_with_count(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);
        Task::factory()->count(3)->create(['user_id' => $user->id, 'course_id' => $course->id]);

        $response = $this->actingAs($user)->get(route('courses.index'));

        $response->assertOk();
        $response->assertSee('3 tugas');
    }

    public function test_course_schedule_is_merged_on_server(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'Pemrograman Web',
            'start_time' => '09:00',
            'end_time' => '10:30',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'user_id' => $user->id,
            'name' => 'Pemrograman Web',
            'time' => '09:00 - 10:30',
        ]);
    }

    public function test_course_rejects_invalid_schedule(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'Pemrograman Web',
            'start_time' => '10:30',
            'end_time' => '09:00',
        ]);

        $response->assertSessionHasErrors('end_time');
    }
}
