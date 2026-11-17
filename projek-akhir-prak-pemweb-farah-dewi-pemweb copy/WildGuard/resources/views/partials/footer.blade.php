@php
  $site = $site ?? false;
  $h = fn($id) => $site ? '#'.$id : route('home').'#'.$id;
@endphp
<footer class="footer">
  <div class="footer-content">
    <div class="footer-brand">
      <h3>WildGuard</h3>
      <p>Bersama menjaga kelestarian flora dan fauna, di darat maupun di laut.</p>
    </div>

    <nav class="footer-nav" aria-label="Navigasi footer">
      <a href="{{ $h('kategori') }}" @if($site) data-nav="kategori" @endif>Flora &amp; Fauna</a>
      <a href="{{ $h('edukasi') }}" @if($site) data-nav="edukasi" @endif>Edukasi</a>
      <a href="{{ $h('laporan') }}" @if($site) data-nav="laporan" @endif>Laporkan</a>
      <a href="{{ $h('tentang') }}" @if($site) data-nav="tentang" @endif>Tentang Kami</a>
    </nav>
  </div>
  <p class="footer-copy">&copy; 2026 WildGuard. Untuk kelestarian alam Indonesia.</p>
</footer>
