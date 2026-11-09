@extends('adminlte::page')

@section('title', 'Tambah Dosen')

@section('content_header')
    <h1>Tambah Dosen</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('admin.dosen.store') }}" class="card">
        <div class="card-body">
            @include('admin.dosen._form')
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
@stop
