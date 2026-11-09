@extends('adminlte::page')

@section('title', 'Departemen')

@section('content_header')
    <h1>Ringkasan Departemen</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Departemen</th>
                        <th class="text-end">Mahasiswa</th>
                        <th class="text-end">Dosen</th>
                        <th class="text-end">Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departemens as $departemen)
                        <tr>
                            <td class="font-monospace">{{ $departemen->kode }}</td>
                            <td>{{ $departemen->nama }}</td>
                            <td class="text-end">{{ $departemen->mahasiswas_count }}</td>
                            <td class="text-end">{{ $departemen->dosens_count }}</td>
                            <td class="text-end">{{ $departemen->tugas_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted p-4">Belum ada departemen. Jalankan <code>php artisan db:seed</code>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
