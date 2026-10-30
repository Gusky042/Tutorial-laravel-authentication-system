<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    private const OLD_PASSWORD = 'Password123!';

    private const NEW_PASSWORD = 'NewPassword456?';

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('password.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_page_renders_for_logged_in_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('password.edit'));

        $response->assertOk();
        $response->assertViewIs('settings.password');
        $response->assertSee('Current password');
    }

    public function test_password_changes_with_correct_current_password_and_new_password_works(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => self::OLD_PASSWORD,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('password.edit'));
        $response->assertSessionHas('status', 'Your password has been changed.');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->fresh()->password));

        // Log out, then log in again with the new password.
        $this->post(route('logout'));
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => 'WrongPassword1!',
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

        $response->assertRedirect(route('password.edit'));
        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }

    public function test_fails_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => self::OLD_PASSWORD,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => 'SomethingElse789!',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }

    public function test_fails_when_new_password_equals_current_password(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => self::OLD_PASSWORD,
                'password' => self::OLD_PASSWORD,
                'password_confirmation' => self::OLD_PASSWORD,
            ]);

        $response->assertSessionHasErrors([
            'password' => 'The new password must be different from your current password.',
        ]);
    }

    public function test_fails_for_weak_new_password(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => self::OLD_PASSWORD,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }
}
