<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskRecommendationService;
use App\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OverdueDefinitionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-11-24 23:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_task_due_earlier_today_is_not_overdue(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create([
            'deadline' => Carbon::parse('2026-11-24 22:00:00'),
            'status' => TaskStatus::Todo,
        ]);

        $this->assertSame(0, Task::overdue()->count());

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertSame(0, $response->viewData('overdueCount'));
    }

    public function test_task_due_yesterday_is_overdue(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create([
            'deadline' => Carbon::parse('2026-11-23 22:00:00'),
            'status' => TaskStatus::Todo,
        ]);

        $this->assertSame(1, Task::overdue()->count());

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertSame(1, $response->viewData('overdueCount'));
    }

    public function test_recommendation_does_not_mark_task_due_today_as_overdue(): void
    {
        $task = Task::factory()->create([
            'deadline' => Carbon::parse('2026-11-24 22:00:00'),
            'status' => TaskStatus::Todo,
        ]);

        $recommendation = (new TaskRecommendationService)->recommend($task);

        $this->assertStringNotContainsString('terlewat', $recommendation->reason);
    }

    public function test_recommendation_marks_task_due_yesterday_as_overdue(): void
    {
        $task = Task::factory()->create([
            'deadline' => Carbon::parse('2026-11-23 22:00:00'),
            'status' => TaskStatus::Todo,
        ]);

        $recommendation = (new TaskRecommendationService)->recommend($task);

        $this->assertStringContainsString('terlewat', $recommendation->reason);
    }
}
