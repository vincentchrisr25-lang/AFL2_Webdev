<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_shows_the_collective_story_artists_and_philosophy(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertSee('class="nav"', false)
            ->assertDontSee('class="about-nav"', false)
            ->assertSee('Holder')
            ->assertDontSee('CALIA')
            ->assertSee('aria-current="page"', false)
            ->assertSee('Photobooth')
            ->assertSee('We don’t stage an idealized version of life.')
            ->assertSee('Name')
            ->assertDontSee('Jo')
            ->assertDontSee('Aldo')
            ->assertDontSee('Lowe')
            ->assertDontSee('Kur')
            ->assertDontSee('Wellsen')
            ->assertSee('Our philosophy')
            ->assertSee('Unscripted Direction')
            ->assertSee('Timeless Monochrome')
            ->assertSee('Unified Storytelling')
            ->assertSee('Found the right eyes for your story?')
            ->assertSee(route('inquiry'));
    }

    public function test_about_page_renders_three_video_director_profiles(): void
    {
        $response = $this->get('/about')->assertOk();

        preg_match(
            '/<section[^>]*id="video-directors"[^>]*>(.*?)<\/section>/s',
            (string) $response->getContent(),
            $videoDirectorsSection
        );

        $this->assertSame(
            3,
            substr_count($videoDirectorsSection[1] ?? '', '<article class="about-artist-card')
        );
    }
}
