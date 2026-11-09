<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanDosenRequest;
use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * CRUD data dosen di panel admin, dengan pencarian dan filter departemen.
 */
class DosenController extends Controller implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-dosen', only: ['index']),
            new Middleware('permission:tambah-dosen', only: ['create', 'store']),
            new Middleware('permission:edit-dosen', only: ['edit', 'update']),
            new Middleware('permission:hapus-dosen', only: ['destroy']),
        ];
    }

    /** Daftar dosen. Filter opsional: ?search= (nama atau NIP) dan ?departemen_id=. */
    public function index(Request $request): View
    {
        // ?search[]=x menghasilkan array, bukan string, jadi dianggap kosong.
        $search = is_string($request->query('search')) ? trim($request->query('search')) : '';

        $dosens = Dosen::with('departemen')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "{$search}%");
                });
            })
            ->when($request->integer('departemen_id'), fn ($query, $id) => $query->where('departemen_id', $id))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dosen.index', [
            'dosens' => $dosens,
            'departemens' => Departemen::orderBy('nama')->get(),
            'search' => $search,
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
