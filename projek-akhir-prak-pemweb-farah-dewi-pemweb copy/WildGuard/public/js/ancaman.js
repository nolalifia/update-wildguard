/* ==========================================================
   WildGuard - ancaman.js
   Muat PALING AKHIR (setelah script.js dan pages.js).
   Isi: intro pembuka, reveal saat scroll, count-up angka,
   donut scrollytelling, bar ancaman, stagger rantai.
   ========================================================== */
(function () {
  "use strict";

  const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  document.documentElement.classList.add("js");

  /* ==========================================================
     DATA DONUT
     - nilai tiap dataset berjumlah 100 (persen dari lingkaran)
     - c = nomor warna 1-5 (cocok dengan .c1 ... .c5 di CSS):
       1 mawar, 2 kelopak, 3 hijau daun, 4 hijau tua, 5 lilac
     - tengah = angka besar di tengah donut

     SUMBER DATA (dikumpulkan November 2026)
     - IBSAP 2025-2045, Bappenas (data IUCN Red List 2022/2023):
       satwa liar terancam: 200 CR, 380 EN, 691 VU (total 1.271)
       tumbuhan terancam:   214 CR, 392 EN, 464 VU (total 1.070)
       Dikuatkan Databoks Katadata (22 Juli 2025) dan CNBC Indonesia
       (8 Agustus 2024). Yang berstatus EW/EX tidak dihitung.
     - Studi "The processes that threaten Indonesian plants",
       Oryx 2010, doi 10.1017/S0030605310001092 (240 tumbuhan terancam).

     PERHITUNGAN
       fauna  CR 200/1271=16%, EN 380/1271=30%, VU 691/1271=54%
              CR+EN 580/1271=46%
       flora  CR 214/1070=20%, EN 392/1070=37%, VU 464/1070=43%
              CR+EN 606/1070=57%
       penyebab  angka asli 82%, 64%, 6% (satu jenis bisa kena banyak
              ancaman) dinormalkan: 82/152=54%, 64/152=42%, 6/152=4%
     ========================================================== */
  const DATASETS = {
    fauna: {
      judul: "Status satwa liar terancam di Indonesia",
      caption: "Pembagian satwa liar terancam punah menurut tingkat risikonya",
      tengah: { angka: 46, akhiran: "%", label: "satwa terancam berstatus Kritis atau Genting" },
      segmen: [
        { label: "Kritis (CR)", nilai: 16, c: 1 },
        { label: "Genting (EN)", nilai: 30, c: 2 },
        { label: "Rentan (VU)", nilai: 54, c: 5 }
      ],
      sumber: "Sumber: IBSAP 2025-2045 Bappenas (IUCN Red List): 200 CR, 380 EN, 691 VU."
    },
    flora: {
      judul: "Status tumbuhan terancam di Indonesia",
      caption: "Pembagian tumbuhan terancam punah menurut tingkat risikonya",
      tengah: { angka: 57, akhiran: "%", label: "tumbuhan terancam berstatus Kritis atau Genting" },
      segmen: [
        { label: "Kritis (CR)", nilai: 20, c: 1 },
        { label: "Genting (EN)", nilai: 37, c: 2 },
        { label: "Rentan (VU)", nilai: 43, c: 5 }
      ],
      sumber: "Sumber: IBSAP 2025-2045 Bappenas (IUCN Red List): 214 CR, 392 EN, 464 VU."
    },
    penyebab: {
      judul: "Penyebab utama kepunahan tumbuhan",
      caption: "Porsi tiap ancaman pada 240 jenis tumbuhan terancam di Indonesia",
      tengah: { angka: 42, akhiran: "%", label: "dari ancaman berupa panen & perdagangan berlebih" },
      segmen: [
        { label: "Hilangnya habitat", nilai: 54, c: 1 },
        { label: "Eksploitasi berlebih (panen & perdagangan)", nilai: 42, c: 2 },
        { label: "Faktor alam (banjir, kekeringan)", nilai: 4, c: 5 }
      ],
      sumber: "Sumber: Oryx 2010. Angka asli 82%, 64%, 6% (satu jenis bisa kena beberapa ancaman) dinormalkan ke 100%."
    }
  };

  /* ---------- count-up angka ---------- */
  const fmt = (n, dec = 0) =>
    Number(n).toLocaleString("id-ID", { minimumFractionDigits: dec, maximumFractionDigits: dec });

  function countUp(el, to, opts = {}) {
    const { suffix = "", dec = 0, ms = 1200, html = false } = opts;
    const write = v => {
      const t = fmt(v, dec);
      if (html && suffix) el.innerHTML = t + "<small>" + suffix + "</small>";
      else el.textContent = t + suffix;
    };
    if (el._raf) cancelAnimationFrame(el._raf);
    const from = reduce ? to : parseFloat(el.dataset.cur || "0");
    if (reduce || from === to) { write(to); el.dataset.cur = String(to); return; }
    const t0 = performance.now();
    (function tick(now) {
      const p = Math.min(1, (now - t0) / ms);
      const e = 1 - Math.pow(1 - p, 3);
      const v = from + (to - from) * e;
      write(p === 1 ? to : v);
      el.dataset.cur = String(v);
      if (p < 1) el._raf = requestAnimationFrame(tick);
    })(t0);
  }

  /* ==========================================================
     1. INTRO PEMBUKA
     HTML: <div class="intro" id="intro" hidden> ... </div>
     Tampil sekali per sesi, hanya di beranda, dan dilewati kalau
     pengguna memilih gerak dikurangi.
     ========================================================== */
  function setupIntro() {
    const intro = $("#intro");
    if (!intro) return;

    const hash = location.hash.replace("#", "");
    const onHome = hash === "" || hash === "beranda";
    let seen = false;
    try { seen = sessionStorage.getItem("wg-intro") === "1"; } catch (e) {}

    if (reduce || seen || !onHome) { intro.hidden = true; return; }

    const DUR = 4600;      // lama intro (ms)
    const LEAVE = 1000;    // lama tirai membuka (ms), samakan dengan CSS
    let finished = false;

    intro.style.setProperty("--intro-dur", DUR + "ms");
    intro.hidden = false;
    document.body.classList.add("intro-lock");

    const skip = $(".intro-skip", intro);
    if (skip) skip.focus();

    function finish() {
      if (finished) return;
      finished = true;
      clearTimeout(timer);
      intro.classList.add("is-leaving");
      try { sessionStorage.setItem("wg-intro", "1"); } catch (e) {}
      setTimeout(() => {
        intro.hidden = true;
        document.body.classList.remove("intro-lock");
        window.scrollTo(0, 0);
      }, LEAVE);
    }

    const timer = setTimeout(finish, DUR);
    if (skip) skip.addEventListener("click", finish);
    document.addEventListener("keydown", e => {
      if (e.key === "Escape" || e.key === "Enter") finish();
    });
  }

  /* ==========================================================
     2. REVEAL SAAT SCROLL + STAGGER + BAR + COUNT-UP
     - Elemen .reveal muncul saat masuk layar
     - Anak langsung dari [data-stagger] otomatis jadi .reveal
       dengan jeda berurutan
     - .bar-row ikut diobservasi (CSS yang mengisi bar-nya)
     - [data-count] (+ data-suffix, data-dec) dihitung naik
     ========================================================== */
  function setupReveal() {
    $$("[data-stagger]").forEach(box => {
      const gap = parseFloat(box.dataset.stagger) || 0.12;
      Array.from(box.children).forEach((child, i) => {
        child.classList.add("reveal");
        child.style.setProperty("--d", (i * gap).toFixed(2) + "s");
      });
    });

    const targets = $$(".reveal, .bar-row");

    const show = el => {
      el.classList.add("is-visible");
      $$("[data-count]", el).forEach(n => {
        countUp(n, parseFloat(n.dataset.count), {
          suffix: n.dataset.suffix || "",
          dec: parseInt(n.dataset.dec || "0", 10)
        });
      });
      if (el.matches("[data-count]")) {
        countUp(el, parseFloat(el.dataset.count), {
          suffix: el.dataset.suffix || "",
          dec: parseInt(el.dataset.dec || "0", 10)
        });
      }
    };

    if (!("IntersectionObserver" in window)) { targets.forEach(show); return; }

    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        show(en.target);
        obs.unobserve(en.target);
      });
    }, { threshold: 0.18, rootMargin: "0px 0px -6% 0px" });

    targets.forEach(el => io.observe(el));
  }

  /* ==========================================================
     3. DONUT SCROLLYTELLING
     HTML yang dibutuhkan:
       #donut-stage  (.donut-stage)
       #donut-title, #donut-caption, #donut-source
       #donut-svg    (<svg class="donut" viewBox="0 0 200 200">)
       #donut-num, #donut-label, #donut-legend (<ul>)
       .krisis-step[data-set="fauna|flora|penyebab"][data-hl="1-5"]
         data-hl opsional: sorot segmen berwarna c-N
     ========================================================== */
  function setupDonut() {
    const stage = $("#donut-stage");
    const svg = $("#donut-svg");
    const steps = $$(".krisis-step");
    if (!stage || !svg || !steps.length) return;

    const titleEl = $("#donut-title");
    const capEl = $("#donut-caption");
    const srcEl = $("#donut-source");
    const numEl = $("#donut-num");
    const labEl = $("#donut-label");
    const legend = $("#donut-legend");

    const NS = "http://www.w3.org/2000/svg";
    const MAKS = 5;
    const GAP = 0.6; // celah antar segmen
    const mk = (cls) => {
      const c = document.createElementNS(NS, "circle");
      c.setAttribute("cx", "100");
      c.setAttribute("cy", "100");
      c.setAttribute("r", "80");
      c.setAttribute("pathLength", "100");
      c.setAttribute("class", cls);
      svg.appendChild(c);
      return c;
    };

    mk("track");
    const segs = Array.from({ length: MAKS }, (_, i) => mk("seg c" + (i + 1)));

    let current = null;

    function render(key) {
      if (key === current) return;
      const ds = DATASETS[key];
      if (!ds) return;
      const first = current === null;
      current = key;

      // segmen donut
      let start = 0;
      segs.forEach((c, i) => {
        const s = ds.segmen[i];
        if (s) {
          c.setAttribute("class", "seg c" + s.c);
          c.style.setProperty("--val", Math.max(0, s.nilai - GAP).toFixed(2));
          c.style.setProperty("--start", start.toFixed(2));
          c.style.setProperty("--d", (i * 0.06).toFixed(2) + "s");
          start += s.nilai;
        } else {
          c.style.setProperty("--val", "0");
          c.style.setProperty("--start", "100");
        }
      });

      // legenda
      legend.innerHTML = ds.segmen.map(s =>
        '<li data-c="' + s.c + '"><i class="c' + s.c + '"></i>' + s.label +
        "<b>" + fmt(s.nilai) + "%</b></li>"
      ).join("");

      // teks (dengan fade singkat, kecuali saat pertama kali)
      const swap = () => {
        titleEl.textContent = ds.judul;
        capEl.textContent = ds.caption;
        srcEl.textContent = ds.sumber;
        labEl.textContent = ds.tengah.label;
        titleEl.style.opacity = "1";
      };
      if (first || reduce) swap();
      else {
        titleEl.style.opacity = "0";
        setTimeout(swap, 220);
      }

      numEl.dataset.cur = first ? "0" : numEl.dataset.cur;
      countUp(numEl, ds.tengah.angka, { suffix: ds.tengah.akhiran || "", html: true, ms: 1100 });
    }

    function sorot(hl) {
      if (hl) stage.setAttribute("data-hl", hl);
      else stage.removeAttribute("data-hl");
      $$("li", legend).forEach(li => li.classList.toggle("is-hl", !!hl && li.dataset.c === hl));
    }

    // tampilkan data awal (donut masih kosong sampai masuk layar)
    render(steps[0].dataset.set);

    if (!("IntersectionObserver" in window)) {
      stage.classList.add("is-in");
      steps[0].classList.add("is-active");
      return;
    }

    // donut "terisi" saat bagian krisis mulai terlihat
    const wrapObs = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting) { stage.classList.add("is-in"); wrapObs.disconnect(); }
      });
    }, { threshold: 0.25 });
    wrapObs.observe(stage);

    // ganti data sesuai langkah yang sedang di tengah layar
    const stepObs = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        steps.forEach(s => s.classList.toggle("is-active", s === en.target));
        render(en.target.dataset.set);
        sorot(en.target.dataset.hl || "");
      });
    }, { rootMargin: "-45% 0px -45% 0px" });

    steps.forEach(s => stepObs.observe(s));
    steps[0].classList.add("is-active");
  }

  /* ---------- jalankan ---------- */
    /* ==========================================================
     4. PENDAMPING SCROLL
     Orangutan memanjat turun mengikuti posisi scroll, pindah
     kiri/kanan tiap ganti bagian; kupu-kupu di sisi sebaliknya.
     HTML: #pendamping > .tali, #monyet, #kupu
     ========================================================== */
  function setupPendamping() {
    const box = $("#pendamping");
    if (!box) return;
    if (reduce) { box.hidden = true; return; }

    const monyet = $("#monyet", box);
    const kupu = $("#kupu", box);
    const urut = ["krisis", "ancaman", "rantai", "kategori", "edukasi", "tentang", "laporan"]
      .map(id => document.getElementById(id))
      .filter(Boolean);

    const clamp = (v, a, b) => Math.min(b, Math.max(a, v));
    let lastY = window.scrollY;
    let vel = 0;
    let ticking = false;
    let timer = null;

    function progress() {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      return max > 0 ? clamp(window.scrollY / max, 0, 1) : 0;
    }

    // bagian mana yang sedang di tengah layar -> genap: kanan, ganjil: kiri
    function sisiKiri() {
      const tengah = window.innerHeight * 0.5;
      let idx = -1;
      urut.forEach((s, i) => {
        if (s.offsetParent === null) return; // sedang disembunyikan
        if (s.getBoundingClientRect().top <= tengah) idx = i;
      });
      return idx >= 0 && idx % 2 === 1;
    }

    function update() {
      ticking = false;
      const y = window.scrollY;
      vel = vel * 0.8 + (y - lastY) * 0.2;
      lastY = y;

      const p = progress();
      const tinggi = monyet.offsetHeight;
      const atas = 96;
      const top = atas + p * Math.max(0, window.innerHeight - tinggi - atas - 24);
      monyet.style.setProperty("--y", top.toFixed(1) + "px");
      monyet.style.setProperty("--tilt", clamp(-vel * 0.35, -14, 14).toFixed(1) + "deg");

      const kiri = sisiKiri();
      box.classList.toggle("kiri", kiri);

      // kupu-kupu: sisi sebaliknya, bergerak naik saat orangutan turun, sambil meliuk
      if (kupu) {
        const W = window.innerWidth;
        const x = (kiri ? W - 64 : 16) + Math.sin(p * 40) * 20;
        const ky = 70 + (1 - p) * (window.innerHeight - 170);
        kupu.style.transform =
          "translate3d(" + x.toFixed(1) + "px," + ky.toFixed(1) + "px,0) rotate(" +
          (Math.cos(p * 40) * 14).toFixed(1) + "deg)";
      }

      box.classList.add("is-moving");
      clearTimeout(timer);
      timer = setTimeout(() => box.classList.remove("is-moving"), 180);
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(update);
    }

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);
    update();
    box.classList.remove("is-moving");
  }

  function init() {
    setupIntro();
    setupReveal();
    setupDonut();
    setupPendamping();
  }

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
  else init();
})();
