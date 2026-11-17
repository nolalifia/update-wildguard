/* ==========================================================
   WildGuard - script.js
   ========================================================== */

/* ---------------- DATA (dikirim dari Laravel lewat window.WG) ---------------- */
const WG = window.WG || { flora: [], fauna: [], artikel: {} };
const floraData = WG.flora;
const faunaData = WG.fauna;
const artikelData = WG.artikel;

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
function showView(viewId, scroll = true) {
  ["home-view", "flora-view", "fauna-view"].forEach(id => {
    document.getElementById(id).hidden = id !== viewId;
  });
  if (scroll) window.scrollTo({ top: 0, behavior: "smooth" });
}

function goToSection(sectionId) {
  const sudahDiBeranda = !document.getElementById("home-view").hidden;
  showView("home-view", false); // jangan scroll ke atas dulu
  requestAnimationFrame(() => {
    const target = document.getElementById(sectionId);
    if (target) {
      target.scrollIntoView({ behavior: sudahDiBeranda ? "smooth" : "auto" });
    }
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
  const links = document.querySelectorAll(".nav-links a[data-nav]");
  const home = document.getElementById("home-view");

  // id section -> menu yang menyala
  const navFor = {
    beranda: "beranda", krisis: "beranda", ancaman: "beranda", rantai: "beranda",
    kategori: "kategori", cek: "kategori", kuis: "kategori",
    peta: "peta", edukasi: "edukasi", tentang: "tentang", laporan: "laporan"
  };
  const ids = Object.keys(navFor);
  let ticking = false;

  function update() {
    ticking = false;
    navbar.classList.toggle("is-scrolled", window.scrollY > 10);
    if (home.hidden) return;

    const line = window.innerHeight * 0.35;
    let current = "beranda", best = -Infinity;
    if (window.scrollY > 40) {
      ids.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        const top = el.getBoundingClientRect().top;
        if (top <= line && top > best) { best = top; current = id; }
      });
    }

    const key = navFor[current];
    links.forEach(a => {
      if (a.dataset.nav === key) a.setAttribute("aria-current", "true");
      else a.removeAttribute("aria-current");
    });
  }

  window.addEventListener("scroll", () => {
    if (!ticking) { ticking = true; requestAnimationFrame(update); }
  }, { passive: true });
  window.addEventListener("resize", update);
  window.addEventListener("load", update);
  update();
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

    // validasi lolos -> kirim ke server (POST /lapor), disimpan ke tabel laporan
    form.submit();
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