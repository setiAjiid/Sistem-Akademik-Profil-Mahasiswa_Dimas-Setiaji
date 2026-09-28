@extends('layouts.app')

@section('title', 'Ide Riset Agentic AI')

@section('content')
    <h1>Ide Riset: {{ $agent['nama'] }}</h1>
    <p class="subjudul">{{ $agent['tagline'] }}</p>

    {{-- Challenge 1: $modeGelap berasal dari ?mode=dark, lihat AppServiceProvider. --}}
    <x-status-banner tipe="info" :judul="$modeGelap ? 'Mode gelap aktif' : 'Mode terang aktif'">
        <p>
            Tema halaman diatur parameter <code>?mode=dark</code> di URL.
            <a href="{{ $urlGantiMode }}" class="font-semibold underline">
                {{ $modeGelap ? 'Kembali ke mode terang' : 'Coba mode gelap' }}
            </a>
        </p>
    </x-status-banner>

    {{-- Hasil kirim form: pesan sukses dari session flash, atau daftar error validasi. --}}
    @if (session('status'))
        <x-status-banner tipe="success" judul="Ide terkirim" bisa-ditutup>
            <p>{{ session('status') }}</p>
        </x-status-banner>
    @endif

    @if ($errors->any())
        <x-status-banner tipe="error" judul="Ide belum terkirim, periksa lagi isian berikut:">
            <ul class="mt-1 mb-0 list-disc pl-5">
                @foreach ($errors->all() as $pesan)
                    <li class="mb-0">{{ $pesan }}</li>
                @endforeach
            </ul>
        </x-status-banner>
    @endif

    {{-- Visualisasi rancangan platform: aliran tugas dari pengguna sampai laporan. --}}
    <x-info-card judul="Rancangan platform" label="alur satu tugas" class="mb-6">
        <ol class="m-0 grid list-none gap-3 p-0 md:grid-cols-5">
            @foreach ($agent['rancangan'] as $i => $bagian)
                <li class="relative m-0 rounded-xl border border-(--garis) bg-(--panel-tebal) p-4">
                    <span class="mb-2 grid size-7 place-items-center rounded-lg bg-(--emas) text-xs font-extrabold text-[#1a1204]">{{ $i + 1 }}</span>
                    <div class="font-bold">{{ $bagian['aktor'] }}</div>
                    <div class="text-sm leading-snug text-(--teks-redup)">{{ $bagian['peran'] }}</div>

                    {{-- Panah ke kotak berikutnya: ke kanan di layar lebar, ke bawah di layar kecil. --}}
                    @unless ($loop->last)
                        <span aria-hidden="true" class="absolute -bottom-3 left-1/2 z-10 -translate-x-1/2 text-(--emas-teks) md:top-1/2 md:-right-3 md:bottom-auto md:left-auto md:translate-x-0 md:-translate-y-1/2">
                            <span class="md:hidden">&darr;</span><span class="hidden md:inline">&rarr;</span>
                        </span>
                    @endunless
                </li>
            @endforeach
        </ol>
        <p class="mt-4 text-sm text-(--teks-redup)">
            Kalau test gagal, agent menambah konteks lalu mengulang langkah 2 sampai 4, paling banyak dua kali.
        </p>
    </x-info-card>

    <div class="mb-6 grid gap-6 md:grid-cols-2">
        <x-info-card judul="Masalah yang diselesaikan">
            <p>{{ $agent['masalah'] }}</p>
        </x-info-card>

        <x-info-card judul="Pembagian peran AI">
            <table class="info">
                @foreach ($agent['pembagian'] as $bagian => $tugas)
                    <tr><th>{{ $bagian }}</th><td>{{ $tugas }}</td></tr>
                @endforeach
            </table>
        </x-info-card>
    </div>

    <x-info-card judul="Teknologi yang dipakai" class="mb-6">
        <table class="info">
            @foreach ($agent['tech_stack'] as $teknologi => $kegunaan)
                <tr><th>{{ $teknologi }}</th><td>{{ $kegunaan }}</td></tr>
            @endforeach
        </table>

        <x-slot:aksi>
            <a href="{{ route('agent.show', ['tema' => 'tokenpilot'] + $kueriMode) }}">Penjelasan lengkap TokenPilot (Tugas 2) &rarr;</a>
        </x-slot:aksi>
    </x-info-card>

    {{-- Formulir pengumpulan ide. @csrf wajib untuk POST (materi Pertemuan 3). --}}
    <x-info-card judul="Kirim ide agent" label="semua kolom wajib diisi" id="form-ide" class="mb-6">
        <form method="POST" action="{{ route('ide-agent.kirim', $kueriMode) }}" class="grid gap-4 sm:grid-cols-2">
            @csrf

            <label class="flex flex-col gap-1.5 text-sm font-semibold text-(--teks-redup)">
                Nama
                <input type="text" name="nama" value="{{ old('nama') }}" maxlength="60" required
                    @class(['w-full', 'border-red-500' => $errors->has('nama')])>
            </label>

            <label class="flex flex-col gap-1.5 text-sm font-semibold text-(--teks-redup)">
                Kategori
                <select name="kategori" required @class(['w-full', 'border-red-500' => $errors->has('kategori')])>
                    <option value="">Pilih kategori</option>
                    @foreach ($kategori as $kunci => $namaKategori)
                        <option value="{{ $kunci }}" @selected(old('kategori') === $kunci)>{{ $namaKategori }}</option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-1.5 text-sm font-semibold text-(--teks-redup) sm:col-span-2">
                Judul ide
                <input type="text" name="judul" value="{{ old('judul') }}" maxlength="100" required
                    placeholder="Contoh: Agent peninjau pull request otomatis"
                    @class(['w-full', 'border-red-500' => $errors->has('judul')])>
            </label>

            <label class="flex flex-col gap-1.5 text-sm font-semibold text-(--teks-redup) sm:col-span-2">
                Deskripsi singkat
                <textarea name="deskripsi" rows="4" maxlength="1000" required
                    placeholder="Masalah apa yang diselesaikan dan bagaimana agent bekerja (minimal 20 karakter)"
                    @class(['w-full resize-y', 'border-red-500' => $errors->has('deskripsi')])>{{ old('deskripsi') }}</textarea>
            </label>

            <div class="sm:col-span-2">
                <button type="submit" class="tombol">Kirim ide</button>
            </div>
        </form>
    </x-info-card>

    @if (count($ideTerkirim) > 0)
        <x-info-card judul="Ide yang sudah kamu kirim" :label="count($ideTerkirim) . ' ide, tersimpan di session'">
            <div class="grid gap-4">
                @foreach ($ideTerkirim as $ide)
                    <article class="rounded-xl border border-(--garis) bg-(--panel-tebal) p-4">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <span class="font-bold">{{ $ide['judul'] }}</span>
                            <span class="chip m-0">{{ $kategori[$ide['kategori']] ?? $ide['kategori'] }}</span>
                        </div>
                        <p class="mb-1 text-sm">{{ $ide['deskripsi'] }}</p>
                        <div class="text-xs text-(--teks-redup)">oleh {{ $ide['nama'] }}, {{ $ide['waktu'] }}</div>
                    </article>
                @endforeach
            </div>
        </x-info-card>
    @endif
@endsection
