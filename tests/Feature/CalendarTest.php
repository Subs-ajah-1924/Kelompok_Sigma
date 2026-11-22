<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_calendar(): void
    {
        $this->get(route('calendar.index'))->assertRedirect(route('login'));
    }

    public function test_calendar_grid_covers_whole_month(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('calendar.index', ['month' => '2026-11']));

        $response->assertOk();

        $days = $response->viewData('days');

        $this->assertCount(42, $days);
        $this->assertSame('2026-10-26', $days[0]->toDateString());
        $this->assertSame('2026-12-06', $days[array_key_last($days)]->toDateString());
    }

    public function test_calendar_handles_month_with_fewer_days_than_current_day(): void
    {
        Carbon::setTestNow('2026-01-31');

        try {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get(route('calendar.index', ['month' => '2026-02']));

            $response->assertOk();

            $days = $response->viewData('days');

            $this->assertSame('2026-01-26', $days[0]->toDateString());
            $this->assertSame('2026-03-01', $days[array_key_last($days)]->toDateString());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_calendar_rejects_invalid_month_param(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('calendar.index', ['month' => ['x']]))->assertOk();
    }
}
