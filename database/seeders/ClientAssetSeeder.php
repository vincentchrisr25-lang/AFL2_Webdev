<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ClientAssetSeeder extends Seeder
{
    private const EXCLUDED_ASSETS = [
        'gallery/Vera & Nuel/Video/Wdding Cinema - Vera & Nuel.mp4',
        'Livesteaming/BTS OLYMPRUN.mp4',
    ];

    private const ASSET_SOURCES = [
        1 => ['gallery/Vera & Nuel'],
        2 => ['gallery/Stephen&Poppy'],
        3 => ['gallery/Erica&Alvin'],
        4 => ['Photoboot/arul silvia'],
        5 => ['Photoboot/calvin meili'],
        6 => ['Photoboot/hendy & sonia'],
        7 => ['Photoboot/jeremmy & anggraini'],
        8 => ['Photoboot/Luna'],
        9 => ['Livesteaming/bts elementary school.mp4'],
        10 => ['Livesteaming/bts livecam bg gold run.mp4'],
        11 => ['Livesteaming/BTS OLYMPRUN.mp4'],
        12 => ['Livesteaming/BTS SMP Cita Hati.mp4'],
    ];

    public function run(): void
    {
        $photoExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tif', 'tiff', 'avif'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'm4v', 'mpeg', 'mpg', '3gp'];

        foreach (ClientSeeder::clientData() as $data) {
            $client = Client::query()->find($data['id']);

            if (! $client) {
                throw new RuntimeException("Client record not found for ID {$data['id']} ({$data['client_name']}).");
            }

            $assetSources = self::ASSET_SOURCES[$data['id']] ?? null;

            if ($assetSources === null) {
                throw new RuntimeException("Client media sources are not configured for {$data['client_name']}.");
            }

            $files = collect($assetSources)
                ->flatMap(function (string $source) {
                    if (Storage::disk('public')->directoryExists($source)) {
                        return Storage::disk('public')->allFiles($source);
                    }

                    if (Storage::disk('public')->exists($source)) {
                        return [$source];
                    }

                    throw new RuntimeException("Client media source not found: {$source}");
                })
                ->reject(fn (string $path) => in_array($path, self::EXCLUDED_ASSETS, true))
                ->filter(function (string $path) use ($photoExtensions, $videoExtensions) {
                    return in_array(
                        strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                        [...$photoExtensions, ...$videoExtensions],
                        true
                    );
                })
                ->unique()
                ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                ->values();

            if ($files->isEmpty()) {
                throw new RuntimeException("No supported photo or video files found for {$client->client_name}.");
            }

            $photos = $files->filter(function (string $path) use ($photoExtensions) {
                return in_array(
                    strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                    $photoExtensions,
                    true
                );
            })->values();

            if ($client->cover_image && ! $photos->contains($client->cover_image)) {
                throw new RuntimeException("Cover image not found among client photos: {$client->cover_image}");
            }

            $client->assets()->delete();

            foreach ($files as $index => $path) {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                $client->assets()->create([
                    'file_path' => $path,
                    'file_type' => in_array($extension, $videoExtensions, true) ? 'video' : 'photo',
                    'alt' => $client->client_name,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
