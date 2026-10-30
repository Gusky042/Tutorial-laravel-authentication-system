<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Password123!';

    /**
     * @return array<string, string>
     */
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New User',
            'email' => 'new.user@example.com',
            'password' => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ], $overrides);
    }

    public function test_register_page_renders_for_guests(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertViewIs('auth.register');
        $response->assertSee('Create account');
    }

    public function test_logged_in_user_visiting_register_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_new_user_can_register_and_lands_on_dashboard_as_first_login(): void
    {
        $response = $this->post(route('register.store'), $this->validData([
            'name' => '  New User  ',
            'email' => '  New.User@Example.COM ',
        ]));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status', 'Welcome! Your account has been created.');

        $user = User::firstWhere('email', 'new.user@example.com');
        $this->assertNotNull($user, 'Email should be stored trimmed and lowercase.');
        $this->assertSame('New User', $user->name);
        $this->assertNotNull($user->last_login_at);
        $this->assertAuthenticatedAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Welcome! Your account has been created.')
            ->assertSee('This is your first login');
    }

    public function test_password_is_stored_hashed(): void
    {
        $this->post(route('register.store'), $this->validData());

        $user = User::firstWhere('email', 'new.user@example.com');

        $this->assertNotSame(self::PASSWORD, $user->password);
        $this->assertTrue(Hash::check(self::PASSWORD, $user->password));
    }

    public function test_registered_event_is_dispatched(): void
    {
        Event::fake([Registered::class]);

        $this->post(route('register.store'), $this->validData());

        Event::assertDispatched(Registered::class, function (Registered $event) {
            return $event->user->email === 'new.user@example.com';
        });
    }

    public function test_registration_fails_for_existing_email_in_any_case(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        foreach (['test@example.com', 'TEST@example.com'] as $email) {
            $response = $this->post(route('register.store'), $this->validData(['email' => $email]));

            $response->assertSessionHasErrors('email');
        }

        $this->assertSame(1, User::count());
        $this->assertGuest();
    }

    public function test_registration_fails_when_confirmation_does_not_match(): void
    {
        $response = $this->post(route('register.store'), $this->validData([
            'password_confirmation' => 'Different456?',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertSame(0, User::count());
        $this->assertGuest();
    }

    public function test_registration_fails_for_weak_password(): void
    {
        $response = $this->post(route('register.store'), $this->validData([
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertSame(0, User::count());
        $this->assertGuest();
    }

    public function test_registration_fails_when_name_or_email_is_missing(): void
    {
        $this->post(route('register.store'), $this->validData(['name' => '']))
            ->assertSessionHasErrors('name');

        $this->post(route('register.store'), $this->validData(['email' => '']))
            ->assertSessionHasErrors('email');

        $this->assertSame(0, User::count());
        $this->assertGuest();
    }

    public function test_newly_registered_user_can_log_out_and_log_back_in(): void
    {
        $this->post(route('register.store'), $this->validData());
        $this->assertAuthenticated();

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => 'new.user@example.com',
            'password' => self::PASSWORD,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs(User::firstWhere('email', 'new.user@example.com'));
    }
}
