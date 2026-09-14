@extends('layouts.app')

@section('title', 'Ide Platform Agentic AI')

@section('content')
    {{-- Rute 3: /agent/{tema?} --}}

    @if ($tema === null)
        {{-- Parameter tema kosong: tampilkan nilai default --}}
        <h1>{{ $default }}</h1>
        <p class="subjudul">Tidak ada tema yang dipilih, jadi halaman ini menampilkan tema bawaan.</p>

        <div class="kotak">
            <p>
                Tema yang tersedia saat ini adalah TokenPilot, ide proyek akhir kelompok kami.
            </p>
            <div class="tautan-tombol">
                <a class="tombol" href="{{ route('agent.show', ['tema' => 'tokenpilot']) }}">Lihat TokenPilot</a>
            </div>
        </div>

    @elseif ($detail === null)
        {{-- Tema diisi tetapi tidak dikenal --}}
        <h1>Tema tidak dikenal</h1>
        <p class="subjudul">Belum ada penjelasan untuk tema <code>{{ $tema }}</code>.</p>

        <div class="kotak">
            <p>Tema yang tersedia saat ini adalah TokenPilot.</p>
            <div class="tautan-tombol">
                <a class="tombol" href="{{ route('agent.show', ['tema' => 'tokenpilot']) }}">Lihat TokenPilot</a>
                <a class="tombol sekunder" href="{{ route('agent.show') }}">Tema bawaan</a>
            </div>
        </div>

    @else
        {{-- Tema dikenal: tampilkan penjelasannya --}}
        <h1>{{ $detail['nama'] }}</h1>
        <p class="subjudul">{{ $detail['tagline'] }}</p>

        <div class="dua-kolom" style="margin-bottom: 24px">
            <div class="kotak">
                <h2>Apa itu {{ $detail['nama'] }}</h2>
                <p>{{ $detail['deskripsi'] }}</p>
            </div>
            <div class="kotak">
                <h2>Masalah yang diselesaikan</h2>
                <p>{{ $detail['masalah'] }}</p>
            </div>
        </div>

        <div class="kotak">
            <h2>Cara kerja</h2>
            <ol class="langkah">
                @foreach ($detail['alur'] as $langkah)
                    <li>{{ $langkah }}</li>
                @endforeach
            </ol>
        </div>

        <div class="kotak">
            <h2>Pembagian peran AI</h2>
            <table class="info">
                @foreach ($detail['pembagian'] as $bagian => $tugas)
                    <tr><th>{{ $bagian }}</th><td>{{ $tugas }}</td></tr>
                @endforeach
            </table>
        </div>

        <div class="kotak">
            <h2>Teknologi yang dipakai</h2>
            <table class="info">
                @foreach ($detail['tech_stack'] as $teknologi => $kegunaan)
                    <tr><th>{{ $teknologi }}</th><td>{{ $kegunaan }}</td></tr>
                @endforeach
            </table>
        </div>

        <div class="pesan info">
            <strong>Target:</strong> {{ $detail['target'] }}
        </div>
    @endif
@endsection
