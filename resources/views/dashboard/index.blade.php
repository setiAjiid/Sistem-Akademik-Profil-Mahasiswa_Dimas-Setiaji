@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Challenge 3: rute di dalam prefix /dashboard --}}
    <h1>Dashboard</h1>
    <p class="subjudul">Ringkasan studi {{ $mahasiswa['nama'] }}.</p>

    <div class="statistik">
        <div class="kotak">
            <div class="angka">{{ number_format($ringkasan['ipk'], 2) }}</div>
            <div class="label">IPK</div>
        </div>
        <div class="kotak">
            <div class="angka">{{ $ringkasan['totalSks'] }}</div>
            <div class="label">SKS lulus</div>
        </div>
        <div class="kotak">
            <div class="angka">{{ $ringkasan['jumlahSemester'] }}</div>
            <div class="label">Semester selesai</div>
        </div>
    </div>

    <div class="kotak">
        <h2>Progres kelulusan</h2>
        <p>
            {{ $ringkasan['totalSks'] }} dari {{ $sksLulus }} SKS
            ({{ number_format($ringkasan['totalSks'] / $sksLulus * 100) }}%).
        </p>
        <div class="bar"><span style="width: {{ $ringkasan['totalSks'] / $sksLulus * 100 }}%"></span></div>
    </div>

    <div class="dua-kolom">
        <div class="kotak">
            <h2>Riwayat studi</h2>
            <p>Daftar mata kuliah dan IP tiap semester.</p>
            <a class="tombol sekunder" href="{{ route('dashboard.riwayat') }}">Buka riwayat</a>
        </div>
        <div class="kotak">
            <h2>Kalkulator IPK</h2>
            <p>Hitung jumlah dan rata-rata IP dua semester.</p>
            <a class="tombol sekunder" href="{{ route('ipk.hitung', ['ip1' => '3.85', 'ip2' => '3.90']) }}">Buka kalkulator</a>
        </div>
    </div>
@endsection
