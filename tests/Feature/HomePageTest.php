<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\Video;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_page_renders_the_editorial_sections_and_existing_navigation(): void
    {
        $this->createHomeMedia();

        $response = $this->get('/')->assertOk();

        $response
            ->assertSee('class="nav"', false)
            ->assertSee('class="home-hero"', false)
            ->assertSee('Tepuk Tangan × Livi Visual — Est. January 2020, Indonesia')
            ->assertSee('Where fleeting golden hours become classic, living heirlooms.')
            ->assertSee('Golden Moments')
            ->assertSee('Crafted for once in a lifetime')
            ->assertSee('Golden moments pass')
            ->assertSee('in a heartbeat.')
            ->assertSee('stay forever.')
            ->assertSee(route('about'))
            ->assertSee(route('gallery'))
            ->assertSee(route('inquiry'));

        $this->assertSame(5, substr_count((string) $response->getContent(), 'class="home-hero-slide"'));
        $this->assertSame(9, substr_count((string) $response->getContent(), 'class="home-gallery-card '));
    }

    public function test_home_page_omits_gallery_filters_and_manual_carousel_controls(): void
    {
        $this->createHomeMedia();

        $response = $this->get('/home');

        $response
            ->assertOk()
            ->assertDontSee('data-gallery-filter=', false)
            ->assertDontSee('data-gallery-kind=', false)
            ->assertDontSee('home-hero-counter')
            ->assertDontSee('home-hero-progress')
            ->assertDontSee('home-hero-arrow')
            ->assertDontSee('data-home-slide=', false)
            ->assertDontSee('data-home-direction=', false)
            ->assertSee('Tepuk Tangan × Livi Visual — Est. January 2020, Indonesia');
    }

    public function test_home_uses_database_media_for_every_image_outside_the_hero_carousel(): void
    {
        $this->createHomeMedia();

        $response = $this->get('/')->assertOk();
        $content = (string) $response->getContent();

        $this->assertSame(0, substr_count($content, 'https://images.unsplash.com'));
        $this->assertSame(20, substr_count($content, '/storage/gallery/photos/'));
        $this->assertSame(9, substr_count($content, 'class="home-gallery-card '));
        $response
            ->assertSee('/storage/gallery/photos/frame-01.jpg')
            ->assertSee('/storage/gallery/photos/frame-20.jpg')
            ->assertSee('Frame 08')
            ->assertSee('Photography by Tepuk Tangan')
            ->assertSee('Wedding story')
            ->assertSee('Film by Livi Visual')
            ->assertSee('© Tepuk Tangan × Livi Visual. Founded 2020, Indonesia.');
    }

    private function createHomeMedia(): void
    {
        for ($index = 1; $index <= 20; $index++) {
            Photo::factory()->create([
                'title' => sprintf('Frame %02d', $index),
                'path' => sprintf('gallery/photos/frame-%02d.jpg', $index),
            ]);
        }

        Video::factory()->create([
            'title' => 'Wedding story',
            'path' => 'gallery/videos/story.mp4',
        ]);
    }
}
