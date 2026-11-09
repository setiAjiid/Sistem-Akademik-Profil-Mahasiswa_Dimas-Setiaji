<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanMahasiswaRequest;
use App\Models\Departemen;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD data mahasiswa untuk dosen dan asdos, termasuk upload foto.
 */
class MahasiswaController extends Controller
{
    public function index(): View
    {
        return view('admin.mahasiswa.index', [
            // with('departemen') memuat semua departemen dalam 1 query tambahan (mencegah N+1).
            'mahasiswas' => Mahasiswa::with('departemen')->orderBy('nama')->paginate(10),
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
        Mahasiswa::create($this->dataDenganFoto($request));

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
        $data = $this->dataDenganFoto($request);

        // Foto lama dihapus hanya kalau ada foto baru yang menggantikannya.
        if (isset($data['foto']) && $mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->update($data);

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Data hasil validasi. Kalau ada file foto, simpan ke storage/app/public/foto-mahasiswa
     * lalu ganti isinya dengan path-nya. Kalau tidak ada, kolom foto tidak ikut diubah.
     *
     * @return array<string, mixed>
     */
    private function dataDenganFoto(SimpanMahasiswaRequest $request): array
    {
        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-mahasiswa', 'public');
        }

        return $data;
    }
}
