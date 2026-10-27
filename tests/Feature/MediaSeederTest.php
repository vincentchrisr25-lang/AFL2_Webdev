<?php

namespace Tests\Feature;

use App\Models\Video;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\VideoSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_video_seeder_corrects_the_legacy_wedding_title_typo(): void
    {
        Storage::fake('public');

        $path = 'gallery/videos/Wdding Cinema - Vera & Nuel.mp4';
        Storage::disk('public')->put($path, 'video');
        Video::factory()->create([
            'title' => 'Wdding Cinema   Vera & Nuel',
            'path' => $path,
        ]);

        $this->seed(VideoSeeder::class);

        $this->assertDatabaseHas('videos', [
            'title' => 'Wedding Cinema Vera & Nuel',
            'path' => $path,
        ]);
    }

    public function test_photo_and_video_seeders_import_storage_paths_without_duplicating_records(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/photos/DIA00001.jpg', 'photo');
        Storage::disk('public')->put('gallery/photos/DIA00002.jpg', 'photo');
        Storage::disk('public')->put('gallery/videos/story.mp4', 'video');

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('photos', 2);
        $this->assertDatabaseHas('photos', [
            'title' => 'Dia00001',
            'path' => 'gallery/photos/DIA00001.jpg',
        ]);
        $this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('videos', [
            'title' => 'Story',
            'path' => 'gallery/videos/story.mp4',
        ]);
        $this->assertDatabaseCount('users', 1);
    }
}
