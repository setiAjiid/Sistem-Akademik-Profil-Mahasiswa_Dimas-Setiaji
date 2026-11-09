@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Data Mahasiswa</h1>
        @can('tambah-mahasiswa')
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
            </a>
        @endcan
    </div>
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        {{-- Form filter memakai GET, jadi hasilnya bisa di-bookmark dan tetap terbawa saat pindah halaman. --}}
        <div class="card-header">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama atau NRP">
                </div>
                <div class="col-md-3">
                    <select name="departemen_id" class="form-select">
                        <option value="">Semua departemen</option>
                        @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->id }}" @selected(request('departemen_id') == $departemen->id)>{{ $departemen->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="cuti" @selected(request('status') === 'cuti')>Cuti</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-secondary"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>NRP</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Angkatan</th>
                        <th>IPK</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mahasiswa)
                        <tr>
                            <td class="font-monospace">{{ $mahasiswa->nrp }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($mahasiswa->urlFoto())
                                        <img src="{{ $mahasiswa->urlFoto() }}" alt="" class="rounded-circle" width="32" height="32" style="object-fit: cover">
                                    @else
                                        <span class="rounded-circle bg-secondary-subtle text-secondary d-inline-flex justify-content-center align-items-center fw-bold" style="width: 32px; height: 32px">{{ mb_substr($mahasiswa->nama, 0, 1) }}</span>
                                    @endif
                                    {{ $mahasiswa->nama }}
                                </div>
                            </td>
                            <td>{{ $mahasiswa->departemen->nama }}</td>
                            <td>{{ $mahasiswa->angkatan }}</td>
                            <td>{{ $mahasiswa->ipk ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $mahasiswa->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $mahasiswa->is_active ? 'Aktif' : 'Cuti' }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                @can('edit-mahasiswa')
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                @endcan
                                {{-- Tombol hapus hanya dirender untuk yang punya izin. Controller tetap mengeceknya lagi. --}}
                                @can('hapus-mahasiswa')
                                    <form method="POST" action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus data {{ $mahasiswa->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted p-4">Tidak ada data yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mahasiswas->hasPages())
            <div class="card-footer">{{ $mahasiswas->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@stop
