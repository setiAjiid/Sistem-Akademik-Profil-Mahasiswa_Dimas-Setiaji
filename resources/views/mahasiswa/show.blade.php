@extends('layouts.app')

@section('title', $mahasiswa['nama'])

@section('content')
    {{-- Rute 2: profil lengkap pemilik NRP --}}
    <section class="hero">
        <div class="avatar foto kecil">
            <img src="{{ asset($mahasiswa['foto']) }}" alt="Foto {{ $mahasiswa['nama'] }}">
        </div>
        <div>
            <h1>{{ $mahasiswa['nama'] }}</h1>
            <p class="meta">
                NRP <span class="mono">{{ $mahasiswa['nrp'] }}</span>
                &nbsp;|&nbsp; {{ $mahasiswa['departemen'] }}, {{ $mahasiswa['fakultas'] }}
            </p>
            <span class="status">Mahasiswa aktif, semester {{ $mahasiswa['semester'] }}</span>
        </div>
    </section>

    <div class="dua-kolom">
        <div class="kotak">
            <h2>Data diri</h2>
            <table class="info">
                <tr><th>Angkatan</th><td>{{ $mahasiswa['angkatan'] }}</td></tr>
                <tr><th>Kelas</th><td>{{ $mahasiswa['kelas'] }}</td></tr>
                <tr><th>Asal</th><td>{{ $mahasiswa['asal'] }}</td></tr>
                <tr><th>Email</th><td><a href="mailto:{{ $mahasiswa['email'] }}">{{ $mahasiswa['email'] }}</a></td></tr>
                <tr><th>LinkedIn</th><td><a href="{{ $mahasiswa['linkedin'] }}" target="_blank" rel="noopener">dimas-setiaji</a></td></tr>
            </table>

            <h2 style="margin-top: 28px">Minat</h2>
            <div>
                @foreach ($mahasiswa['minat'] as $minat)
                    <span class="chip">{{ $minat }}</span>
                @endforeach
            </div>

            <h2 style="margin-top: 22px">Keahlian</h2>
            <div>
                @foreach ($mahasiswa['keahlian'] as $keahlian)
                    <span class="chip">{{ $keahlian }}</span>
                @endforeach
            </div>
        </div>

        <div class="kotak">
            <h2>Ringkasan studi</h2>
            <table class="info">
                <tr><th>IPK</th><td>{{ number_format($ringkasan['ipk'], 2) }}</td></tr>
                <tr><th>SKS lulus</th><td>{{ $ringkasan['totalSks'] }}</td></tr>
                <tr><th>Semester selesai</th><td>{{ $ringkasan['jumlahSemester'] }}</td></tr>
            </table>

            <h2 style="margin-top: 28px">Pengalaman</h2>
            <ul>
                @foreach ($mahasiswa['peran'] as $peran)
                    <li>
                        {{ $peran['nama'] }}
                        <div style="color: var(--teks-redup); font-size: 0.88rem">{{ $peran['tempat'] }}, {{ $peran['waktu'] }}</div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="kotak" style="margin-top: 24px">
        <h2>Prestasi</h2>
        <table>
            @foreach ($mahasiswa['prestasi'] as $prestasi)
                <tr>
                    <th style="width: 70px">{{ $prestasi['tahun'] }}</th>
                    <td>{{ $prestasi['nama'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="kotak">
        <h2>Riwayat studi</h2>
        <table>
            <thead>
                <tr><th>Semester</th><th>Tahun ajaran</th><th>SKS</th><th>IP</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $s)
                    <tr>
                        <td>{{ $s['semester'] }}</td>
                        <td>{{ $s['tahun'] }}</td>
                        <td>{{ $s['sks'] }}</td>
                        @if ($s['ip'] !== null)
                            <td>{{ number_format($s['ip'], 2) }}</td>
                            {{-- lebar bar = IP dibagi nilai maksimum 4 --}}
                            <td><div class="bar"><span style="width: {{ $s['ip'] / 4 * 100 }}%"></span></div></td>
                        @else
                            <td colspan="2"><span class="status">Sedang berjalan</span></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 18px">
            <a href="{{ route('dashboard.riwayat') }}">Lihat mata kuliah tiap semester</a>
        </p>
    </div>
@endsection
