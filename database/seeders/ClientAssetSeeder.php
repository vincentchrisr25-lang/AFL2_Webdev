<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ClientAssetSeeder extends Seeder
{
    private const ASSET_SOURCES = [
        'Vera & Nuel' => ['gallery/Vera & Nuel'],
        'Stephen & Poppy' => ['gallery/Stephen&Poppy'],
        'Erica & Alvin' => ['gallery/Erica&Alvin'],
        'Arul Silvia' => ['Photoboot/arul silvia'],
        'Calvin Meili' => ['Photoboot/calvin meili'],
        'Hendy & Sonia' => ['Photoboot/hendy & sonia'],
        'Jeremmy & Anggraini' => ['Photoboot/jeremmy & anggraini'],
        'Luna' => ['Photoboot/Luna'],
        'BTS Elementary School' => ['Livesteaming/bts elementary school.mp4'],
        'BTS Livecam BG Gold Run' => ['Livesteaming/bts livecam bg gold run.mp4'],
        'BTS SMP Cita Hati' => ['Livesteaming/BTS SMP Cita Hati.mp4'],
    ];

    public function run(): void
    {
        $photoExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tif', 'tiff', 'avif'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'm4v', 'mpeg', 'mpg', '3gp'];

        foreach (ClientSeeder::clientData() as $data) {
            $client = Client::query()->where('client_name', $data['client_name'])->first();

            if (! $client) {
                throw new RuntimeException("Client record not found for {$data['client_name']}.");
            }

            $assetSources = self::ASSET_SOURCES[$data['client_name']] ?? null;

            if ($assetSources === null) {
                $this->command?->warn("No media sources configured for {$data['client_name']}; skipping assets.");

                continue;
            }

            $files = collect($assetSources)
                ->flatMap(function (string $source) {
                    if (Storage::disk('public')->directoryExists($source)) {
                        return Storage::disk('public')->allFiles($source);
                    }

                    if (Storage::disk('public')->exists($source)) {
                        return [$source];
                    }

                    $this->command?->warn("Skipping missing media source: {$source}");

                    return [];
                })
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
                $this->command?->warn("No supported photo or video files found for {$client->client_name}; skipping assets.");

                continue;
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
