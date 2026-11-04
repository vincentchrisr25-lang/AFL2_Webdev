@extends('layouts.app')

@section('content')
    <section class="gallery-hero" aria-labelledby="gallery-title">
        <div class="gallery-hero__inner">
            <div class="gallery-hero__copy">
                <h1 class="gallery-hero__title" id="gallery-title">@yield('page-heading')</h1>
                <p class="gallery-hero__subtitle">@yield('page-subtitle')</p>

                <blockquote class="gallery-hero__quote">
                    <p>@yield('page-description')</p>
                    <cite>@yield('page-caption')</cite>
                </blockquote>
            </div>

        </div>
    </section>

    <section class="gallery-archive" aria-label="Client stories">
        <div class="gallery-card-grid">
            @forelse ($clients as $client)
                <a
                    class="gallery-card"
                    href="{{ route($detailRoute ?? 'gallery.detail', $client) }}"
                    aria-label="View full album for {{ $client->client_name }}"
                >
                    <span class="gallery-card__cover">
                        <img
                            src="{{ $client->cover_url ?: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=85' }}"
                            alt="{{ $client->client_name }} — Tepuk Tangan visual archive"
                            loading="lazy"
                        >
                        <span class="gallery-card__shade" aria-hidden="true"></span>
                    </span>

                    <span class="gallery-card__meta">
                        <span class="gallery-card__title">{{ $client->client_name }} <span class="gallery-card__arrow" aria-hidden="true">→</span></span>
                    </span>
                    <span class="gallery-card__rule" aria-hidden="true"></span>
                </a>
            @empty
                <p class="client-list__empty">{{ $emptyMessage ?? 'No client stories yet.' }}</p>
            @endforelse
        </div>
    </section>
@endsection
