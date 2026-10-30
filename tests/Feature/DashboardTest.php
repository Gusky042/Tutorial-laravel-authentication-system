<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_logged_in_user_sees_their_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Ada <b>Lovelace</b>',
            'email' => 'ada@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard');
        $response->assertSee('Ada <b>Lovelace</b>'); // escaped by default
        $response->assertDontSee('<b>Lovelace</b>', false); // raw HTML must never appear
        $response->assertSee('ada@example.com');
    }

    public function test_logging_in_updates_last_login_at(): void
    {
        $previous = now()->subDays(2)->startOfSecond();
        $user = User::factory()->create(['password' => 'Password123!']);
        $user->last_login_at = $previous;
        $user->save();

        $this->freezeSecond();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->last_login_at->equalTo(now()));
        $this->assertSame($previous->toIso8601String(), session('previous_login_at'));

        $this->get(route('dashboard'))
            ->assertSee($previous->format('j M Y, H:i'))
            ->assertDontSee('This is your first login');
    }

    public function test_dashboard_shows_first_login_message_for_new_user(): void
    {
        $user = User::factory()->create(['password' => 'Password123!']);
        $this->assertNull($user->last_login_at);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('This is your first login');
    }
}
