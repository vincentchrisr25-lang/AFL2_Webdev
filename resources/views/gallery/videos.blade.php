@extends('layouts.app')

@section('title', 'Films by Livi Visual | Tepuk Tangan')

@section('content')
    <div class="video-page">
        <section class="video-hero" aria-labelledby="video-hero-title">
            @if ($featuredFilm)
                <video class="video-hero-ambient" data-ambient-src="{{ $featuredFilm['video']->url }}" poster="{{ $heroPoster?->url }}" muted loop playsinline preload="none" aria-hidden="true"></video>
            @endif
            @if ($heroPoster)
                <img class="video-hero-poster" src="{{ $heroPoster->url }}" alt="" aria-hidden="true" fetchpriority="high">
            @endif
            <div class="video-hero-vignette" aria-hidden="true"></div>
            <div class="video-hero-copy">
                <p class="video-eyebrow">Motion by Livi Visual — Est. 2020</p>
                <h1 id="video-hero-title">Stories that breathe, move, and linger in motion.</h1>
                <p class="video-hero-subtitle">Atmosphere, unscripted emotion, and timeless soundscapes—capturing how your golden moments truly felt.</p>
            </div>
            <div class="video-hero-hud">
                <button class="video-sound-toggle" type="button" data-ambient-sound aria-pressed="false" @if (! $featuredFilm) disabled @endif>
                    <span aria-hidden="true">◖</span> Sound Off
                </button>
                <a href="#selected-films">Explore selected films <span aria-hidden="true">↓</span></a>
            </div>
        </section>

        <section class="video-archive" id="selected-films" aria-labelledby="video-archive-title">
            <header class="video-archive-header">
                <div class="video-archive-intro">
                    <p class="video-eyebrow">Livi Visual — Indonesia</p>
                    <h2 id="video-archive-title">Selected Motion Archive</h2>
                    <p>Every film is crafted with a simple, classic, and timeless sensibility—woven from spoken vows, quiet glances, and fleeting golden hours.</p>
                </div>
                <div class="video-filters" role="group" aria-label="Filter films">
                    <button type="button" data-film-filter="all" aria-pressed="true">All films</button>
                    <button type="button" data-film-filter="wedding-cinema" aria-pressed="false">Wedding cinema</button>
                    <button type="button" data-film-filter="pre-wedding" aria-pressed="false">Pre-wedding &amp; elopement</button>
                    <button type="button" data-film-filter="portraits" aria-pressed="false">Intimate portraits</button>
                    <button type="button" data-film-filter="reels" aria-pressed="false">Teasers / Reels</button>
                </div>
            </header>

            @if ($films->isNotEmpty())
                <div class="video-film-grid">
                    @foreach ($films as $film)
                        <button class="video-film-card video-film-card--{{ $loop->iteration === 1 ? 'feature' : ($loop->iteration % 2 === 0 ? 'pair-left' : 'pair-right') }}" type="button" data-film-card data-film-category="{{ $film['category'] }}" data-film-open data-film-index="{{ $loop->index }}" data-film-src="{{ $film['video']->url }}" data-film-title="{{ $film['video']->title }}" data-film-location="Indonesia" @if ($film['poster']) data-film-poster="{{ $film['poster']->url }}" @endif>
                            <span class="video-film-poster">
                                @if ($film['poster'])
                                    <img src="{{ $film['poster']->url }}" alt="" aria-hidden="true" loading="lazy">
                                @endif
                                <span class="video-film-vignette" aria-hidden="true"></span>
                                <span class="video-play-badge">Play film <span aria-hidden="true">⟶</span></span>
                            </span>
                            <span class="video-film-meta">
                                <span>
                                    <span class="video-film-title">{{ $film['video']->title }}</span>
                                    <span class="video-film-detail">Indonesia · {{ str($film['category'])->replace('-', ' ')->upper() }}</span>
                                </span>
                                <span class="video-film-duration">Livi Visual</span>
                            </span>
                        </button>
                    @endforeach
                </div>
                <p class="video-empty-filter" hidden>No films in this collection just yet.</p>
            @else
                <p class="video-empty-state">Our films are being prepared for the archive. Please check back soon.</p>
            @endif
        </section>

        <section class="video-craft" aria-labelledby="video-craft-title">
            <div class="video-craft-inner">
                <p class="video-eyebrow">A considered way of seeing</p>
                <h2 id="video-craft-title">The Craft of Motion</h2>
                <div class="video-craft-grid">
                    <article>
                        <p class="video-craft-number">01 /</p>
                        <h3>Unscripted Audio &amp; Vows</h3>
                        <p>Layering real laughter, shaky breaths, and heartfelt speeches as the narrative spine.</p>
                    </article>
                    <article>
                        <p class="video-craft-number">02 /</p>
                        <h3>Timeless Color Grading</h3>
                        <p>Warm, filmic skin tones and classic monochrome sequences that never feel dated.</p>
                    </article>
                    <article>
                        <p class="video-craft-number">03 /</p>
                        <h3>Seamless Synergy</h3>
                        <p>Photographers and videographers moving as one unified team without intruding on your moment.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="video-commission" aria-labelledby="video-commission-title">
            <p class="video-eyebrow">Commission a film</p>
            <h2 id="video-commission-title">Photographs hold a second still. Film lets you hear their voice all over again.</h2>
            <p>Share your magical ideas or dream celebration with us—let’s craft a film that feels unmistakably yours.</p>
            <a class="video-inquire-link" href="{{ route('inquiry') }}">Inquire film availability <span aria-hidden="true">⟶</span></a>
        </section>

        <footer class="video-footer">
            <p>© Tepuk Tangan × Livi Visual. Founded January 2020, Indonesia.</p>
            <nav aria-label="Social media">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">YouTube</a>
                <a href="https://www.whatsapp.com/" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </nav>
        </footer>

        <div class="video-theater" data-video-theater aria-hidden="true" hidden>
            <div class="video-theater-panel" role="dialog" aria-modal="true" aria-labelledby="video-theater-title" tabindex="-1">
                <header class="video-theater-header">
                    <div>
                        <p class="video-eyebrow" data-theater-location></p>
                        <h2 id="video-theater-title" data-theater-title></h2>
                    </div>
                    <button class="video-theater-close" type="button" data-theater-close>Close <span aria-hidden="true">✕</span></button>
                </header>
                <div class="video-theater-screen">
                    <video data-theater-video controls playsinline preload="none"></video>
                </div>
                <footer class="video-theater-footer">
                    <button type="button" data-theater-next @if (! $nextFilm) hidden @endif>Next film <span aria-hidden="true">⟶</span></button>
                    <a href="{{ route('inquiry') }}">Inquire about this film style <span aria-hidden="true">⟶</span></a>
                </footer>
            </div>
        </div>
    </div>
@endsection
