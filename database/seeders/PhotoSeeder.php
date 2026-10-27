<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paths = collect(Storage::disk('public')->allFiles('gallery/photos'))
            ->filter(fn (string $path): bool => in_array(
                strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                ['jpg', 'jpeg', 'png', 'webp', 'avif'],
                true,
            ))
            ->sort()
            ->values();

        if ($paths->isEmpty()) {
            throw new RuntimeException('No supported photo files were found in storage/app/public/gallery/photos.');
        }

        foreach ($paths as $path) {
            Photo::query()->firstOrCreate(
                ['path' => $path],
                ['title' => Str::of(pathinfo($path, PATHINFO_FILENAME))
                    ->replace(['-', '_'], ' ')
                    ->title()
                    ->toString()],
            );
        }
    }
}
