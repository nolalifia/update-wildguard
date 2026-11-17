@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

{{-- sprite ikon SVG --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <symbol id="dv-i-flora" viewBox="0 0 24 24">
    <path d="M12 21V11"/><path d="M12 15c-4 0-6-2-6-5 4 0 6 2 6 5Z"/><path d="M12 13c0-3 2-5 6-5 0 3-2 5-6 5Z"/><circle cx="12" cy="6" r="2.4"/>
  </symbol>
  <symbol id="dv-i-fauna" viewBox="0 0 24 24">
    <path d="M8.5 16.5c0-2.2 1.6-3.8 3.5-3.8s3.5 1.6 3.5 3.8c0 1.6-1.4 2.4-3.5 2.4s-3.5-.8-3.5-2.4Z"/>
    <circle cx="6" cy="11.5" r="1.6"/><circle cx="9.5" cy="7.5" r="1.6"/><circle cx="14.5" cy="7.5" r="1.6"/><circle cx="18" cy="11.5" r="1.6"/>
  </symbol>
  <symbol id="dv-i-artikel" viewBox="0 0 24 24">
    <path d="M3 5.5c3-1 6-.8 9 1v13c-3-1.8-6-2-9-1v-13Z"/><path d="M21 5.5c-3-1-6-.8-9 1v13c3-1.8 6-2 9-1v-13Z"/>
  </symbol>
  <symbol id="dv-i-laporan" viewBox="0 0 24 24">
    <rect x="3" y="5.5" width="18" height="13" rx="3"/><path d="m4 8 8 5.5L20 8"/>
  </symbol>
  <symbol id="dv-i-user" viewBox="0 0 24 24">
    <circle cx="9" cy="8.5" r="3"/><path d="M3.5 19c.5-3.2 2.8-5 5.500-5s5 1.800 5.500 5"/><circle cx="17" cy="9.5" r="2.3"/><path d="M16 14.2c2.6-.2 4.4 1.4 4.8 4.3"/>
  </symbol>
  <symbol id="dv-i-chart" viewBox="0 0 24 24">
    <path d="M4 20V11"/><path d="M10 20V5"/><path d="M16 20v-8"/><path d="M21 20H3"/>
  </symbol>
</svg>

<div class="dv">

  {{-- BANNER --}}
  <div class="dv-halo">
    <div class="dv-mascot" aria-hidden="true">
      <svg class="dv-bird" viewBox="0 0 100 110">
        <path class="ranting" d="M-10 102 H110"/>
        <g class="tubuh">
          <path class="ekor" d="M42 78 C34 92 32 102 38 108 C45 104 50 92 52 80Z"/>
          <path class="ekor dua" d="M50 80 C46 94 46 104 52 108 C58 102 60 92 58 80Z"/>
          <ellipse class="badan" cx="50" cy="62" rx="22" ry="28"/>
          <ellipse class="perut" cx="55" cy="68" rx="13" ry="19"/>
          <path class="sayap" d="M36 50 C22 60 24 82 38 92 C48 80 50 62 42 50Z"/>
          <path class="kaki" d="M46 88 V101 M56 88 V101"/>
          <path class="kepala" d="M50 22 C52 12 60 10 64 14 C58 14 56 18 56 22Z"/>
          <circle class="kepala" cx="54" cy="34" r="17"/>
          <circle class="mata" cx="60" cy="32" r="5.2"/>
          <circle class="pupil" cx="61" cy="32" r="2.5"/>
          <circle class="kelopak" cx="60" cy="32" r="5.4"/>
          <path class="paruh" d="M69 31 C80 31 82 42 73 47 C71 43 69 38 69 31Z"/>
        </g>
      </svg>
    </div>
    <div>
      <h1>Halo, {{ auth()->user()->name }}!</h1>
      <p>Ini ringkasan data WildGuard hari ini. Yuk jaga flora dan fauna kita bareng-bareng.</p>
    </div>
  </div>

  {{-- STATISTIK (tiap kartu adalah link) --}}
  <div class="dv-stats">
    <a href="{{ route('spesies.index', ['tipe' => 'flora']) }}" class="dv-stat dv-flora">
      <div class="dv-stat-ico"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-flora"/></svg></div>
      <strong class="dv-angka" data-to="{{ $jFlora }}">{{ $jFlora }}</strong>
      <span class="dv-label">Flora</span>
    </a>
    <a href="{{ route('spesies.index', ['tipe' => 'fauna']) }}" class="dv-stat dv-fauna">
      <div class="dv-stat-ico"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-fauna"/></svg></div>
      <strong class="dv-angka" data-to="{{ $jFauna }}">{{ $jFauna }}</strong>
      <span class="dv-label">Fauna</span>
    </a>
    <a href="{{ route('artikel.index') }}" class="dv-stat dv-artikel">
      <div class="dv-stat-ico"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-artikel"/></svg></div>
      <strong class="dv-angka" data-to="{{ $jArtikel }}">{{ $jArtikel }}</strong>
      <span class="dv-label">Artikel</span>
    </a>
    <a href="{{ route('laporan.index') }}" class="dv-stat dv-laporan">
      <div class="dv-stat-ico"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-laporan"/></svg></div>
      <strong class="dv-angka" data-to="{{ $jLaporan }}">{{ $jLaporan }}</strong>
      <span class="dv-label">Laporan</span>
    </a>
    <div class="dv-stat dv-user">
      <div class="dv-stat-ico"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-user"/></svg></div>
      <strong class="dv-angka" data-to="{{ $jUser }}">{{ $jUser }}</strong>
      <span class="dv-label">Pengguna</span>
    </div>
  </div>

  {{-- TABEL --}}
  @php $maks = max(1, (int) collect($perJenis)->max('laporan_count')); @endphp

  <div class="dv-cols">
    <section class="dv-card">
      <div class="dv-head">
        <span class="dv-ico-sm"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-chart"/></svg></span>
        <h2>Laporan per Jenis Temuan</h2>
      </div>

      <table class="dv-table">
        <thead><tr><th>Jenis</th><th class="r">Jumlah</th></tr></thead>
        <tbody>
        @foreach ($perJenis as $r)
          <tr>
            <td>{{ $r->nama_jenis }}</td>
            <td class="r"><span class="dv-bar"><i style="--w: {{ round($r->laporan_count / $maks * 100) }}%"></i></span>{{ $r->laporan_count }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>

      <details class="dv-note">
        <summary>Catatan teknis</summary>
        Dihasilkan lewat relasi jenis_temuan → laporan (withCount / GROUP BY).
      </details>
    </section>

    <section class="dv-card">
      <div class="dv-head">
        <span class="dv-ico-sm pink"><svg class="dv-ico" aria-hidden="true"><use href="#dv-i-laporan"/></svg></span>
        <h2>Laporan Terbaru</h2>
      </div>

      @if ($terbaru->isEmpty())
        <div class="dv-kosong">
          <svg viewBox="0 0 64 64" aria-hidden="true">
            <path class="k-batang" d="M32 58V30"/>
            <path class="k-daun-b" d="M32 40C20 40 14 32 14 22c12 0 18 8 18 18Z"/>
            <path class="k-daun" d="M32 34c0-10 6-18 18-18 0 10-6 18-18 18Z"/>
          </svg>
          <p>Belum ada laporan masuk. Santai dulu ya!</p>
        </div>
      @else
        <table class="dv-table">
          <thead><tr><th>Lokasi</th><th>Jenis</th><th>Status</th></tr></thead>
          <tbody>
          @foreach ($terbaru as $r)
            <tr>
              <td>{{ $r->lokasi_kejadian }}</td>
              <td>{{ $r->jenis->nama_jenis }}</td>
              <td><span class="dv-badge dv-b-{{ $r->status_laporan }}">{{ $r->status_laporan }}</span></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      @endif
    </section>
  </div>

</div>

<script>
  (function () {
    var kurang = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    document.querySelectorAll(".dv-angka[data-to]").forEach(function (el) {
      var to = parseInt(el.dataset.to, 10);
      if (kurang || isNaN(to) || to <= 0) return;
      var t0 = null, ms = 1100;
      el.textContent = "0";
      function tick(now) {
        if (t0 === null) t0 = now;
        var p = Math.min(1, (now - t0) / ms);
        el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    });
  })();
</script>
@endsection