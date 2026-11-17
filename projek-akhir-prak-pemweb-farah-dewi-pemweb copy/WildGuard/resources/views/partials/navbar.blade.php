@php
  $site = $site ?? false;                       // true hanya di halaman utama
  $u = auth()->user();
  $h = fn($id) => $site ? '#'.$id : route('home').'#'.$id;
@endphp
<a href="#konten" class="skip-link">Lewati ke konten</a>

<header class="navbar">
  <div class="nav-container">
    <a href="{{ $h('beranda') }}" class="logo" @if($site) data-nav="beranda" @endif aria-label="WildGuard, ke beranda">
      <svg class="logo-mark" viewBox="0 0 32 32" aria-hidden="true">
        <path d="M16 2 4 7v8c0 7.5 5 12.5 12 15 7-2.5 12-7.5 12-15V7L16 2Z" fill="currentColor"/>
        <circle cx="11.5" cy="13" r="2" fill="#fff"/><circle cx="16" cy="10.5" r="2" fill="#fff"/>
        <circle cx="20.5" cy="13" r="2" fill="#fff"/>
        <path d="M16 15c3 0 5.5 2.4 5.5 5 0 1.6-1.4 2.5-2.8 2.2-.9-.2-1.7-.5-2.7-.5s-1.8.3-2.7.5c-1.4.3-2.8-.6-2.8-2.200 0-2.600 2.500-5 5.500-5Z" fill="#fff"/>
      </svg>
      <span>WildGuard</span>
    </a>

    <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Buka menu">
      <span></span><span></span><span></span>
    </button>

    <nav id="nav-menu" aria-label="Navigasi utama">
      <ul class="nav-links">
        <li><a href="{{ $h('beranda') }}" @if($site) data-nav="beranda" @endif>Beranda</a></li>
        <li><a href="{{ $h('kategori') }}" @if($site) data-nav="kategori" @endif>Flora &amp; Fauna</a></li>
        <li><a href="{{ $h('edukasi') }}" @if($site) data-nav="edukasi" @endif>Edukasi</a></li>
        <li><a href="{{ $h('laporan') }}" @if($site) data-nav="laporan" @endif>Laporkan</a></li>
        <li><a href="{{ $h('tentang') }}" @if($site) data-nav="tentang" @endif>Tentang Kami</a></li>

        @guest
          <li><a href="{{ route('login') }}" class="nav-btn nav-masuk">Masuk</a></li>
        @endguest
        @auth
          @if ($u->role === 'admin')
            <li><a href="{{ route('admin.dashboard') }}" class="nav-btn">Kelola</a></li>
          @else
            <li><a href="{{ route('laporan-saya.index') }}" class="nav-btn">Laporan Saya</a></li>
            <li><a href="{{ route('artikel-saya.index') }}" class="nav-btn">Artikel Saya</a></li>
          @endif
          <li>
            <form method="post" action="{{ route('logout') }}" class="nav-logout">
              @csrf
              <button type="submit" class="nav-keluar">Keluar</button>
            </form>
          </li>
        @endauth
      </ul>
    </nav>
  </div>
</header>
