@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('content')
    <section class="hero">
        <div class="avatar foto">
            <img src="{{ asset($mahasiswa['foto']) }}" alt="Foto {{ $mahasiswa['nama'] }}">
        </div>
        <div>
            <span class="sapaan">Profil mahasiswa</span>
            <h1>{{ $mahasiswa['nama'] }}</h1>
            <p class="meta">
                NRP <span class="mono">{{ $mahasiswa['nrp'] }}</span>
                &nbsp;|&nbsp; {{ $mahasiswa['departemen'] }}, {{ $mahasiswa['fakultas'] }}
            </p>
            <span class="status">Mahasiswa aktif, semester {{ $mahasiswa['semester'] }}</span>
        </div>
    </section>

    {{-- Setiap blok data profil memakai komponen <x-info-card>. --}}
    <div class="grid gap-6 md:grid-cols-2">
        <x-info-card judul="Data diri">
            <table class="info">
                <tr><th>Angkatan</th><td>{{ $mahasiswa['angkatan'] }}</td></tr>
                <tr><th>Kelas</th><td>{{ $mahasiswa['kelas'] }}</td></tr>
                <tr><th>Asal</th><td>{{ $mahasiswa['asal'] }}</td></tr>
                <tr><th>Email</th><td><a href="mailto:{{ $mahasiswa['email'] }}">{{ $mahasiswa['email'] }}</a></td></tr>
                <tr><th>LinkedIn</th><td><a href="{{ $mahasiswa['linkedin'] }}" target="_blank" rel="noopener">dimas-setiaji</a></td></tr>
            </table>
        </x-info-card>

        <x-info-card judul="Ringkasan studi" :label="$ringkasan['jumlahSemester'] . ' semester selesai'">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-3xl font-extrabold text-(--emas-teks)">{{ number_format($ringkasan['ipk'], 2) }}</div>
                    <div class="text-sm text-(--teks-redup)">IPK sementara</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-(--emas-teks)">{{ $ringkasan['totalSks'] }}</div>
                    <div class="text-sm text-(--teks-redup)">SKS lulus</div>
                </div>
            </div>

            <x-slot:aksi>
                <a href="{{ route('dashboard.riwayat', $kueriMode) }}">Lihat riwayat studi per semester &rarr;</a>
            </x-slot:aksi>
        </x-info-card>

        <x-info-card judul="Minat dan keahlian">
            <p class="text-sm text-(--teks-redup)">Minat</p>
            <div class="mb-4">
                @foreach ($mahasiswa['minat'] as $minat)
                    <span class="chip">{{ $minat }}</span>
                @endforeach
            </div>

            <p class="text-sm text-(--teks-redup)">Keahlian</p>
            <div>
                @foreach ($mahasiswa['keahlian'] as $keahlian)
                    <span class="chip">{{ $keahlian }}</span>
                @endforeach
            </div>
        </x-info-card>

        <x-info-card judul="Pengalaman">
            <ul class="list-none pl-0">
                @foreach ($mahasiswa['peran'] as $peran)
                    <li class="mb-4 border-l-2 border-(--emas) pl-4 last:mb-0">
                        <div class="font-semibold">{{ $peran['nama'] }}</div>
                        <div class="text-sm text-(--teks-redup)">{{ $peran['tempat'] }}, {{ $peran['waktu'] }}</div>
                    </li>
                @endforeach
            </ul>
        </x-info-card>

        <x-info-card judul="Prestasi" :label="count($mahasiswa['prestasi']) . ' prestasi'" class="md:col-span-2">
            <table>
                @foreach ($mahasiswa['prestasi'] as $prestasi)
                    <tr>
                        <th class="w-20">{{ $prestasi['tahun'] }}</th>
                        <td>{{ $prestasi['nama'] }}</td>
                    </tr>
                @endforeach
            </table>
        </x-info-card>
    </div>
@endsection
