<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-11-24 10:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_weekly_productivity_uses_completed_at(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'status' => TaskStatus::Done,
            'completed_at' => Carbon::parse('2026-11-17 09:00:00'),
            'updated_at' => Carbon::parse('2026-11-24 09:00:00'),
        ]);

        $response = $this->actingAs($user)->get(route('statistics.index'));

        $weeks = $response->viewData('weeklyProductivity');

        $this->assertSame(0, $weeks[7]['count'], 'task completed last week must not count this week');
        $this->assertSame(1, $weeks[6]['count'], 'task must count in the week it was completed');
    }
}
