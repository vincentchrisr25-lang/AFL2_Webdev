# Explanation Document — AFL2_Webdev

File ini berisi penjelasan lengkap setiap baris kode dalam proyek Laravel **AFL2_Webdev**, diturunkan dari rangkuman arsitektur MVC, database, migration, model, controller, view, route, public, storage, components, layouts, CSS, dan JavaScript.

---

## 1. Ringkasan Proyek (Overview)

**AFL2_Webdev** adalah website portofolio dan inquiry untuk kolektif fotografi & videografi bernama **"Tepuk Tangan"** (fotografi) dan **"Livi Visual"** (videography/film). Didirikan Januari 2020 di Indonesia.

Situs ini berfungsi sebagai:
- **Galeri online** untuk menampilkan karya klien berdasarkan kategori (gallery, photobooth, lumenara, livestreaming)
- **Halaman detail** per klien dengan foto dan video
- **Form inquiry/kontak** yang terintegrasi dengan WhatsApp untuk calon klien

**Stack Teknologi:**
- Framework: Laravel 13.x
- PHP: ^8.3
- Database: MySQL (`tepuktan_laravel`)
- Templating: Blade
- CSS: Kustom (`public/mydesign/style.css`)
- JavaScript: Vanilla JS (`public/mydesign/app.js`)
- Build tool: Vite
- Storage: Laravel Filesystem (disk `public`)

---

## 2. Arsitektur MVC (Model-View-Controller)

Proyek ini mengikuti pola arsitektur MVC bawaan Laravel:

| Layer | Tanggung Jawab | Lokasi |
|-------|---------------|--------|
| **Model** | Data & bisnis logic (Eloquent ORM) | `app/Models/` |
| **View** | Tampilan halaman (Blade templates) | `resources/views/` |
| **Controller** | Menangani request, hubungkan Model dan View | `app/Http/Controllers/` |
| **Route** | Memetakan URL ke Controller | `routes/web.php` |

**Alur Request:**
1. Browser mengirim request HTTP ke URL tertentu
2. `routes/web.php` mencocokkan URL dengan route yang terdaftar
3. Route mengarahkan ke method dalam Controller
4. Controller mengambil data dari Model (query database)
5. Controller melempar data ke View (Blade)
6. View merender HTML dan mengirimkannya ke browser

---

## 3. Routes (`routes/web.php`)

File ini berisi definisi semua halaman yang dapat diakses di website.

```php
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/home', [HomeController::class, 'index'])->name('home');
```
- **Baris 8**: Route `GET /` (root) diarahkan ke `HomeController@index`, dinamai `root`
- **Baris 9**: Route `GET /home` diarahkan ke `HomeController@index`, dinamai `home`
- Keduanya menampilkan halaman utama (homepage)

```php
Route::get('/about', [AboutController::class, 'index'])->name('about');
```
- **Baris 11**: Route `GET /about` diarahkan ke `AboutController@index`, dinamai `about`
- **Catatan**: File `AboutController.php` **tidak ada** di folder controllers → akan error jika dibuka

```php
Route::view('/inquiry', 'inquiry', ['active' => 'inquiry'])->name('inquiry');
```
- **Baris 13**: Route `GET /inquiry` langsung menampilkan view `inquiry.blade.php` tanpa melewati controller
- Mengirim variabel `active` dengan nilai `'inquiry'` ke view

```php
Route::get('/gallery', [ClientController::class, 'galleryClients'])->name('gallery');
Route::get('/gallery/{client}', [ClientController::class, 'galleryDetail'])->name('gallery.detail');
```
- **Baris 15**: `GET /gallery` → daftar klien bertipe `gallery`
- **Baris 16**: `GET /gallery/{client}` → detail satu klien galeri (Route Model Binding)

```php
Route::get('/photobooth', [ClientController::class, 'photoboothClients'])->name('photobooth');
Route::get('/photobooth/{client}', [ClientController::class, 'photoboothDetail'])->name('photobooth.detail');
```
- **Baris 18-19**: Sama seperti gallery tapi untuk tipe `photobooth`

```php
Route::get('/lumenara', [ClientController::class, 'lumenaraClients'])->name('lumenara');
Route::get('/lumenara/{client}', [ClientController::class, 'lumenaraDetail'])->name('lumenara.detail');
```
- **Baris 21-22**: Sama seperti gallery tapi untuk tipe `lumenara`

```php
Route::get('/livestreaming', [ClientController::class, 'livestreamingClients'])->name('livestreaming');
Route::get('/livestreaming/{client}', [ClientController::class, 'livestreamDetail'])->name('livestreaming.detail');
```
- **Baris 24-25**: Sama seperti gallery tapi untuk tipe `livestream`

---

## 4. Controllers (`app/Http/Controllers/`)

### 4.1 `Controller.php` (Base Controller)

```php
<?php
namespace App\Http\Controllers;
abstract class Controller {}
```
- Kelas dasar abstrak untuk semua controller. Tidak memiliki method apapun, hanya sebagai parent class.

### 4.2 `HomeController.php`

```php
<?php
namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'active' => 'home',
            'heroSlides' => ['DIA00001.jpg', 'DIA00003.jpg', 'DIA00007.jpg', 'DIA00021.jpg', 'DIA00039.jpg'],
            'galleryPhotos' => ['DIA00313.jpg', 'DIA00368.jpg', 'DIA00384.jpg', 'DIA00396.jpg', 'DIA00462.jpg', 'DIA00465.jpg', 'DIA00478.jpg', 'DIA00486.jpg'],
            'collageFrames' => [
                ['class' => 'one', 'parallax' => '0.12', 'file' => 'DIA08913.jpg'],
                ['class' => 'two', 'parallax' => '-0.1', 'file' => 'DIA08966.jpg'],
                ['class' => 'three', 'parallax' => '0.08', 'file' => 'DIA09013.jpg'],
                ['class' => 'four', 'parallax' => '-0.14', 'file' => 'DIA09069.jpg'],
            ],
        ]);
    }
}
```
- **Baris 7-8**: Class `HomeController` extends `Controller`
- **Baris 9**: Method `index()` mengembalikan `View`
- **Baris 11**: Memanggil view `home.blade.php` dengan data:
  - `active` = `'home'` (untuk highlight navigasi)
  - `heroSlides` = Array 5 nama file gambar hero (diurutkan)
  - `galleryPhotos` = Array 8 nama file gambar galeri utama
  - `collageFrames` = Array 4 frame collage dengan class CSS, nilai parallax, dan nama file

### 4.3 `ClientController.php`

```php
<?php
namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Contracts\View\View;

class ClientController extends Controller
{
    public function galleryClients(): View
    {
        $clients = Client::query()
            ->where('client_type', 'gallery')
            ->latest()
            ->get();

        return view('index', [
            'active' => 'gallery',
            'clients' => $clients,
        ]);
    }
    // ... dan seterusnya
}
```

**Method `galleryClients()` (baris 10-21):**
- Query semua klien dengan `client_type = 'gallery'`, diurutkan dari terbaru (`latest()`)
- Kirim ke view `index.blade.php` dengan `active = 'gallery'`

**Method `photoboothClients()` (baris 23-31):**
- Memanggil helper private `listClients()` dengan tipe `'photobooth'`, view `'photobooth'`, route `'photobooth.detail'`, dan pesan kosong `'Belum ada klien photobooth.'`

**Method `lumenaraClients()` (baris 33-41):**
- Sama seperti photobooth tapi tipe `'lumenara'`

**Method `livestreamingClients()` (baris 43-51):**
- Sama seperti photobooth tapi tipe `'livestream'`

**Method `galleryDetail(Client $client)` (baris 53-56):**
- Menerima model `Client` via Route Model Binding (Laravel otomatis ambil data dari DB berdasarkan ID di URL)
- Memanggil `showClient('gallery', $client, 'gallerydetail', 'gallery')`

**Method `photoboothDetail()` (baris 58-61):**
- Sama, tipe `'photobooth'`, view `'photoboothdetail'`

**Method `lumenaraDetail()` (baris 63-66):**
- Sama, tipe `'lumenara'`, view `'lumenaradetail'`

**Method `livestreamDetail()` (baris 68-71):**
- Sama, tipe `'livestream'`, view `'livestreamdetail'`

**Method `listClients()` (baris 73-86):**
- Helper private untuk daftar klien
- Query `Client` berdasarkan `$type`, diurutkan `latest()`
- Return view dengan `active`, `clients`, `detailRoute`, `emptyMessage`

**Method `showClient()` (baris 88-98):**
- Helper private untuk halaman detail
- `abort_unless($client->client_type === $type, 404)` → jika tipe klien tidak cocok, return 404
- `$client->load(['videos', 'photos'])` → eager load relasi videos dan photos
- Return view dengan `active` dan `client`

### 4.4 `ClientAssetController.php`

Semua method masih kosong (stub). CRUD untuk `ClientAsset` belum diimplementasi.

### 4.5 `GalleryController.php`

```php
public function index(): View
{
    return view('index', ['active' => 'gallery']);
}
```
- Method `index()` return view `index` dengan `active = 'gallery'`
- Fungsionalitasnya duplikasi dengan `ClientController@galleryClients` tapi tanpa query data klien

### 4.6 `AboutController.php`

**File tidak ada** (hilang). Route `/about` akan error 404/500 jika diakses.

---

## 5. Models (`app/Models/`)

### 5.1 `Client.php`

```php
<?php
namespace App\Models;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'client_name', 'card_title', 'cover_image', 'month_date', 'client_type',
    ];
    // ... relasi dan accessor
}
```

| Baris | Penjelasan |
|-------|------------|
| 12-13 | `HasFactory` trait memungkinkan penggunaan factory untuk generate data dummy |
| 15-21 | `$fillable` mendefinisikan kolom yang dapat diisi secara mass assignment: `client_name`, `card_title`, `cover_image`, `month_date`, `client_type` |
| 23-26 | `assets()` → Relasi HasMany ke `ClientAsset` (semua aset milik klien ini) |
| 28-33 | `videos()` → HasMany `ClientAsset` filtered `file_type = 'video'`, urutkan `sort_order` lalu `id` |
| 35-42 | `photos()` → HasMany `ClientAsset` filtered `file_type = 'photo'`, urutkan `sort_order` lalu `id` |
| 44-51 | `getCoverUrlAttribute()` → Accessor, return `asset('storage/' . $this->cover_image)` atau null |
| 53-56 | `getDisplayTitleAttribute()` → Accessor, return `card_title` jika ada, jika tidak pakai `client_name` |

### 5.2 `ClientAsset.php`

```php
<?php
namespace App\Models;
use Database\Factories\ClientAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAsset extends Model
{
    /** @use HasFactory<ClientAssetFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id', 'file_path', 'file_type', 'alt', 'sort_order',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }
}
```

| Baris | Penjelasan |
|-------|------------|
| 15-21 | `$fillable`: `client_id` (FK), `file_path` (path file), `file_type` (photo/video), `alt` (alt text), `sort_order` (urutan) |
| 23-26 | `client()` → Relasi BelongsTo ke `Client` (satu aset milik satu klien) |
| 28-31 | `getUrlAttribute()` → Accessor, return URL lengkap via `asset('storage/' . $this->file_path)` |

### 5.3 `User.php`

Model default Laravel untuk authentikasi. Memiliki `HasFactory`, `Notifiable`, fillable `['name', 'email', 'password']`, hidden `['password', 'remember_token']`, dan casts untuk `email_verified_at` (datetime) dan `password` (hashed).

---

## 6. Migrations (`database/migrations/`)

Migration adalah file PHP yang mendefinisikan struktur tabel database. Dijalankan dengan `php artisan migrate`.

### 6.1 `2026_11_02_173103_create_clients_table.php`

```php
Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('client_name');
    $table->string('card_title');
    $table->string('cover_image')->nullable();
    $table->string('month_date')->nullable();
    $table->string('client_type')->index();
    $table->timestamps();
});
```

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigIncrements | Primary Key |
| `client_name` | string | Nama klien |
| `card_title` | string | Judul yang ditampilkan di kartu |
| `cover_image` | string (nullable) | Path gambar sampul |
| `month_date` | string (nullable) | Bulan/tanggal acara |
| `client_type` | string (indexed) | Tipe: gallery, photobooth, lumenara, livestream |
| `timestamps` | | `created_at`, `updated_at` |

### 6.2 `2026_11_02_173109_create_client_assets_table.php`

```php
Schema::create('client_assets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
    $table->string('file_path');
    $table->string('file_type')->index();
    $table->string('alt')->nullable();
    $table->integer('sort_order')->nullable();
    $table->timestamps();
});
```

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigIncrements | Primary Key |
| `client_id` | foreignId | Foreign Key ke `clients.id`, cascade on delete |
| `file_path` | string | Path file aset di storage |
| `file_type` | string (indexed) | `photo` atau `video` |
| `alt` | string (nullable) | Alternatif teks untuk gambar |
| `sort_order` | integer (nullable) | Urutan tampilan aset |
| `timestamps` | | `created_at`, `updated_at` |

### 6.3 Tabel Lama (Tidak Digunakan Lagi)

- `2026_10_27_050243_create_photos_table.php` → Tabel `photos` (`id`, `title`, `path`, timestamps)
- `2026_10_27_050256_create_videos_table.php` → Tabel `videos` (`id`, `title`, `path`, timestamps)

Keduanya sudah digantikan oleh `client_assets` yang lebih generik (menangani baik foto maupun video).

### 6.4 Default Laravel

- `create_users_table` → Tabel `users` untuk authentikasi
- `create_cache_table` → Tabel cache
- `create_jobs_table` → Tabel antrean pekerjaan (queue)

---

## 7. Seeders (`database/seeders/`)

Seeder digunakan untuk mengisi data awal ke database. Dijalankan dengan `php artisan db:seed`.

### 7.1 `DatabaseSeeder.php`

```php
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (! User::query()->where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            ClientSeeder::class,
            ClientAssetSeeder::class,
        ]);
    }
}
```

- Membuat satu user test jika belum ada (`test@example.com`)
- Memanggil `ClientSeeder` dan `ClientAssetSeeder`

### 7.2 `ClientSeeder.php`

```php
$clients = [
    ['client_name' => 'Stephen & Phoppy Wedding', 'cover_image' => 'gallery/Stephen&Poppy/_NRO0465.jpg', 'month_date' => '13/10/25'],
    ['client_name' => 'Erica & Alvin', 'cover_image' => 'gallery/Erica&Alvin/DIA00699.JPG', 'month_date' => '06/06/22'],
    ['client_name' => 'Vera & Nuel', 'cover_image' => 'gallery/photos/.jpg', 'month_date' => null],
];

foreach ($clients as $data) {
    Client::updateOrCreate(
        ['client_name' => $data['client_name']],
        ['cover_image' => $data['cover_image'], 'month_date' => $data['month_date'], 'client_type' => 'gallery']
    );
}
```

- Membuat 3 data klien bertipe `gallery`
- `updateOrCreate` → update jika sudah ada, create jika belum
- `Vera & Nuel` tidak memiliki `month_date` (null)

### 7.3 `ClientAssetSeeder.php`

```php
$clients = [
    'Stephen & Phoppy Wedding' => 'gallery/Stephen&Poppy',
    'Erica & Alvin' => 'gallery/Erica&Alvin',
    'Vera & Nuel' => 'gallery/photos',
];

foreach ($clients as $clientName => $folder) {
    $client = Client::query()->where('client_name', $clientName)->first();
    if (! $client) continue;

    $files = Storage::disk('public')->files($folder);
    $files = array_values(array_filter($files, function (string $path): bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
    }));

    sort($files, SORT_NATURAL | SORT_FLAG_CASE);

    $client->update(['cover_image' => $files[0] ?? null]);
    $client->assets()->where('file_type', 'photo')->delete();

    foreach ($files as $index => $path) {
        $client->assets()->create([
            'file_path' => $path,
            'file_type' => 'photo',
            'alt' => $client->client_name,
            'sort_order' => $index,
        ]);
    }

    if ($clientName === 'Vera & Nuel') {
        $videoPath = 'gallery/videos/Wdding Cinema - Vera & Nuel.mp4';
        if (! Storage::disk('public')->exists($videoPath)) {
            throw new \RuntimeException('The Vera & Nuel wedding video was not found in gallery/videos.');
        }
        $client->assets()->updateOrCreate(
            ['file_path' => $videoPath],
            ['file_type' => 'video', 'alt' => 'Vera & Nuel wedding film', 'sort_order' => 0]
        );
    }
}
```

- Membaca file fisik dari folder `storage/app/public/gallery/*`
- Filter hanya file dengan ekstensi gambar (jpg, jpeg, png, webp, gif)
- Urutkan file secara natural
- Set file pertama sebagai `cover_image`
- Hapus semua foto lama lalu insert ulang dengan `sort_order`
- Untuk `Vera & Nuel`, tambahkan satu video tambahan

---

## 8. Factories (`database/factories/`)

Factory digunakan untuk generate data dummy (testing/development).

### 8.1 `ClientFactory.php`

```php
public function definition(): array
{
    return [
        'client_name' => fake()->name(),
        'card_title' => fake()->words(2, true),
        'cover_image' => null,
        'month_date' => fake()->date('d/m/y'),
        'client_type' => fake()->randomElement(['gallery', 'photobooth', 'lumenara', 'livestream']),
    ];
}
```

- `client_name`: Nama acak dari Faker
- `card_title`: 2 kata acak
- `cover_image`: null
- `month_date`: Tanggal acak format `d/m/y`
- `client_type`: Pilihan random dari 4 tipe

### 8.2 `ClientAssetFactory.php`

```php
public function definition(): array
{
    return [
        'client_id' => Client::factory(),
        'file_path' => 'clients/sample.jpg',
        'file_type' => fake()->randomElement(['photo', 'video']),
        'alt' => null,
        'sort_order' => fake()->numberBetween(0, 10),
    ];
}
```

- `client_id`: Buat client baru secara otomatis via factory
- `file_path`: Path dummy
- `file_type`: Random `photo` atau `video`
- `alt`: null
- `sort_order`: Angka 0-10

### 8.3 `UserFactory.php`

```php
public function definition(): array
{
    return [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'remember_token' => bin2hex(random_bytes(5)),
    ];
}
```

- `name`: Nama acak
- `email`: Email acak unik
- `email_verified_at`: Waktu sekarang (sudah terverifikasi)
- `password`: Hash dari string `'password'`
- `remember_token`: Token acak heksadesimal

---

## 9. Views (Blade Templates) (`resources/views/`)

### 9.1 `home.blade.php` — Halaman Utama

```blade
@extends('layouts.app')

@section('title', 'Tepuk Tangan | Home')

@section('content')
    <div class="home-page">
        <section class="home-hero" aria-label="Featured wedding stories">
            <div class="home-hero-track" aria-live="off">
                @foreach ($heroSlides as $index => $file)
                    <article class="home-hero-slide">
                        <img src="{{ asset('assets/Home/'.$file) }}"
                             alt="Wedding photography by Tepuk Tangan"
                             width="1600" height="900"
                             @if ($index === 0) fetchpriority="high" decoding="async"
                             @else loading="lazy" decoding="async" @endif>
                    </article>
                @endforeach
            </div>
            <div class="home-hero-vignette" aria-hidden="true"></div>
        </section>

        <section class="home-intro" id="our-approach" aria-labelledby="home-intro-title">
            <div class="home-intro-copy">
                <h1 id="home-intro-title">Where fleeting golden hours become classic, living heirlooms.</h1>
                <div class="home-intro-story">
                    <p class="home-eyebrow">Who we are</p>
                    <p>We are a collective of passionate...</p>
                </div>
                <div class="home-intro-story home-intro-belief">
                    <p class="home-eyebrow">Our believe</p>
                    <p>Rooted in a simple, classic, and timeless style...</p>
                </div>
            </div>
            <figure class="home-intro-figure">
                <img class="home-intro-portrait" src="{{ asset('assets/Home/DIA00056.jpg') }}"
                     alt="Wedding photography by Tepuk Tangan" width="600" height="900"
                     loading="lazy" decoding="async">
                <img class="home-intro-detail" src="{{ asset('assets/Home/DIA00223.jpg') }}"
                     alt="" aria-hidden="true" width="400" height="300"
                     loading="lazy" decoding="async">
            </figure>
        </section>

        <section class="home-darkroom" id="selected-works" aria-labelledby="home-darkroom-title">
            <div class="home-darkroom-inner">
                <header class="home-darkroom-header">
                    <div>
                        <p class="home-eyebrow">A living archive, made together</p>
                        <h2 id="home-darkroom-title">Golden Moments<br>&amp; Motion</h2>
                    </div>
                </header>

                <div class="home-gallery-grid">
                    @foreach ($galleryPhotos as $index => $file)
                        <a class="home-gallery-card home-gallery-card--{{ $index + 1 }}" href="{{ route('gallery') }}">
                            <img src="{{ asset('assets/Home/'.$file) }}"
                                 alt="Wedding photography by Tepuk Tangan"
                                 width="800" height="1000"
                                 loading="lazy" decoding="async">
                            <span><i>Photography by Tepuk Tangan</i></span>
                        </a>
                    @endforeach

                    <a class="home-gallery-card home-gallery-card--film" href="{{ route('gallery') }}">
                        <img src="{{ asset('assets/Home/DIA08889.jpg') }}"
                             alt="Wedding film by Livi Visual"
                             width="800" height="800"
                             loading="lazy" decoding="async">
                        <span><i>Film by Livi Visual</i></span>
                        <span class="home-gallery-film-mark" aria-hidden="true">Play film ↗</span>
                    </a>
                </div>
                <a class="home-archive-link" href="{{ route('gallery') }}">Explore all stories <span aria-hidden="true">⟶</span></a>
            </div>
        </section>

        <section class="home-collage" aria-labelledby="home-cta-title">
            @foreach ($collageFrames as $frame)
                <div class="home-collage-frame home-collage-frame--{{ $frame['class'] }}"
                     data-parallax="{{ $frame['parallax'] }}">
                    <img src="{{ asset('assets/Home/'.$frame['file']) }}"
                         alt="" aria-hidden="true"
                         width="500" height="500"
                         loading="lazy" decoding="async">
                </div>
            @endforeach
            <div class="home-collage-copy">
                <p class="home-eyebrow">Crafted for once in a lifetime</p>
                <h2 id="home-cta-title"><span>Golden moments pass</span> <span>in a heartbeat.</span> <span>Let's make yours</span> <span>stay forever.</span></h2>
                <p class="home-collage-invitation">Kindly share your magical ideas or dreams with us...</p>
                <a class="home-cta-link" href="{{ route('inquiry') }}">Share your dream with us <span aria-hidden="true">⟶</span></a>
            </div>
        </section>
    </div>
@endsection
```

**Penjelasan Komponen:**
- **Baris 1**: `@extends('layouts.app')` — Menggunakan layout utama
- **Baris 3**: `@section('title', ...)` — Judul halaman: "Tepuk Tangan | Home"
- **Baris 5-77**: `@section('content')` — Isi halaman utama
- **Baris 7-16**: Hero section dengan slideshow gambar (5 slide)
  - Slide pertama: `fetchpriority="high"` (load prioritas tinggi untuk LCP)
  - Slide lainnya: `loading="lazy"` (muat saat scroll ke sana)
  - Semua: `decoding="async"` (decode di luar thread utama)
- **Baris 18-34**: Intro section dengan teks tentang kolektif dan 2 gambar potret
- **Baris 36-61**: Darkroom section — galeri grid (8 foto + 1 film) dengan link ke `/gallery`
- **Baris 63-75**: Collage section — 4 frame dengan efek parallax + CTA ke inquiry
- **Semua gambar**: Memiliki `width` dan `height` untuk menghindari layout shift

### 9.2 `index.blade.php` — Halaman Gallery

```blade
@extends('layouts.app')

@section('title', 'Gallery | Tepuk Tangan Photography & Videography')

@section('content')
    <section class="gallery-hero" aria-labelledby="gallery-title">
        <div class="gallery-hero__inner">
            <div class="gallery-hero__copy">
                <h1 class="gallery-hero__title" id="gallery-title">Gallery</h1>
                <p class="gallery-hero__subtitle">The Art of Stillness &amp; Motion</p>
                <blockquote class="gallery-hero__quote">...</blockquote>
            </div>
            <div class="gallery-moodboard" aria-label="Editorial photography and film moodboard">
                <figure class="gallery-moodboard__portrait">
                    <img src="https://images.unsplash.com/..." alt="..." fetchpriority="high">
                </figure>
                <figure class="gallery-moodboard__landscape">
                    <img src="https://images.unsplash.com/..." alt="..." loading="lazy">
                </figure>
            </div>
        </div>
    </section>

    <section class="gallery-archive" aria-label="Daftar klien galeri">
        <div class="gallery-card-grid">
            @foreach ($clients as $client)
                <a class="gallery-card" href="{{ route('gallery.detail', $client) }}"
                   aria-label="View full album for {{ $client->display_title }}">
                    <span class="gallery-card__cover">
                        <img src="{{ $client->cover_url ?: 'https://images.unsplash.com/...' }}"
                             alt="{{ $client->display_title }} — Tepuk Tangan visual archive"
                             loading="lazy">
                        <span class="gallery-card__shade" aria-hidden="true"></span>
                    </span>
                    <span class="gallery-card__meta">
                        <span class="gallery-card__title">{{ $client->display_title }}
                            <span class="gallery-card__arrow" aria-hidden="true">→</span>
                        </span>
                    </span>
                    <span class="gallery-card__rule" aria-hidden="true"></span>
                </a>
            @endforeach
        </div>
    </section>
@endsection
```

**Penjelasan:**
- Menampilkan halaman galeri dengan hero quote dan grid kartu klien
- `$clients` dikirim dari `ClientController@galleryClients`
- Setiap kartu link ke `route('gallery.detail', $client)` (detail klien)
- `$client->cover_url` menggunakan accessor dari model Client
- Fallback gambar dari Unsplash jika cover_image kosong

### 9.3 `gallerydetail.blade.php`, `photoboothdetail.blade.php`, `lumenaradetail.blade.php`, `livestreamdetail.blade.php`

Semua detail view memiliki struktur identik:

```blade
@extends('layouts.app')

@section('title', $client->display_title)

@section('content')
    @include('components.client-detail', ['client' => $client, 'listingRoute' => 'gallery'])
@endsection
```

- `$client` dikirim dari Controller melalui Route Model Binding
- `@include('components.client-detail')` memasukkan komponen detail klien
- `listingRoute` menentukan tujuan tombol "Kembali" (gallery, photobooth, lumenara, atau livestreaming)

### 9.4 `photobooth.blade.php`, `lumenara.blade.php`, `livestreaming.blade.php`

Struktur identik, hanya berbeda pada judul section dan route:

```blade
@extends('layouts.app')

@section('title', 'Photobooth | AFL2 Webdev')

@section('content')
    <section class="client-page" aria-labelledby="photobooth-title">
        <h1 class="client-page__title" id="photobooth-title">Photobooth</h1>

        @include('components.client-list', [
            'clients' => $clients,
            'detailRoute' => $detailRoute,
            'emptyMessage' => $emptyMessage,
        ])
    </section>
@endsection
```

- `$clients`, `$detailRoute`, `$emptyMessage` dikirim dari `ClientController@listClients()`

### 9.5 `inquiry.blade.php` — Form Inquiry/Kontak

```blade
@extends('layouts.app')

@section('title', 'Inquiry | AFL2 Webdev')

@section('content')
    <div class="inquiry-page">
        <aside class="inquiry-sidebar">
            <section class="inquiry-office" aria-labelledby="office-title">
                <h1 class="inquiry-heading" id="office-title">Office &amp; Studio.</h1>
                <address class="inquiry-address">
                    <span>Holder</span>
                    <span>Indonesia</span>
                </address>
            </section>
            <figure class="inquiry-portrait">
                <img src="{{ asset('assets/images/portrait.jpg') }}" alt="Fotografer sedang mengambil gambar" class="inquiry-portrait-img">
                <figcaption>Behind every frame, there is a story.</figcaption>
            </figure>
        </aside>

        <section class="inquiry-form-section" aria-labelledby="inquiry-title">
            <p class="inquiry-eyebrow">A LITTLE ABOUT YOUR PLANS</p>
            <h2 class="inquiry-form-title" id="inquiry-title">Inquire within.</h2>
            <p class="inquiry-intro">Tell us about your story, moodboard, or dream session...</p>

            <form class="inquiry-form" data-whatsapp-number="6285748430957">
                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Your Name <span class="inquiry-required" aria-label="required">*</span></span>
                        <input type="text" name="name" autocomplete="name" placeholder="How should we call you?" required>
                    </label>
                    <label class="inquiry-field">
                        <span>Partner / Brand Name <span class="inquiry-optional">(optional)</span></span>
                        <input type="text" name="partner_or_brand" autocomplete="organization" placeholder="A name we should know">
                    </label>
                </div>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Email <span class="inquiry-required" aria-label="required">*</span></span>
                        <input type="email" name="email" autocomplete="email" placeholder="you@example.com" required>
                    </label>
                    <label class="inquiry-field">
                        <span>WhatsApp Number</span>
                        <input type="tel" name="whatsapp" autocomplete="tel" placeholder="+6285748430957">
                    </label>
                </div>

                <label class="inquiry-field">
                    <span>Instagram Handle <span class="inquiry-optional">(optional)</span></span>
                    <input type="text" name="instagram" autocomplete="off" placeholder="@yourhandle">
                </label>

                <fieldset class="inquiry-fieldset">
                    <legend>What kind of session are you dreaming of? <span class="inquiry-required" aria-label="required">*</span></legend>
                    <div class="inquiry-pill-options">
                        @foreach (['wedding' => 'Wedding', 'pre-wedding' => 'Pre-wedding', 'portrait' => 'Portrait', 'commercial' => 'Commercial', 'editorial' => 'Editorial'] as $value => $label)
                            <label class="inquiry-pill">
                                <input type="checkbox" name="session_type[]" value="{{ $value }}" @if ($loop->first) required @endif>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="inquiry-name-fields">
                    <label class="inquiry-field">
                        <span>Estimated Shoot Date</span>
                        <input type="date" name="shoot_date">
                    </label>
                    <label class="inquiry-field">
                        <span>Location / Venue</span>
                        <input type="text" name="location" placeholder="City, venue, or still deciding">
                    </label>
                </div>

                <label class="inquiry-field">
                    <span>Tell us about your story, moodboard, or dream session <span class="inquiry-required" aria-label="required">*</span></span>
                    <textarea name="vision" rows="4" placeholder="The feeling, the little details, the things that matter most to you..." required></textarea>
                </label>

                <div class="inquiry-submit-area">
                    <button class="inquiry-submit" type="submit">Send Inquiry <span aria-hidden="true">→</span></button>
                    <p class="inquiry-response-note">
                        We typically respond within 24–48 hours. For urgent commercial bookings,
                        <a href="https://wa.me/6285748430957">reach us directly via WhatsApp</a>.
                    </p>
                </div>
            </form>
        </section>
    </div>

    <script src="{{ asset('mydesign/app.js') }}" defer></script>
@endsection
```

**Penjelasan:**
- Halaman form kontak/inquiry
- Sidebar kiri: alamat office (Holder, Indonesia) + gambar potret
- Form kanan: Nama, Partner/Brand, Email, WhatsApp, Instagram, Jenis Session (checkbox), Tanggal Shoot, Lokasi, Visi cerita (textarea)
- `data-whatsapp-number="6285748430957"` — nomor WhatsApp untuk tujuan inquiry
- Semua input required ditandai dengan `*` (aria-label="required")
- Submit form dibawa ke WhatsApp via JavaScript (`mydesign/app.js`)

---

## 10. Components (`resources/views/components/`)

### 10.1 `client-detail.blade.php`

```blade
<div class="client-detail">
    <header class="client-detail__header">
        <h1 class="client-detail__title">{{ $client->display_title }}</h1>
        @if ($client->month_date)
            <p class="client-detail__meta">
                <span>{{ $client->month_date }}</span>
            </p>
        @endif
    </header>

    <div class="client-detail__media">
        @foreach ($client->videos as $asset)
            <video class="client-detail__video" controls playsinline preload="metadata"
                   aria-label="{{ $client->display_title }} wedding video">
                <source src="{{ $asset->url }}">
            </video>
        @endforeach

        @if ($client->photos->isNotEmpty())
            <div class="client-detail__photos">
                @foreach ($client->photos as $asset)
                    <img class="client-detail__photo{{ $loop->first ? ' client-detail__photo--feature' : '' }}"
                         src="{{ $asset->url }}"
                         alt="{{ $asset->alt ?: $client->display_title }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                @endforeach
            </div>
        @endif
    </div>

    @if ($client->videos->isEmpty() && $client->photos->isEmpty())
        <p class="client-detail__empty">Belum ada aset untuk klien ini.</p>
    @endif

    <a class="client-detail__back" href="{{ route($listingRoute) }}">Kembali</a>
</div>
```

- Menampilkan detail satu klien: judul, tanggal, video, dan foto
- `$client->videos` dan `$client->photos` menggunakan accessor relasi dari model Client
- Foto pertama diberi class `client-detail__photo--feature` dan `loading="eager"`
- Foto lainnya `loading="lazy"`
- Jika tidak ada aset, tampilkan pesan "Belum ada aset untuk klien ini."
- Tombol "Kembali" mengarahkan ke route listing yang ditentukan

### 10.2 `client-list.blade.php`

```blade
<div class="client-list">
    @forelse ($clients as $client)
        @include('components.client-card', ['client' => $client, 'detailRoute' => $detailRoute])
    @empty
        <p class="client-list__empty">{{ $emptyMessage }}</p>
    @endforelse
</div>
```

- `@forelse` — loop clients, jika kosong tampilkan `$emptyMessage`
- Setiap klien dirender melalui `components.client-card`

### 10.3 `client-card.blade.php`

```blade
<article class="client-card">
    <a class="client-card__cover-link" href="{{ route($detailRoute, $client) }}" aria-label="Buka {{ $client->display_title }}">
        @if ($client->cover_url)
            <img class="client-card__cover" src="{{ $client->cover_url }}" alt="{{ $client->display_title }}" loading="lazy">
        @else
            <span class="client-card__cover client-card__cover--empty" aria-hidden="true"></span>
        @endif
    </a>
    <h2 class="client-card__title">{{ $client->display_title }}</h2>
    <a class="client-card__more" href="{{ route($detailRoute, $client) }}">Read More</a>
</article>
```

- Kartu klien dengan gambar sampul, judul, dan link "Read More"
- Jika `cover_url` ada, tampilkan gambar; jika tidak, tampilkan placeholder kosong
- Link menuju halaman detail klien

### 10.4 `artist-card.blade.php`

Komponen untuk halaman About (masih menggunakan data statis "Holder"). Tidak digunakan dalam flow utama saat ini.

### 10.5 Navbar Components

Ada 6 file navbar yang hampir identik, berbeda hanya pada logo yang digunakan:
- `navbar-home.blade.php` — Logo `tepuktangan_orange.png`
- `navbar-gallery.blade.php` — Logo `tepuktangan_white.png`
- `navbar-inquiry.blade.php` — Logo `tepuktangan_white.png`
- `navbar-photobooth.blade.php`, `navbar-livestream.blade.php`, `navbar-lumenara.blade.php` — Struktur sama

Setiap navbar memiliki:
- Brand logo (link ke home)
- Toggle button untuk mobile menu
- Menu link: Home, Gallery, Photobooth, LiveStreaming, Lumenara, Inquiry
- `request()->routeIs()` digunakan untuk menandai link aktif (`is-active` class dan `aria-current="page"`)

### 10.6 `social-links.blade.php`

```blade
<nav aria-label="Social media">
    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">YouTube</a>
    <a href="https://www.whatsapp.com/" target="_blank" rel="noopener noreferrer">WhatsApp</a>
</nav>
```

- Link sosial media di footer

---

## 11. Layouts (`resources/views/layouts/`)

### 11.1 `app.blade.php` — Layout Utama

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#263317">
    <title>@yield('title', 'AFL2 Webdev')</title>
    <link rel="preload" href="{{ asset('font/GlacialIndifference-Regular.otf') }}" as="font" type="font/otf" crossorigin>
    <link rel="preload" href="{{ asset('font/GlacialIndifference-Bold.otf') }}" as="font" type="font/otf" crossorigin>
    <link rel="stylesheet" href="{{ asset('mydesign/style.css') }}">
</head>
<body>
    @php
    $route = request()->route()->getName();
    if (in_array($route, ['home', 'root'])) {
        $navbarFile = 'components.navbar-home';
    } elseif (in_array($route, ['gallery', 'gallery.detail'])) {
        $navbarFile = 'components.navbar-gallery';
    } elseif ($route === 'inquiry') {
        $navbarFile = 'components.navbar-inquiry';
    } elseif (in_array($route, ['photobooth', 'photobooth.detail'])) {
        $navbarFile = 'components.navbar-photobooth';
    } elseif (in_array($route, ['livestreaming', 'livestreaming.detail'])) {
        $navbarFile = 'components.navbar-livestream';
    } elseif (in_array($route, ['lumenara', 'lumenara.detail'])) {
        $navbarFile = 'components.navbar-lumenara';
    } else {
        $navbarFile = 'components.navbar-home';
    }
@endphp
@include($navbarFile)

<main>
    @yield('content')
</main>

@include('layouts.footer')
</body>
</html>
```

**Penjelasan:**
- **Baris 8**: `@yield('title', 'AFL2 Webdev')` — Judul halaman, default 'AFL2 Webdev'
- **Baris 9-10**: Preload font kustom (Glacial Indifference Regular dan Bold)
- **Baris 11**: Load CSS utama
- **Baris 13-30**: PHP inline untuk memilih navbar berdasarkan route name saat ini
- **Baris 31**: `@include($navbarFile)` — Include navbar yang dipilih
- **Baris 34**: `@yield('content')` — Isi konten dari masing-masing view
- **Baris 37**: `@include('layouts.footer')` — Include footer

### 11.2 `footer.blade.php`

```blade
<footer class="home-footer">
    <p>© Tepuk Tangan. Founded 2020, Indonesia.</p>
    @include('components.social-links')
</footer>
```

- Footer dengan copyright dan link sosial media

---

## 12. Public Folder (`public/`)

### 12.1 `public/assets/Home/`

Folder berisi gambar-gambar untuk halaman utama:
- Hero slides: `DIA00001.jpg`, `DIA00003.jpg`, `DIA00007.jpg`, `DIA00021.jpg`, `DIA00039.jpg`
- Gallery photos: `DIA00313.jpg`, `DIA00368.jpg`, `DIA00384.jpg`, `DIA00396.jpg`, `DIA00462.jpg`, `DIA00465.jpg`, `DIA00478.jpg`, `DIA00486.jpg`
- Collage frames: `DIA08913.jpg`, `DIA08966.jpg`, `DIA09013.jpg`, `DIA09069.jpg`
- Intro: `DIA00056.jpg`, `DIA00223.jpg`
- Film card: `DIA08889.jpg`

Semua file JPEG mentah dari kamera (ukuran besar, tidak terkompresi) → penyebab loading lambat.

### 12.2 `public/mydesign/style.css`

File CSS utama (3197 baris). Beberapa bagian penting:

**Font-face (baris 1-31):**
- Glacial Indifference (Regular dan Bold, format OTF)
- Bodoni Moda (Variable font, format TTF)

**Variabel CSS (baris 33-36):**
```css
:root {
    --font-sans: 'Glacial Indifference', Arial, sans-serif;
    --font-editorial: 'Bodoni Moda', Georgia, 'Times New Roman', serif;
}
```

**Home page styles (baris 1115-1345):**
- `body:has(.home-page)` — Background putih untuk halaman home
- `.home-hero` — Hero section dengan overflow hidden
- `.home-hero-track` — Flex track untuk slideshow
- `.home-hero-slide` — Setiap slide, flex 0 0 20%, object-fit cover
- `.home-hero-slide img` — Animasi `home-hero-image-in` 1300ms
- `.home-gallery-grid` — Grid untuk kartu galeri
- `.home-gallery-card img` — Transition transform 800ms + filter saturate
- `.home-darkroom` dan `.home-collage` — **Ditambahkan** `content-visibility: auto` dan `contain-intrinsic-size: 1px 1000px` untuk optimasi render

**Responsive:**
- `@media (max-width: 900px)` dan `@media (max-width: 620px)` — Penyesuaian layout untuk mobile

### 12.3 `public/mydesign/app.js`

File JavaScript vanilla (371 baris) dengan 4 modul utama:

**1. Navigasi Mobile (baris 1-24):**
- Toggle menu hamburger untuk `.nav`
- Ketika diklik, ganti `aria-expanded` dan toggle class `is-open` pada menu
- Klik link di menu akan menutup otomatis

**2. Hero Slideshow (baris 26-125):**
- Auto-advance slide setiap 5000ms
- Clone slide pertama dan terakhir untuk infinite loop
- Touch/swipe support (pointer events)
- `prefers-reduced-motion` untuk mengurangi animasi
- `fetchpriority` dihapus pada clone slide

**3. Video Theater (baris 127-275):**
- Modal video untuk memutar film klien
- Tombol play, close, next
- Keyboard navigation (Escape untuk close, Tab untuk focus trap)
- Filter film berdasarkan kategori
- Ambient video dengan toggle sound

**4. Parallax Collage (baris 277-305):**
- Elemen dengan `data-parallax` Bergerak sesuai scroll
- `requestAnimationFrame` untuk performa
- Respect `prefers-reduced-motion`

**5. Inquiry Form WhatsApp (baris 307-371):**
- Saat form disubmit, mencegah default submit
- Kumpulkan semua data form (nama, email, WhatsApp, Instagram, jenis session, tanggal, lokasi, visi)
- Format tanggal ke format Indonesia (`id-ID`)
- Buka URL WhatsApp: `https://wa.me/{nomor}?text={pesan terformat}`
- Fallback `window.location.href` jika `window.open` diblokir

---

## 13. Storage (`storage/`)

Aset klien (foto dan video yang diupload) disimpan di:
- `storage/app/public/` — Lokasi fisik file
- `public/storage/` — Symbolic link ke `storage/app/public/`

Model menggunakan helper `asset('storage/' . $this->file_path)` untuk generate URL publik.

Seeder `ClientAssetSeeder` membaca file dari folder `storage/app/public/gallery/*` dan mencatat path-nya ke tabel `client_assets`.

---

## 14. Konfigurasi (.env)

File `.env` di root proyek:

| Variabel | Nilai | Keterangan |
|----------|-------|------------|
| `APP_NAME` | Laravel | Nama aplikasi |
| `APP_ENV` | local | Environment |
| `APP_KEY` | base64:... | Application key |
| `APP_DEBUG` | true | Debug mode |
| `APP_URL` | http://localhost:9000 | URL aplikasi (Laravel Herd) |
| `DB_CONNECTION` | mysql | Driver database |
| `DB_HOST` | 127.0.0.1 | Host database |
| `DB_PORT` | 3306 | Port database |
| `DB_DATABASE` | tepuktan_laravel | Nama database |
| `DB_USERNAME` | root | Username DB |
| `DB_PASSWORD` | (kosong) | Password DB |
| `FILESYSTEM_DISK` | public | Disk storage default |
| `QUEUE_CONNECTION` | database | Driver queue |
| `CACHE_STORE` | database | Driver cache |
| `SESSION_DRIVER` | database | Driver session |
| `MAIL_MAILER` | log | Driver mail (log only) |

---

## 15. Rangkuman Lengkap Alur Kerja

Berikut adalah alur lengkap dari request hingga response di proyek ini:

### Contoh 1: Membuka Halaman Utama (`/`)

1. Browser request `GET http://localhost:9000/`
2. `routes/web.php` cocokkan dengan `Route::get('/', [HomeController::class, 'index'])->name('root')`
3. `HomeController@index()` dijalankan:
   - Siapkan array `heroSlides`, `galleryPhotos`, `collageFrames`
   - Return view `home` dengan data dan `active = 'home'`
4. `layouts/app.blade.php` render:
   - Deteksi route name = `root` → load `components.navbar-home`
   - `@yield('content')` → render `home.blade.php`
   - Include `layouts.footer`
5. `home.blade.php` loop semua data dan output HTML
6. Browser menerima HTML, memuat CSS dan JS, menampilkan halaman

### Contoh 2: Membuka Detail Klien Gallery (`/gallery/1`)

1. Browser request `GET http://localhost:9000/gallery/1`
2. `routes/web.php` cocokkan dengan `Route::get('/gallery/{client}', [ClientController::class, 'galleryDetail'])->name('gallery.detail')`
3. Laravel Route Model Binding otomatis query `Client::findOrFail(1)`
4. `ClientController@galleryDetail(Client $client)` dijalankan:
   - Panggil `showClient('gallery', $client, 'gallerydetail', 'gallery')`
   - Cek `client_type === 'gallery'`, jika tidak → abort 404
   - `$client->load(['videos', 'photos'])` → eager load aset
   - Return view `gallerydetail` dengan `$client` dan `active = 'gallery'`
5. `gallerydetail.blade.php` include `components.client-detail`
6. Komponen menampilkan judul, tanggal, video, dan foto klien
7. Tombol "Kembali" link ke `route('gallery')`

### Contoh 3: Submit Form Inquiry

1. User isi form di `/inquiry` dan klik "Send Inquiry"
2. JavaScript (`app.js` baris 338-370) tangkap event submit
3. Kumpulkan semua nilai input, format tanggal ke Indonesia
4. Bangun pesan WhatsApp dan buka `https://wa.me/6285748430957?text=...`
5. Chat WhatsApp terbuka dengan pesan inquiry yang sudah diformat

---

## 16. Catatan & Masalah Diketahui

| Item | Detail |
|------|--------|
| `AboutController` hilang | Route `/about` terdaftar tapi file controller tidak ada → error jika dibuka |
| `ClientAssetController` | Semua method masih stub (kosong), CRUD aset belum diimplementasi |
| `GalleryController` | Duplikasi fungsionalitas dengan `ClientController@galleryClients` tanpa query data |
| Gambar belum teroptimasi | File di `assets/Home/` adalah JPEG mentah dari kamera (banyak MB), menyebabkan loading lambat. Sudah ditambahkan `loading="lazy"`, `decoding="async"`, `width`/`height`, dan `content-visibility: auto`, tapi solusi terbaik adalah kompresi ke WebP |
| Tabel lama (`photos`, `videos`) | Masih ada di database tapi sudah digantikan oleh `client_assets` |
| Tidak ada seeder khusus untuk non-gallery | `ClientSeeder` hanya membuat data bertipe `gallery` |
| Navbar terpisah per route | 6 file navbar yang hampir identik, bisa digabung menjadi satu |

---

## 17. Cara Menjalankan Proyek

```bash
# 1. Install dependensi PHP
composer install

# 2. Install dependensi Node.js
npm install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migration
php artisan migrate

# 6. Jalankan seeder (opsional, untuk data dummy)
php artisan db:seed

# 7. Buat symbolic link storage
php artisan storage:link

# 8. Build aset frontend
npm run build

# 9. Jalankan server development
php artisan serve
# atau dengan Laravel Herd
```

---

*File ini dibuat sebagai dokumentasi lengkap proyek AFL2_Webdev untuk referensi pengembangan dan pemeliharaan.*
