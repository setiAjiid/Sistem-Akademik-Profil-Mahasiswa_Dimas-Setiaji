<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses URL berdasarkan kolom role milik user.
 *
 * Contoh pemakaian di rute: ->middleware('cek.peran:dosen,asdos')
 * Alias "cek.peran" didaftarkan di bootstrap/app.php.
 */
class CekPeran
{
    /**
     * @param  string  ...$peran  Daftar peran yang boleh masuk.
     */
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $user = $request->user();

        // Tamu yang belum login diarahkan ke halaman login.
        if ($user === null) {
            return redirect()->route('login');
        }

        // User sudah login tetapi perannya tidak cocok: kembalikan ke portal dengan pesan error.
        if (! $user->punyaPeran(...$peran)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Kamu tidak punya izin untuk membuka halaman itu.');
        }

        return $next($request);
    }
}
