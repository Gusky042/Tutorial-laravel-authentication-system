<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeRouteTest extends TestCase
{
    public function test_guest_visiting_home_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_login_page_returns_ok(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertViewIs('auth.login');
        $response->assertSee(config('app.name'));
    }

    public function test_pages_show_the_logo_and_favicons(): void
    {
        $response = $this->get('/login');

        $response->assertSee('Enterprise suite');
        $response->assertSee('M50 6 90 29 50 52 10 29Z', false); // the cube's top face
        $response->assertSee('favicon.svg', false);
        $response->assertSee('apple-touch-icon.png', false);

        $this->assertFileExists(public_path('favicon.svg'));
        $this->assertFileExists(public_path('apple-touch-icon.png'));
        $this->assertGreaterThan(0, filesize(public_path('favicon.ico')));
    }
}
