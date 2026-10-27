<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Video;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $photos = Photo::query()
            ->orderBy('path')
            ->limit(20)
            ->get();

        return view('home', [
            'heroPhotos' => $photos->slice(0, 5)->values(),
            'approachPhoto' => $photos->get(5),
            'approachDetailPhoto' => $photos->get(6),
            'galleryPhotos' => $photos->slice(7, 8)->values(),
            'filmPoster' => $photos->get(15),
            'collagePhotos' => $photos->slice(16, 4)->values(),
            'filmVideo' => Video::query()->orderBy('path')->first(),
        ]);
    }
}
