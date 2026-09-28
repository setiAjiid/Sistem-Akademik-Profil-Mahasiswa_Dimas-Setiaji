@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Challenge 2: /beranda?user=Andi. $namaUser null kalau parameter user tidak ada. --}}
    @if ($namaUser)
        <x-status-banner tipe="success" :judul="'Selamat datang, ' . $namaUser . '!'" bisa-ditutup>
            <p>Terima kasih sudah mampir ke profil akademik saya. Silakan lihat profil lengkap atau ide riset Agentic AI kelompok kami.</p>
        </x-status-banner>
    @endif

    {{-- Sambutan dan profil singkat --}}
    <section class="hero">
        <div class="avatar foto">
            <img src="{{ asset($mahasiswa['foto']) }}" alt="Foto {{ $mahasiswa['nama'] }}">
        </div>
        <div>
            <span class="sapaan">Vivat ITS! Selamat datang di profil akademik saya.</span>
            <h1>{{ $mahasiswa['nama'] }}</h1>
            <p class="meta">
                <span class="mono">{{ $mahasiswa['nrp'] }}</span>
                &nbsp;|&nbsp; {{ $mahasiswa['departemen'] }}, {{ $mahasiswa['fakultas'] }}
                &nbsp;|&nbsp; Angkatan {{ $mahasiswa['angkatan'] }}
            </p>
            <div>
                @foreach ($mahasiswa['minat'] as $minat)
                    <span class="chip">{{ $minat }}</span>
                @endforeach
            </div>
            <div class="tautan-tombol">
                <a class="tombol" href="{{ route('profil', $kueriMode) }}">Lihat profil lengkap</a>
                <a class="tombol sekunder" href="{{ route('ide-agent', $kueriMode) }}">Ide riset agent</a>
            </div>
        </div>
    </section>

    <div class="statistik">
        <div class="kotak">
            <div class="angka">{{ number_format($ringkasan['ipk'], 2) }}</div>
            <div class="label">IPK sementara</div>
        </div>
        <div class="kotak">
            <div class="angka">{{ $ringkasan['totalSks'] }}</div>
            <div class="label">SKS lulus</div>
        </div>
        <div class="kotak">
            <div class="angka">{{ $mahasiswa['semester'] }}</div>
            <div class="label">Semester berjalan</div>
        </div>
    </div>

    <div class="dua-kolom">
        <x-info-card judul="Tentang saya">
            <p>
                Mahasiswa {{ $mahasiswa['departemen'] }} ITS angkatan {{ $mahasiswa['angkatan'] }}
                asal {{ $mahasiswa['asal'] }}. {{ $mahasiswa['ringkasan'] }}
            </p>
            <p>Saat ini aktif sebagai:</p>
            <ul>
                @foreach ($mahasiswa['peran'] as $peran)
                    <li>{{ $peran['nama'] }}</li>
                @endforeach
            </ul>
        </x-info-card>

        <x-info-card judul="Ide proyek akhir" label="Agentic AI">
            <h3>TokenPilot</h3>
            <p>
                Platform Agentic AI yang mengatur konteks agar AI coding bekerja lebih terarah
                dan hemat token.
            </p>

            <x-slot:aksi>
                <a href="{{ route('ide-agent', $kueriMode) }}">Lihat rancangan dan kirim ide &rarr;</a>
            </x-slot:aksi>
        </x-info-card>
    </div>
@endsection
