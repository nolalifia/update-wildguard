<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'WildGuard') - WildGuard</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/wildguard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>
<body class="halaman-dalam">
  <span id="konten"></span>
  @include('partials.navbar')

  @if (auth()->user()?->role === 'admin' && request()->is('admin*'))
    <nav class="subnav" aria-label="Menu kelola">
      <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <symbol id="sn-home" viewBox="0 0 24 24"><path d="M3.5 11 12 4l8.5 7"/><path d="M5.5 9.5V20h13V9.5"/><path d="M10 20v-5h4v5"/></symbol>
        <symbol id="sn-paw" viewBox="0 0 24 24"><path d="M8.5 16.5c0-2.2 1.6-3.8 3.5-3.8s3.5 1.6 3.5 3.8c0 1.6-1.4 2.4-3.5 2.4s-3.5-.8-3.5-2.4Z"/><circle cx="6" cy="11.5" r="1.6"/><circle cx="9.5" cy="7.5" r="1.6"/><circle cx="14.5" cy="7.5" r="1.6"/><circle cx="18" cy="11.5" r="1.6"/></symbol>
        <symbol id="sn-book" viewBox="0 0 24 24"><path d="M3 5.5c3-1 6-.8 9 1v13c-3-1.8-6-2-9-1v-13Z"/><path d="M21 5.5c-3-1-6-.8-9 1v13c3-1.8 6-2 9-1v-13Z"/></symbol>
        <symbol id="sn-mail" viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="3"/><path d="m4 8 8 5.5L20 8"/></symbol>
        <symbol id="sn-quiz" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="M9.7 9.6c.2-1.400 1.200-2.100 2.400-2.100 1.400 0 2.300.9 2.300 2 0 1.800-2.400 1.900-2.400 3.700"/><circle cx="12" cy="16.6" r=".6"/></symbol>
        <symbol id="sn-db" viewBox="0 0 24 24"><ellipse cx="12" cy="6" rx="7.5" ry="3"/><path d="M4.5 6v6c0 1.7 3.4 3 7.5 3s7.500-1.300 7.500-3V6"/><path d="M4.5 12v6c0 1.700 3.400 3 7.500 3s7.500-1.300 7.500-3v-6"/></symbol>
      </svg>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><svg class="sn-ico" aria-hidden="true"><use href="#sn-home"/></svg>Dashboard</a>
      <a href="{{ route('spesies.index') }}" class="{{ request()->routeIs('spesies.*') ? 'on' : '' }}"><svg class="sn-ico" aria-hidden="true"><use href="#sn-paw"/></svg>Spesies</a>
      <a href="{{ route('artikel.index') }}" class="{{ request()->routeIs('artikel.*') ? 'on' : '' }}"><svg class="sn-ico" aria-hidden="true"><use href="#sn-book"/></svg>Artikel</a>
      <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'on' : '' }}"><svg class="sn-ico" aria-hidden="true"><use href="#sn-mail"/></svg>Laporan</a>
      <a href="{{ route('kuis-soal.index') }}" class="{{ request()->routeIs('kuis-soal.*') ? 'on' : '' }}"><svg class="sn-ico" aria-hidden="true"><use href="#sn-quiz"/></svg>Kuis</a>
      <details class="dd {{ request()->routeIs('master.*') ? 'on' : '' }}">
        <summary><svg class="sn-ico" aria-hidden="true"><use href="#sn-db"/></svg>Data Master <svg class="sn-ico sn-caret" aria-hidden="true" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></summary>
        <div>
          <a href="{{ route('master.index', 'status') }}">Status Perlindungan</a>
          <a href="{{ route('master.index', 'lokasi') }}">Lokasi</a>
          <a href="{{ route('master.index', 'kategori') }}">Kategori Artikel</a>
          <a href="{{ route('master.index', 'jenis') }}">Jenis Temuan</a>
        </div>
      </details>
    </nav>
  @endif

  <main class="crud">
    @if (session('ok')) <div class="alert ok">{{ session('ok') }}</div> @endif
    @if (session('err')) <div class="alert err">{{ session('err') }}</div> @endif
    @yield('content')
  </main>

  @include('partials.footer')
  <script src="{{ asset('js/nav.js') }}"></script>
</body>
</html>