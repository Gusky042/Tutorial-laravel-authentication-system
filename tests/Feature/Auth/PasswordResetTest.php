<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private const OLD_PASSWORD = 'Password123!';

    private const NEW_PASSWORD = 'NewPassword456?';

    public function test_forgot_password_page_renders(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_reset_link_is_sent_to_existing_user(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertSessionHas('status', PasswordResetLinkController::NEUTRAL_STATUS);
        $response->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            // The link in the email must be built from APP_URL.
            $url = $notification->toMail($user)->actionUrl;

            return str_starts_with($url, rtrim(config('app.url'), '/').'/reset-password/');
        });
    }

    public function test_unknown_email_gets_neutral_message_and_no_notification(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), ['email' => 'nobody@example.com']);

        $response->assertSessionHas('status', PasswordResetLinkController::NEUTRAL_STATUS);
        $response->assertSessionHasNoErrors();

        Notification::assertNothingSent();
    }

    public function test_reset_password_page_renders_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]));

        $response->assertOk();
        $response->assertViewIs('auth.reset-password');
        $response->assertSee($token);
        $response->assertSee($user->email);
    }

    public function test_password_can_be_reset_with_valid_token_and_user_can_log_in(): void
    {
        Notification::fake();

        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $this->post(route('password.email'), ['email' => $user->email]);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        $response = $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Your password has been reset. Please log in.');
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->fresh()->password));
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_reset_fails_with_invalid_token(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);

        $response = $this->from(route('password.reset', ['token' => 'invalid-token']))
            ->post(route('password.store'), [
                'token' => 'invalid-token',
                'email' => $user->email,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

        $response->assertSessionHasErrors(['email' => __(Password::INVALID_TOKEN)]);
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }

    public function test_reset_fails_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);
        $token = Password::createToken($user);

        $response = $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => 'SomethingElse789!',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }

    public function test_reset_fails_for_weak_password(): void
    {
        $user = User::factory()->create(['password' => self::OLD_PASSWORD]);
        $token = Password::createToken($user);

        $response = $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check(self::OLD_PASSWORD, $user->fresh()->password));
    }
}
