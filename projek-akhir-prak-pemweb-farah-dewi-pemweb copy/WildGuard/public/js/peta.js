(function () {
  if (!window.L) return;

  var CENTER = [-2.5, 118], ZOOM = 5;

  function baseMap(el) {
    var map = L.map(el, { scrollWheelZoom: false }).setView(CENTER, ZOOM);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    // scroll zoom baru aktif setelah peta diklik, supaya tidak "menyandera" scroll halaman
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });
    return map;
  }

  function pin(selesai) {
    return L.divIcon({
      className: 'wg-pin-wrap',
      html: '<span class="wg-pin' + (selesai ? ' is-selesai' : '') + '"></span>',
      iconSize: [30, 30],
      iconAnchor: [15, 36],
      popupAnchor: [0, -34]
    });
  }

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  /* ========== 1) PICKER DI FORM ========== */
  var pickEl = document.getElementById('peta-pilih');
  if (pickEl) {
    var map = baseMap(pickEl);
    var marker = null;
    var latIn = document.getElementById('lat');
    var lngIn = document.getElementById('lng');
    var info = document.getElementById('peta-koordinat');

    function setPoint(lat, lng, zoom) {
      if (!marker) marker = L.marker([lat, lng], { icon: pin(false) }).addTo(map);
      else marker.setLatLng([lat, lng]);
      latIn.value = lat.toFixed(6);
      lngIn.value = lng.toFixed(6);
      info.textContent = 'Titik dipilih: ' + lat.toFixed(4) + ', ' + lng.toFixed(4);
      info.classList.add('is-set');
      if (zoom) map.setView([lat, lng], zoom);
    }

    function clearPoint() {
      if (marker) { map.removeLayer(marker); marker = null; }
      latIn.value = lngIn.value = '';
      info.textContent = 'Klik peta untuk menandai lokasi.';
      info.classList.remove('is-set');
      map.setView(CENTER, ZOOM);
    }

    map.on('click', function (e) { setPoint(e.latlng.lat, e.latlng.lng); });

    document.getElementById('btn-lokasi').addEventListener('click', function () {
      if (!navigator.geolocation) {
        info.textContent = 'Browser tidak mendukung lokasi. Klik peta saja.';
        return;
      }
      info.textContent = 'Mencari lokasi...';
      navigator.geolocation.getCurrentPosition(
        function (p) { setPoint(p.coords.latitude, p.coords.longitude, 15); },
        function () { info.textContent = 'Izin lokasi ditolak. Klik peta untuk memilih manual.'; },
        { enableHighAccuracy: true, timeout: 10000 }
      );
    });

    var form = document.getElementById('laporan-form');
    if (form) form.addEventListener('reset', clearPoint);

    // ukuran peta bisa salah kalau dihitung sebelum layout selesai
    setTimeout(function () { map.invalidateSize(); }, 300);
  }

  /* ========== 2) PETA PUBLIK ========== */
  var pubEl = document.getElementById('peta-publik-canvas');
  if (pubEl) {
    var pmap = baseMap(pubEl);

    fetch('/peta/laporan', { headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (rows) {
        var selesai = 0, pts = [];
        rows.forEach(function (r) {
          var done = r.status === 'selesai';
          if (done) selesai++;
          pts.push([r.lat, r.lng]);
          L.marker([r.lat, r.lng], { icon: pin(done) }).addTo(pmap).bindPopup(
            '<strong>' + esc(r.jenis) + '</strong><br>' +
            esc(r.tanggal) + '<br>' + (done ? 'Selesai ditangani' : 'Dalam proses')
          );
        });
        document.getElementById('peta-total').textContent = rows.length;
        document.getElementById('peta-selesai').textContent = selesai;
        if (pts.length) pmap.fitBounds(pts, { padding: [40, 40], maxZoom: 9 });
      })
      .catch(function () {});

    setTimeout(function () { pmap.invalidateSize(); }, 300);
  }
  // setelah layout selesai, hitung ulang posisi anchor jika URL sudah punya hash
    window.addEventListener('load', function () {
    if (location.hash) {
        var t = document.querySelector(location.hash);
        if (t) t.scrollIntoView();
    }
    });
    // setelah smooth scroll selesai, koreksi posisi kalau tinggi halaman berubah di tengah jalan
    document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href="#laporan"], [data-nav="laporan"]');
    if (!a) return;
    setTimeout(function () {
        var t = document.getElementById('laporan');
        if (t) t.scrollIntoView({ behavior: 'auto', block: 'start' });
    }, 1000);
    });
})();