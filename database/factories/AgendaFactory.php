<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('now', '+14 days')->format('Y-m-d'),
            'start_time' => fake()->optional()->time('H:i'),
            'end_time' => null,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
