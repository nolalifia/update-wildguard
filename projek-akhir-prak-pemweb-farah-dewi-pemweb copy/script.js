/* ==========================================================
   WildGuard - script.js
   ========================================================== */

/* ---------------- DATA FLORA ---------------- */
const floraData = [
  {
    id: "rafflesia",
    nama: "Rafflesia Arnoldii",
    latin: "Rafflesia arnoldii",
    status: "Dilindungi",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIyQI258x8TipVWLSCMXqAKoQfMJhoDvegRgsit7zH2w&s=10",
    short: "Bunga terbesar di dunia yang tumbuh liar di hutan hujan Sumatera dan Kalimantan.",
    info: {
      "Status": "Dilindungi",
      "Lokasi": "Sumatera & Kalimantan",
      "Habitat": "Hutan hujan tropis",
      "Ciri Khas": "Tanpa akar, batang, daun"
    },
    desc: "Rafflesia arnoldii adalah tumbuhan parasit yang hidup menempel pada akar tanaman merambat Tetrastigma. Bunganya bisa tumbuh hingga diameter satu meter dan menjadi bunga tunggal terbesar di dunia. Karena tidak punya daun untuk berfotosintesis, ia bergantung sepenuhnya pada inangnya untuk bertahan hidup.",
    tip: "Bunga Rafflesia mengeluarkan bau seperti daging busuk untuk menarik lalat sebagai penyerbuk, makanya sering dijuluki 'bunga bangkai' meski beda spesies dengan Amorphophallus titanum."
  },
  {
    id: "bunga-bangkai",
    nama: "Bunga Bangkai",
    latin: "Amorphophallus titanum",
    status: "Dilindungi",
    img: "https://media.istockphoto.com/id/2173142816/id/foto/bunga-mayat-amorphophallus-titanum.jpg?s=612x612&w=0&k=20&c=AiLy1f3ZiXZtZhAXhJCArUFseWZvey750RGGxYDiRDU=",
    short: "Tumbuhan endemik Sumatera dengan bunga majemuk tertinggi di dunia.",
    info: {
      "Status": "Dilindungi",
      "Lokasi": "Sumatera",
      "Habitat": "Hutan dataran rendah",
      "Ciri Khas": "Tinggi bisa capai 3-4 m"
    },
    desc: "Berbeda dari Rafflesia, Amorphophallus titanum punya umbi dan daun, hanya saja bunganya (yang sebenarnya adalah rangkaian bunga majemuk atau perbungaan) mekar sangat jarang, kadang hanya sekali dalam beberapa tahun. Perbungaannya adalah yang tertinggi di dunia.",
    tip: "Saat mekar, bunga bangkai bisa mengeluarkan panas dan aroma busuk yang menyebar sampai ratusan meter untuk menarik serangga penyerbuk."
  },
  {
    id: "anggrek-hitam",
    nama: "Anggrek Hitam",
    latin: "Coelogyne pandurata",
    status: "Dilindungi",
    img: "https://ugm.ac.id/wp-content/uploads/2023/10/orchidplantcare.info_.jpg",
    short: "Anggrek langka khas Kalimantan dengan bercak hitam khasnya di kelopak bunga.",
    info: {
      "Status": "Dilindungi",
      "Lokasi": "Kalimantan",
      "Habitat": "Hutan rawa & tepi sungai",
      "Ciri Khas": "Bercak hitam di lidah bunga"
    },
    desc: "Anggrek hitam adalah flora identitas Kalimantan Timur. Warna hijau kekuningan pada kelopaknya dipadukan dengan corak hitam keunguan yang khas membuatnya jadi salah satu anggrek paling dicari kolektor, sekaligus alasan populasinya terus menurun akibat perburuan liar.",
    tip: "Karena keindahannya, anggrek hitam sering diburu untuk diperjualbelikan secara ilegal, padahal statusnya dilindungi undang-undang."
  },
  {
    id: "cendana",
    nama: "Cendana",
    latin: "Santalum album",
    status: "Dilindungi",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnj6mOqfV2hTHT09BwUA2BkBbwBgxa2WlaRaQp0HcSi4Z_YjIm_hjz4I0&s=10",
    short: "Pohon penghasil kayu wangi khas Nusa Tenggara Timur yang populasinya kian menipis.",
    info: {
      "Status": "Dilindungi",
      "Lokasi": "Nusa Tenggara Timur",
      "Habitat": "Hutan tropis kering",
      "Ciri Khas": "Kayu & akar beraroma khas"
    },
    desc: "Cendana dikenal karena kayu dan minyaknya yang harum, banyak dipakai untuk parfum, kosmetik, hingga upacara adat. Nilai ekonominya yang tinggi justru membuat pohon ini terus diburu dan ditebang, sehingga populasinya di alam liar semakin sulit ditemukan.",
    tip: "Cendana butuh puluhan tahun untuk tumbuh sampai kayunya cukup wangi dan bernilai, sehingga penebangan liar bisa berdampak sangat lama untuk pemulihannya."
  }
];

/* ---------------- DATA FAUNA ---------------- */
const faunaData = [
  {
    id: "orangutan-sumatera",
    nama: "Orangutan Sumatera",
    latin: "Pongo abelii",
    status: "Critically Endangered",
    img: "https://cdn.betahita.id/5/8/9/7/5897.jpeg",
    short: "Kera besar endemik Sumatera yang habitatnya terus menyusut akibat deforestasi.",
    info: {
      "Status IUCN": "Critically Endangered",
      "Lokasi": "Hutan hujan Sumatera",
      "Habitat": "Hutan primer dataran rendah",
      "Populasi": "Sekitar 14.000 ekor"
    },
    desc: "Orangutan Sumatera hidup di kanopi hutan hujan dan sangat bergantung pada hutan primer yang luas. Perusakan habitat akibat perkebunan sawit, pembalakan liar, dan perburuan membuat populasinya terus menurun drastis dalam beberapa dekade terakhir.",
    tip: "Orangutan Sumatera termasuk hewan paling cerdas di dunia dan bisa menggunakan alat sederhana, misalnya ranting untuk mengambil makanan dari celah pohon."
  },
  {
    id: "harimau-sumatera",
    nama: "Harimau Sumatera",
    latin: "Panthera tigris sumatrae",
    status: "Critically Endangered",
    img: "https://www.marariversafarilodge.com/wp-content/uploads/2022/08/harimau-sumatra-1024x576.jpg",
    short: "Subspesies harimau terakhir di Indonesia yang kini jumlahnya kurang dari 600 ekor.",
    info: {
      "Status IUCN": "Critically Endangered",
      "Lokasi": "Pulau Sumatera",
      "Habitat": "Hutan hujan & pegunungan",
      "Populasi": "Kurang dari 600 ekor"
    },
    desc: "Setelah harimau Jawa dan Bali dinyatakan punah, harimau Sumatera menjadi satu-satunya subspesies harimau yang tersisa di Indonesia. Ancaman utamanya adalah hilangnya habitat dan perburuan liar untuk diambil kulit serta bagian tubuhnya.",
    tip: "Setiap harimau Sumatera punya pola garis loreng yang unik, mirip seperti sidik jari manusia, sehingga tidak ada dua individu dengan pola yang persis sama."
  },
  {
    id: "badak-jawa",
    nama: "Badak Jawa",
    latin: "Rhinoceros sondaicus",
    status: "Critically Endangered",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9e70pAjniADcNWJNH5gGjV0aixTts5J4pxCBmI7HyyD-Iv4bQ8aYJolot&s=10",
    short: "Salah satu mamalia paling langka di dunia yang hanya tersisa di Ujung Kulon.",
    info: {
      "Status IUCN": "Critically Endangered",
      "Lokasi": "Taman Nasional Ujung Kulon",
      "Habitat": "Hutan hujan dataran rendah",
      "Populasi": "Sekitar 76 ekor"
    },
    desc: "Badak Jawa kini hanya bisa ditemukan di satu tempat di dunia, yaitu Taman Nasional Ujung Kulon, Banten. Populasi kecil dan area penyebaran yang sangat terbatas membuat spesies ini sangat rentan terhadap bencana alam maupun penyakit.",
    tip: "Karena populasinya sangat kecil dan terisolasi di satu lokasi, badak Jawa disebut sebagai salah satu mamalia besar paling terancam punah di dunia."
  },
  {
    id: "komodo",
    nama: "Komodo",
    latin: "Varanus komodoensis",
    status: "Endangered",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQKS95r_gl0yzA_orF5LYhY1Ip3H3ZHAWBrs3LLiz8sA&s=10",
    short: "Kadal terbesar di dunia yang hanya hidup di beberapa pulau di Nusa Tenggara.",
    info: {
      "Status IUCN": "Endangered",
      "Lokasi": "Pulau Komodo & sekitarnya",
      "Habitat": "Savana & hutan tropis kering",
      "Populasi": "Sekitar 3.000 ekor"
    },
    desc: "Komodo adalah kadal terbesar yang masih hidup saat ini dan hanya ditemukan secara alami di beberapa pulau di Taman Nasional Komodo. Air liurnya mengandung bakteri dan racun yang membantunya melumpuhkan mangsa berukuran besar.",
    tip: "Komodo bisa mendeteksi bau bangkai dari jarak beberapa kilometer menggunakan lidahnya yang bercabang, mirip cara kerja indra penciuman ular."
  }
];

/* ---------------- DATA ARTIKEL (isi untuk modal) ---------------- */
const artikelData = {
  "apa-itu-perdagangan-ilegal": [
    "Perdagangan ilegal satwa liar adalah kegiatan menangkap, memelihara, menjual, atau mengangkut satwa dan tumbuhan liar tanpa izin resmi. Yang diperdagangkan bisa berupa hewan hidup, kulit, tanduk, gading, sisik, maupun tumbuhan langka seperti anggrek.",
    "Di Indonesia, jenis yang dilindungi diatur dalam peraturan perundang-undangan. Menangkap, memiliki, menyimpan, atau memperdagangkan jenis yang dilindungi, baik dalam keadaan hidup maupun mati, termasuk pelanggaran hukum.",
    "Perdagangan ini terus ada karena ada permintaan, misalnya untuk hewan peliharaan eksotis, koleksi, hiasan, dan bahan obat tradisional. Kini banyak transaksi terjadi lewat media sosial dan marketplace, sehingga lebih sulit dipantau."
  ],
  "dampak-keanekaragaman-hayati": [
    "Setiap spesies punya peran dalam ekosistemnya. Orangutan membantu menyebarkan biji, harimau menjaga jumlah hewan mangsa, dan lebah serta serangga penyerbuk menjaga tumbuhan tetap berbunga dan berbuah.",
    "Ketika satu spesies diambil terus-menerus dari alam, rantai ini ikut terganggu. Populasi yang menyusut membuat spesies lebih rentan terhadap penyakit dan perubahan lingkungan, dan dalam kasus terburuk berujung pada kepunahan.",
    "Kepunahan tidak bisa dibatalkan. Itu sebabnya mencegah perdagangan ilegal jauh lebih efektif daripada memulihkan populasi yang sudah hampir habis."
  ],
  "cara-melindungi-sekitar-kita": [
    "Melindungi flora dan fauna tidak harus dimulai dari hal besar. Langkah sederhana di sekitar kita sudah berarti.",
    "Jangan membeli atau memelihara satwa dan tumbuhan dilindungi, sekalipun dijual murah atau terlihat menggemaskan. Cari tahu dulu asal-usul dan status perlindungannya sebelum membeli tanaman hias atau hewan peliharaan.",
    "Kurangi jejak lingkungan kita: pilih produk kayu dan kertas yang bersertifikat, kurangi sampah plastik, dan jangan membuang sampah di hutan, sungai, atau pantai.",
    "Terakhir, sebarkan informasinya. Ajak teman dan keluarga mengenal spesies dilindungi, dan laporkan kalau menemukan dugaan perdagangan ilegal."
  ],
  "status-perlindungan": [
    "Status perlindungan sebuah spesies bisa berasal dari beberapa sistem yang berbeda, dan sering kali dibaca bersamaan.",
    "Status dilindungi adalah penetapan di tingkat nasional. Spesies dengan status ini tidak boleh ditangkap, dipelihara, atau diperdagangkan tanpa izin.",
    "CITES adalah perjanjian internasional yang mengatur perdagangan lintas negara. Spesies dikelompokkan ke dalam Apendiks I, II, dan III sesuai tingkat pembatasan perdagangannya.",
    "IUCN Red List menilai risiko kepunahan sebuah spesies di alam, dari Least Concern sampai Critically Endangered. Ini adalah penilaian ilmiah, bukan aturan hukum, tetapi sering menjadi dasar penentuan prioritas konservasi."
  ],
  "hutan-sumatera-menyusut": [
    "Hutan hujan tropis menyimpan sebagian besar keanekaragaman hayati daratan, tetapi luasnya terus berkurang. Penyebab utamanya antara lain pembukaan lahan untuk perkebunan, pembalakan liar, kebakaran, dan pembangunan infrastruktur.",
    "Ketika hutan terpecah menjadi bagian-bagian kecil, satwa kehilangan ruang jelajah dan sumber makanan. Pertemuan satwa dengan permukiman juga makin sering terjadi dan sering berakhir merugikan satwa.",
    "Melindungi hutan berarti melindungi rumah ribuan spesies sekaligus menjaga air, udara, dan iklim yang kita butuhkan."
  ],
  "melapor-dengan-aman": [
    "Kalau menemukan dugaan perdagangan satwa atau tumbuhan dilindungi, utamakan keselamatan diri. Jangan mendekati atau menegur pelaku sendirian.",
    "Catat hal yang kamu lihat: jenis satwa atau tumbuhan, lokasi, tanggal dan waktu, serta ciri-ciri penjual atau akun penjualnya. Kalau aman, ambil foto atau tangkapan layar tanpa mengganggu situasi.",
    "Sampaikan laporan lewat formulir WildGuard di bawah, atau langsung ke Balai Konservasi Sumber Daya Alam (BKSDA) terdekat. Jangan membeli satwa tersebut dengan alasan 'menyelamatkan', karena itu justru menjaga permintaan tetap ada."
  ]
};

/* gambar pengganti kalau URL gambar gagal dimuat */
const FALLBACK_IMG =
  "data:image/svg+xml;utf8," +
  encodeURIComponent(
    "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'>" +
    "<rect width='400' height='300' fill='#E7F4E2'/>" +
    "<path d='M200 90c-40 26-46 80-6 112 40-28 46-82 6-112Zm0 0v140' fill='none' stroke='#4C7A57' stroke-width='6' stroke-linecap='round'/>" +
    "</svg>"
  );

function attachImgFallback(img) {
  img.addEventListener("error", () => {
    if (img.dataset.fallback === "1") return;
    img.dataset.fallback = "1";
    img.src = FALLBACK_IMG;
  });
}

/* ---------------- RENDER GRID FLORA / FAUNA ---------------- */
function renderGrid(containerId, data) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = "";

  data.forEach(item => {
    const card = document.createElement("div");
    card.className = "item-card";
    card.innerHTML = `
      <img class="item-img" src="${item.img}" alt="${item.nama}" loading="lazy" referrerpolicy="no-referrer">
      <h3>${item.nama}</h3>
      <p class="item-short">${item.short}</p>
      <button type="button" class="btn-detail" data-id="${item.id}">Lihat Detail</button>
    `;
    attachImgFallback(card.querySelector("img"));
    container.appendChild(card);
  });
}

function findItem(id) {
  return floraData.find(item => item.id === id) || faunaData.find(item => item.id === id);
}

/* ---------------- MODAL (umum: kunci scroll, fokus, tutup) ---------------- */
let lastFocused = null;

function lockScroll(lock) {
  document.body.style.overflow = lock ? "hidden" : "";
}

function showModal(modal, focusTarget) {
  lastFocused = document.activeElement;
  modal.hidden = false;
  lockScroll(true);
  if (focusTarget) focusTarget.focus();
}

function hideModal(modal) {
  if (modal.hidden) return;
  modal.hidden = true;
  lockScroll(false);
  if (lastFocused && typeof lastFocused.focus === "function") lastFocused.focus();
}

/* ---------------- MODAL DETAIL SPESIES ---------------- */
function openModal(id) {
  const item = findItem(id);
  if (!item) return;

  const img = document.getElementById("modal-img");
  img.dataset.fallback = "";
  img.src = item.img;
  img.alt = item.nama;

  document.getElementById("modal-badge").textContent = item.status;
  document.getElementById("modal-nama").textContent = item.nama;
  document.getElementById("modal-latin").textContent = item.latin;
  document.getElementById("modal-desc").textContent = item.desc;
  document.getElementById("modal-tip").textContent = item.tip;

  const infoGrid = document.getElementById("modal-info");
  infoGrid.innerHTML = "";
  Object.entries(item.info).forEach(([label, value]) => {
    const infoItem = document.createElement("div");
    infoItem.className = "info-item";
    const strong = document.createElement("strong");
    const span = document.createElement("span");
    strong.textContent = label;
    span.textContent = value;
    infoItem.append(strong, span);
    infoGrid.appendChild(infoItem);
  });

  const modal = document.getElementById("detail-modal");
  showModal(modal, document.getElementById("modal-close"));
  modal.querySelector(".modal-box").scrollTop = 0;
}

function closeModal() {
  hideModal(document.getElementById("detail-modal"));
}

/* ---------------- MODAL ARTIKEL ---------------- */
function openArtikel(linkEl) {
  const key = linkEl.dataset.artikel;
  const paragraphs = artikelData[key];
  if (!paragraphs) return;

  const thumb = linkEl.querySelector(".artikel-thumb");
  const modalImg = document.getElementById("artikel-modal-img");
  modalImg.dataset.fallback = "";
  modalImg.src = thumb ? thumb.src.replace("w=400", "w=900") : "";
  modalImg.alt = "";

  const time = linkEl.querySelector("time");
  const tag = linkEl.querySelector(".tag");

  const tgl = document.getElementById("artikel-modal-tanggal");
  tgl.textContent = time ? time.textContent : "";
  if (time) tgl.setAttribute("datetime", time.getAttribute("datetime"));

  const modalTag = document.getElementById("artikel-modal-tag");
  modalTag.textContent = tag ? tag.textContent : "";
  modalTag.className = tag ? tag.className : "tag";

  document.getElementById("artikel-modal-judul").textContent =
    linkEl.querySelector("h3").textContent;

  const isi = document.getElementById("artikel-modal-isi");
  isi.innerHTML = "";
  paragraphs.forEach(text => {
    const p = document.createElement("p");
    p.textContent = text;
    isi.appendChild(p);
  });

  const modal = document.getElementById("artikel-modal");
  showModal(modal, document.getElementById("artikel-modal-close"));
  modal.querySelector(".modal-box").scrollTop = 0;
}

function closeArtikel() {
  hideModal(document.getElementById("artikel-modal"));
}

/* ---------------- PINDAH VIEW & NAVIGASI ---------------- */
function showView(viewId) {
  ["home-view", "flora-view", "fauna-view"].forEach(id => {
    document.getElementById(id).hidden = id !== viewId;
  });
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function goToSection(sectionId) {
  showView("home-view");
  requestAnimationFrame(() => {
    const target = document.getElementById(sectionId);
    if (target) target.scrollIntoView({ behavior: "smooth" });
  });
}

/* ---------------- MENU MOBILE ---------------- */
function setupNavToggle() {
  const toggle = document.getElementById("nav-toggle");
  const menu = document.getElementById("nav-menu");
  if (!toggle || !menu) return;

  const setOpen = open => {
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Tutup menu" : "Buka menu");
    document.body.classList.toggle("nav-open", open);
  };

  toggle.addEventListener("click", () => {
    setOpen(toggle.getAttribute("aria-expanded") !== "true");
  });

  menu.addEventListener("click", e => {
    if (e.target.closest("a")) setOpen(false);
  });

  document.addEventListener("keydown", e => {
    if (e.key === "Escape") setOpen(false);
  });
}

/* ---------------- NAVBAR: bayangan saat scroll + link aktif ---------------- */
function setupNavState() {
  const navbar = document.querySelector(".navbar");
  const onScroll = () => navbar.classList.toggle("is-scrolled", window.scrollY > 10);
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  if (!("IntersectionObserver" in window)) return;

  const map = { beranda: "beranda", kategori: "kategori", edukasi: "edukasi", laporan: "laporan", tentang: "tentang" };
  const links = document.querySelectorAll(".nav-links a[data-nav]");

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const key = map[entry.target.id];
      links.forEach(a => {
        if (a.dataset.nav === key) a.setAttribute("aria-current", "true");
        else a.removeAttribute("aria-current");
      });
    });
  }, { rootMargin: "-45% 0px -50% 0px" });

  Object.keys(map).forEach(id => {
    const el = document.getElementById(id);
    if (el) observer.observe(el);
  });
}

/* ---------------- STATISTIK HERO ---------------- */
function setupCounts() {
  const flora = document.querySelector('[data-count="flora"]');
  const fauna = document.querySelector('[data-count="fauna"]');
  if (flora) flora.textContent = floraData.length;
  if (fauna) fauna.textContent = faunaData.length;
}

/* ---------------- EDUKASI: cari, filter topik, lihat semua ---------------- */
function setupArtikel() {
  const list = document.getElementById("artikel-list");
  if (!list) return;

  const search = document.getElementById("artikel-search");
  const topik = document.getElementById("artikel-topik");
  const more = document.getElementById("artikel-more");
  const empty = document.getElementById("artikel-empty");
  const items = Array.from(list.querySelectorAll(".artikel-item"));

  let showAll = false;

  function update() {
    const q = search.value.trim().toLowerCase();
    const t = topik.value;
    const filtering = q !== "" || t !== "semua";

    let visible = 0;
    items.forEach(item => {
      const title = item.querySelector("h3").textContent.toLowerCase();
      const matchText = title.includes(q);
      const matchTopic = t === "semua" || item.dataset.topik === t;
      const isExtra = item.classList.contains("is-extra");
      // saat mencari/memfilter, semua artikel ikut dicari
      const allowed = filtering || showAll || !isExtra;

      const show = matchText && matchTopic && allowed;
      item.hidden = !show;
      if (show) visible++;
    });

    empty.hidden = visible !== 0;

    // tombol hanya relevan saat tidak sedang mencari/memfilter
    more.hidden = filtering;
    more.textContent = showAll ? "Tampilkan Lebih Sedikit" : "Lihat Semua Artikel";
    more.setAttribute("aria-expanded", String(showAll));
  }

  search.addEventListener("input", update);
  topik.addEventListener("change", update);
  more.addEventListener("click", () => {
    showAll = !showAll;
    update();
  });

  list.addEventListener("click", e => {
    const link = e.target.closest(".artikel-link");
    if (!link) return;
    e.preventDefault();
    openArtikel(link);
  });

  update();
}

/* ---------------- FORM LAPORAN ---------------- */
function setupForm() {
  const form = document.getElementById("laporan-form");
  if (!form) return;

  const success = document.getElementById("form-success");
  const waktu = document.getElementById("waktu");
  const lampiran = document.getElementById("lampiran");
  const MAX_FILE = 5 * 1024 * 1024;

  // tanggal kejadian tidak boleh di masa depan
  const today = new Date();
  const iso = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
    .toISOString().slice(0, 10);
  waktu.max = iso;

  const rules = {
    jenis: el => (el.value ? "" : "Pilih jenis temuan terlebih dahulu."),
    lokasi: el => (el.value.trim().length >= 3 ? "" : "Isi lokasi kejadian, minimal 3 karakter."),
    waktu: el => {
      if (!el.value) return "Pilih tanggal kejadian.";
      if (el.value > iso) return "Tanggal kejadian tidak boleh di masa depan.";
      return "";
    },
    deskripsi: el => (el.value.trim().length >= 20 ? "" : "Jelaskan apa yang Anda lihat, minimal 20 karakter.")
  };

  function setError(name, message) {
    const field = form.elements[name];
    const out = form.querySelector(`[data-error-for="${name}"]`);
    if (!field || !out) return;
    if (message) {
      out.textContent = message;
      out.hidden = false;
      field.setAttribute("aria-invalid", "true");
      field.setAttribute("aria-describedby", out.id || (out.id = `err-${name}`));
    } else {
      out.hidden = true;
      field.removeAttribute("aria-invalid");
      field.removeAttribute("aria-describedby");
    }
  }

  function validateField(name) {
    const message = rules[name](form.elements[name]);
    setError(name, message);
    return !message;
  }

  function validateFile() {
    const file = lampiran.files[0];
    const hint = lampiran.closest(".field").querySelector(".field-hint");
    if (file && file.size > MAX_FILE) {
      hint.textContent = "Ukuran file melebihi 5 MB. Pilih file yang lebih kecil.";
      hint.classList.add("is-error");
      lampiran.setAttribute("aria-invalid", "true");
      return false;
    }
    hint.textContent = "Foto atau PDF pendukung, maksimal 5 MB.";
    hint.classList.remove("is-error");
    lampiran.removeAttribute("aria-invalid");
    return true;
  }

  // validasi saat user selesai mengisi kolom
  Object.keys(rules).forEach(name => {
    const el = form.elements[name];
    el.addEventListener("blur", () => validateField(name));
    el.addEventListener("input", () => {
      if (el.getAttribute("aria-invalid") === "true") validateField(name);
    });
  });
  lampiran.addEventListener("change", validateFile);

  form.addEventListener("submit", e => {
    e.preventDefault();
    success.hidden = true;

    const results = Object.keys(rules).map(validateField);
    const fileOk = validateFile();

    if (results.includes(false) || !fileOk) {
      const firstBad = form.querySelector('[aria-invalid="true"]');
      if (firstBad) firstBad.focus();
      return;
    }

    // Belum ada server: data hanya dikumpulkan di sini.
    const data = Object.fromEntries(new FormData(form).entries());
    delete data.lampiran;
    console.info("Laporan diterima (simulasi):", data);

    form.reset();
    success.hidden = false;
    success.scrollIntoView({ behavior: "smooth", block: "center" });
  });
}

/* ---------------- INIT ---------------- */
document.addEventListener("DOMContentLoaded", () => {
  renderGrid("flora-grid", floraData);
  renderGrid("fauna-grid", faunaData);
  setupCounts();
  setupNavToggle();
  setupNavState();
  setupArtikel();
  setupForm();

  // gambar statis di halaman juga diberi cadangan
  document.querySelectorAll("main img, .modal-img").forEach(attachImgFallback);

  // satu momen masuk untuk hero (gaya diatur di CSS)
  requestAnimationFrame(() => document.body.classList.add("is-ready"));

  // kartu kategori -> halaman daftar
  document.querySelectorAll("[data-view]").forEach(el => {
    el.addEventListener("click", e => {
      e.preventDefault();
      showView(el.dataset.view);
    });
  });

  // semua link navigasi internal
  document.querySelectorAll("[data-nav]").forEach(el => {
    el.addEventListener("click", e => {
      e.preventDefault();
      goToSection(el.dataset.nav);
    });
  });

  // tombol "Lihat Detail"
  document.addEventListener("click", e => {
    const btn = e.target.closest(".btn-detail");
    if (btn) openModal(btn.dataset.id);
  });

  // tutup modal spesies
  document.getElementById("modal-close").addEventListener("click", closeModal);
  document.getElementById("detail-modal").addEventListener("click", e => {
    if (e.target.id === "detail-modal") closeModal();
  });

  // tutup modal artikel
  document.getElementById("artikel-modal-close").addEventListener("click", closeArtikel);
  document.getElementById("artikel-modal").addEventListener("click", e => {
    if (e.target.id === "artikel-modal") closeArtikel();
  });

  document.addEventListener("keydown", e => {
    if (e.key === "Escape") {
      closeModal();
      closeArtikel();
    }
  });
});