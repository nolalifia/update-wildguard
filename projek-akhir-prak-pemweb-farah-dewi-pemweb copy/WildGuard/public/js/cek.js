/* WildGuard - Cek Status Dilindungi (jalan di browser, data dari window.WG) */
(function () {
  'use strict';

  var root = document.getElementById('cek');
  if (!root) return;

  var input = document.getElementById('cek-input');
  var hasil = document.getElementById('cek-hasil');
  var contohBox = document.getElementById('cek-contoh-list');

  /* ====== PETA NAMA FIELD ======
     Kalau nama field di WG kamu beda, tambahkan di daftar yang sesuai. */
  var KEYS = {
    nama:    ['nama', 'nama_umum', 'nama_indonesia', 'name'],
    latin:   ['latin', 'nama_latin', 'latin_name', 'scientific_name'],
    status:  ['iucn', 'status_iucn', 'status'],
    lindung: ['status_perlindungan', 'perlindungan', 'dilindungi'],
    img:     ['gambar_url', 'gambar', 'foto', 'image', 'img'],
    desc:    ['deskripsi', 'desc', 'description', 'keterangan'],
    habitat: ['habitat'],
    asal:    ['asal', 'wilayah', 'daerah', 'persebaran', 'lokasi'],
    tip:     ['tip', 'fakta', 'tahukah_kamu', 'tahukah'],
    jenis:   ['jenis', 'kategori', 'tipe']
  };

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function polos(s) {
    return String(s == null ? '' : s).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
  }

  function pick(o, keys) {
    for (var i = 0; i < keys.length; i++) {
      var v = o[keys[i]];
      if (v != null && v !== '') return v;
    }
    return '';
  }

  function toArray(v) {
    if (Array.isArray(v)) return v;
    if (v && typeof v === 'object') return Object.keys(v).map(function (k) { return v[k]; });
    return [];
  }

  /* ---- ambil semua spesies dari window.WG ---- */
  function olah(o, jenis) {
    var d = {
      jenis:   jenis || String(pick(o, KEYS.jenis)),
      nama:    String(pick(o, KEYS.nama)),
      latin:   String(pick(o, KEYS.latin)),
      status:  String(pick(o, KEYS.status)),
      lindung: String(pick(o, KEYS.lindung)),
      img:     String(pick(o, KEYS.img)),
      desc:    String(pick(o, KEYS.desc)),
      habitat: String(pick(o, KEYS.habitat)),
      asal:    String(pick(o, KEYS.asal)),
      tip:     String(pick(o, KEYS.tip))
    };
    d.kunci = polos(d.nama + ' ' + d.latin);
    return d;
  }

  function kumpulkan() {
    var wg = window.WG, daftar = [];
    function tambah(v, jenis) {
      toArray(v).forEach(function (o) {
        if (o && typeof o === 'object') daftar.push(olah(o, jenis));
      });
    }
    if (Array.isArray(wg)) {
      tambah(wg, '');
    } else if (wg && typeof wg === 'object') {
      tambah(wg.flora, 'Flora');
      tambah(wg.fauna, 'Fauna');
      if (!daftar.length) {
        ['spesies', 'species', 'items', 'data'].forEach(function (k) { tambah(wg[k], ''); });
      }
    }
    return daftar.filter(function (d) { return d.nama; });
  }

  var DATA = kumpulkan();
  if (!DATA.length) console.warn('WildGuard (cek): tidak ada spesies terbaca dari window.WG. Isinya:', window.WG);

  /* ---- kode IUCN dari teks status ---- */
  function iucnKode(t) {
    var s = polos(t);
    if (!s) return '';
    var m = s.match(/\b(cr|en|vu|nt|lc)\b/);
    if (m) return m[1].toUpperCase();
    if (/kritis|critical/.test(s)) return 'CR';
    if (/genting|endangered/.test(s)) return 'EN';
    if (/rentan|vulnerable/.test(s)) return 'VU';
    if (/hampir|near/.test(s)) return 'NT';
    if (/rendah|least/.test(s)) return 'LC';
    return '';
  }

  /* ---- pencarian ---- */
  function cari(q) {
    var k = polos(q);
    if (k.length < 2) return [];
    var kata = k.split(/\s+/);
    return DATA.map(function (d) {
      var n = polos(d.nama), l = polos(d.latin), skor = 0;
      if (n === k || l === k) skor = 100;
      else if (n.indexOf(k) === 0 || l.indexOf(k) === 0) skor = 80;
      else if (d.kunci.indexOf(k) > -1) skor = 60;
      else if (kata.every(function (w) { return d.kunci.indexOf(w) > -1; })) skor = 40;
      return { d: d, skor: skor };
    }).filter(function (x) { return x.skor > 0; })
      .sort(function (a, b) { return b.skor - a.skor; })
      .slice(0, 5);
  }

  /* ---- tampilan ---- */
  function kosong() {
    hasil.innerHTML = '<p class="cek-kosong">' +
      (DATA.length
        ? 'Ketik minimal 2 huruf, atau pilih salah satu contoh di samping.'
        : 'Data spesies belum termuat. Coba muat ulang halaman.') +
      '</p>';
  }

  function tidakAda(q) {
    hasil.innerHTML =
      '<div class="cek-kartu is-tidak"><div class="cek-kartu-body">' +
        '<div class="cek-verdict"><span class="verdict verdict-tidak">Belum ada di database</span></div>' +
        '<h3>&ldquo;' + esc(q) + '&rdquo; belum tercatat</h3>' +
        '<p>Ini <strong>bukan</strong> berarti jenis tersebut aman atau legal. Database WildGuard baru memuat sebagian jenis yang dilindungi.</p>' +
        '<p>Untuk memastikan, cek daftar jenis dilindungi dari KLHK (Permen LHK No. P.106 Tahun 2018) atau tanyakan ke BKSDA setempat. Kalau kamu curiga ada perdagangan, laporkan.</p>' +
        '<a href="#laporan" class="btn-primary" data-nav="laporan">Laporkan Temuan</a>' +
      '</div></div>';
  }

  function infoItem(label, nilai) {
    return nilai ? '<div class="info-item"><strong>' + esc(label) + '</strong><span>' + esc(nilai) + '</span></div>' : '';
  }

  function kartu(d) {
    var kode = iucnKode(d.status);
    var badge = kode ? '<span class="iucn-badge iucn-' + kode.toLowerCase() + '">' + kode + '</span>' : '';
    var ringkas = d.desc.length > 240 ? d.desc.slice(0, 240).replace(/\s+\S*$/, '') + '...' : d.desc;

    var info =
      infoItem('Jenis', d.jenis) +
      infoItem(kode ? 'Status IUCN' : 'Status', d.status) +
      infoItem('Perlindungan', d.lindung) +
      infoItem('Habitat', d.habitat) +
      infoItem('Asal', d.asal);

    hasil.innerHTML =
      '<article class="cek-kartu">' +
        (d.img ? '<img class="cek-kartu-img" src="' + esc(d.img) + '" alt="' + esc(d.nama) + '">' : '') +
        '<div class="cek-kartu-body">' +
          '<div class="cek-verdict"><span class="verdict verdict-ya">Terdaftar sebagai dilindungi</span>' + badge + '</div>' +
          '<h3>' + esc(d.nama) + '</h3>' +
          (d.latin ? '<p class="cek-latin">' + esc(d.latin) + '</p>' : '') +
          (info ? '<div class="info-grid">' + info + '</div>' : '') +
          (ringkas ? '<p>' + esc(ringkas) + '</p>' : '') +
          '<div class="tip-box"><strong>Apa yang bisa kamu lakukan?</strong>' +
            '<p>Jangan membeli, memelihara, atau memperdagangkan jenis ini tanpa izin resmi. ' +
            'Kalau melihatnya diperjualbelikan, satu laporan singkat sudah sangat membantu.</p></div>' +
          '<p style="margin-top:16px;margin-bottom:0"><a href="#laporan" class="btn-primary" data-nav="laporan">Laporkan Temuan</a></p>' +
        '</div>' +
      '</article>';

    var im = hasil.querySelector('.cek-kartu-img');
    if (im) im.addEventListener('error', function () { im.remove(); });
  }

  function opsi(list) {
    hasil.innerHTML = '<div class="cek-daftar" role="list"></div>';
    var box = hasil.firstChild;
    list.forEach(function (x) {
      var d = x.d, b = document.createElement('button');
      b.type = 'button';
      b.className = 'cek-opsi';
      b.setAttribute('role', 'listitem');
      b.innerHTML =
        (d.img ? '<img src="' + esc(d.img) + '" alt="" loading="lazy">' : '') +
        '<span><strong>' + esc(d.nama) + '</strong><small>' + esc(d.latin) + '</small></span>';
      b.addEventListener('click', function () { kartu(d); });
      var im = b.querySelector('img');
      if (im) im.addEventListener('error', function () { im.remove(); });
      box.appendChild(b);
    });
  }

  function proses() {
    var q = input.value.trim();
    if (polos(q).length < 2) { kosong(); return; }
    var list = cari(q);
    if (!list.length) tidakAda(q);
    else if (list.length === 1 || list[0].skor >= 100) kartu(list[0].d);
    else opsi(list);
  }

  /* ---- chip contoh (diambil acak dari data, jadi tidak hardcode) ---- */
  function isiContoh() {
    var acak = DATA.slice().sort(function () { return Math.random() - 0.5; }).slice(0, 4);
    acak.forEach(function (d) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'cek-chip';
      b.textContent = d.nama;
      b.addEventListener('click', function () { input.value = d.nama; proses(); });
      contohBox.appendChild(b);
    });
  }

  var t = null;
  input.addEventListener('input', function () {
    clearTimeout(t);
    t = setTimeout(proses, 150);
  });

  isiContoh();
  kosong();
})();