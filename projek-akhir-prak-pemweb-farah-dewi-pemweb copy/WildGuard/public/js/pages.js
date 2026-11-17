/* ==========================================================
   WildGuard - pages.js
   Muat SETELAH script.js. Mengubah satu halaman panjang jadi
   halaman terpisah seperti mockup: Beranda, Flora & Fauna,
   Detail, Edukasi, Laporkan, Tentang. Pakai hash agar tombol
   Back browser bekerja (#beranda, #jelajah/flora, #detail/komodo).
   ========================================================== */
(function () {
  const PER_PAGE = 6;
  const ALL = [
    ...floraData.map(d => ({ ...d, kind: "flora" })),
    ...faunaData.map(d => ({ ...d, kind: "fauna" }))
  ];
  const state = { kind: "semua", q: "", page: 1 };
  const no = d => "No. " + String(ALL.indexOf(d) + 1).padStart(3, "0");
  const slug = s => s.toLowerCase().replace(/[^a-z]+/g, "-");

  const footer = document.querySelector(".footer");
  const arsip = document.createElement("main");
  const detail = document.createElement("main");
  arsip.id = "arsip-view"; detail.id = "detail-view";
  arsip.className = detail.className = "sheet";
  arsip.hidden = detail.hidden = true;
  footer.before(arsip, detail);

  const rail = document.createElement("div");
  rail.className = "rail";
  rail.setAttribute("aria-hidden", "true");
  document.body.appendChild(rail);

  /* ---------- kerangka arsip (dibuat sekali) ---------- */
  arsip.innerHTML = `
    <p class="folio">Arsip spesies</p>
    <h2 class="arsip-title">Yang kita jaga, <br>satu per satu.</h2>
    <p class="section-desc">Cari berdasarkan nama, atau pilah lewat kategori. Buka setiap kartu untuk membaca arsip lengkapnya.</p>
    <div class="arsip-tools">
      <label class="search-box" for="arsip-q">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.200-4.200"/></svg>
        <span class="sr-only">Cari nama flora atau fauna</span>
        <input type="search" id="arsip-q" placeholder="Cari nama flora atau fauna..." autocomplete="off">
      </label>
      <div class="chips" role="group" aria-label="Kategori">
        <button type="button" class="chip" data-kind="semua">Semua</button>
        <button type="button" class="chip" data-kind="flora">Flora</button>
        <button type="button" class="chip" data-kind="fauna">Fauna</button>
      </div>
    </div>
    <p class="arsip-count" id="arsip-count" aria-live="polite"></p>
    <div class="specimen-grid" id="arsip-grid"></div>
    <nav class="pager" id="arsip-pager" aria-label="Halaman daftar"></nav>`;

  const grid = arsip.querySelector("#arsip-grid");
  const pager = arsip.querySelector("#arsip-pager");
  const count = arsip.querySelector("#arsip-count");
  const input = arsip.querySelector("#arsip-q");

  function filtered() {
    const q = state.q.trim().toLowerCase();
    return ALL.filter(d =>
      (state.kind === "semua" || d.kind === state.kind) &&
      (!q || (d.nama + " " + d.latin).toLowerCase().includes(q)));
  }

  function renderGrid() {
    const items = filtered();
    const pages = Math.max(1, Math.ceil(items.length / PER_PAGE));
    state.page = Math.min(state.page, pages);
    const slice = items.slice((state.page - 1) * PER_PAGE, state.page * PER_PAGE);

    arsip.querySelectorAll(".chip").forEach(c =>
      c.setAttribute("aria-pressed", String(c.dataset.kind === state.kind)));
    count.textContent = items.length + " spesimen ditemukan";

    grid.innerHTML = slice.length ? slice.map(d => `
      <a class="spec spec-${d.kind}" href="#detail/${d.id}">
        <span class="spec-kind">${d.kind === "flora" ? "Flora" : "Fauna"}</span>
        <img class="spec-img" src="${d.img}" alt="${d.nama}" loading="lazy" referrerpolicy="no-referrer">
        <span class="spec-no">${no(d)}</span>
        <h3>${d.nama}</h3>
        <p class="spec-latin">${d.latin}</p>
        <span class="spec-go">Buka arsip</span>
      </a>`).join("")
      : `<p class="artikel-empty">Belum ada yang cocok dengan pencarianmu. Coba nama lain atau pilih Semua.</p>`;
    grid.querySelectorAll("img").forEach(attachImgFallback);

    pager.hidden = pages === 1;
    let html = `<button type="button" data-p="${state.page - 1}" aria-label="Halaman sebelumnya" ${state.page === 1 ? "disabled" : ""}>&lsaquo;</button>`;
    for (let i = 1; i <= pages; i++)
      html += `<button type="button" data-p="${i}" ${i === state.page ? 'aria-current="page"' : ""}>${i}</button>`;
    html += `<button type="button" data-p="${state.page + 1}" aria-label="Halaman berikutnya" ${state.page === pages ? "disabled" : ""}>&rsaquo;</button>`;
    pager.innerHTML = html;
  }

  input.addEventListener("input", () => { state.q = input.value; state.page = 1; renderGrid(); });
  arsip.querySelector(".chips").addEventListener("click", e => {
    const c = e.target.closest(".chip");
    if (!c) return;
    state.kind = c.dataset.kind; state.page = 1; renderGrid();
  });
  pager.addEventListener("click", e => {
    const b = e.target.closest("button[data-p]");
    if (!b || b.disabled) return;
    state.page = +b.dataset.p; renderGrid();
    arsip.querySelector(".arsip-tools").scrollIntoView({ behavior: "smooth", block: "start" });
  });

  /* ---------- halaman detail ---------- */
  function renderDetail(d) {
    const i = ALL.indexOf(d);
    const prev = ALL[(i - 1 + ALL.length) % ALL.length];
    const next = ALL[(i + 1) % ALL.length];
    detail.innerHTML = `
      <a class="back-btn" href="#jelajah">&larr; Kembali ke arsip</a>
      <article class="folio-sheet">
        <figure class="detail-fig spec-${d.kind}">
          <img class="detail-img" src="${d.img}" alt="${d.nama}" referrerpolicy="no-referrer">
          <div class="status-stamp"><span>${d.status}</span></div>
        </figure>
        <div class="detail-main">
          <p class="folio">${no(d)}, ${d.kind === "flora" ? "flora" : "fauna"}</p>
          <h2>${d.nama}</h2>
          <p class="modal-latin">${d.latin}</p>
          <dl class="label-list">
            ${Object.entries(d.info).map(([k, v]) => `<div><dt>${k}</dt><dd>${v}</dd></div>`).join("")}
          </dl>
          <h3>Deskripsi</h3>
          <p>${d.desc}</p>
          <aside class="sticky-note"><strong>Tahukah kamu?</strong><p>${d.tip}</p></aside>
        </div>
      </article>
      <nav class="spec-nav" aria-label="Spesimen lain">
        <a class="spec-link" href="#detail/${prev.id}"><small>Sebelumnya</small>${prev.nama}</a>
        <a class="spec-link r" href="#detail/${next.id}"><small>Berikutnya</small>${next.nama}</a>
      </nav>`;
    attachImgFallback(detail.querySelector("img"));
  }

  /* ---------- router ---------- */
  const NAMA = { home: "Beranda", arsip: "Arsip", detail: "Detail", edukasi: "Edukasi", laporan: "Laporan", tentang: "Tentang" };
  const NAV = { home: "beranda", arsip: "kategori", detail: "kategori", edukasi: "edukasi", laporan: "laporan", tentang: "tentang" };

  function route() {
    const [name, arg] = decodeURIComponent(location.hash.slice(1)).split("/");
    let page = "home", item = null, anchor = null;
    const dariBeranda = document.body.dataset.page === "home";

    if (name === "jelajah") {
      page = "arsip";
      if (["semua", "flora", "fauna"].includes(arg)) { state.kind = arg; state.page = 1; }
    } else if (name === "detail" && (item = ALL.find(d => d.id === arg))) {
      page = "detail";
    } else if (["edukasi", "laporan", "tentang"].includes(name)) {
      page = name;
    } else if (["peta", "cek", "kuis", "krisis", "ancaman", "rantai"].includes(name)) {
      anchor = name; // bagian di dalam Beranda: scroll ke sana, bukan ke atas
    }

    document.body.dataset.page = page;
    document.body.classList.remove("nav-open");
    document.getElementById("home-view").hidden = page === "arsip" || page === "detail";
    document.getElementById("flora-view").hidden = true;
    document.getElementById("fauna-view").hidden = true;
    arsip.hidden = page !== "arsip";
    detail.hidden = page !== "detail";

    if (page === "arsip") { input.value = state.q; renderGrid(); }
    if (page === "detail") renderDetail(item);

    document.querySelectorAll(".nav-links a[data-nav]").forEach(a =>
      a.dataset.nav === NAV[page] ? a.setAttribute("aria-current", "true") : a.removeAttribute("aria-current"));
    rail.textContent = "WildGuard, " + NAMA[page].toLowerCase();
    document.title = (item ? item.nama : NAMA[page]) + " - WildGuard";
    const target = anchor && document.getElementById(anchor);
    if (target) requestAnimationFrame(() => target.scrollIntoView({ behavior: dariBeranda ? "smooth" : "auto", block: "start" }));
    else window.scrollTo(0, 0);
  }

  function go(hash) {
    if (location.hash === "#" + hash) route(); else location.hash = hash;
  }

  /* ---------- ganti fungsi navigasi bawaan script.js ---------- */
  window.goToSection = id => go(id === "kategori" ? "jelajah/semua" : id);
  window.showView = id => go(id === "flora-view" ? "jelajah/flora" : id === "fauna-view" ? "jelajah/fauna" : "beranda");
  window.openModal = id => go("detail/" + id);

  window.addEventListener("hashchange", route);
  document.addEventListener("DOMContentLoaded", route);
})();