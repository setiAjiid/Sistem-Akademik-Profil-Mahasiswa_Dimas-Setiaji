<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Beranda panel admin: widget ringkasan statistik dari database.
 */
class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Middleware tingkat controller. Ini lapisan keamanan kedua setelah grup rute.
     *
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
        return view('admin.dashboard', [
            'totalMahasiswa' => Mahasiswa::count(),
            'mahasiswaAktif' => Mahasiswa::aktif()->count(),
            'totalDosen' => Dosen::count(),
            'totalTugasAktif' => Tugas::aktif()->count(),
            'totalDepartemen' => Departemen::count(),
            'mahasiswaTerbaru' => Mahasiswa::with('departemen')->latest()->take(5)->get(),
        ]);
    }
}
