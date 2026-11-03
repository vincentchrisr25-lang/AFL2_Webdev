<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
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
