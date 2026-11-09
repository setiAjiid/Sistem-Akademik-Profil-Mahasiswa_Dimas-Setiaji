<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanDosenRequest;
use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * CRUD data dosen untuk dosen dan asdos.
 */
class DosenController extends Controller
{
    public function index(): View
    {
        return view('admin.dosen.index', [
            'dosens' => Dosen::with('departemen')->orderBy('nama')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.dosen.create', [
            'departemens' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function store(SimpanDosenRequest $request): RedirectResponse
    {
        Dosen::create($request->validated());

        return redirect()
            ->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View
    {
        return view('admin.dosen.edit', [
            'dosen' => $dosen,
            'departemens' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function update(SimpanDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $dosen->update($request->validated());

        return redirect()
            ->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return redirect()
            ->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil dihapus.');
    }
}
