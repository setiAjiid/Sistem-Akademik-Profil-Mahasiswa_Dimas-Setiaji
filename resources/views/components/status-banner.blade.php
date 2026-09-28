{{--
    Banner notifikasi.
    Pemakaian: <x-status-banner tipe="success" judul="Berhasil" bisa-ditutup> pesan </x-status-banner>
    - tipe         : success | error | warning | info, menentukan warna (class Tailwind dipilih dinamis)
    - judul        : opsional, teks tebal di atas pesan
    - bisaDitutup  : opsional, menampilkan tombol x (ditangani resources/js/app.js)
--}}
@props(['tipe' => 'info', 'judul' => null, 'bisaDitutup' => false])

@php
    // Pemetaan tipe ke class Tailwind. Varian dark: dipakai saat <html class="dark"> (?mode=dark).
    $warna = [
        'success' => 'border-green-500 bg-green-50 text-green-900 dark:bg-green-500/10 dark:text-green-200',
        'error' => 'border-red-500 bg-red-50 text-red-900 dark:bg-red-500/10 dark:text-red-200',
        'warning' => 'border-yellow-500 bg-yellow-50 text-yellow-900 dark:bg-yellow-500/10 dark:text-yellow-200',
        'info' => 'border-blue-500 bg-blue-50 text-blue-900 dark:bg-blue-500/10 dark:text-blue-200',
    ][$tipe] ?? 'border-gray-500 bg-gray-50 text-gray-900 dark:bg-gray-500/10 dark:text-gray-200';

    $ikon = ['success' => '✓', 'error' => '!', 'warning' => '!', 'info' => 'i'][$tipe] ?? 'i';
@endphp

<div
    role="{{ $tipe === 'error' ? 'alert' : 'status' }}"
    data-status-banner
    {{ $attributes->class(['mb-6 flex items-start gap-3 rounded-lg border-l-4 p-4 shadow-sm', $warna]) }}
>
    <span class="grid size-6 shrink-0 place-items-center rounded-full bg-current/15 text-sm font-extrabold">{{ $ikon }}</span>

    <div class="flex-1 text-sm [&_p]:mb-1 [&>:last-child]:mb-0">
        @if ($judul)
            <p class="font-bold">{{ $judul }}</p>
        @endif
        {{ $slot }}
    </div>

    @if ($bisaDitutup)
        <button type="button" data-tutup-banner aria-label="Tutup notifikasi"
            class="-my-1 cursor-pointer rounded px-2 text-lg leading-none opacity-60 hover:opacity-100">&times;</button>
    @endif
</div>
