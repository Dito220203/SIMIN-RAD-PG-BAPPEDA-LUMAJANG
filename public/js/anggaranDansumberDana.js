document.addEventListener("DOMContentLoaded", function () {
    // --- Elemen-elemen yang dibutuhkan ---
    const modalElement = document.getElementById('anggaranModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const modalForm = document.getElementById('modal-anggaran-form');
    const anggaranInput = document.getElementById('modal-anggaran');
    const sumberDanaSelect = document.getElementById('modal-sumberdana');
    const lainnyaContainer = document.getElementById('modal-sumberdana-lainnya-container');
    const lainnyaInput = document.getElementById('modal-sumberdana-lainnya');
    const addButton = document.getElementById('tambah-ke-tabel');
    const tableBody = document.getElementById('anggaran-table-body');
    const hiddenContainer = document.getElementById('hidden-inputs-container');

    // --- Fungsi Helper untuk Format Rupiah ---
    const formatRupiah = (input) => {
        let value = input.value.replace(/\D/g, '');
        return value ? 'Rp. ' + parseInt(value).toLocaleString('id-ID') : '';
    };

    // --- Event Listener untuk Modal ---
    anggaranInput.addEventListener('input', () => {
        anggaranInput.value = formatRupiah({
            value: anggaranInput.value
        });
    });

    sumberDanaSelect.addEventListener('change', () => {
        if (sumberDanaSelect.value === 'Lainnya') {
            lainnyaContainer.style.display = 'block';
            lainnyaInput.required = true;
        } else {
            lainnyaContainer.style.display = 'none';
            lainnyaInput.required = false;
        }
    });

    // --- Logika saat tombol "Tambah ke Tabel" di Modal diklik ---
    addButton.addEventListener('click', () => {
        if (!modalForm.checkValidity()) {
            modalForm.reportValidity();
            return;
        }

        // --- Blok Kode yang Diubah ---
        const anggaranFormatted = anggaranInput.value; // <-- PERUBAHAN: Ambil nilai asli yang sudah diformat
        const anggaranValueOnly = anggaranInput.value.replace(/\D/g, ''); // <-- PERUBAHAN: Buat versi angka murni hanya untuk validasi
        // --- Akhir Blok Kode yang Diubah ---

        let sumberDanaValue = sumberDanaSelect.value;
        const sumberDanaText = sumberDanaValue === 'Lainnya' ? lainnyaInput.value : sumberDanaSelect.options[sumberDanaSelect.selectedIndex].text;

        if (sumberDanaValue === 'Lainnya') {
            sumberDanaValue = lainnyaInput.value;
        }

        // Validasi menggunakan versi angka murni
        if (!anggaranValueOnly || !sumberDanaValue) { // <-- PERUBAHAN: Menggunakan anggaranValueOnly
            alert('Anggaran dan Sumber Dana harus diisi.');
            return;
        }

        const uniqueId = 'row-' + Date.now();

        const newRow = `
            <tr id="${uniqueId}">
                <td class="text-center">${anggaranFormatted}</td>
                <td class="text-center">${sumberDanaText}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm hapus-anggaran-row" data-target="${uniqueId}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);

        const newHiddenInputs = `
            <div id="hidden-${uniqueId}">
                <input type="hidden" name="anggaran[]" value="${anggaranFormatted}">
                <input type="hidden" name="sumberdana[]" value="${sumberDanaValue}">
            </div>
        `; // <-- PERUBAHAN: 'value' dari input anggaran menggunakan anggaranFormatted
        hiddenContainer.insertAdjacentHTML('beforeend', newHiddenInputs);

        modalForm.reset();
        lainnyaContainer.style.display = 'none';
        lainnyaInput.required = false;
        modal.hide();
    });

    // --- Logika Hapus Baris (Event Delegation) ---
    tableBody.addEventListener('click', (e) => {
        const deleteButton = e.target.closest('.hapus-anggaran-row');
        if (deleteButton) {
            const targetId = deleteButton.dataset.target;
            document.getElementById(targetId)?.remove();
            document.getElementById('hidden-' + targetId)?.remove();
        }
    });
});
