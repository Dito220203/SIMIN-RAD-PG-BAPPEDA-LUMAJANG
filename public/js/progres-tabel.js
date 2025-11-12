// Gunakan $(document).ready() untuk memastikan DOM
// dan jQuery sudah siap sebelum skrip dijalankan.
$(document).ready(function() {

    // 1. Inisialisasi DataTables pada tabel dengan id #tabelSaya
    const dataTable = $('#tabelSaya').DataTable({
        "paging": true,       // Aktifkan paginasi (halaman)
        "lengthChange": true, // Aktifkan "show X entries"
        "ordering": true,     // Aktifkan sorting (mengurutkan)
        "info": true,         // Aktifkan info "Showing X to Y..."
        "autoWidth": false,
        "responsive": true,

        // 'l' = length, 'r' = processing, 't' = table, 'i' = info, 'p' = paging
        "dom": 'lrtip', // kita pakai custom search, jadi 'f' dihapus

        "language": {
            "search": "Cari:",
            "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ entri",
            "infoEmpty": "Tidak ada data untuk ditampilkan",
            "infoFiltered": "(disaring dari _MAX_ total entri)",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": ">",
                "previous": "<"
            }
        }
    });

    // 2. Hubungkan input search custom (#liveSearchInput) ke API DataTables
    $('#liveSearchInput').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    // 3. Pindahkan dropdown "Show entries" ke dalam header card
    $('#tabelSaya_length').appendTo('#lengthContainer');
});
