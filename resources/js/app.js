import Alpine from 'alpinejs';

// Alpine dipakai komponen Breeze (dropdown, modal, menu mobile).
window.Alpine = Alpine;
Alpine.start();

// Tombol x pada <x-status-banner bisa-ditutup>: hapus banner yang berisi tombol tersebut.
document.addEventListener('click', (event) => {
    const tombol = event.target.closest('[data-tutup-banner]');

    if (tombol) {
        tombol.closest('[data-status-banner]').remove();
    }
});
