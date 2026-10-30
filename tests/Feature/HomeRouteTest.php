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
}
