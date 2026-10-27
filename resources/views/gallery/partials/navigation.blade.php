<nav aria-label="Jenis galeri">
    <a href="{{ route('gallery') }}">Semua</a>
    <a href="{{ route('gallery.photos') }}" @if (request()->routeIs('gallery.photos')) aria-current="page" @endif>Photo</a>
    <a href="{{ route('gallery.videos') }}" @if (request()->routeIs('gallery.videos')) aria-current="page" @endif>Video</a>
</nav>
