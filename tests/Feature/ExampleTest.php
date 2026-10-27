<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('class="nav"', false)
            ->assertSee('mydesign/style.css')
            ->assertSee('Tepuk Tangan & Livi Visual | Home')
            ->assertSee('Home')
            ->assertSee('aria-current="page"', false)
            ->assertSee('Photobooth');
    }

    public function test_all_navigation_pages_render_the_shared_navigation(): void
    {
        foreach (['/home', '/inquiry', '/livestreaming', '/gallery', '/photoboot', '/about'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('class="nav"', false)
                ->assertSee('class="nav__toggle"', false)
                ->assertSee('aria-controls="primary-navigation"', false)
                ->assertSee('class="nav__menu"', false)
                ->assertSee('aria-current="page"', false)
                ->assertSee('Photobooth');
        }
    }
}
