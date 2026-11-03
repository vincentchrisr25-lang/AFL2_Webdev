@php
    $brand = match (true) {
        request()->routeIs('home', 'root') => [
            'logo' => 'tepuktangan_orange.png',
            'class' => '',
            'alt' => 'Tepuk Tangan',
        ],
        request()->routeIs('photobooth', 'photobooth.detail') => [
            'logo' => 'logo_photobooth.png',
            'class' => 'nav__brand-logo--compact',
            'alt' => 'Lifetime',
        ],
        request()->routeIs('livestreaming', 'livestreaming.detail') => [
            'logo' => 'logo_livestream.png',
            'class' => 'nav__brand-logo--livestream',
            'alt' => 'Liveplus',
        ],
        request()->routeIs('lumenara', 'lumenara.detail') => [
            'logo' => 'lumenaara_white.png',
            'class' => '',
            'alt' => 'Lumenara',
        ],
        default => [
            'logo' => 'tepuktangan_white.png',
            'class' => '',
            'alt' => 'Tepuk Tangan',
        ],
    };
@endphp

<nav class="nav" aria-label="Navigasi utama">
    <a class="nav__brand" href="{{ route('home') }}">
        <span @class(['nav__brand-logo', $brand['class']])>
            <img src="{{ asset('assets/Logo/'.$brand['logo']) }}" alt="{{ $brand['alt'] }}" />
        </span>
    </a>
    <button
        class="nav__toggle"
        type="button"
        aria-label="Buka menu navigasi"
        aria-expanded="false"
        aria-controls="primary-navigation"
    >
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="nav__menu" id="primary-navigation">
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('home', 'root')])
            href="{{ route('home') }}"
            @if (request()->routeIs('home', 'root')) aria-current="page" @endif
        >Home</a>
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('gallery', 'gallery.detail')])
            href="{{ route('gallery') }}"
            @if (request()->routeIs('gallery', 'gallery.detail')) aria-current="page" @endif
        >Gallery</a>
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('photobooth', 'photobooth.detail')])
            href="{{ route('photobooth') }}"
            @if (request()->routeIs('photobooth', 'photobooth.detail')) aria-current="page" @endif
        >Photobooth</a>
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('livestreaming', 'livestreaming.detail')])
            href="{{ route('livestreaming') }}"
            @if (request()->routeIs('livestreaming', 'livestreaming.detail')) aria-current="page" @endif
        >LiveStreaming</a>
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('lumenara', 'lumenara.detail')])
            href="{{ route('lumenara') }}"
            @if (request()->routeIs('lumenara', 'lumenara.detail')) aria-current="page" @endif
        >Lumenara</a>
        <a
            @class(['nav__link', 'is-active' => request()->routeIs('inquiry')])
            href="{{ route('inquiry') }}"
            @if (request()->routeIs('inquiry')) aria-current="page" @endif
        >Inquiry</a>
    </div>
</nav>
