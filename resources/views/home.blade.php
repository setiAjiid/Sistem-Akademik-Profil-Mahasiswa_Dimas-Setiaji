@extends('layouts.app')

@section('title', 'Home')

@section('content')
    {{-- Rute 1: sambutan dan profil singkat --}}
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
                <a class="tombol" href="{{ route('mahasiswa.show', ['nrp' => $mahasiswa['nrp']]) }}">Lihat profil lengkap</a>
                <a class="tombol sekunder" href="{{ route('dashboard.index') }}">Buka dashboard</a>
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
        <div class="kotak">
            <h2>Tentang saya</h2>
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
        </div>

        <div class="kotak">
            <h2>Ide proyek akhir</h2>
            <h3>TokenPilot</h3>
            <p>
                Platform Agentic AI yang mengatur konteks agar AI coding bekerja lebih terarah
                dan hemat token.
            </p>
            <p><a href="{{ route('agent.show', ['tema' => 'tokenpilot']) }}">Baca penjelasan lengkap</a></p>
        </div>
    </div>
@endsection
