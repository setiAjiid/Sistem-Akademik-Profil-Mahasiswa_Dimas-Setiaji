<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Contracts\View\View;

/**
 * Ringkasan jumlah mahasiswa, dosen, dan tugas per departemen.
 */
class DepartemenController extends Controller
{
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
