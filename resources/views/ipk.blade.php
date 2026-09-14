@extends('layouts.app')

@section('title', 'Kalkulator IPK')

@section('content')
    {{-- Challenge 2: /hitung-ipk/{ip1}/{ip2} --}}
    <h1>Kalkulator IPK</h1>
    <p class="subjudul">Menjumlahkan dan merata-ratakan IP dua semester dari parameter URL.</p>

    @if (! $valid)
        <div class="pesan">
            Nilai IP harus berada pada rentang 0 sampai 4. Nilai yang dimasukkan: {{ $ip1 }} dan {{ $ip2 }}.
        </div>
    @else
        <div class="statistik">
            <div class="kotak">
                <div class="angka">{{ number_format($ip1, 2) }}</div>
                <div class="label">IP semester pertama</div>
                <div class="bar biru" style="margin-top: 12px"><span style="width: {{ $ip1 / 4 * 100 }}%"></span></div>
            </div>
            <div class="kotak">
                <div class="angka">{{ number_format($ip2, 2) }}</div>
                <div class="label">IP semester kedua</div>
                <div class="bar biru" style="margin-top: 12px"><span style="width: {{ $ip2 / 4 * 100 }}%"></span></div>
            </div>
            <div class="kotak">
                <div class="angka">{{ number_format($rata, 2) }}</div>
                <div class="label">Rata-rata, dari jumlah {{ number_format($jumlah, 2) }}</div>
                <div class="bar" style="margin-top: 12px"><span style="width: {{ $rata / 4 * 100 }}%"></span></div>
            </div>
        </div>
    @endif

    <div class="kotak">
        <h2>Hitung nilai lain</h2>
        {{-- Form ini hanya menyusun URL /hitung-ipk/{ip1}/{ip2}, perhitungannya tetap di controller --}}
        <form class="baris" id="form-ipk">
            <label>
                IP semester pertama
                <input type="number" name="ip1" min="0" max="4" step="0.01" value="{{ $ip1 }}" required>
            </label>
            <label>
                IP semester kedua
                <input type="number" name="ip2" min="0" max="4" step="0.01" value="{{ $ip2 }}" required>
            </label>
            <button type="submit" class="tombol">Hitung</button>
        </form>
    </div>

    <div class="kotak">
        <h2>Contoh URL</h2>
        <ul>
            <li><a href="{{ route('ipk.hitung', ['ip1' => '3.5', 'ip2' => '3.75']) }}">/hitung-ipk/3.5/3.75</a></li>
            <li><a href="{{ route('ipk.hitung', ['ip1' => '4', 'ip2' => '4']) }}">/hitung-ipk/4/4</a></li>
            <li><a href="{{ route('ipk.hitung', ['ip1' => '5', 'ip2' => '3']) }}">/hitung-ipk/5/3</a> (di luar rentang, ditolak controller)</li>
            <li><code>/hitung-ipk/abc/3</code> (bukan angka, ditolak regex di rute sehingga 404)</li>
        </ul>
    </div>

    <script>
        // Pola URL diambil dari route() supaya tidak ada URL yang ditulis manual.
        var polaUrl = "{{ route('ipk.hitung', ['ip1' => 'IP1', 'ip2' => 'IP2']) }}";

        document.getElementById('form-ipk').addEventListener('submit', function (event) {
            event.preventDefault();
            window.location.href = polaUrl
                .replace('IP1', this.ip1.value)
                .replace('IP2', this.ip2.value);
        });
    </script>
@endsection
