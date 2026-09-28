// Tombol x pada <x-status-banner bisa-ditutup>: hapus banner yang berisi tombol tersebut.
document.addEventListener('click', (event) => {
    const tombol = event.target.closest('[data-tutup-banner]');

    if (tombol) {
        tombol.closest('[data-status-banner]').remove();
    }
});
