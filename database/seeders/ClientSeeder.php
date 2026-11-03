<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public static function clientData(): array
    {
        return [
            [
                'client_name' => 'Stephen & Poppy',
                'cover_image' => 'gallery/Stephen&Poppy/_NRO0465.jpg',
                'month_date' => '13/10/25',
                'client_type' => 'gallery',
            ],
            [
                'client_name' => 'Erica & Alvin',
                'cover_image' => 'gallery/Erica&Alvin/DIA00699.JPG',
                'month_date' => '06/06/22',
                'client_type' => 'gallery',
            ],
            [
                'client_name' => 'Vera & Nuel',
                'cover_image' => 'gallery/Vera & Nuel/Photo/DIA00001.jpg',
                'month_date' => '16/06/23',
                'client_type' => 'gallery',
            ],
            [
                'client_name' => 'Arul Silvia',
                'cover_image' => 'Photoboot/arul silvia/20260718_192008_949.jpg',
                'month_date' => '12/01/23',
                'client_type' => 'photobooth',
            ],
            [
                'client_name' => 'Calvin Meili',
                'cover_image' => 'Photoboot/calvin meili/20260509_181606_319.jpg',
                'month_date' => '09/05/23',
                'client_type' => 'photobooth',
            ],
            [
                'client_name' => 'Hendy & Sonia',
                'cover_image' => 'Photoboot/hendy & sonia/20260117_193151_788.jpg',
                'month_date' => '17/01/23',
                'client_type' => 'photobooth',
            ],
            [
                'client_name' => 'Jeremmy & Anggraini',
                'cover_image' => 'Photoboot/jeremmy & anggraini/20260502_193919_577.jpg',
                'month_date' => '02/05/23',
                'client_type' => 'photobooth',
            ],
            [
                'client_name' => 'Luna',
                'cover_image' => 'Photoboot/Luna/20261009_174245_349.jpg',
                'month_date' => '09/10/23',
                'client_type' => 'photobooth',
            ],
            [
                'client_name' => 'BTS Elementary School',
                'cover_image' => null,
                'month_date' => '15/06/23',
                'client_type' => 'livestream',
            ],
            [
                'client_name' => 'BTS Livecam BG Gold Run',
                'cover_image' => null,
                'month_date' => '20/06/23',
                'client_type' => 'livestream',
            ],
            [
                'client_name' => 'BTS SMP Cita Hati',
                'cover_image' => null,
                'month_date' => '12/01/23',
                'client_type' => 'livestream',
            ],
        ];
    }

    public function run(): void
    {
        foreach (self::clientData() as $data) {
            Client::query()->updateOrCreate(
                ['client_name' => $data['client_name']],
                [
                    'cover_image' => $data['cover_image'],
                    'month_date' => $data['month_date'] ?? null,
                    'client_type' => $data['client_type'],
                ]
            );
        }
    }
}
