<?php

namespace App\Http\Controllers;

use App\Data\DataStatis;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Tiga halaman utama Tugas 4 (Pertemuan 4): Beranda, Profil, dan Ide-Agent.
 * Semua view-nya mewarisi layouts/app.blade.php.
 */
class PageController extends Controller
{
    /** Batas jumlah ide yang disimpan di session, supaya daftar tidak tumbuh tanpa batas. */
    private const MAKS_IDE_TERSIMPAN = 5;

    /**
     * Beranda: / dan /beranda.
     * Challenge 2: ?user=Andi memunculkan banner sapaan untuk nama tersebut.
     */
    public function beranda(Request $request): View
    {
        return view('home', [
            'mahasiswa' => DataStatis::cariMahasiswa(DataStatis::NRP_PEMILIK),
            'ringkasan' => DataStatis::ringkasanStudi(),
            'namaUser' => $this->namaDariUrl($request),
        ]);
    }

    /** Profil: /profil-mahasiswa */
    public function profil(): View
    {
        return view('profil', [
            'mahasiswa' => DataStatis::cariMahasiswa(DataStatis::NRP_PEMILIK),
            'ringkasan' => DataStatis::ringkasanStudi(),
        ]);
    }

    /**
     * Ide riset: /ide-agent
     * Menampilkan rancangan TokenPilot dan formulir pengumpulan ide.
     * Challenge 1 (?mode=dark) ditangani View Composer di AppServiceProvider, jadi berlaku di semua halaman.
     */
    public function ideAgent(Request $request): View
    {
        return view('ide-agent', [
            'agent' => DataStatis::cariTemaAgent('tokenpilot'),
            'kategori' => DataStatis::KATEGORI_IDE,
            'ideTerkirim' => $request->session()->get('ide_terkirim', []),
        ]);
    }

    /**
     * POST /ide-agent
     * Belum ada database (baru pekan depan), jadi ide disimpan di session pengunjung.
     * Kalau validasi gagal, Laravel otomatis kembali ke form dengan $errors dan old().
     */
    public function kirimIde(Request $request): RedirectResponse
    {
        $ide = $request->validate([
            'nama' => ['required', 'string', 'max:60'],
            'judul' => ['required', 'string', 'max:100'],
            'kategori' => ['required', Rule::in(array_keys(DataStatis::KATEGORI_IDE))],
            'deskripsi' => ['required', 'string', 'min:20', 'max:1000'],
        ], [
            'required' => ':Attribute wajib diisi.',
            'min' => ':Attribute minimal :min karakter.',
            'max' => ':Attribute maksimal :max karakter.',
            'in' => ':Attribute yang dipilih tidak tersedia.',
        ], [
            'nama' => 'nama',
            'judul' => 'judul ide',
            'kategori' => 'kategori',
            'deskripsi' => 'deskripsi',
        ]);

        // Ide terbaru di urutan pertama.
        $daftar = $request->session()->get('ide_terkirim', []);
        array_unshift($daftar, $ide + ['waktu' => now()->format('d M Y, H:i')]);
        $request->session()->put('ide_terkirim', array_slice($daftar, 0, self::MAKS_IDE_TERSIMPAN));

        // Pola Post/Redirect/Get: refresh halaman tidak mengirim ulang form.
        return redirect()
            ->route('ide-agent', $request->query('mode') === 'dark' ? ['mode' => 'dark'] : [])
            ->with('status', "Ide \"{$ide['judul']}\" berhasil dikirim. Terima kasih, {$ide['nama']}!");
    }

    /**
     * Mengambil ?user= dari URL. Spasi berlebih dirapikan dan panjangnya dibatasi.
     * Tidak perlu membuang karakter HTML: {{ }} di Blade sudah meng-escape-nya (aman dari XSS).
     */
    private function namaDariUrl(Request $request): ?string
    {
        $nama = $request->query('user');

        // ?user[]=x menghasilkan array, bukan string.
        if (! is_string($nama)) {
            return null;
        }

        $nama = Str::limit(Str::squish($nama), 40, '');

        return $nama === '' ? null : $nama;
    }
}
