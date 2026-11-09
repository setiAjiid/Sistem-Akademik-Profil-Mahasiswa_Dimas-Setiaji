<?php

use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Semua rute di file ini otomatis berawalan /api dan berjenis JSON.
// Pembatasan 30 permintaan per menit per IP supaya API publik tidak disalahgunakan.
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('api.mahasiswa.index');
    Route::get('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show'])->name('api.mahasiswa.show');
});
