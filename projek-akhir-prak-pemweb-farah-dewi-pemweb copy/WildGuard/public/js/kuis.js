/* WildGuard - Kuis Kenali Spesies (jalan di browser, data dari window.WG) */
(function () {
  'use strict';

  var root = document.getElementById('kuis');
  var card = document.getElementById('kuis-card');
  if (!root || !card) return;

  var TOTAL = 5;
  var HURUF = ['A', 'B', 'C', 'D'];

  /* ====== PETA NAMA FIELD (samakan dengan cek.js) ====== */
  var KEYS = {
    nama:   ['nama', 'nama_umum', 'nama_indonesia', 'name'],
    latin:  ['latin', 'nama_latin', 'latin_name', 'scientific_name'],
    status: ['iucn', 'status_iucn', 'status'],
    img:    ['gambar_url', 'gambar', 'foto', 'image', 'img'],
    tip:    ['tip', 'fakta', 'tahukah_kamu', 'tahukah'],
    jenis:  ['jenis', 'kategori', 'tipe']
  };

  var LABEL_IUCN = {
    CR: 'Kritis (CR)',
    EN: 'Genting (EN)',
    VU: 'Rentan (VU)',
    NT: 'Hampir terancam (NT)',
    LC: 'Risiko rendah (LC)'
  };

  /* ---------- helper ---------- */
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
  function acak(arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var t = a[i]; a[i] = a[j]; a[j] = t;
    }
    return a;
  }
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

  /* ---------- data ---------- */
  function olah(o, jenis) {
    var j = jenis || String(pick(o, KEYS.jenis));
    var jp = polos(j);
    return {
      jenis:  jp === 'flora' ? 'Flora' : (jp === 'fauna' ? 'Fauna' : ''),
      nama:   String(pick(o, KEYS.nama)),
      latin:  String(pick(o, KEYS.latin)),
      kode:   iucnKode(String(pick(o, KEYS.status))),
      img:    String(pick(o, KEYS.img)),
      tip:    String(pick(o, KEYS.tip))
    };
  }

  function kumpulkan() {
    var wg = window.WG, daftar = [];
    function tambah(v, jenis) {
      toArray(v).forEach(function (o) {
        if (o && typeof o === 'object') daftar.push(olah(o, jenis));
      });
    }
    if (Array.isArray(wg)) tambah(wg, '');
    else if (wg && typeof wg === 'object') {
      tambah(wg.flora, 'Flora');
      tambah(wg.fauna, 'Fauna');
      if (!daftar.length) ['spesies', 'species', 'items', 'data'].forEach(function (k) { tambah(wg[k], ''); });
    }
    return daftar.filter(function (d) { return d.nama; });
  }

  var DATA = kumpulkan();

  /* ---------- soal buatan admin (tabel kuis_soal, dikirim lewat WG.kuis) ----------
     Kalau admin sudah punya soal aktif, kuis memakai soal itu saja.
     Kalau belum ada, kuis jatuh kembali ke soal acak dari data spesies. */
  function soalAdmin() {
    var wg = window.WG;
    var daftar = wg && !Array.isArray(wg) ? toArray(wg.kuis) : [];
    return daftar.filter(function (k) {
      return k && k.soal && Array.isArray(k.opsi) && k.opsi.length >= 2 &&
             k.benar >= 0 && k.benar < k.opsi.length;
    }).map(function (k) {
      return {
        soal: String(k.soal),
        img: String(k.img || ''),
        alt: 'Gambar untuk soal ini',
        opsi: k.opsi.map(String),
        benar: k.benar | 0,
        d: { tip: '' },
        penjelasan: String(k.penjelasan || '')
      };
    });
  }

  var DB = soalAdmin();

  /* tanpa soal admin, butuh minimal 4 spesies untuk membuat pilihan jawaban */
  if (!DB.length && DATA.length < 4) {
    console.warn('WildGuard (kuis): spesies kurang dari 4 dan belum ada soal admin, kuis disembunyikan. Isi WG:', window.WG);
    root.hidden = true;
    return;
  }

  function jumlahSoal() {
    return Math.min(TOTAL, DB.length || DATA.length);
  }

  /* ---------- pembuat soal ---------- */
  function lainnya(d, ambil, kunci) {
    var terpakai = {};
    terpakai[polos(d[kunci])] = true;
    var hasil = [];
    acak(DATA).forEach(function (x) {
      var v = x[kunci];
      if (!v || terpakai[polos(v)] || hasil.length >= ambil) return;
      terpakai[polos(v)] = true;
      hasil.push(v);
    });
    return hasil.length === ambil ? hasil : null;
  }

  function susun(soal, benar, salah, d, penjelasan) {
    var opsi = acak([benar].concat(salah));
    return { soal: soal, img: soal.img, opsi: opsi, benar: opsi.indexOf(benar), d: d, penjelasan: penjelasan };
  }

  var BUAT = {
    foto: function (d) {
      if (!d.img) return null;
      var salah = lainnya(d, 3, 'nama');
      if (!salah) return null;
      var q = susun('Satwa atau tumbuhan apa ini?', d.nama, salah, d,
        d.nama + (d.latin ? ' (' + d.latin + ')' : '') + '.');
      q.img = d.img;
      return q;
    },

    status: function (d) {
      if (!d.kode) return null;
      var lain = acak(Object.keys(LABEL_IUCN).filter(function (k) { return k !== d.kode; })).slice(0, 3);
      var q = susun('Apa status IUCN ' + d.nama + '?', LABEL_IUCN[d.kode],
        lain.map(function (k) { return LABEL_IUCN[k]; }), d,
        d.nama + ' berstatus ' + LABEL_IUCN[d.kode] + ' di daftar merah IUCN.');
      q.img = d.img;
      return q;
    },

    latin: function (d) {
      if (!d.latin) return null;
      var salah = lainnya(d, 3, 'latin');
      if (!salah) return null;
      var q = susun('Apa nama Latin dari ' + d.nama + '?', d.latin, salah, d,
        'Nama Latin ' + d.nama + ' adalah ' + d.latin + '.');
      q.img = d.img;
      return q;
    },

    jenis: function (d) {
      if (!d.jenis) return null;
      var salah = [d.jenis === 'Flora' ? 'Fauna' : 'Flora'];
      var q = { soal: d.nama + ' termasuk flora atau fauna?', opsi: ['Flora', 'Fauna'],
                benar: d.jenis === 'Flora' ? 0 : 1, d: d,
                penjelasan: d.nama + ' termasuk ' + d.jenis.toLowerCase() + '.' };
      q.img = d.img;
      return q;
    }
  };

  function buatSoal() {
    /* soal admin: urutan soal diacak, urutan pilihan dibiarkan seperti yang ditulis admin */
    if (DB.length) return acak(DB).slice(0, jumlahSoal());

    var tipe = acak(Object.keys(BUAT));
    var jumlah = jumlahSoal();
    var daftar = [];

    acak(DATA).forEach(function (d) {
      if (daftar.length >= jumlah) return;
      var mulai = daftar.length % tipe.length;
      for (var j = 0; j < tipe.length; j++) {
        var q = BUAT[tipe[(mulai + j) % tipe.length]](d);
        if (q) { daftar.push(q); return; }
      }
    });
    return daftar;
  }

  /* ---------- lencana ---------- */
  function lencana(skor, total) {
    var p = skor / total;
    if (p === 1)  return { nama: 'Penjaga Rimba',    teks: 'Sempurna! Kamu benar-benar mengenal mereka.' };
    if (p >= 0.8) return { nama: 'Penjelajah Hutan', teks: 'Hampir sempurna. Pengetahuanmu sudah kuat.' };
    if (p >= 0.6) return { nama: 'Pengamat Satwa',   teks: 'Bagus. Masih ada beberapa yang bisa kamu pelajari lagi.' };
    if (p >= 0.4) return { nama: 'Pemula Rimba',     teks: 'Awal yang baik. Jelajahi daftar flora dan fauna untuk tahu lebih banyak.' };
    return            { nama: 'Bibit Baru',          teks: 'Tidak apa-apa. Setiap penjaga rimba mulai dari nol.' };
  }

  /* ---------- tampilan ---------- */
  var soalList = [], idx = 0, skor = 0, terjawab = false;

  function layarMulai() {
    card.innerHTML =
      '<div class="kuis-mulai">' +
        '<h3>Siap menguji dirimu?</h3>' +
        '<p>' + jumlahSoal() + ' soal acak. Soalnya berbeda setiap kali kamu main.</p>' +
        '<button type="button" class="btn-primary" id="kuis-start">Mulai Kuis</button>' +
      '</div>';
    card.querySelector('#kuis-start').addEventListener('click', mulai);
  }

  function mulai() {
    soalList = buatSoal();
    if (!soalList.length) { card.innerHTML = '<p class="kuis-mulai">Soal belum bisa dibuat dari data yang ada.</p>'; return; }
    idx = 0; skor = 0;
    tampilSoal(true);
  }

  function tampilSoal(fokus) {
    var q = soalList[idx];
    terjawab = false;

    card.innerHTML =
      '<div class="kuis-progress">' +
        '<span>Soal ' + (idx + 1) + ' dari ' + soalList.length + '</span>' +
        '<span class="kuis-bar"><span class="kuis-bar-isi" style="width:' + (idx / soalList.length * 100) + '%"></span></span>' +
        '<span class="kuis-skor">Skor ' + skor + '</span>' +
      '</div>' +
      (q.img ? '<img class="kuis-gambar" src="' + esc(q.img) + '" alt="' + esc(q.alt != null ? q.alt : (q.soal.indexOf('Satwa atau tumbuhan apa') === 0 ? 'Foto untuk ditebak' : '')) + '">' : '') +
      '<h3 class="kuis-soal" tabindex="-1">' + esc(q.soal) + '</h3>' +
      '<div class="kuis-opsi-list" role="group" aria-label="Pilihan jawaban"></div>' +
      '<div id="kuis-umpan"></div>';

    var im = card.querySelector('.kuis-gambar');
    if (im) im.addEventListener('error', function () { im.remove(); });

    var box = card.querySelector('.kuis-opsi-list');
    q.opsi.forEach(function (teks, i) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'kuis-opsi';
      b.setAttribute('data-huruf', HURUF[i]);
      b.textContent = teks;
      b.addEventListener('click', function () { jawab(i); });
      box.appendChild(b);
    });

    /* kembalikan fokus ke soal supaya pengguna keyboard/pembaca layar tidak tersesat */
    if (fokus) card.querySelector('.kuis-soal').focus({ preventScroll: true });
  }

  function jawab(i) {
    if (terjawab) return;
    terjawab = true;

    var q = soalList[idx];
    var benar = i === q.benar;
    if (benar) skor++;

    card.querySelectorAll('.kuis-opsi').forEach(function (b, k) {
      b.disabled = true;
      if (k === q.benar) b.classList.add('is-benar');
      else if (k === i) b.classList.add('is-salah');
      else b.classList.add('is-dim');
    });

    var terakhir = idx === soalList.length - 1;
    var tip = q.d.tip ? '<br><small>' + esc(q.d.tip) + '</small>' : '';

    card.querySelector('#kuis-umpan').innerHTML =
      '<div class="kuis-feedback' + (benar ? '' : ' is-salah') + '" role="status">' +
        '<strong>' + (benar ? 'Benar!' : 'Kurang tepat.') + '</strong>' +
        esc(q.penjelasan) + tip +
      '</div>' +
      '<div class="kuis-aksi">' +
        '<button type="button" class="btn-primary" id="kuis-lanjut">' + (terakhir ? 'Lihat Hasil' : 'Soal Berikutnya') + '</button>' +
      '</div>';

    card.querySelector('.kuis-skor').textContent = 'Skor ' + skor;
    card.querySelector('.kuis-bar-isi').style.width = ((idx + 1) / soalList.length * 100) + '%';

    var lanjut = card.querySelector('#kuis-lanjut');
    lanjut.addEventListener('click', function () {
      if (terakhir) layarHasil();
      else { idx++; tampilSoal(true); }
    });
    lanjut.focus({ preventScroll: true });
  }

  function layarHasil() {
    var total = soalList.length;
    var persen = Math.round(skor / total * 100);
    var l = lencana(skor, total);

    card.innerHTML =
      '<div class="kuis-hasil">' +
        '<div class="kuis-ring" style="--persen:' + persen + '"><span>' + skor + '/' + total + '</span></div>' +
        '<span class="lencana">' + esc(l.nama) + '</span>' +
        '<h3 tabindex="-1">Kuis selesai</h3>' +
        '<p>' + esc(l.teks) + '</p>' +
        '<div class="kuis-hasil-aksi">' +
          '<button type="button" class="btn-primary" id="kuis-ulang">Main Lagi</button>' +
          '<a href="#kategori" class="btn-ghost" data-nav="kategori">Pelajari Spesiesnya</a>' +
        '</div>' +
      '</div>';

    card.querySelector('h3').focus({ preventScroll: true });
    card.querySelector('#kuis-ulang').addEventListener('click', mulai);
  }

  layarMulai();
})();