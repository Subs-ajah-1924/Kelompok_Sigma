<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_overdue_count_uses_start_of_day(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'deadline' => now()->setTime(9, 0),
            'status' => 'todo',
        ]);

        Task::factory()->for($user)->create([
            'deadline' => now()->subDay()->setTime(23, 59),
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $this->assertSame(1, $response->viewData('overdueCount'));
    }
}
