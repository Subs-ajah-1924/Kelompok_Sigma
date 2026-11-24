<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_is_idempotent(): void
    {
        $this->seed(DemoSeeder::class);

        $first = [User::count(), Course::count(), Task::count(), Agenda::count()];

        $this->seed(DemoSeeder::class);

        $this->assertSame($first, [User::count(), Course::count(), Task::count(), Agenda::count()]);
    }
}
