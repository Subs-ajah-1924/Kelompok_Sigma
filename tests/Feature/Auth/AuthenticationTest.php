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

    public function test_login_does_not_reveal_whether_email_is_registered(): void
    {
        $user = User::factory()->create();

        $wrongPassword = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $unknownEmail = $this->post('/login', [
            'email' => 'tidak-terdaftar@example.com',
            'password' => 'wrong-password',
        ]);

        $wrongPassword->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        $unknownEmail->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        $this->assertGuest();
    }

    public function test_unknown_email_attempts_are_rate_limited(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post('/login', [
                'email' => 'tidak-terdaftar@example.com',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        }

        $response = $this->post('/login', [
            'email' => 'tidak-terdaftar@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts',
            session('errors')->first('email')
        );
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
