<?php

use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Semua rute diberi name() supaya tautan di Blade memakai route() dan tidak ada URL yang ditulis manual.

// Pola angka desimal positif (3, 3.5, 3.75) untuk kalkulator IPK.
$angkaDesimal = '[0-9]+(\.[0-9]+)?';

// Rute 1: Home
Route::get('/', [ProfilController::class, 'home'])
    ->name('home');

// Rute 2: Detail profil, parameter {nrp} wajib.
// Challenge 1: where() membatasi NRP ke 10 digit angka; yang tidak cocok langsung 404 tanpa masuk controller.
Route::get('/mahasiswa/{nrp}', [ProfilController::class, 'mahasiswa'])
    ->where('nrp', '[0-9]{10}')
    ->name('mahasiswa.show');

// Rute 3: Ide platform Agentic AI, parameter {tema} opsional.
Route::get('/agent/{tema?}', [ProfilController::class, 'agent'])
    ->where('tema', '[A-Za-z0-9-]+')
    ->name('agent.show');

// Challenge 2: Kalkulator IPK dua semester.
Route::get('/hitung-ipk/{ip1}/{ip2}', [ProfilController::class, 'hitungIpk'])
    ->where(['ip1' => $angkaDesimal, 'ip2' => $angkaDesimal])
    ->name('ipk.hitung');

// Challenge 3: Rute profil akademik dikelompokkan di prefix /dashboard.
// name('dashboard.') membuat semua nama rute di dalamnya berawalan "dashboard.".
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [ProfilController::class, 'dashboard'])
        ->name('index');

    Route::get('/riwayat-studi', [ProfilController::class, 'riwayatStudi'])
        ->name('riwayat');
});

// Challenge 3: Fallback untuk URL yang tidak cocok dengan rute mana pun. Harus diletakkan paling akhir.
Route::fallback([ProfilController::class, 'tidakDitemukan'])
    ->name('fallback');
