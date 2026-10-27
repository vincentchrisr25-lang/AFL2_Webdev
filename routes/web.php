<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/inquiry', function () {
    return view('inquiry');
})->name('inquiry');

Route::get('/livestreaming', function () {
    return view('livestreaming');
})->name('livestreaming');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/photos', [PhotoController::class, 'index'])->name('gallery.photos');
Route::get('/gallery/videos', [VideoController::class, 'index'])->name('gallery.videos');

Route::get('/photoboot', function () {
    return view('photoboot');
})->name('photobooth');
