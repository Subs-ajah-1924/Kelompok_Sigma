<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_login_rejects_unknown_email_with_generic_message(): void
    {
        $user = User::factory()->create();

        $unknownEmail = $this->post('/login', [
            'email' => 'tidak-ada@example.com',
            'password' => 'password',
        ]);

        $wrongPassword = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $unknownEmail->assertSessionHasErrors('email');
        $wrongPassword->assertSessionHasErrors('email');

        $unknownMessage = $unknownEmail->getSession()->get('errors')->first('email');
        $wrongPasswordMessage = $wrongPassword->getSession()->get('errors')->first('email');

        $this->assertSame($unknownMessage, $wrongPasswordMessage);
        $this->assertSame('Email atau password salah.', $unknownMessage);
    }

    public function test_unknown_email_attempts_are_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'tidak-ada@example.com',
                'password' => 'password',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->post('/login', [
            'email' => 'tidak-ada@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts',
            $response->getSession()->get('errors')->first('email')
        );
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
