@extends('layouts.app')

@section('title', 'Riwayat Studi')

@section('content')
    {{-- Challenge 3: rute di dalam prefix /dashboard --}}
    <h1>Riwayat Studi</h1>
    <p class="subjudul">Mata kuliah dan indeks prestasi tiap semester. Klik semester untuk melihat mata kuliahnya.</p>

    <div class="kotak">
        @foreach ($riwayat as $s)
            {{-- Semester berjalan dibuka otomatis supaya langsung terlihat --}}
            <details {{ $s['ip'] === null ? 'open' : '' }}>
                <summary>
                    Semester {{ $s['semester'] }}, {{ $s['tahun'] }}
                    <span class="mono" style="color: var(--teks-redup)">
                        &nbsp; {{ $s['sks'] }} SKS{{ $s['ip'] !== null ? ', IP '.number_format($s['ip'], 2) : '' }}
                    </span>
                    @if ($s['ip'] === null)
                        <span class="status">Sedang berjalan</span>
                    @endif
                </summary>

                @if ($s['ip'] !== null)
                    <div class="bar" style="margin: 10px 0 0 20px; max-width: 320px"><span style="width: {{ $s['ip'] / 4 * 100 }}%"></span></div>
                @endif

                <table style="margin: 12px 0 4px 20px; width: calc(100% - 20px)">
                    @foreach ($s['mata_kuliah'] as $mk)
                        <tr>
                            <td>{{ $mk['nama'] }}</td>
                            <td style="width: 80px; text-align: right">{{ $mk['sks'] }} SKS</td>
                            <td style="width: 50px; text-align: right" class="mono">{{ $mk['nilai'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </table>
            </details>
        @endforeach
    </div>

    <p><a href="{{ route('dashboard.index') }}">Kembali ke dashboard</a></p>
@endsection
