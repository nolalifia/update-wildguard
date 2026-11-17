<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="WildGuard adalah platform edukasi untuk mengenal flora dan fauna yang dilindungi serta melawan perdagangan ilegal satwa liar (SDG 15.7).">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>WildGuard - Lindungi Flora & Fauna</title>

  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
  <link rel="stylesheet" href="{{ asset('css/ancaman.css') }}">
</head>
<body>

  <a href="#konten" class="skip-link">Lewati ke konten</a>

    <!-- ================= PENDAMPING SCROLL ================= -->
  <div class="pendamping" id="pendamping" aria-hidden="true">
    <div class="tali"></div>

    <div class="monyet" id="monyet">
      <svg viewBox="0 0 100 150">
        <!-- kaki -->
        <g class="kaki kaki-a"><path class="m-garis" d="M42 112 C38 124 36 132 36 140"/><circle class="m-gelap" cx="36" cy="141" r="5"/></g>
        <g class="kaki kaki-b"><path class="m-garis" d="M58 112 C62 124 64 130 64 138"/><circle class="m-gelap" cx="64" cy="139" r="5"/></g>
        <!-- badan -->
        <ellipse class="m-bulu" cx="50" cy="92" rx="21" ry="28"/>
        <!-- lengan memegang tali -->
        <g class="lengan lengan-a"><path class="m-garis" d="M36 76 C27 60 40 46 50 34"/><circle class="m-gelap" cx="50" cy="34" r="5.5"/></g>
        <g class="lengan lengan-b"><path class="m-garis" d="M64 76 C73 56 60 40 50 20"/><circle class="m-gelap" cx="50" cy="20" r="5.5"/></g>
        <!-- kepala -->
        <ellipse class="m-pipi" cx="34" cy="53" rx="5.5" ry="9"/>
        <ellipse class="m-pipi" cx="66" cy="53" rx="5.5" ry="9"/>
        <circle class="m-bulu" cx="50" cy="48" r="18"/>
        <path class="m-bulu" d="M36 36 C40 22 60 22 64 36 C58 30 42 30 36 36Z"/>
        <circle class="m-wajah" cx="50" cy="53" r="11.5"/>
        <circle class="m-gelap" cx="45" cy="50" r="2.3"/>
        <circle class="m-gelap" cx="55" cy="50" r="2.3"/>
        <path class="m-mulut" d="M45 59 Q50 63 55 59"/>
      </svg>
    </div>

    <div class="kupu" id="kupu">
      <svg viewBox="0 0 40 40">
        <ellipse class="sayap sayap-pink" cx="12" cy="15" rx="11" ry="9"/>
        <ellipse class="sayap sayap-hijau" cx="14" cy="26" rx="8" ry="6"/>
        <ellipse class="sayap r sayap-pink" cx="28" cy="15" rx="11" ry="9"/>
        <ellipse class="sayap r sayap-hijau" cx="26" cy="26" rx="8" ry="6"/>
        <rect class="badan" x="18.4" y="9" width="3.2" height="21" rx="1.6"/>
      </svg>
    </div>
  </div>

  <!-- ================= INTRO PEMBUKA ================= -->
  <div class="intro" id="intro" aria-label="Pembuka WildGuard" hidden>
    <div class="intro-inner">

      <svg class="intro-scene" viewBox="0 0 900 340" role="img" aria-label="Tumbuhan tumbuh dan satwa melintas di hutan">

        <path class="ground" pathLength="1" d="M30 300 H870"/>

        <!-- ===== TUMBUHAN ===== -->

        <!-- A. tanaman berdaun -->
        <g class="plant" style="--d:.3s">
          <path class="stem" pathLength="1" d="M130 300 C130 262 120 232 132 190"/>
          <g transform="translate(130 272) rotate(-62)"><path class="leaf" d="M0 0 C-16 -12 -16 -36 0 -50 C16 -36 16 -12 0 0Z"/></g>
          <g transform="translate(130 272) rotate(62)"><path class="leaf light" d="M0 0 C-16 -12 -16 -36 0 -50 C16 -36 16 -12 0 0Z"/></g>
          <g transform="translate(126 236) rotate(-48)"><path class="leaf light" d="M0 0 C-16 -12 -16 -36 0 -50 C16 -36 16 -12 0 0Z"/></g>
          <g transform="translate(126 236) rotate(52)"><path class="leaf" d="M0 0 C-16 -12 -16 -36 0 -50 C16 -36 16 -12 0 0Z"/></g>
          <g transform="translate(132 192) rotate(-6)"><path class="leaf" d="M0 0 C-20 -16 -20 -46 0 -64 C20 -46 20 -16 0 0Z"/></g>
        </g>

        <!-- B. bunga pink (mirip Rafflesia) -->
        <g class="plant" style="--d:.6s">
          <path class="stem pink" pathLength="1" d="M300 300 C300 282 296 268 300 252"/>
          <g transform="translate(300 252) rotate(-80)"><path class="petal deep" d="M0 0 C-14 -8 -14 -30 0 -40 C14 -30 14 -8 0 0Z"/></g>
          <g transform="translate(300 252) rotate(80)"><path class="petal deep" d="M0 0 C-14 -8 -14 -30 0 -40 C14 -30 14 -8 0 0Z"/></g>
          <g transform="translate(300 252) rotate(-40)"><path class="petal" d="M0 0 C-14 -8 -14 -30 0 -40 C14 -30 14 -8 0 0Z"/></g>
          <g transform="translate(300 252) rotate(40)"><path class="petal" d="M0 0 C-14 -8 -14 -30 0 -40 C14 -30 14 -8 0 0Z"/></g>
          <g transform="translate(300 252) rotate(0)"><path class="petal deep" d="M0 0 C-14 -8 -14 -30 0 -40 C14 -30 14 -8 0 0Z"/></g>
          <g transform="translate(300 254)"><circle class="petal" style="fill:#fff" cx="0" cy="-8" r="7"/></g>
        </g>

        <!-- C. anggrek tinggi -->
        <g class="plant" style="--d:.9s">
          <path class="stem pink" pathLength="1" d="M520 300 C536 250 504 214 526 150"/>
          <g transform="translate(520 290) rotate(-58)"><path class="leaf light" d="M0 0 C-14 -10 -14 -32 0 -44 C14 -32 14 -10 0 0Z"/></g>
          <g transform="translate(522 288) rotate(54)"><path class="leaf" d="M0 0 C-14 -10 -14 -32 0 -44 C14 -32 14 -10 0 0Z"/></g>
          <!-- bunga besar -->
          <g transform="translate(526 150) rotate(-70)"><path class="petal" d="M0 0 C-12 -7 -12 -26 0 -34 C12 -26 12 -7 0 0Z"/></g>
          <g transform="translate(526 150) rotate(-24)"><path class="petal deep" d="M0 0 C-12 -7 -12 -26 0 -34 C12 -26 12 -7 0 0Z"/></g>
          <g transform="translate(526 150) rotate(24)"><path class="petal" d="M0 0 C-12 -7 -12 -26 0 -34 C12 -26 12 -7 0 0Z"/></g>
          <g transform="translate(526 150) rotate(70)"><path class="petal deep" d="M0 0 C-12 -7 -12 -26 0 -34 C12 -26 12 -7 0 0Z"/></g>
          <g transform="translate(526 152)"><circle class="petal" style="fill:#fff" cx="0" cy="-6" r="6"/></g>
          <!-- bunga kecil -->
          <g transform="translate(511 206) rotate(-50)"><path class="petal" d="M0 0 C-8 -5 -8 -18 0 -24 C8 -18 8 -5 0 0Z"/></g>
          <g transform="translate(511 206) rotate(0)"><path class="petal deep" d="M0 0 C-8 -5 -8 -18 0 -24 C8 -18 8 -5 0 0Z"/></g>
          <g transform="translate(511 206) rotate(50)"><path class="petal" d="M0 0 C-8 -5 -8 -18 0 -24 C8 -18 8 -5 0 0Z"/></g>
        </g>

        <!-- D. semak berdaun lebar -->
        <g class="plant" style="--d:1.2s">
          <path class="stem" pathLength="1" d="M700 300 C700 270 706 248 700 218"/>
          <g transform="translate(700 222) rotate(-72)"><path class="leaf" d="M0 0 C-22 -18 -22 -52 0 -72 C22 -52 22 -18 0 0Z"/></g>
          <g transform="translate(700 222) rotate(-36)"><path class="leaf light" d="M0 0 C-22 -18 -22 -52 0 -72 C22 -52 22 -18 0 0Z"/></g>
          <g transform="translate(700 222) rotate(0)"><path class="leaf" d="M0 0 C-22 -18 -22 -52 0 -72 C22 -52 22 -18 0 0Z"/></g>
          <g transform="translate(700 222) rotate(36)"><path class="leaf light" d="M0 0 C-22 -18 -22 -52 0 -72 C22 -52 22 -18 0 0Z"/></g>
          <g transform="translate(700 222) rotate(72)"><path class="leaf" d="M0 0 C-22 -18 -22 -52 0 -72 C22 -52 22 -18 0 0Z"/></g>
        </g>

        <!-- E. rumput kecil -->
        <g class="plant" style="--d:1.4s">
          <path class="stem" pathLength="1" d="M420 300 C420 290 414 282 408 274"/>
          <path class="stem" pathLength="1" d="M424 300 C424 288 426 278 428 266"/>
          <path class="stem" pathLength="1" d="M428 300 C430 292 436 284 444 278"/>
        </g>
        <g class="plant" style="--d:1.5s">
          <path class="stem" pathLength="1" d="M820 300 C820 290 814 282 808 274"/>
          <path class="stem" pathLength="1" d="M824 300 C824 288 826 278 828 266"/>
          <path class="stem" pathLength="1" d="M828 300 C830 292 836 284 844 278"/>
        </g>

        <!-- ===== HEWAN ===== -->

        <!-- Harimau berjalan -->
        <g class="mover" style="--from:-200px;--to:1000px;--dur:5.4s;--d:.7s">
          <g transform="translate(0 300)">
            <g class="bob">
              <!-- ekor -->
              <path d="M8 -50 C-14 -52 -24 -76 -10 -90" fill="none" stroke="#F27CA6" stroke-width="7" stroke-linecap="round"/>
              <!-- kaki belakang -->
              <g>
                <rect x="14" y="-30" width="10" height="30" rx="4" class="fill-pink"/>
                <animateTransform attributeName="transform" type="rotate" values="-16 19 -30;16 19 -30;-16 19 -30" dur=".7s" repeatCount="indefinite"/>
              </g>
              <g>
                <rect x="34" y="-30" width="10" height="30" rx="4" class="fill-pink"/>
                <animateTransform attributeName="transform" type="rotate" values="16 39 -30;-16 39 -30;16 39 -30" dur=".7s" repeatCount="indefinite"/>
              </g>
              <!-- badan -->
              <ellipse cx="55" cy="-46" rx="50" ry="24" class="fill-pink"/>
              <!-- kaki depan -->
              <g>
                <rect x="70" y="-30" width="10" height="30" rx="4" class="fill-pink"/>
                <animateTransform attributeName="transform" type="rotate" values="-16 75 -30;16 75 -30;-16 75 -30" dur=".7s" repeatCount="indefinite"/>
              </g>
              <g>
                <rect x="88" y="-30" width="10" height="30" rx="4" class="fill-pink"/>
                <animateTransform attributeName="transform" type="rotate" values="16 93 -30;-16 93 -30;16 93 -30" dur=".7s" repeatCount="indefinite"/>
              </g>
              <!-- belang -->
              <g fill="none" stroke="#17402F" stroke-width="3" stroke-linecap="round">
                <path d="M30 -66 C34 -58 34 -52 30 -44"/>
                <path d="M48 -69 C52 -60 52 -52 48 -42"/>
                <path d="M66 -69 C70 -60 70 -52 66 -42"/>
                <path d="M84 -66 C87 -58 87 -52 84 -46"/>
              </g>
              <!-- kepala -->
              <circle cx="108" cy="-60" r="18" class="fill-pink"/>
              <path d="M98 -74 L100 -90 L111 -77Z" class="fill-pink"/>
              <path d="M110 -77 L121 -88 L123 -71Z" class="fill-pink"/>
              <path d="M104 -76 L106 -68" stroke="#17402F" stroke-width="3" stroke-linecap="round"/>
              <circle cx="115" cy="-63" r="2.6" fill="#17402F"/>
              <circle cx="125" cy="-56" r="3.2" fill="#17402F"/>
            </g>
          </g>
        </g>

        <!-- Komodo merayap -->
        <g class="mover" style="--from:-280px;--to:1000px;--dur:8s;--d:1.1s">
          <g transform="translate(0 300)">
            <g class="bob slow">
              <path d="M22 -20 C-14 -16 -40 -8 -72 -2 C-40 -14 -6 -30 24 -30Z" class="fill-rimba"/>
              <g>
                <rect x="30" y="-16" width="8" height="16" rx="3" class="fill-rimba"/>
                <animateTransform attributeName="transform" type="rotate" values="-18 34 -16;18 34 -16;-18 34 -16" dur=".9s" repeatCount="indefinite"/>
              </g>
              <g>
                <rect x="48" y="-16" width="8" height="16" rx="3" class="fill-rimba"/>
                <animateTransform attributeName="transform" type="rotate" values="18 52 -16;-18 52 -16;18 52 -16" dur=".9s" repeatCount="indefinite"/>
              </g>
              <ellipse cx="64" cy="-20" rx="44" ry="13" class="fill-rimba"/>
              <g>
                <rect x="78" y="-16" width="8" height="16" rx="3" class="fill-rimba"/>
                <animateTransform attributeName="transform" type="rotate" values="-18 82 -16;18 82 -16;-18 82 -16" dur=".9s" repeatCount="indefinite"/>
              </g>
              <g>
                <rect x="96" y="-16" width="8" height="16" rx="3" class="fill-rimba"/>
                <animateTransform attributeName="transform" type="rotate" values="18 100 -16;-18 100 -16;18 100 -16" dur=".9s" repeatCount="indefinite"/>
              </g>
              <ellipse cx="116" cy="-22" rx="20" ry="9" class="fill-rimba"/>
              <circle cx="122" cy="-26" r="2.2" fill="#17402F"/>
              <path d="M136 -22 L148 -22 M148 -22 L153 -26 M148 -22 L153 -18" stroke="#F27CA6" stroke-width="2" fill="none" stroke-linecap="round"/>
            </g>
          </g>
        </g>

        <!-- Burung besar -->
        <g class="mover" style="--from:-120px;--to:1020px;--dur:4.2s;--d:.5s">
          <g transform="translate(0 78)">
            <g class="bob slow">
              <path d="M-14 0 L-32 -5 L-30 5Z" class="fill-pink"/>
              <path class="wing back fill-rimba" d="M-2 -2 C-8 -26 -2 -44 10 -54 C12 -34 8 -14 2 -2Z"/>
              <ellipse cx="0" cy="0" rx="17" ry="8" class="fill-soft"/>
              <circle cx="16" cy="-3" r="6" class="fill-soft"/>
              <path d="M21 -4 L32 -1 L21 1Z" class="fill-pink"/>
              <circle cx="18" cy="-4" r="1.4" fill="#17402F"/>
              <path class="wing fill-rimba" d="M-2 -2 C-8 -26 -2 -44 10 -54 C12 -34 8 -14 2 -2Z"/>
            </g>
          </g>
        </g>

        <!-- Burung kecil -->
        <g class="mover" style="--from:-120px;--to:1020px;--dur:5.2s;--d:1.8s">
          <g transform="translate(0 132) scale(.75)">
            <g class="bob slow">
              <path d="M-14 0 L-32 -5 L-30 5Z" class="fill-rimba"/>
              <path class="wing back fill-pink" d="M-2 -2 C-8 -26 -2 -44 10 -54 C12 -34 8 -14 2 -2Z"/>
              <ellipse cx="0" cy="0" rx="17" ry="8" class="fill-pink"/>
              <circle cx="16" cy="-3" r="6" class="fill-pink"/>
              <path d="M21 -4 L32 -1 L21 1Z" class="fill-soft"/>
              <circle cx="18" cy="-4" r="1.4" fill="#17402F"/>
              <path class="wing fill-pink" d="M-2 -2 C-8 -26 -2 -44 10 -54 C12 -34 8 -14 2 -2Z"/>
            </g>
          </g>
        </g>

        <!-- Kupu-kupu -->
        <g class="mover" style="--from:-60px;--to:960px;--dur:6s;--d:1.2s">
          <g transform="translate(0 196)">
            <g class="bob">
              <ellipse class="wing-x fill-pink" cx="-9" cy="-3" rx="10" ry="8"/>
              <ellipse class="wing-x fill-pink" cx="-7" cy="6" rx="7" ry="5"/>
              <ellipse class="wing-x r fill-pink" cx="9" cy="-3" rx="10" ry="8"/>
              <ellipse class="wing-x r fill-pink" cx="7" cy="6" rx="7" ry="5"/>
              <rect x="-1.6" y="-8" width="3.2" height="16" rx="1.6" class="fill-soft"/>
            </g>
          </g>
        </g>

        <g class="mover" style="--from:-60px;--to:960px;--dur:7s;--d:2.2s">
          <g transform="translate(0 236) scale(.8)">
            <g class="bob">
              <ellipse class="wing-x fill-rimba" cx="-9" cy="-3" rx="10" ry="8"/>
              <ellipse class="wing-x fill-rimba" cx="-7" cy="6" rx="7" ry="5"/>
              <ellipse class="wing-x r fill-rimba" cx="9" cy="-3" rx="10" ry="8"/>
              <ellipse class="wing-x r fill-rimba" cx="7" cy="6" rx="7" ry="5"/>
              <rect x="-1.6" y="-8" width="3.2" height="16" rx="1.6" class="fill-soft"/>
            </g>
          </g>
        </g>

        <!-- Kunang-kunang -->
        <circle class="spark" cx="210" cy="250" r="3" style="--d:1s"/>
        <circle class="spark" cx="400" cy="230" r="2.5" style="--d:1.6s"/>
        <circle class="spark" cx="620" cy="250" r="3" style="--d:2.1s"/>
        <circle class="spark" cx="770" cy="200" r="2.5" style="--d:1.3s"/>
        <circle class="spark" cx="60" cy="230" r="2.5" style="--d:2.4s"/>
      </svg>

      <h1 class="intro-title">Lindungi mereka, <span>sebelum hilang.</span></h1>
      <p class="intro-sub">Membuka arsip spesies...</p>
      <div class="intro-bar" aria-hidden="true"></div>
    </div>

    <button type="button" class="intro-skip">Lewati</button>
  </div>

  <!-- tampilkan intro secepat mungkin supaya beranda tidak sempat berkedip -->
  <script>
    (function () {
      try {
        var i = document.getElementById("intro");
        var h = location.hash;
        var kurang = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        if (i && sessionStorage.getItem("wg-intro") !== "1" && !kurang && (h === "" || h === "#beranda")) {
          i.hidden = false;
          document.body.classList.add("intro-lock");
        }
      } catch (e) {}
    })();
  </script>

  <!-- ================= NAVBAR ================= -->
  <header class="navbar">
    <div class="nav-container">
      <a href="#beranda" class="logo" data-nav="beranda" aria-label="WildGuard, ke beranda">
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
          <li><a href="#beranda" data-nav="beranda">Beranda</a></li>
          <li><a href="#kategori" data-nav="kategori">Flora & Fauna</a></li>
          <li><a href="#edukasi" data-nav="edukasi">Edukasi</a></li>
          <li><a href="#laporan" data-nav="laporan">Laporkan</a></li>
          <li><a href="#tentang" data-nav="tentang">Tentang Kami</a></li>
          <li><a href="#peta" data-nav="peta">Peta</a></li>
        </ul>

        <div class="nav-auth">
          @guest
            <a href="{{ route('register') }}" class="btn-nav btn-nav-ghost">Daftar</a>
            <a href="{{ route('login') }}" class="btn-nav btn-nav-solid">Masuk</a>
          @else
            <span class="nav-user">Hai, {{ Str::before(auth()->user()->name, ' ') }}</span>

            @if (auth()->user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="btn-nav btn-nav-solid">Dashboard</a>
            @else
              <a href="{{ route('laporan-saya.index') }}" class="btn-nav btn-nav-solid">Laporan Saya</a>
            @endif

            <form method="post" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="btn-nav btn-nav-ghost">Keluar</button>
            </form>
          @endguest
        </div>
      </nav>
    </div>
  </header>

  <!-- ================= HALAMAN UTAMA ================= -->
  <main id="home-view">
    <span id="konten" tabindex="-1"></span>

    <!-- HERO -->
    <section id="beranda" class="hero">
      <div class="hero-text">
        <p class="hero-badge">Konservasi Flora & Fauna Indonesia</p>

        <h1>
          <span class="h1-line">Lindungi Mereka,</span>
          <span class="h1-line">Lindungi Masa Depan.</span>
        </h1>

        <p class="hero-desc">
          Kenali flora dan fauna yang dilindungi, pahami ancaman yang mereka hadapi,
          dan ikut berperan menghentikan perdagangan satwa liar ilegal.
        </p>

        <div class="hero-actions">
          <a href="#kategori" class="btn-primary" data-nav="kategori">Mulai Jelajahi</a>
          <a href="#laporan" class="btn-ghost" data-nav="laporan">Laporkan Temuan</a>
        </div>

        <dl class="hero-stats">
          <div>
            <dt>Flora tercatat</dt>
            <dd data-count="flora">{{ $jFlora }}</dd>
          </div>
          <div>
            <dt>Fauna tercatat</dt>
            <dd data-count="fauna">{{ $jFauna }}</dd>
          </div>
          <div>
            <dt>Target SDG</dt>
            <dd>15.7</dd>
          </div>
        </dl>
      </div>

      <!--
        HERO SLIDER
        Cara pakai: hapus seluruh blok lama  <div class="hero-image"> ... </div>
        (yang isinya <figure class="hero-figure"> dan <div class="hero-stamp">),
        lalu ganti dengan blok di bawah ini.

        Menambah foto: salin satu blok <div class="slide"> ... </div>, tempel di dalam
        <div class="hero-slider">, lalu ganti src, alt, judul, dan keterangannya.
        Bentuk foto (lengkung / daun / lingkaran) dipilih otomatis dari ukuran foto.
        Kalau mau dipaksa, tambahkan data-shape="portrait", "landscape", atau "square"
        pada <div class="slide">.
      -->
      <div class="hero-image" data-hero-slider>
        <figure class="hero-figure is-slider">
          <div class="hero-slider" role="group" aria-roledescription="carousel"
              aria-label="Galeri flora dan fauna" tabindex="0">

            <!-- Slide 1: kubah -->
            <div class="slide is-active" data-shape="landscape" data-variant="1">
              <div class="slide-shape">
                <img
                  src="https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=900&q=80"
                  alt="Hutan hujan tropis dengan kanopi lebat">
                <div class="specimen-tag">
                  <strong>Hutan hujan tropis</strong>
                  <span>Rumah bagi ribuan spesies yang belum semuanya tercatat.</span>
                </div>
              </div>
            </div>

            <!-- Slide 2: blob organik -->
            <div class="slide" data-shape="portrait" data-variant="2">
              <div class="slide-shape">
                <img
                  src="https://images.unsplash.com/photo-1535338454770-8be927b5a00b?auto=format&fit=crop&w=900&q=80"
                  alt="Satwa liar di habitatnya">
                <div class="specimen-tag">
                  <strong>Fauna dilindungi</strong>
                  <span>Setiap spesies punya peran yang tidak bisa digantikan.</span>
                </div>
              </div>
            </div>

            <!-- Slide 3: tetesan -->
            <div class="slide" data-shape="portrait" data-variant="1">
              <div class="slide-shape">
                <img
                  src="https://images.unsplash.com/photo-1497250681960-ef046c08a56e?auto=format&fit=crop&w=900&q=80"
                  alt="Tumbuhan langka">
                <div class="specimen-tag">
                  <strong>Flora langka</strong>
                  <span>Banyak tumbuhan tumbuh lambat dan sulit pulih bila diambil.</span>
                </div>
              </div>
            </div>
          </div>   
        </figure>

        <div class="hero-stamp" aria-hidden="true">
          <svg viewBox="0 0 120 120">
            <defs><path id="stamp-path" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
            <text><textPath href="#stamp-path">Dilindungi undang-undang • Dilindungi undang-undang •</textPath></text>
            <path class="stamp-leaf" d="M60 40c-12 8-14 24-2 34 12-8 14-24 2-34Zm0 0v42" />
          </svg>
        </div>

        <div class="slider-ctrl">
          <button type="button" class="slider-btn" data-slider="prev" aria-label="Foto sebelumnya">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg>
          </button>
          <div class="slider-dots"></div>
          <button type="button" class="slider-btn" data-slider="next" aria-label="Foto berikutnya">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <script>
      (function () {
        var root = document.querySelector('[data-hero-slider]');
        if (!root) return;

        var stage = root.querySelector('.hero-slider');
        var slides = Array.prototype.slice.call(root.querySelectorAll('.slide'));
        var dotsBox = root.querySelector('.slider-dots');
        var ctrl = root.querySelector('.slider-ctrl');
        var INTERVAL = 5000;                       /* jeda ganti otomatis (ms) */
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var now = 0, timer = null, paused = false;

        root.classList.add('is-ready');

        /* ---- bentuk otomatis dari rasio foto ---- */
        function setShape(slide) {
          var pilih = slide.getAttribute('data-shape');
          if (pilih && pilih !== 'auto') return;   /* sudah ditentukan manual */
          var img = slide.querySelector('img');
          function hitung() {
            var r = img.naturalWidth && img.naturalHeight ? img.naturalWidth / img.naturalHeight : 0.8;
            slide.setAttribute('data-shape', r > 1.15 ? 'landscape' : (r < 0.87 ? 'portrait' : 'square'));
          }
          if (img.complete && img.naturalWidth) hitung();
          else {
            img.addEventListener('load', hitung);
            img.addEventListener('error', function () { slide.setAttribute('data-shape', 'portrait'); });
          }
        }
        slides.forEach(setShape);
        slides.forEach(function (s, i) {
        if (!s.hasAttribute('data-variant')) s.setAttribute('data-variant', String(i % 3));
        });

        /* ---- titik penanda ---- */
        var dots = slides.map(function (_, i) {
          var b = document.createElement('button');
          b.type = 'button';
          b.className = 'slider-dot';
          b.setAttribute('aria-label', 'Foto ' + (i + 1));
          b.addEventListener('click', function () { go(i, true); });
          dotsBox.appendChild(b);
          return b;
        });

        function show(i) {
          slides.forEach(function (s, k) {
            var aktif = k === i;
            s.classList.toggle('is-active', aktif);
            s.setAttribute('aria-hidden', aktif ? 'false' : 'true');
            s.setAttribute('role', 'group');
            s.setAttribute('aria-label', 'Foto ' + (k + 1) + ' dari ' + slides.length);
            dots[k].classList.toggle('is-active', aktif);
            if (aktif) dots[k].setAttribute('aria-current', 'true');
            else dots[k].removeAttribute('aria-current');
          });
          now = i;
        }

        function go(i, manual) {
          show((i + slides.length) % slides.length);
          if (manual) restart();
        }

        function play() {
          stop();
          if (reduce || slides.length < 2) return;
          timer = setInterval(function () { if (!paused) go(now + 1); }, INTERVAL);
        }
        function stop() { if (timer) { clearInterval(timer); timer = null; } }
        function restart() { play(); }

        /* ---- tombol, keyboard, hover, swipe ---- */
        root.querySelector('[data-slider="prev"]').addEventListener('click', function () { go(now - 1, true); });
        root.querySelector('[data-slider="next"]').addEventListener('click', function () { go(now + 1, true); });

        stage.addEventListener('keydown', function (e) {
          if (e.key === 'ArrowLeft') { go(now - 1, true); e.preventDefault(); }
          if (e.key === 'ArrowRight') { go(now + 1, true); e.preventDefault(); }
        });

        root.addEventListener('mouseenter', function () { paused = true; });
        root.addEventListener('mouseleave', function () { paused = false; });
        root.addEventListener('focusin', function () { paused = true; });
        root.addEventListener('focusout', function () { paused = false; });
        document.addEventListener('visibilitychange', function () { paused = document.hidden; });

        var x0 = null;
        stage.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
        stage.addEventListener('touchend', function (e) {
          if (x0 === null) return;
          var dx = e.changedTouches[0].clientX - x0;
          if (Math.abs(dx) > 40) go(now + (dx < 0 ? 1 : -1), true);
          x0 = null;
        });

        /* satu foto saja: sembunyikan kontrol */
        if (slides.length < 2) ctrl.hidden = true;

        show(0);
        play();
      })();
      </script>
    </section>

    <!-- PITA BERJALAN -->
    <div class="ticker" aria-hidden="true">
      <div class="ticker-track">
        <span>Rafflesia arnoldii</span><span>Pongo abelii</span><span>Amorphophallus titanum</span>
        <span>Panthera tigris sumatrae</span><span>Coelogyne pandurata</span><span>Rhinoceros sondaicus</span>
        <span>Santalum album</span><span>Varanus komodoensis</span>
        <span>Rafflesia arnoldii</span><span>Pongo abelii</span><span>Amorphophallus titanum</span>
        <span>Panthera tigris sumatrae</span><span>Coelogyne pandurata</span><span>Rhinoceros sondaicus</span>
        <span>Santalum album</span><span>Varanus komodoensis</span>
      </div>
    </div>

    <!-- ============ BARU: KRISIS (donut scrollytelling) ============ -->
    <section id="krisis" class="krisis">
      <header class="section-head krisis-head reveal">
        <span class="folio-label">Fakta lapangan</span>
        <h2>Seberapa genting keadaannya?</h2>
        <p class="section-desc">Scroll pelan-pelan. Diagramnya akan berubah mengikuti ceritanya.</p>
      </header>

      <div class="krisis-layout">

        <div class="donut-stage" id="donut-stage">
          <h3 class="donut-title" id="donut-title">Status fauna Indonesia</h3>
          <p class="donut-caption" id="donut-caption"></p>

          <div class="donut-wrap">
            <svg class="donut" id="donut-svg" viewBox="0 0 200 200" role="img" aria-label="Diagram lingkaran tingkat ancaman flora dan fauna Indonesia"></svg>
            <div class="donut-center">
              <span class="donut-num" id="donut-num">0</span>
              <span class="donut-label" id="donut-label"></span>
            </div>
          </div>

          <ul class="donut-legend" id="donut-legend"></ul>
          <p class="donut-source" id="donut-source"></p>
        </div>

        <div class="krisis-steps">

          <article class="krisis-step" data-set="fauna">
            <div class="step-card">
              <span class="step-no">1</span>
              <h3>Satwa kita sedang di tepi jurang.</h3>
              <span class="step-big">CR &middot; EN &middot; VU</span>
              <p>Orangutan sumatera, harimau sumatera, dan badak jawa semuanya sudah masuk kategori Kritis (CR) di daftar merah IUCN, tingkat risiko tertinggi sebelum punah di alam.</p>
              <p>Di bawahnya ada Genting (EN) dan Rentan (VU). Makin ke atas, makin sedikit waktu yang tersisa.</p>
            </div>
          </article>

          <article class="krisis-step" data-set="flora">
            <div class="step-card">
              <span class="step-no">2</span>
              <h3>Tumbuhan ikut menghilang, tapi lebih sepi dibicarakan.</h3>
              <p>Rafflesia, anggrek hitam, dan cendana tumbuh sangat lambat. Cendana butuh puluhan tahun sebelum kayunya bernilai, sementara ia ditebang jauh lebih cepat dari tumbuhnya.</p>
              <p>Kalau satu jenis hilang, serangga dan satwa yang bergantung padanya ikut terdampak.</p>
            </div>
          </article>

          <article class="krisis-step" data-set="penyebab" data-hl="1">
            <div class="step-card">
              <span class="step-no">3</span>
              <h3>Rumah mereka hilang lebih dulu.</h3>
              <p>Pembukaan lahan untuk perkebunan, tambang, dan permukiman, ditambah kebakaran hutan, memecah hutan jadi potongan kecil. Satwa kehilangan ruang jelajah dan sumber makanan.</p>
            </div>
          </article>

          <article class="krisis-step" data-set="penyebab" data-hl="2">
            <div class="step-card">
              <span class="step-no">4</span>
              <h3>Lalu datang pemburu dan pembeli.</h3>
              <p>Perburuan dan perdagangan ilegal bukan cuma soal pemburu di hutan. Ada pengepul, pengirim, penjual online, dan pembeli yang membuat semuanya terus berjalan.</p>
              <p>Bagian ini adalah satu-satunya yang bisa kita putus bersama. Kita lihat mengapa mereka diburu di bawah ini.</p>
            </div>
          </article>

        </div>
      </div>
    </section>

    <!-- ============ BARU: ANCAMAN (bar) ============ -->
    <section id="ancaman" class="ancaman">
      <div class="ancaman-panel">

        <div class="reveal">
          <span class="folio-label">Sisi permintaan</span>
          <h2>Mengapa mereka diburu?</h2>
          <p class="lead">Perburuan tidak berhenti karena selalu ada yang membeli. Ini alasan permintaan yang paling sering berada di balik perdagangan ilegal.</p>
        </div>

       
        <div>
          <div class="bar-list">

            <div class="bar-row" style="--w:62.6; --d:.05s">
              <div class="bar-name">Satwa hidup
                <small>Burung, reptil, mamalia, dan satwa lainnya</small>
              </div>
              <span class="bar-val" data-count="62.6" data-suffix="%">0%</span>
              <div class="bar-track"><span class="bar-fill"></span></div>
            </div>

            <div class="bar-row" style="--w:20.7; --d:.15s">
              <div class="bar-name">Bagian tubuh satwa
                <small>Gading gajah, bagian tubuh harimau, dan lainnya</small>
              </div>
              <span class="bar-val" data-count="20.7" data-suffix="%">0%</span>
              <div class="bar-track"><span class="bar-fill"></span></div>
            </div>

            <div class="bar-row" style="--w:13.9; --d:.25s">
              <div class="bar-name">Produk satwa
                <small>Aksesori seperti pipa rokok, cincin, gelang, dan kalung</small>
              </div>
              <span class="bar-val" data-count="13.9" data-suffix="%">0%</span>
              <div class="bar-track"><span class="bar-fill"></span></div>
            </div>

            <div class="bar-row" style="--w:2.6; --d:.35s">
              <div class="bar-name">Kerajinan tangan
                <small>Produk kerajinan yang berasal dari satwa liar</small>
              </div>
              <span class="bar-val" data-count="2.6" data-suffix="%">0%</span>
              <div class="bar-track"><span class="bar-fill"></span></div>
            </div>

          </div>

          <p class="ancaman-note">
            Sumber: Padang et al. (2025), Oryx, Volume 59 Issue 2.
            Data perdagangan satwa liar ilegal secara daring di Indonesia,
            April 2021–Maret 2022.
          </p>
        </div>


      </div>
    </section>

    <!-- ============ BARU: RANTAI ============ -->
    <section id="rantai" class="rantai">
      <header class="section-head rantai-head reveal">
        <span class="folio-label">Jalur gelap</span>
        <h2>Dari hutan ke pasar gelap.</h2>
        <p class="section-desc">Satu satwa bisa berpindah tangan berkali-kali sebelum sampai ke pembeli. Setiap langkah adalah kesempatan untuk memutusnya.</p>
      </header>

      <ol class="rantai-list" data-stagger=".14">
        <li class="rantai-step">
          <div class="rantai-node" aria-hidden="true"></div>
          <h3>Diburu di habitat</h3>
          <p>Pemburu menjerat atau menangkap satwa, atau mencabut tumbuhan langka langsung dari hutan.</p>
        </li>
        <li class="rantai-step">
          <div class="rantai-node" aria-hidden="true"></div>
          <h3>Dikumpulkan dan diselundupkan</h3>
          <p>Pengepul menampung lalu mengirimnya lewat jalur darat, laut, atau kargo, sering dalam kondisi tersiksa.</p>
        </li>
        <li class="rantai-step">
          <div class="rantai-node" aria-hidden="true"></div>
          <h3>Dijual diam-diam</h3>
          <p>Dipasarkan di pasar gelap dan makin sering lewat media sosial dan marketplace.</p>
        </li>
        <li class="rantai-step">
          <div class="rantai-node" aria-hidden="true"></div>
          <h3>Berakhir di tangan pembeli</h3>
          <p>Jadi peliharaan, hiasan, obat, atau koleksi. Mata rantai inilah yang bisa kita putus: jangan membeli, dan laporkan.</p>
        </li>
      </ol>
    </section>

    <!-- KATEGORI -->
    <section id="kategori" class="kategori">
      <header class="section-head">
        <h2>Jelajahi Berdasarkan Kategori</h2>
        <p class="section-desc">Temukan informasi lengkap seputar flora, fauna, dan upaya pelestariannya.</p>
      </header>

      <div class="card-grid">

        <a href="#" class="card card-flora" data-view="flora-view">
          <div class="card-media">
            <img
              src="https://images.unsplash.com/photo-1497250681960-ef046c08a56e?auto=format&fit=crop&w=500&q=80"
              alt="Flora dan tumbuhan" loading="lazy">
          </div>
          <div class="card-body">
            <h3>Flora yang Dilindungi</h3>
            <p>Tumbuhan langka yang keberadaannya perlu dijaga.</p>
          </div>
        </a>

        <a href="#" class="card card-fauna" data-view="fauna-view">
          <div class="card-media">
            <img
              src="https://images.unsplash.com/photo-1535338454770-8be927b5a00b?auto=format&fit=crop&w=500&q=80"
              alt="Fauna" loading="lazy">
          </div>
          <div class="card-body">
            <h3>Fauna yang Dilindungi</h3>
            <p>Satwa langka yang terancam punah di alam liar.</p>
          </div>
        </a>

        <a href="#edukasi" class="card card-edukasi" data-nav="edukasi">
          <div class="card-media">
            <img
              src="https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=500&q=80"
              alt="Hutan untuk edukasi" loading="lazy">
          </div>
          <div class="card-body">
            <h3>Edukasi & Artikel</h3>
            <p>Pelajari lebih lanjut tentang konservasi alam.</p>
          </div>
        </a>

        <a href="#laporan" class="card card-laporan" data-nav="laporan">
          <div class="card-media">
            <img
              src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=500&q=80"
              alt="Lingkungan" loading="lazy">
          </div>
          <div class="card-body">
            <h3>Laporkan Temuan</h3>
            <p>Sampaikan dugaan perdagangan satwa liar ilegal.</p>
          </div>
        </a>

      </div>
    </section>
    
    <!-- ============ BARU: CEK STATUS DILINDUNGI ============ -->
    <section id="cek" class="cek" aria-labelledby="cek-judul">
      <div class="cek-layout">

        <div class="cek-intro">
          <span class="folio-label">Cek cepat</span>
          <h2 id="cek-judul">Apakah ini dilindungi?</h2>
          <p>Ketik nama satwa atau tumbuhan, bisa nama umum atau nama Latin. Kami cocokkan dengan database WildGuard.</p>

          <div class="cek-input-wrap">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.200-4.200"/></svg>
            <label class="sr-only" for="cek-input">Nama satwa atau tumbuhan</label>
            <input type="search" id="cek-input" placeholder="Contoh: harimau, rafflesia..." autocomplete="off">
          </div>

          <div class="cek-contoh">
            <span>Coba:</span>
            <span id="cek-contoh-list" style="display:contents"></span>
          </div>
        </div>

        <div class="cek-hasil" id="cek-hasil" aria-live="polite"></div>

      </div>
    </section>

    <!-- ============ BARU: KUIS KENALI SPESIES ============ -->
    <section id="kuis" class="kuis" aria-labelledby="kuis-judul">
      <div class="kuis-layout">

        <div class="kuis-intro">
          <h2 id="kuis-judul">Seberapa kenal kamu dengan mereka?</h2>
          <p>Lima soal acak dari database WildGuard. Tebak nama, status, dan jenisnya, lalu kumpulkan lencana penjaga rimba.</p>
        </div>

        <div class="kuis-card" id="kuis-card"></div>

      </div>
    </section>

    <!-- EDUKASI & ARTIKEL -->
    <section id="edukasi" class="edukasi">
      <header class="section-head">
        <h2>Edukasi & Artikel</h2>
        <p class="section-desc">Bacaan singkat untuk memahami ancaman perdagangan satwa liar dan cara kita melawannya.</p>
      </header>

      <div class="edukasi-layout">

        <div class="artikel-panel">
          <div class="artikel-tools">
            <label class="search-box" for="artikel-search">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.200-4.200"/></svg>
              <span class="sr-only">Cari artikel</span>
              <input type="search" id="artikel-search" placeholder="Cari artikel..." autocomplete="off">
            </label>

            <label class="select-box" for="artikel-topik">
              <span class="sr-only">Filter topik</span>
              <select id="artikel-topik">
                <option value="semua">Semua Topik</option>
                @foreach ($kategori as $k)
                  <option value="{{ \Illuminate\Support\Str::slug($k->nama_kategori) }}">{{ $k->nama_kategori }}</option>
                @endforeach
              </select>
            </label>
          </div>

          <ul class="artikel-list" id="artikel-list">
            @foreach ($artikel as $i => $a)
              <li class="artikel-item {{ $i >= 3 ? 'is-extra' : '' }}" data-topik="{{ \Illuminate\Support\Str::slug($a->kategori->nama_kategori) }}" @if ($i >= 3) hidden @endif>
                <a href="#" class="artikel-link" data-artikel="{{ $a->slug }}">
                  <img class="artikel-thumb" src="{{ $a->gambar_url }}" alt="" loading="lazy">
                  <div class="artikel-info">
                    <h3>{{ $a->judul }}</h3>
                    <div class="artikel-meta">
                      <time datetime="{{ $a->tanggal->format('Y-m-d') }}">{{ $a->tanggal->locale('id')->translatedFormat('j F Y') }}</time>
                      <span class="tag tag-{{ \Illuminate\Support\Str::slug($a->kategori->nama_kategori) }}">{{ $a->kategori->nama_kategori }}</span>
                    </div>
                  </div>
                </a>
              </li>
            @endforeach
          </ul>

          <p class="artikel-empty" id="artikel-empty" hidden>
            Tidak ada artikel yang cocok. Coba kata kunci lain atau pilih Semua Topik.
          </p>

          <button type="button" class="btn-outline" id="artikel-more" aria-expanded="false">Lihat Semua Artikel</button>
        </div>

        <aside class="sdg-panel" aria-label="Mengapa konservasi penting">
          <h3>Mengapa konservasi penting?</h3>
          <p>Keseimbangan ekosistem darat dan laut bergantung pada kelestarian flora dan fauna di dalamnya.</p>

          <div class="sdg-item">
            <img
              src="https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=700&q=80"
              alt="Hutan dan ekosistem daratan" loading="lazy">
            <h4>SDG 15 &ndash; Ekosistem Daratan</h4>
            <p>Menjaga hutan, satwa liar, dan keanekaragaman hayati di daratan agar tetap lestari bagi generasi mendatang.</p>
          </div>

          <div class="sdg-item">
            <img
              src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=700&q=80"
              alt="Ekosistem laut" loading="lazy">
            <h4>SDG 14 &ndash; Ekosistem Lautan</h4>
            <p>Melindungi biota laut dari eksploitasi berlebihan dan pencemaran yang mengancam kelangsungannya.</p>
          </div>
        </aside>

      </div>
    </section>

    <!-- TENTANG KAMI (isi laporan) -->
    <section id="tentang" class="tentang">
      <header class="section-head">
        <h2>Tentang WildGuard</h2>
        <p class="section-desc">
          Platform web edukasi untuk mengenal flora dan fauna yang dilindungi serta meningkatkan kesadaran
          masyarakat terhadap perdagangan ilegal satwa liar sebagai bentuk dukungan terhadap SDG 15.7.
        </p>
      </header>

      <div class="tentang-grid">

        <article class="tentang-block block-tujuan">
          <h3>Tujuan</h3>
          <p>
            WildGuard dibuat untuk mendukung Sustainable Development Goals (SDGs) Tujuan 15:
            Life on Land, khususnya target SDG 15.7.
          </p>
          <p>
            Tujuan WildGuard adalah meningkatkan kesadaran masyarakat mengenai pentingnya perlindungan
            flora dan fauna serta memberikan edukasi mengenai ancaman perdagangan ilegal terhadap
            keanekaragaman hayati.
          </p>
          <div class="sdg-callout">
            <strong>SDG 15.7</strong>
            <p>Mengambil tindakan untuk mengakhiri perburuan dan perdagangan ilegal spesies flora dan fauna yang dilindungi.</p>
          </div>
        </article>

        <article class="tentang-block block-fungsi">
          <h3>Fungsi</h3>
          <p>WildGuard berfungsi sebagai platform edukasi yang membantu masyarakat untuk:</p>
          <ul class="check-list">
            <li>Mengenal flora dan fauna yang dilindungi.</li>
            <li>Mengetahui status perlindungan suatu spesies.</li>
            <li>Memahami ancaman terhadap flora dan fauna.</li>
            <li>Meningkatkan kesadaran mengenai perdagangan ilegal satwa liar.</li>
            <li>Mencatat dugaan temuan perdagangan ilegal flora dan fauna.</li>
          </ul>
        </article>

        <article class="tentang-block block-target">
          <h3>Target Pengguna</h3>
          <p>WildGuard ditujukan untuk:</p>
          <ul class="dot-list">
            <li>Pelajar dan mahasiswa.</li>
            <li>Masyarakat umum.</li>
            <li>Pecinta lingkungan dan satwa.</li>
            <li>Orang yang ingin mempelajari flora dan fauna Indonesia.</li>
          </ul>
        </article>

        <article class="tentang-block block-tim">
          <h3>Anggota Kelompok</h3>
          <div class="table-wrap">
            <table class="tim-table">
              <thead>
                <tr><th scope="col">No</th><th scope="col">Nama</th><th scope="col">NIM</th></tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>Kaila Audriya Setiawan</td><td>140810250007</td></tr>
                <tr><td>2</td><td>Farah Nadia Aqila</td><td>140810250049</td></tr>
                <tr><td>3</td><td>Naura Alifia Lubna</td><td>140810250088</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </div>
    </section>

    <!-- ============ BARU: AJAKAN AKHIR ============ -->
     <section id="peta" class="peta-publik" aria-labelledby="peta-judul">
      <header class="section-head">
        <h2 id="peta-judul">Sebaran laporan</h2>
        <p class="section-desc">Lokasi sengaja dibulatkan dan tidak menunjukkan titik persis pelapor.</p>
      </header>

      <div class="peta-ringkas">
        <span class="peta-chip"><strong id="peta-total">0</strong>laporan berlokasi</span>
        <span class="peta-chip"><strong id="peta-selesai">0</strong>selesai ditangani</span>
      </div>

      <div class="peta-box peta-besar"><div class="peta-canvas" id="peta-publik-canvas"></div></div>

      <div class="peta-legend">
        <span>Dalam proses</span><span class="lg-selesai">Selesai</span>
      </div>
      <p class="peta-catatan">Data peta &copy; kontributor OpenStreetMap.</p>
    </section>

    <section class="cta-akhir reveal" aria-labelledby="cta-judul">
      <h2 id="cta-judul">Kamu sudah tahu. Sekarang bantu menghentikannya.</h2>
      <p>Kalau kamu melihat satwa atau tumbuhan dilindungi diperjualbelikan, satu laporan singkat bisa jadi awal penyelamatannya.</p>
      <a href="#laporan" class="btn-primary" data-nav="laporan">Laporkan Temuan</a>
    </section>

    <!-- LAPORAN (paling bawah) -->
    <section id="laporan" class="laporan">
      <div class="laporan-intro">
        <h2>Laporkan Dugaan Perdagangan Ilegal Flora & Fauna</h2>
        <p>Laporan Anda dapat membantu menghentikan perdagangan satwa liar ilegal di sekitar Anda.</p>

        <ul class="laporan-notes">
          <li>Laporkan secara bertanggung jawab. Sampaikan hanya yang Anda lihat langsung.</li>
          <li>Identitas pelapor akan dirahasiakan.</li>
          <li>Jangan mendekati atau menghadapi pelaku sendirian.</li>
        </ul>
      </div>

      <form class="laporan-form" id="laporan-form" method="post" action="{{ route('lapor.kirim') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="field">
          <label for="jenis">Jenis Temuan</label>
          <select id="jenis" name="jenis" required>
            <option value="" selected disabled>Pilih jenis temuan</option>
            @foreach ($jenisTemuan as $j)
              <option value="{{ $j->id_jenis }}" @selected((int) old('jenis') === (int) $j->id_jenis)>{{ $j->nama_jenis }}</option>
            @endforeach
          </select>
          <p class="field-error" data-error-for="jenis" hidden>Pilih jenis temuan terlebih dahulu.</p>
        </div>

        <div class="field">
          <label for="lokasi">Lokasi Kejadian</label>
          <input type="text" id="lokasi" name="lokasi" placeholder="Masukkan lokasi" required>
          <p class="field-error" data-error-for="lokasi" hidden>Isi lokasi kejadian.</p>
        </div>

        <div class="field">
          <label for="waktu">Waktu Kejadian</label>
          <input type="date" id="waktu" name="waktu" required>
          <p class="field-error" data-error-for="waktu" hidden>Pilih tanggal kejadian.</p>
        </div>

        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <div class="peta-picker">
          <label for="peta-pilih">Titik Lokasi <span class="opsional">(opsional)</span></label>
          <div class="peta-box">
            <div class="peta-canvas" id="peta-pilih"></div>
          </div>
          <div class="peta-tools">
            <button type="button" class="btn-lokasi" id="btn-lokasi">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/><circle cx="12" cy="12" r="8"/></svg>
              Pakai lokasi saya
            </button>
            <span class="peta-koordinat" id="peta-koordinat">Klik peta untuk menandai lokasi.</span>
          </div>
          <p class="field-hint">Di peta publik, titik akan ditampilkan kurang presisi demi keamanan pelapor.</p>
        </div>

        <div class="field field-wide">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan secara detail..." required></textarea>
          <p class="field-error" data-error-for="deskripsi" hidden>Jelaskan apa yang Anda lihat, minimal beberapa kalimat.</p>
        </div>

        <div class="field field-wide">
          <label for="lampiran">Lampiran <span class="opsional">(opsional)</span></label>
          <input type="file" id="lampiran" name="lampiran" accept="image/*,.pdf">
          <p class="field-hint">Foto atau PDF pendukung, maksimal 5 MB.</p>
        </div>

        @if ($errors->any())
          <div class="field field-wide"><p class="field-error" role="alert">{{ $errors->first() }}</p></div>
        @endif

        <div class="field field-wide">
          <button type="submit" class="btn-primary btn-block">Kirim Laporan</button>
        </div>

        <div class="form-success" id="form-success" role="status" aria-live="polite" @unless (session('laporan_ok')) hidden @endunless>
          <strong>Laporan terkirim.</strong>
          <p>Terima kasih sudah peduli. Laporan Anda sudah kami catat.</p>
        </div>

      </form>
    </section>

  </main>

  <!-- ================= DAFTAR FLORA ================= -->
  <main id="flora-view" class="list-view" hidden>
    <div class="list-view-header">
      <button type="button" class="back-btn" data-nav="kategori">&larr; Kembali</button>
      <div>
        <h2>Flora yang Dilindungi</h2>
        <p class="list-view-desc">Daftar tumbuhan berstatus dilindungi beserta informasi selengkapnya.</p>
      </div>
    </div>
    <div class="item-grid" id="flora-grid"></div>
  </main>

  <!-- ================= DAFTAR FAUNA ================= -->
  <main id="fauna-view" class="list-view" hidden>
    <div class="list-view-header">
      <button type="button" class="back-btn" data-nav="kategori">&larr; Kembali</button>
      <div>
        <h2>Fauna yang Dilindungi</h2>
        <p class="list-view-desc">Daftar satwa berstatus dilindungi beserta informasi selengkapnya.</p>
      </div>
    </div>
    <div class="item-grid" id="fauna-grid"></div>
  </main>

  <!-- ================= FOOTER ================= -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-brand">
        <h3>WildGuard</h3>
        <p>Bersama menjaga kelestarian flora dan fauna, di darat maupun di laut.</p>
      </div>

      <nav class="footer-nav" aria-label="Navigasi footer">
        <a href="#kategori" data-nav="kategori">Flora & Fauna</a>
        <a href="#edukasi" data-nav="edukasi">Edukasi</a>
        <a href="#laporan" data-nav="laporan">Laporkan</a>
        <a href="#tentang" data-nav="tentang">Tentang Kami</a>
      </nav>
    </div>
    <p class="footer-copy">&copy; 2026 WildGuard. Untuk kelestarian alam Indonesia.</p>
  </footer>

  <!-- ================= MODAL DETAIL SPESIES ================= -->
  <div class="modal-overlay" id="detail-modal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-nama">
      <button type="button" class="modal-close" id="modal-close" aria-label="Tutup">&times;</button>

      <img id="modal-img" class="modal-img" src="" alt="">

      <div class="modal-body">
        <span class="hero-badge" id="modal-badge"></span>

        <h3 id="modal-nama"></h3>
        <p class="modal-latin" id="modal-latin"></p>

        <div class="info-grid" id="modal-info"></div>

        <h4>Deskripsi</h4>
        <p id="modal-desc"></p>

        <div class="tip-box">
          <strong>Tahukah Kamu?</strong>
          <p id="modal-tip"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= MODAL ARTIKEL ================= -->
  <div class="modal-overlay" id="artikel-modal" hidden>
    <div class="modal-box modal-artikel" role="dialog" aria-modal="true" aria-labelledby="artikel-modal-judul">
      <button type="button" class="modal-close" id="artikel-modal-close" aria-label="Tutup">&times;</button>
      <img id="artikel-modal-img" class="modal-img" src="" alt="">
      <div class="modal-body">
        <div class="artikel-meta">
          <time id="artikel-modal-tanggal"></time>
          <span class="tag" id="artikel-modal-tag"></span>
        </div>
        <h3 id="artikel-modal-judul"></h3>
        <div id="artikel-modal-isi"></div>
      </div>
    </div>
  </div>

  <script>window.WG = @json($wg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);</script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="{{ asset('js/pages.js') }}"></script>
  <script src="{{ asset('js/ancaman.js') }}"></script>
  <script src="{{ asset('js/cek.js') }}"></script>
  <script src="{{ asset('js/kuis.js') }}"></script>
  <script src="{{ asset('js/peta.js') }}"></script>
</body>
</html>