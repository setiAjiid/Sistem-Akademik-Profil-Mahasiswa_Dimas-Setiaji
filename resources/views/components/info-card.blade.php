{{--
    Kartu informasi profil.
    Pemakaian: <x-info-card judul="Data diri" class="md:col-span-2"> ...isi... <x-slot:aksi>...</x-slot:aksi> </x-info-card>
    - judul : prop wajib, tampil sebagai heading kartu
    - label : prop opsional, teks kecil di kanan heading (mis. "4 semester")
    - slot  : isi kartu
    - aksi  : named slot opsional untuk tautan di bagian bawah
    - atribut lain (class, id, ...) digabung ke <section> lewat $attributes
    Kartu tidak punya margin sendiri; jarak antar kartu diatur pembungkusnya (grid/gap).
--}}
@props(['judul', 'label' => null])

<section {{ $attributes->class([
    'rounded-2xl border border-(--garis) bg-(--panel) p-6 shadow-sm sm:p-8',
]) }}>
    <div class="mb-4 flex items-baseline justify-between gap-3">
        <h2 class="mb-0">{{ $judul }}</h2>
        @if ($label)
            <span class="text-xs font-semibold text-(--teks-redup)">{{ $label }}</span>
        @endif
    </div>

    <div class="[&>:last-child]:mb-0">
        {{ $slot }}
    </div>

    @isset($aksi)
        <div class="mt-5 border-t border-(--garis) pt-4 text-sm font-semibold">
            {{ $aksi }}
        </div>
    @endisset
</section>
