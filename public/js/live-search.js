// Tunggu sampai seluruh dokumen HTML dimuat
document.addEventListener('DOMContentLoaded', function() {

    // 1. Ambil elemen input pencarian
    const searchInput = document.getElementById('liveSearchInput');

    // 2. Ambil semua baris data
    // Pastikan ID 'dataTabelBody' sesuai dengan <tbody> Anda
    const dataRows = document.querySelectorAll('#dataTabelBody tr');

    // Jika tidak ada dataRows (mungkin halaman kosong), hentikan script
    if (!dataRows || dataRows.length === 0) {
        return;
    }

    // Jika input tidak ditemukan, hentikan script
    if (!searchInput) {
        console.warn('Elemen input dengan ID "liveSearchInput" tidak ditemukan.');
        return;
    }

    // 3. Tambahkan event listener 'input' pada kotak pencarian
    searchInput.addEventListener('input', function() {
        // Ambil nilai input, ubah ke huruf kecil untuk pencarian case-insensitive
        const filter = this.value.toLowerCase();

        // 4. Loop setiap baris data (tr)
        dataRows.forEach(row => {
            // Ambil seluruh teks dari baris tersebut, ubah ke huruf kecil
            const rowText = row.textContent.toLowerCase();

            // 5. Periksa apakah teks baris mengandung teks filter
            if (rowText.includes(filter)) {
                // Jika ya, tampilkan barisnya
                row.style.display = ''; // '' mengembalikan ke display default (biasanya 'table-row')
            } else {
                // Jika tidak, sembunyikan barisnya
                row.style.display = 'none';
            }
        });
    });
});
