<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_notifications(): void
    {
        $this->get(route('notifications.index'))->assertRedirect(route('login'));
    }

    public function test_overdue_uses_start_of_day_boundary(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Hari ini',
            'deadline' => now()->setTime(9, 0),
            'status' => 'todo',
        ]);

        Task::factory()->for($user)->create([
            'title' => 'Kemarin',
            'deadline' => now()->subDay()->setTime(23, 59),
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertOk();
        $overdue = $response->viewData('overdue');
        $today = $response->viewData('today');

        $this->assertCount(1, $overdue);
        $this->assertSame('Kemarin', $overdue->first()->title);
        $this->assertCount(1, $today);
        $this->assertSame('Hari ini', $today->first()->title);
    }
}
