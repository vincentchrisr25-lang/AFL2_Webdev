<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\Video;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class GalleryPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_gallery_page_links_to_photo_and_video_subpages(): void
    {
        $this->get('/gallery')
            ->assertOk()
            ->assertViewIs('gallery.index')
            ->assertSee('Photo')
            ->assertSee('Video')
            ->assertSee(route('gallery.photos'))
            ->assertSee(route('gallery.videos'));
    }

    public function test_photo_subpage_shows_empty_state_when_there_are_no_photos(): void
    {
        $this->get('/gallery/photos')
            ->assertOk()
            ->assertViewIs('gallery.photos')
            ->assertSee('Belum ada foto di galeri.')
            ->assertSee(route('gallery.videos'))
            ->assertSee('aria-current="page"', false);
    }

    public function test_video_subpage_shows_empty_state_when_there_are_no_videos(): void
    {
        $this->get('/gallery/videos')
            ->assertOk()
            ->assertViewIs('gallery.videos')
            ->assertSee('Our films are being prepared for the archive.')
            ->assertSee('Motion by Livi Visual')
            ->assertSee('class="nav"', false);
    }

    public function test_photo_and_video_pages_render_media_from_the_database(): void
    {
        $photo = Photo::factory()->create([
            'title' => 'Lake Como',
            'path' => 'gallery/photos/lake-como.jpg',
        ]);
        $video = Video::factory()->create([
            'title' => 'Wedding Film',
            'path' => 'gallery/videos/wedding-film.mp4',
        ]);

        Photo::factory()->create([
            'title' => 'Wedding Still',
            'path' => 'gallery/photos/wedding-still.jpg',
        ]);

        $this->get('/gallery/photos')
            ->assertOk()
            ->assertSee($photo->url)
            ->assertSee('alt="Lake Como"', false);

        $this->get('/gallery/videos')
            ->assertOk()
            ->assertSee('data-film-src="'.$video->url.'"', false)
            ->assertSee('data-film-title="Wedding Film"', false)
            ->assertSee('data-film-category="wedding-cinema"', false)
            ->assertSee('/storage/gallery/photos/wedding-still.jpg')
            ->assertSee('class="video-film-title">Wedding Film</span>', false)
            ->assertDontSee('01 — Wedding Film')
            ->assertSee('Selected Motion Archive')
            ->assertSee('The Craft of Motion')
            ->assertSee('data-video-theater', false)
            ->assertDontSee('class="video-watch-button"', false)
            ->assertSee(route('inquiry'));
    }
}
