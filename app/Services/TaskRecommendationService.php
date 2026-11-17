<?php

namespace App\Services;

use App\Models\Task;
use App\Priority;
use App\TaskStatus;

class TaskRecommendationService
{
    public function recommend(Task $task): Recommendation
    {
        if ($task->status === TaskStatus::Done) {
            return Recommendation::done();
        }

        $difficultyScore = $task->difficulty->weight();

        $now = now();
        $deadline = $task->deadline;
        $isOverdue = $deadline->lt($now);
        $days = (int) $now->copy()->startOfDay()
            ->diffInDays($deadline->copy()->startOfDay(), false);

        $urgency = match (true) {
            $isOverdue => 3,
            $days <= 1 => 3,
            $days <= 3 => 2,
            $days <= 7 => 1,
            default => 0,
        };

        $score = $difficultyScore + $urgency;

        $priority = match (true) {
            $isOverdue => Priority::High,
            $score >= 5 => Priority::High,
            $score >= 3 => Priority::Medium,
            default => Priority::Low,
        };

        return new Recommendation(
            priority: $priority,
            score: $score,
            reason: $this->buildReason($task, $days, $isOverdue),
        );
    }

    private function buildReason(Task $task, int $days, bool $isOverdue): string
    {
        $difficultyLabel = $task->difficulty->label();
        $difficultyPart = "Tingkat kesulitan {$difficultyLabel}.";

        $deadlinePart = match (true) {
            $isOverdue => 'Deadline sudah terlewat '.abs($days).' hari.',
            $days <= 1 => 'Deadline kurang dari 24 jam lagi.',
            $days <= 3 => "Deadline tinggal {$days} hari lagi.",
            $days <= 7 => "Deadline {$days} hari lagi.",
            default => "Deadline masih {$days} hari lagi.",
        };

        return "{$difficultyPart} {$deadlinePart}";
    }
}
