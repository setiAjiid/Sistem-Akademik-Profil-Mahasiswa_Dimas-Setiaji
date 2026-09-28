<?php

namespace App\Http\Controllers;

use App\Data\DataStatis;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

/**
 * Halaman-halaman Tugas 2 (Pertemuan 2). Setiap method dipanggil dari routes/web.php.
 * Rute 1 (/) sekarang ditangani PageController::beranda() untuk Tugas 4.
 */
class ProfilController extends Controller
{
    /**
     * Rute 2: /mahasiswa/{nrp}
     * Format 10 digit sudah dijaga where() di rute, di sini tinggal cek apakah NRP terdaftar.
     */
    public function mahasiswa(string $nrp): View
    {
        $mahasiswa = DataStatis::cariMahasiswa($nrp);

        abort_if($mahasiswa === null, 404, "Mahasiswa dengan NRP {$nrp} tidak terdaftar.");

        return view('mahasiswa.show', [
            'mahasiswa' => $mahasiswa,
            'riwayat' => DataStatis::riwayatStudi(),
            'ringkasan' => DataStatis::ringkasanStudi(),
        ]);
    }

    /**
     * Rute 3: /agent/{tema?}
     * Tanpa tema, view menampilkan nilai default. Tema yang tidak dikenal menghasilkan $detail null.
     */
    public function agent(?string $tema = null): View
    {
        return view('agent', [
            'tema' => $tema,
            'detail' => $tema === null ? null : DataStatis::cariTemaAgent($tema),
            'default' => DataStatis::TEMA_AGENT_DEFAULT,
        ]);
    }

    /**
     * Challenge 2: /hitung-ipk/{ip1}/{ip2}
     * Regex di rute sudah menjamin keduanya angka positif, jadi cukup cek batas atas 4.
     */
    public function hitungIpk(string $ip1, string $ip2): View
    {
        $ip1 = (float) $ip1;
        $ip2 = (float) $ip2;

        return view('ipk', [
            'ip1' => $ip1,
            'ip2' => $ip2,
            'valid' => $ip1 <= 4 && $ip2 <= 4,
            'jumlah' => $ip1 + $ip2,
            'rata' => ($ip1 + $ip2) / 2,
        ]);
    }

    /** Challenge 3: /dashboard */
    public function dashboard(): View
    {
        return view('dashboard.index', [
            'mahasiswa' => DataStatis::cariMahasiswa(DataStatis::NRP_PEMILIK),
            'ringkasan' => DataStatis::ringkasanStudi(),
            'sksLulus' => DataStatis::SKS_LULUS,
        ]);
    }

    /** Challenge 3: /dashboard/riwayat-studi */
    public function riwayatStudi(): View
    {
        return view('dashboard.riwayat', [
            'riwayat' => DataStatis::riwayatStudi(),
        ]);
    }

    /** Challenge 3: Route::fallback(), untuk URL yang tidak cocok dengan rute mana pun. */
    public function tidakDitemukan(): Response
    {
        return response()->view('errors.404', [], 404);
    }
}
