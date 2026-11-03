<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::view('/inquiry', 'inquiry')->name('inquiry');

Route::get('/gallery', [ClientController::class, 'galleryClients'])->name('gallery');
Route::get('/gallery/{client}', [ClientController::class, 'galleryDetail'])->name('gallery.detail');

Route::get('/photobooth', [ClientController::class, 'photoboothClients'])->name('photobooth');
Route::get('/photobooth/{client}', [ClientController::class, 'photoboothDetail'])->name('photobooth.detail');

Route::get('/lumenara', [ClientController::class, 'lumenaraClients'])->name('lumenara');
Route::get('/lumenara/{client}', [ClientController::class, 'lumenaraDetail'])->name('lumenara.detail');

Route::get('/livestreaming', [ClientController::class, 'livestreamingClients'])->name('livestreaming');
Route::get('/livestreaming/{client}', [ClientController::class, 'livestreamDetail'])->name('livestreaming.detail');
