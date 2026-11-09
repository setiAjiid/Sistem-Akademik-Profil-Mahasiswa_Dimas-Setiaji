@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Selamat datang, {{ Auth::user()->name }}. Kamu masuk sebagai <strong>{{ Auth::user()->getRoleNames()->implode(', ') }}</strong>.</p>

    {{-- Widget statistik. Angkanya dihitung langsung dari database lewat count() di DashboardController. --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box title="{{ $totalMahasiswa }}" text="Total Mahasiswa" icon="bi bi-people" theme="primary"
                url="{{ route('admin.mahasiswa.index') }}" url-text="Lihat data"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box title="{{ $mahasiswaAktif }}" text="Mahasiswa Aktif" icon="bi bi-person-check" theme="success"
                url="{{ route('admin.mahasiswa.index', ['status' => 'aktif']) }}" url-text="Lihat data"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box title="{{ $totalDosen }}" text="Total Dosen" icon="bi bi-person-badge" theme="info"
                url="{{ route('admin.dosen.index') }}" url-text="Lihat data"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box title="{{ $totalTugasAktif }}" text="Tugas Aktif" icon="bi bi-journal-text" theme="warning"
                url="{{ route('admin.departemen.index') }}" url-text="Per departemen"/>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Mahasiswa Terbaru</h3></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>NRP</th><th>Nama</th><th>Departemen</th><th>Angkatan</th></tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswaTerbaru as $mahasiswa)
                        <tr>
                            <td>{{ $mahasiswa->nrp }}</td>
                            <td>{{ $mahasiswa->nama }}</td>
                            <td>{{ $mahasiswa->departemen->nama }}</td>
                            <td>{{ $mahasiswa->angkatan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Belum ada data. Jalankan <code>php artisan db:seed</code>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
