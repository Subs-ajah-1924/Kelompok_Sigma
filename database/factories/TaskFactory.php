<?php

namespace Database\Factories;

use App\Difficulty;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'deadline' => fake()->dateTimeBetween('now', '+14 days'),
            'difficulty' => fake()->randomElement(Difficulty::cases()),
            'status' => TaskStatus::Todo,
            'completed_at' => null,
        ];
    }

    public function todo(): static
    {
        return $this->state(fn () => ['status' => TaskStatus::Todo, 'completed_at' => null]);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => ['status' => TaskStatus::InProgress, 'completed_at' => null]);
    }

    public function done(): static
    {
        return $this->state(fn () => ['status' => TaskStatus::Done, 'completed_at' => now()]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => ['deadline' => now()->subDays(3), 'status' => TaskStatus::Todo]);
    }

    public function dueSoon(): static
    {
        return $this->state(fn () => ['deadline' => now()->addDays(2), 'status' => TaskStatus::Todo]);
    }

    public function hard(): static
    {
        return $this->state(fn () => ['difficulty' => Difficulty::Sulit]);
    }

    public function easy(): static
    {
        return $this->state(fn () => ['difficulty' => Difficulty::Mudah]);
    }
}
