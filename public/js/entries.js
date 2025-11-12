document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".entriesSelect").forEach(select => {
        select.addEventListener("change", function () {
            const tableId = this.dataset.target;
            applyFilter(tableId);
        });

        // langsung jalanin pas load awal
        applyFilter(select.dataset.target);
    });
});

function applyFilter(tableId) {
    const select = document.querySelector(`.entriesSelect[data-target="${tableId}"]`);
    const limit = parseInt(select.value, 10);

    const searchInput = document.querySelector(`.searchInput[data-target="${tableId}"]`);
    const searchValue = searchInput ? searchInput.value.toLowerCase() : "";

    const rows = document.querySelectorAll(`#${tableId} tbody tr`);
    let shown = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const match = searchValue === "" || text.includes(searchValue);

        if (match && shown < limit) {
            row.style.display = "";
            shown++;
        } else {
            row.style.display = "none";
        }
    });
}
