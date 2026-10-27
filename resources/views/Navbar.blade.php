<nav class="nav" aria-label="Navigasi utama">
    <a class="nav__brand" href="{{ route('home') }}">Holder</a>
    <button class="nav__toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="primary-navigation">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="nav__menu" id="primary-navigation">
        <a class="nav__link{{ request()->routeIs('home', 'root') ? ' is-active' : '' }}" href="{{ route('home') }}" @if (request()->routeIs('home', 'root')) aria-current="page" @endif>Home</a>
        <a class="nav__link{{ request()->routeIs('about') ? ' is-active' : '' }}" href="{{ route('about') }}" @if (request()->routeIs('about')) aria-current="page" @endif>About</a>
        <div class="nav__item nav__item--dropdown">
            <a class="nav__link{{ request()->routeIs('gallery', 'gallery.*') ? ' is-active' : '' }}" href="{{ route('gallery') }}" @if (request()->routeIs('gallery', 'gallery.*')) aria-current="page" @endif>Gallery</a>
            <div class="nav__dropdown">
                <a class="nav__dropdown-link{{ request()->routeIs('gallery.photos') ? ' is-active' : '' }}" href="{{ route('gallery.photos') }}" @if (request()->routeIs('gallery.photos')) aria-current="page" @endif>Photo</a>
                <a class="nav__dropdown-link{{ request()->routeIs('gallery.videos') ? ' is-active' : '' }}" href="{{ route('gallery.videos') }}" @if (request()->routeIs('gallery.videos')) aria-current="page" @endif>Video</a>
            </div>
        </div>
        <a class="nav__link{{ request()->routeIs('photobooth') ? ' is-active' : '' }}" href="{{ route('photobooth') }}" @if (request()->routeIs('photobooth')) aria-current="page" @endif>Photobooth</a>
        <a class="nav__link{{ request()->routeIs('livestreaming') ? ' is-active' : '' }}" href="{{ route('livestreaming') }}" @if (request()->routeIs('livestreaming')) aria-current="page" @endif>LiveStreaming</a>
        <a class="nav__link{{ request()->routeIs('inquiry') ? ' is-active' : '' }}" href="{{ route('inquiry') }}" @if (request()->routeIs('inquiry')) aria-current="page" @endif>Inquiry</a>
    </div>
</nav>
