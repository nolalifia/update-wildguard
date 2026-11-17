<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="WildGuard adalah platform edukasi untuk mengenal flora dan fauna yang dilindungi serta melawan perdagangan ilegal satwa liar (SDG 15.7).">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>WildGuard - Lindungi Flora & Fauna</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/wildguard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>
<body>

  @include('partials.navbar', ['site' => true])

  @if (session('ok'))
    <div class="toast" role="status">{{ session('ok') }}</div>
  @endif
  @if (session('err'))
    <div class="toast err" role="alert">{{ session('err') }}</div>
  @endif

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

      <div class="hero-image">
        <figure class="hero-figure">
          <img
            src="https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=900&q=80"
            alt="Hutan hujan tropis dengan kanopi lebat">
          <figcaption class="specimen-tag">
            <strong>Hutan hujan tropis</strong>
            <span>Rumah bagi ribuan spesies yang belum semuanya tercatat.</span>
          </figcaption>
        </figure>
        <div class="hero-stamp" aria-hidden="true">
          <svg viewBox="0 0 120 120">
            <defs><path id="stamp-path" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
            <text><textPath href="#stamp-path">Dilindungi undang-undang • Dilindungi undang-undang •</textPath></text>
            <path class="stamp-leaf" d="M60 40c-12 8-14 24-2 34 12-8 14-24 2-34Zm0 0v42" />
          </svg>
        </div>
      </div>
    </section>

    <!-- PITA BERJALAN -->
    <div class="ticker" aria-hidden="true">
      <div class="ticker-track">
        @for ($ulang = 0; $ulang < 2; $ulang++)
          @foreach ($ticker as $sp)
            <span>{{ $sp->nama_latin }}</span>
          @endforeach
        @endfor
      </div>
    </div>

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

    <!-- EDUKASI & ARTIKEL -->
    <section id="edukasi" class="edukasi">
      <header class="section-head">
        <h2>Edukasi & Artikel</h2>
        <p class="section-desc">Bacaan singkat untuk memahami ancaman perdagangan satwa liar dan cara kita melawannya.</p>
      </header>

      <div class="edukasi-layout">

        <div class="artikel-panel">
          @if ($isAdmin)
            <a class="btn-tambah" href="{{ route('artikel.create', ['kembali' => 'artikel']) }}">+ Tambah Artikel</a>
          @elseif (auth()->check())
            <a class="btn-tambah" href="{{ route('artikel-saya.create') }}">+ Usulkan Artikel</a>
          @endif
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
                @if ($isAdmin)
                  <div class="admin-actions">
                    <a class="btn-mini" href="{{ route('artikel.edit', ['artikel' => $a->id_artikel, 'kembali' => 'artikel']) }}">Ubah</a>
                    <form method="post" action="{{ route('artikel.destroy', $a->id_artikel) }}" onsubmit="return confirm('Hapus artikel ini dari database?')">
                      @csrf @method('DELETE')
                      <input type="hidden" name="kembali" value="artikel">
                      <button type="submit" class="btn-mini hapus">Hapus</button>
                    </form>
                  </div>
                @endif
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

    <!-- LAPORAN -->
    <section id="laporan" class="laporan">
      <div class="laporan-intro">
        <h2>Laporkan Dugaan Perdagangan Ilegal Flora & Fauna</h2>
        <p>Laporan Anda dapat membantu menghentikan perdagangan satwa liar ilegal di sekitar Anda.</p>

        <ul class="laporan-notes">
          <li>Laporkan secara bertanggung jawab. Sampaikan hanya yang Anda lihat langsung.</li>
          <li>Identitas pelapor akan dirahasiakan.</li>
          @guest
            <li class="note-login">Punya akun? <a href="{{ route('login') }}">Masuk</a> agar laporan Anda bisa dipantau dan diubah. Tanpa akun pun tetap bisa melapor.</li>
          @else
            <li class="note-login">Anda masuk sebagai {{ auth()->user()->name }}. Laporan ini tercatat di <a href="{{ route('laporan-saya.index') }}">Laporan Saya</a>.</li>
          @endguest
          <li>Jangan mendekati atau menghadapi pelaku sendirian.</li>
        </ul>
      </div>

      <form class="laporan-form" id="laporan-form" method="post" action="{{ route('lapor.kirim') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="field">
          <label for="jenis">Jenis Temuan</label>
          <select id="jenis" name="jenis" required>
            <option value="" {{ old('jenis') ? '' : 'selected' }} disabled>Pilih jenis temuan</option>
            @foreach ($jenisTemuan as $j)
              <option value="{{ $j->id_jenis }}" @selected((int) old('jenis') === (int) $j->id_jenis)>{{ $j->nama_jenis }}</option>
            @endforeach
          </select>
          <p class="field-error" data-error-for="jenis" hidden>Pilih jenis temuan terlebih dahulu.</p>
        </div>

        <div class="field">
          <label for="lokasi">Lokasi Kejadian</label>
          <input type="text" id="lokasi" name="lokasi" placeholder="Masukkan lokasi" value="{{ old('lokasi') }}" required>
          <p class="field-error" data-error-for="lokasi" hidden>Isi lokasi kejadian.</p>
        </div>

        <div class="field">
          <label for="waktu">Waktu Kejadian</label>
          <input type="date" id="waktu" name="waktu" value="{{ old('waktu') }}" required>
          <p class="field-error" data-error-for="waktu" hidden>Pilih tanggal kejadian.</p>
        </div>

        <div class="field field-wide">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan secara detail..." required>{{ old('deskripsi') }}</textarea>
          <p class="field-error" data-error-for="deskripsi" hidden>Jelaskan apa yang Anda lihat, minimal beberapa kalimat.</p>
        </div>

        <div class="field field-wide">
          <label for="lampiran">Lampiran <span class="opsional">(opsional)</span></label>
          <input type="file" id="lampiran" name="lampiran" accept="image/*,.pdf">
          <p class="field-hint">Foto atau PDF pendukung, maksimal 5 MB.</p>
        </div>

        <div class="field field-wide">
          <button type="submit" class="btn-primary btn-block">Kirim Laporan</button>
        </div>

        <div class="form-success" id="form-success" role="status" aria-live="polite" {{ session('laporan_ok') ? '' : 'hidden' }}>
          <strong>Laporan terkirim.</strong>
          <p>Terima kasih sudah peduli. Laporan Anda sudah kami catat.</p>
        </div>

        @if ($errors->any())
          <div class="form-error" role="alert">
            <strong>Laporan belum terkirim.</strong>
            <ul>
              @foreach ($errors->all() as $pesan)
                <li>{{ $pesan }}</li>
              @endforeach
            </ul>
          </div>
        @endif

      </form>
    </section>

    @auth
    <!-- KELOLA LAPORAN (hanya untuk yang login) -->
    <section id="kelola-laporan" class="crud">
      <div class="head">
        <div>
          <h2>{{ $isAdmin ? 'Semua Laporan Masuk' : 'Laporan Saya' }}</h2>
          <p class="muted small">
            @if ($isAdmin)
              Ubah status penanganan laporan langsung dari sini. Nama pelapor tidak ditampilkan.
            @else
              Laporan Anda bisa diubah atau dihapus selama statusnya masih baru.
            @endif
          </p>
        </div>
        @if ($isAdmin)
          <a class="btn-mini" href="{{ route('laporan.index') }}">Buka panel laporan</a>
        @endif
      </div>

      <div class="card tbl">
        <table>
          <thead><tr><th>Tanggal</th><th>Jenis</th><th>Lokasi</th><th>Status</th><th class="r">Aksi</th></tr></thead>
          <tbody>
          @forelse ($daftarLaporan as $r)
            @php $boleh = $isAdmin || $r->status_laporan === 'baru'; @endphp
            <tr>
              <td>{{ $r->tanggal_kejadian->format('d/m/Y') }}</td>
              <td>{{ $r->jenis->nama_jenis }}</td>
              <td>{{ $r->lokasi_kejadian }}<br><span class="muted small">{{ \Illuminate\Support\Str::limit($r->deskripsi, 70) }}</span></td>
              <td><span class="badge b-{{ $r->status_laporan }}">{{ $r->status_laporan }}</span></td>
              <td class="r act">
                @if ($boleh)
                  @if ($isAdmin)
                    <a href="{{ route('laporan.edit', ['laporan' => $r->id_laporan, 'kembali' => 'laporan']) }}">Ubah status</a>
                  @else
                    <a href="{{ route('laporan-saya.edit', ['laporan' => $r->id_laporan, 'kembali' => 'laporan']) }}">Ubah</a>
                    <form method="post" action="{{ route('laporan-saya.destroy', $r->id_laporan) }}"
                          onsubmit="return confirm('Hapus laporan ini?')">
                      @csrf @method('DELETE')
                      <input type="hidden" name="kembali" value="laporan">
                      <button class="link danger">Hapus</button>
                    </form>
                  @endif
                @else
                  <span class="muted small">Sudah ditangani</span>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="kosong">🍃 Belum ada laporan.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </section>
    @endauth

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

  </main>

  <!-- ================= DAFTAR FLORA ================= -->
  <main id="flora-view" class="list-view" hidden>
    <div class="list-view-header">
      <button type="button" class="back-btn" data-nav="kategori">&larr; Kembali</button>
      <div>
        <h2>Flora yang Dilindungi</h2>
        <p class="list-view-desc">Daftar tumbuhan berstatus dilindungi beserta informasi selengkapnya.</p>
        @if ($isAdmin)
          <a class="btn-tambah" href="{{ route('spesies.create', ['tipe' => 'flora', 'kembali' => 'flora']) }}">+ Tambah Flora</a>
        @endif
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
        @if ($isAdmin)
          <a class="btn-tambah" href="{{ route('spesies.create', ['tipe' => 'fauna', 'kembali' => 'fauna']) }}">+ Tambah Fauna</a>
        @endif
      </div>
    </div>
    <div class="item-grid" id="fauna-grid"></div>
  </main>

  <!-- ================= FOOTER ================= -->
  @include('partials.footer', ['site' => true])

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
  <script src="{{ asset('js/wildguard.js') }}"></script>
</body>
</html>