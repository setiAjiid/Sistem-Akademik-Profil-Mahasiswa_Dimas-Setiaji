@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Data Dosen</h1>
        @can('tambah-dosen')
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Dosen
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
        <div class="card-header">
            <form method="GET" action="{{ route('admin.dosen.index') }}" class="row g-2">
                <div class="col-md-5">
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama atau NIP">
                </div>
                <div class="col-md-4">
                    <select name="departemen_id" class="form-select">
                        <option value="">Semua departemen</option>
                        @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->id }}" @selected(request('departemen_id') == $departemen->id)>{{ $departemen->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-secondary"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dosens as $dosen)
                        <tr>
                            <td class="font-monospace">{{ $dosen->nip }}</td>
                            <td>{{ $dosen->nama }}</td>
                            <td>{{ $dosen->departemen->nama }}</td>
                            <td class="text-muted">{{ $dosen->email ?? '-' }}</td>
                            <td class="text-end text-nowrap">
                                @can('edit-dosen')
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                @endcan
                                @can('hapus-dosen')
                                    <form method="POST" action="{{ route('admin.dosen.destroy', $dosen) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus data {{ $dosen->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted p-4">Tidak ada data yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dosens->hasPages())
            <div class="card-footer">{{ $dosens->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@stop
