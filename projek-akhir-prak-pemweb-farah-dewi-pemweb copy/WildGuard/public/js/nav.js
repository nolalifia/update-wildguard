// Menu mobile untuk halaman selain beranda (di beranda sudah ditangani wildguard.js)
(function () {
  var toggle = document.getElementById("nav-toggle");
  var menu = document.getElementById("nav-menu");
  if (!toggle || !menu) return;
  function setOpen(open) {
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Tutup menu" : "Buka menu");
    document.body.classList.toggle("nav-open", open);
  }
  toggle.addEventListener("click", function () {
    setOpen(toggle.getAttribute("aria-expanded") !== "true");
  });
  menu.addEventListener("click", function (e) {
    if (e.target.closest("a")) setOpen(false);
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") setOpen(false);
  });
  window.addEventListener("scroll", function () {
    var nb = document.querySelector(".navbar");
    if (nb) nb.classList.toggle("is-scrolled", window.scrollY > 10);
  }, { passive: true });
})();
