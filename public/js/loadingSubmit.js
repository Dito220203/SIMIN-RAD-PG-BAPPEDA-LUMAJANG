$(document).ready(function() {
    // Dengarkan event 'submit' pada semua form di halaman
    $('form').on('submit', function() {
        // Cari tombol dengan type="submit" di dalam form yang sedang di-submit
        const submitButton = $(this).find('button[type="submit"]');

        // Jika tombol submit ditemukan
        if (submitButton.length) {

            // Simpan teks asli tombol jika belum disimpan
            if (!submitButton.data('original-text')) {
                submitButton.data('original-text', submitButton.html());
            }

            // Nonaktifkan tombol untuk mencegah klik ganda
            submitButton.prop('disabled', true);

            // Tambahkan kelas penanda
            submitButton.addClass('is-loading');

            // Ganti isi tombol dengan spinner dan teks "Memproses..."
            submitButton.html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`
            );
        }
    });
});
