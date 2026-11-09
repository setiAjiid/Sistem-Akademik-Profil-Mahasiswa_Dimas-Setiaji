<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Ringkasan jumlah mahasiswa, dosen, dan tugas per departemen.
 */
class DepartemenController extends Controller implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-dashboard'),
        ];
    }

    public function index(): View
    {
        return view('admin.departemen.index', [
            // withCount menghitung relasi di database (1 query), tanpa memuat semua barisnya.
            'departemens' => Departemen::withCount(['mahasiswas', 'dosens', 'tugas'])
                ->orderBy('nama')
                ->get(),
        ]);
    }
}
