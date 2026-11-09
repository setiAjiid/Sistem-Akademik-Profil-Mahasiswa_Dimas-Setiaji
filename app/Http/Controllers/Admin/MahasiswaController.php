<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanMahasiswaRequest;
use App\Models\Departemen;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD data mahasiswa di panel admin, termasuk pencarian, filter, dan upload foto.
 */
class MahasiswaController extends Controller implements HasMiddleware
{
    /**
     * Setiap aksi dikunci permission Spatie di sisi server. Menyembunyikan tombol di Blade
     * saja tidak cukup, karena request bisa dikirim manual lewat Postman.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-mahasiswa', only: ['index']),
            new Middleware('permission:tambah-mahasiswa', only: ['create', 'store']),
            new Middleware('permission:edit-mahasiswa', only: ['edit', 'update']),
            new Middleware('permission:hapus-mahasiswa', only: ['destroy']),
        ];
    }

    /**
     * Daftar mahasiswa. Filter opsional: ?search= (nama atau NRP), ?departemen_id=, ?status=aktif|cuti.
     * when() hanya menambah klausa kalau nilainya diisi, jadi tidak perlu blok if bertingkat.
     */
    public function index(Request $request): View
    {
        // ?search[]=x menghasilkan array, bukan string, jadi dianggap kosong.
        $search = is_string($request->query('search')) ? trim($request->query('search')) : '';
        $status = $request->query('status');

        $mahasiswas = Mahasiswa::with('departemen')
            ->when($search !== '', function ($query) use ($search) {
                // Dikelompokkan dalam closure supaya OR tidak merusak filter lain.
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nrp', 'like', "{$search}%");
                });
            })
            ->when($request->integer('departemen_id'), fn ($query, $id) => $query->where('departemen_id', $id))
            ->when(in_array($status, ['aktif', 'cuti'], true), fn ($query) => $query->where('is_active', $status === 'aktif'))
            ->orderBy('nama')
            ->paginate(10)
            // Parameter filter ikut terbawa di link halaman berikutnya.
            ->withQueryString();

        return view('admin.mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'departemens' => Departemen::orderBy('nama')->get(),
            'search' => $search,
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
