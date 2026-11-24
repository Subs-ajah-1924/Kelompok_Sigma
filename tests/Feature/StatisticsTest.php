<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_statistics(): void
    {
        $this->get(route('statistics.index'))->assertRedirect(route('login'));
    }

    public function test_weekly_productivity_uses_completed_at(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');
        $user = User::factory()->create();

        $completedLastWeek = now()->subWeek()->startOfWeek()->addDay();
        Task::factory()->for($user)->create([
            'status' => 'done',
            'completed_at' => $completedLastWeek,
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('statistics.index'));

        $response->assertOk();
        $weekly = $response->viewData('weeklyProductivity');

        $this->assertSame(1, $weekly[6]['count']);
        $this->assertSame(0, $weekly[7]['count']);
    }
}
