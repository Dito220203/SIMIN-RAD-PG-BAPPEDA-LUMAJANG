// public/assets/js/preload.js

const preloader = document.getElementById('preloader');
const body = document.body;

// Sembunyikan preloader HANYA setelah SEMUA aset (gambar, css, dll) selesai dimuat
window.addEventListener('load', () => {
  if (preloader) {
    // Tambahkan class 'loaded' untuk memicu transisi fade-out di CSS
    preloader.classList.add('loaded');
  }
  if (body) {
    // Hapus class 'preload-active' dari body agar main content bisa muncul (jika pakai CSS opsional)
    body.classList.remove('preload-active');
  }
});

// (Hapus atau komentari bagian event listener untuk klik link jika hanya fokus pada FOUC awal)
/*
document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])').forEach(link => {
  link.addEventListener('click', (event) => {
    if (!event.ctrlKey && !event.metaKey && link.getAttribute('href') !== 'javascript:void(0);') {
      if (preloader && !preloader.classList.contains('loaded')) { // Cek jika preloader masih ada
         // Jika ingin preloader muncul lagi saat pindah halaman, hapus class 'loaded'
         // preloader.classList.remove('loaded'); // Hati-hati, ini bisa menyebabkan preloader muncul lagi sesaat
      }
    }
  });
});
*/

// Tetap pertahankan bagian pageshow untuk handle tombol back/forward
window.addEventListener('pageshow', function(event) {
    // Jika halaman dari bfcache dan preloader masih terlihat (belum ada class 'loaded'), sembunyikan
    if (event.persisted && preloader && !preloader.classList.contains('loaded')) {
        preloader.classList.add('loaded');
        if (body) {
          body.classList.remove('preload-active');
        }
    }
});