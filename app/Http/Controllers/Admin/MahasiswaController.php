<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanMahasiswaRequest;
use App\Models\Departemen;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * CRUD data mahasiswa untuk dosen dan asdos.
 */
class MahasiswaController extends Controller
{
    public function index(): View
    {
        return view('admin.mahasiswa.index', [
            'mahasiswas' => Mahasiswa::orderBy('nama')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.mahasiswa.create', [
            'departemens' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function store(SimpanMahasiswaRequest $request): RedirectResponse
    {
        Mahasiswa::create($request->validated());

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        return view('admin.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
            'departemens' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function update(SimpanMahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->update($request->validated());

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        $mahasiswa->delete();

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil dihapus.');
    }
}
