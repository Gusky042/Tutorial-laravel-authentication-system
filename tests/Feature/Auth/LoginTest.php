<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_for_guests(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('auth.login');
        $response->assertSee('Remember me');
        $response->assertSee('data-toggle-password="password"', false);
    }

    public function test_user_can_log_in_with_correct_credentials(): void
    {
        $user = User::factory()->create(['password' => 'Password123!']);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_user_cannot_log_in_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => 'Password123!']);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
        $response->assertSessionHasInput('email', $user->email);
        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $user = User::factory()->create(['password' => 'Password123!']);

        for ($i = 0; $i < LoginRequest::MAX_ATTEMPTS; $i++) {
            $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
        }

        // Even the correct password is refused while locked out.
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('seconds', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_logged_in_user_visiting_login_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_guest_visiting_dashboard_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'You have been logged out.');
        $this->assertGuest();
    }

    public function test_logout_via_get_is_not_allowed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');

        $response->assertStatus(405);
        $this->assertAuthenticatedAs($user);
    }
}
