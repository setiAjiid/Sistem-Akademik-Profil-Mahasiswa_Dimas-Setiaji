@extends('adminlte::page')

@section('title', 'Ubah Dosen')

@section('content_header')
    <h1>Ubah Dosen</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}" class="card">
        @method('PUT')
        <div class="card-body">
            @include('admin.dosen._form')
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
@stop
