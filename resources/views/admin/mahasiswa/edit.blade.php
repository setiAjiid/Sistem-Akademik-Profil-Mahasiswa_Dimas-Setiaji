@extends('adminlte::page')

@section('title', 'Ubah Mahasiswa')

@section('content_header')
    <h1>Ubah Mahasiswa</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" enctype="multipart/form-data" class="card">
        @method('PUT')
        <div class="card-body">
            @include('admin.mahasiswa._form')
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
@stop
