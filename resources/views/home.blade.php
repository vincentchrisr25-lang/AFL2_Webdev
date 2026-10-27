@extends('layouts.app')

@section('title', 'Tepuk Tangan & Livi Visual | Home')

@section('content')
    <div class="home-page">
        <section class="home-hero" aria-label="Featured wedding stories">
            <div class="home-hero-track" aria-live="off">
                @foreach ($heroPhotos as $photo)
                    <article class="home-hero-slide">
                        <img src="{{ $photo->url }}" alt="{{ $photo->title }}" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                    </article>
                @endforeach
            </div>
            <div class="home-hero-vignette" aria-hidden="true"></div>
            <div class="home-hero-hud">
                <p class="home-hero-caption" aria-live="polite">
                    <span class="home-hero-caption-text">Tepuk Tangan × Livi Visual — Est. January 2020, Indonesia</span>
                </p>
            </div>
        </section>

        <section class="home-intro" id="our-approach" aria-labelledby="home-intro-title">
            <div class="home-intro-copy">
                <p class="home-eyebrow">Hello — Est. January 2020, Indonesia</p>
                <h1 id="home-intro-title">Where fleeting golden hours become classic, living heirlooms.</h1>
                <div class="home-intro-story">
                    <p class="home-eyebrow">Who we are</p>
                    <p>We are a collective of passionate, creative photographers and videographers collaborating together to realize your once-in-a-lifetime story. Founded in January 2020 in Indonesia, Tepuk Tangan and Livi Visual have continuously challenged our team to capture your golden moments—and it is always a genuine pleasure to preserve every frame.</p>
                </div>
                <div class="home-intro-story home-intro-belief">
                    <p class="home-eyebrow">Our believe</p>
                    <p>Rooted in a simple, classic, and timeless style, we deeply value how our creative minds grow-nurturing our craft, emotions, and passion together. We believe in the quiet magic of capturing every beautiful moment through each artist's unique strengths.</p>
                </div>
                <a class="home-text-link" href="{{ route('about') }}">Meet the collective <span aria-hidden="true">⟶</span></a>
            </div>
            <figure class="home-intro-figure">
                @if ($approachPhoto)
                    <img class="home-intro-portrait" src="{{ $approachPhoto->url }}" alt="{{ $approachPhoto->title }}" loading="lazy">
                    <figcaption>{{ $approachPhoto->title }} <span>— Indonesia</span></figcaption>
                @endif
                @if ($approachDetailPhoto)
                    <img class="home-intro-detail" src="{{ $approachDetailPhoto->url }}" alt="" aria-hidden="true" loading="lazy">
                @endif
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
                    @foreach ($galleryPhotos as $photo)
                        <a class="home-gallery-card home-gallery-card--{{ $loop->iteration }}" href="{{ route('gallery.photos') }}">
                            <img src="{{ $photo->url }}" alt="{{ $photo->title }}" loading="lazy">
                            <span>{{ $photo->title }} <i>— Photography by Tepuk Tangan</i></span>
                        </a>
                    @endforeach
                    @if ($filmVideo && $filmPoster)
                        <a class="home-gallery-card home-gallery-card--film" href="{{ route('gallery.videos') }}">
                            <img src="{{ $filmPoster->url }}" alt="{{ $filmPoster->title }}" loading="lazy">
                            <span>{{ $filmVideo->title }} <i>— Film by Livi Visual</i></span>
                            <span class="home-gallery-film-mark" aria-hidden="true">Play film ↗</span>
                        </a>
                    @endif
                </div>
                <a class="home-archive-link" href="{{ route('gallery') }}">Explore all stories <span aria-hidden="true">⟶</span></a>
            </div>
        </section>

        <section class="home-collage" aria-labelledby="home-cta-title">
            @if ($collagePhotos->get(0))
                <div class="home-collage-frame home-collage-frame--one" data-parallax="0.12">
                    <img src="{{ $collagePhotos->get(0)->url }}" alt="" aria-hidden="true" loading="lazy">
                </div>
            @endif
            @if ($collagePhotos->get(1))
                <div class="home-collage-frame home-collage-frame--two" data-parallax="-0.1">
                    <img src="{{ $collagePhotos->get(1)->url }}" alt="" aria-hidden="true" loading="lazy">
                </div>
            @endif
            @if ($collagePhotos->get(2))
                <div class="home-collage-frame home-collage-frame--three" data-parallax="0.08">
                    <img src="{{ $collagePhotos->get(2)->url }}" alt="" aria-hidden="true" loading="lazy">
                </div>
            @endif
            @if ($collagePhotos->get(3))
                <div class="home-collage-frame home-collage-frame--four" data-parallax="-0.14">
                    <img src="{{ $collagePhotos->get(3)->url }}" alt="" aria-hidden="true" loading="lazy">
                </div>
            @endif
            <div class="home-collage-copy">
                <p class="home-eyebrow">Crafted for once in a lifetime</p>
                <h2 id="home-cta-title"><span>Golden moments pass</span> <span>in a heartbeat.</span> <span>Let’s make yours</span> <span>stay forever.</span></h2>
                <p class="home-collage-invitation">Kindly share your magical ideas or dreams with us—we would love to hear from you and make them happen.</p>
                <a class="home-cta-link" href="{{ route('inquiry') }}">Share your dream with us <span aria-hidden="true">⟶</span></a>
            </div>
        </section>

        <footer class="home-footer">
            <p>© Tepuk Tangan × Livi Visual. Founded 2020, Indonesia.</p>
            <nav aria-label="Social media">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">YouTube</a>
                <a href="https://www.whatsapp.com/" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </nav>
        </footer>
    </div>
@endsection
