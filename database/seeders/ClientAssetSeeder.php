<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ClientAssetSeeder extends Seeder
{
    private const ASSET_SOURCES = [
        'Stephen & Poppy' => ['gallery/Stephen&Poppy'],
        'Erica & Alvin' => ['gallery/Erica&Alvin'],
        'Vera & Nuel' => ['gallery/Vera & Nuel'],
        'Arul Silvia' => ['Photoboot/arul silvia'],
        'Calvin Meili' => ['Photoboot/calvin meili'],
        'Hendy & Sonia' => ['Photoboot/hendy & sonia'],
        'Jeremmy & Anggraini' => ['Photoboot/jeremmy & anggraini'],
        'Luna' => ['Photoboot/Luna'],
        'BTS Elementary School' => ['Livesteaming/bts elementary school.mp4'],
        'BTS Livecam BG Gold Run' => ['Livesteaming/bts livecam bg gold run.mp4'],
        'BTS Olymprun' => ['Livesteaming/BTS OLYMPRUN.mp4'],
        'BTS SMP Cita Hati' => ['Livesteaming/BTS SMP Cita Hati.mp4'],
    ];

    public function run(): void
    {
        $photoExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tif', 'tiff', 'avif'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'm4v', 'mpeg', 'mpg', '3gp'];

        foreach (ClientSeeder::clientData() as $data) {
            $client = Client::query()
                ->where('client_name', $data['client_name'])
                ->where('client_type', $data['client_type'])
                ->first();

            if (! $client) {
                throw new RuntimeException("Client record not found for {$data['client_name']} ({$data['client_type']}).");
            }

            $assetSources = self::ASSET_SOURCES[$data['client_name']] ?? null;

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
