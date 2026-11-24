<?php

namespace Tests\Unit;

use App\Difficulty;
use App\Models\Task;
use App\TaskStatus;
use Carbon\Carbon;
use Tests\TestCase;

class TaskRecommendationTest extends TestCase
{
    public function test_task_due_today_is_not_overdue(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');
        $task = new Task([
            'title' => 'Hari ini',
            'deadline' => now()->setTime(9, 0),
            'difficulty' => Difficulty::Mudah,
            'status' => TaskStatus::Todo,
        ]);

        $recommendation = app(\App\Services\TaskRecommendationService::class)->recommend($task);

        $this->assertSame(\App\Priority::Medium, $recommendation->priority);
        $this->assertStringContainsString('kurang dari 24 jam', $recommendation->reason);
    }

    public function test_task_due_yesterday_is_overdue(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');
        $task = new Task([
            'title' => 'Kemarin',
            'deadline' => now()->subDay()->setTime(23, 59),
            'difficulty' => Difficulty::Mudah,
            'status' => TaskStatus::Todo,
        ]);

        $recommendation = app(\App\Services\TaskRecommendationService::class)->recommend($task);

        $this->assertSame(\App\Priority::High, $recommendation->priority);
        $this->assertStringContainsString('terlewat', $recommendation->reason);
    }
}
