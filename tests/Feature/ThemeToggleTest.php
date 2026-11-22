<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_toggle_state_is_rendered_without_javascript(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('theme-btn-light', false);
        $response->assertSee('theme-btn-dark', false);
        $response->assertSee('dark:text-outline-variant', false);
        $response->assertDontSee('updateThemeButtons', false);
    }
}
