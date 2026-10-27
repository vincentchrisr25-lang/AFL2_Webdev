@extends('layouts.app')

@section('title', 'Photo Gallery | AFL2 Webdev')

@section('content')
    <section aria-labelledby="gallery-title">
        <h1 id="gallery-title">Photo Gallery</h1>

        @include('gallery.partials.navigation')

        @forelse ($photos as $photo)
            <article>
                <img class="gallery-media" src="{{ $photo->url }}" alt="{{ $photo->title }}" loading="lazy">
                <h2>{{ $photo->title }}</h2>
                <p>Ditambahkan {{ $photo->created_at->format('d M Y') }}</p>
            </article>
        @empty
            <p>Belum ada foto di galeri.</p>
        @endforelse
    </section>
@endsection
