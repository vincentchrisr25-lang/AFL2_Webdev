<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $videos = Video::query()->orderBy('path')->get();
        $posters = Photo::query()->orderBy('path')->limit($videos->count() + 1)->get();

        $films = $videos->values()->map(function (Video $video, int $index) use ($posters): array {
            $title = Str::lower($video->title);
            $category = match (true) {
                Str::contains($title, ['pre-wedding', 'pre wedding', 'elopement']) => 'pre-wedding',
                Str::contains($title, ['portrait', 'intimate']) => 'portraits',
                Str::contains($title, ['teaser', 'reel']) => 'reels',
                default => 'wedding-cinema',
            };

            return [
                'video' => $video,
                'poster' => $posters->get($index + 1) ?? $posters->first(),
                'category' => $category,
            ];
        });

        return view('gallery.videos', [
            'films' => $films,
            'heroPoster' => $posters->first(),
            'featuredFilm' => $films->first(),
            'nextFilm' => $films->get(1),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
