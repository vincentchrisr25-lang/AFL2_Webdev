<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paths = collect(Storage::disk('public')->allFiles('gallery/videos'))
            ->filter(fn (string $path): bool => in_array(
                strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                ['mp4', 'm4v', 'mov', 'webm'],
                true,
            ))
            ->sort()
            ->values();

        if ($paths->isEmpty()) {
            throw new RuntimeException('No supported video files were found in storage/app/public/gallery/videos.');
        }

        foreach ($paths as $path) {
            $title = Str::of(pathinfo($path, PATHINFO_FILENAME))
                ->replace(['-', '_'], ' ')
                ->squish()
                ->title()
                ->replace('Wdding', 'Wedding')
                ->toString();

            $video = Video::query()->firstOrCreate(
                ['path' => $path],
                ['title' => $title],
            );

            if (Str::contains($video->title, 'Wdding')) {
                $video->update(['title' => $title]);
            }
        }
    }
}
