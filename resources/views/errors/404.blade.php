@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    {{-- Dipakai oleh Route::fallback() dan oleh abort(404) di controller --}}
    <div class="kode-error">404</div>
    <h1>Halaman tidak ditemukan</h1>
    <p class="subjudul">Alamat <code>/{{ request()->path() }}</code> tidak ada di aplikasi ini.</p>

    <div class="kotak">
        @if (isset($exception) && $exception->getMessage())
            <p>{{ $exception->getMessage() }}</p>
        @else
            <p>Periksa kembali alamat yang dituju, atau kembali ke halaman utama.</p>
        @endif
        <div class="tautan-tombol">
            <a class="tombol" href="{{ route('home') }}">Kembali ke Home</a>
        </div>
    </div>
@endsection
