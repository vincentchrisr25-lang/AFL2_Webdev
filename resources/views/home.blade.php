@extends('layouts.app')

@section('title', 'Tepuk Tangan | Home')

@section('content')
    <div class="home-page">
        <section class="home-hero" aria-label="Featured wedding stories">
            <div class="home-hero-track" aria-live="off">
                @foreach ($heroSlides as $index => $file)
                    <article class="home-hero-slide">
                        <img src="{{ asset('assets/Home/'.$file) }}" alt="Wedding photography by Tepuk Tangan" width="1600" height="900" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" fetchpriority="{{ $index === 0 ? 'high' : 'low' }}" decoding="async">
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
                    <p>We are a collective of passionate, creative photographers and videographers collaborating together to realize your once-in-a-lifetime story. Founded in January 2020 in Indonesia, Tepuk Tangan and Livi Visual have continuously challenged our team to capture your golden moments—and it is always a genuine pleasure to preserve every frame.</p>
                </div>
                <div class="home-intro-story home-intro-belief">
                    <p class="home-eyebrow">Our believe</p>
                    <p>Rooted in a simple, classic, and timeless style, we deeply value how our creative minds grow-nurturing our craft, emotions, and passion together. We believe in the quiet magic of capturing every beautiful moment through each artist's unique strengths.</p>
                </div>
            </div>
            <figure class="home-intro-figure">
                <img class="home-intro-portrait" src="{{ asset('assets/Home/DIA00056.jpg') }}" alt="Wedding photography by Tepuk Tangan" width="600" height="900" loading="lazy" fetchpriority="low" decoding="async">
                <img class="home-intro-detail" src="{{ asset('assets/Home/DIA00223.jpg') }}" alt="" aria-hidden="true" width="400" height="300" loading="lazy" fetchpriority="low" decoding="async">
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
                            <img src="{{ asset('assets/Home/'.$file) }}" alt="Wedding photography by Tepuk Tangan" width="800" height="1000" loading="lazy" fetchpriority="low" decoding="async">
                            <span><i>Photography by Tepuk Tangan</i></span>
                        </a>
                    @endforeach

                    <a class="home-gallery-card home-gallery-card--film" href="{{ route('gallery') }}">
                        <img src="{{ asset('assets/Home/DIA08889.jpg') }}" alt="Wedding film by Livi Visual" width="800" height="800" loading="lazy" fetchpriority="low" decoding="async">
                        <span><i>Film by Livi Visual</i></span>
                        <span class="home-gallery-film-mark" aria-hidden="true">Play film ↗</span>
                    </a>
                </div>
                <a class="home-archive-link" href="{{ route('gallery') }}">Explore all stories <span aria-hidden="true">⟶</span></a>
            </div>
        </section>

        <section class="home-collage" aria-labelledby="home-cta-title">
            @foreach ($collageFrames as $frame)
                <div class="home-collage-frame home-collage-frame--{{ $frame['class'] }}" data-parallax="{{ $frame['parallax'] }}">
                    <img src="{{ asset('assets/Home/'.$frame['file']) }}" alt="" aria-hidden="true" width="500" height="500" loading="lazy" fetchpriority="low" decoding="async">
                </div>
            @endforeach
            <div class="home-collage-copy">
                <p class="home-eyebrow">Crafted for once in a lifetime</p>
                <h2 id="home-cta-title"><span>Golden moments pass</span> <span>in a heartbeat.</span> <span>Let’s make yours</span> <span>stay forever.</span></h2>
                <p class="home-collage-invitation">Kindly share your magical ideas or dreams with us—we would love to hear from you and make them happen.</p>
                <a class="home-cta-link" href="{{ route('inquiry') }}">Share your dream with us <span aria-hidden="true">⟶</span></a>
            </div>
        </section>

    </div>
@endsection
