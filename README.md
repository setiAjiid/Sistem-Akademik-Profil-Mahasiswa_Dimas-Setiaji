# Sistem Akademik Profil Mahasiswa

Tugas Mandiri PBKK, dikerjakan bertahap di repo yang sama:

- **Pertemuan 4 (terbaru):** Blade Templating & Integrasi Frontend Modern. Master layout, Blade components, Vite + Tailwind.
- **Pertemuan 2:** Laravel Local Sandbox (routing dasar). Versi saat dikumpulkan ada di tag [`tugas-2`](https://github.com/setiAjiid/Sistem-Akademik-Profil-Mahasiswa_Dimas-Setiaji/tree/tugas-2).

| | |
|---|---|
| Nama | Dimas Setiaji |
| NRP | 5025241056 |
| Kelas / Kelompok | B / B1 |
| Framework | Laravel 13, PHP 8.5, Vite 8, Tailwind CSS 4 |

Mini-website akademik pribadi (tanpa database) berisi profil diri, rancangan platform Agentic AI kelompok (TokenPilot), dan formulir pengumpulan ide.

## Cara Menjalankan

```bash
composer install
pnpm install            # atau npm install
cp .env.example .env
php artisan key:generate
php artisan migrate     # tabel sessions untuk SESSION_DRIVER=database
```

Lalu jalankan dua terminal:

```bash
pnpm dev                # terminal 1: Vite (CSS, JS, font)
php artisan serve       # terminal 2: server Laravel
```

Buka <http://localhost:8000>. Tanpa `pnpm dev`, jalankan `pnpm build` sekali agar `public/build` terisi.

## Tugas 4: Blade Templating & Vite

| Rute | Nama | Keterangan |
|---|---|---|
| `GET /` | `home` | Beranda (`PageController@beranda`) |
| `GET /beranda` | `beranda` | Beranda yang sama. Challenge 2: `?user=Andi` |
| `GET /profil-mahasiswa` | `profil` | Profil (`PageController@profil`), tiap blok memakai `<x-info-card>` |
| `GET /ide-agent` | `ide-agent` | Rancangan TokenPilot dan formulir ide (`PageController@ideAgent`). Challenge 1: `?mode=dark` |
| `POST /ide-agent` | `ide-agent.kirim` | Validasi form, simpan ide di session, redirect dengan pesan sukses |

Pemenuhan spesifikasi:

- **Master layout** `resources/views/layouts/app.blade.php`: title dinamis (`@yield('title')`), navbar, container `<main>`, footer ITS. Semua halaman memakai `@extends('layouts.app')`.
- **Komponen** di `resources/views/components/`:
  - `<x-info-card judul="..." label="...">`: prop `judul`/`label`, slot isi, named slot `aksi`, dan `$attributes->class()` untuk class tambahan.
  - `<x-status-banner tipe="success|error|warning|info" judul="..." bisa-ditutup>`: warna Tailwind dipilih dinamis dari `tipe`. Dipakai untuk notifikasi form, error validasi, info mode, dan sapaan.
- **Vite**: Tailwind dipasang lewat NPM (`@tailwindcss/vite`), CSS masuk dari `resources/css/app.css`, dimuat dengan `@vite`. Font di-host lokal lewat `@fonts`. Tidak ada tautan CDN.
- **Challenge 1**: View Composer di `AppServiceProvider` membaca `?mode=dark` menjadi `$modeGelap`. Layout memakai `@class` untuk menambah class `dark` di `<html>` serta class latar Tailwind di `<body>`. Mode ikut terbawa di tautan navbar, dan ada tombol ganti tema.
- **Challenge 2**: `PageController@beranda` merapikan `?user=` (string, maksimal 40 karakter), lalu beranda menampilkan `<x-status-banner>` sapaan yang bisa ditutup (`resources/js/app.js`). Nama dicetak dengan `{{ }}`, jadi aman dari XSS.

Contoh URL: `/beranda?user=Andi`, `/ide-agent?mode=dark`, `/profil-mahasiswa?mode=dark`.

## Tugas 2: Routing Dasar

Daftar lengkap bisa dilihat dengan `php artisan route:list --except-vendor`. CSS ada di `resources/css/` dan dimuat lewat Vite (`@vite`), font di-host lokal lewat `@fonts`.

| Rute | Nama | Keterangan |
|---|---|---|
| `GET /` | `home` | Rute 1. Sambutan dan profil singkat (sekarang lewat `PageController`) |
| `GET /mahasiswa/{nrp}` | `mahasiswa.show` | Rute 2. Parameter wajib, profil lengkap pemilik NRP |
| `GET /agent/{tema?}` | `agent.show` | Rute 3. Parameter opsional, kosong berarti `General Assistant Agent` |
| `GET /hitung-ipk/{ip1}/{ip2}` | `ipk.hitung` | Challenge 2. Jumlah dan rata-rata IP dua semester |
| `GET /dashboard` | `dashboard.index` | Challenge 3. Grup `prefix('dashboard')` |
| `GET /dashboard/riwayat-studi` | `dashboard.riwayat` | Challenge 3. Grup `prefix('dashboard')` |
| lainnya | `fallback` | Challenge 3. `Route::fallback()` ke halaman 404 |

Challenge 1: `->where('nrp', '[0-9]{10}')` pada `/mahasiswa/{nrp}`. NRP yang bukan 10 digit angka langsung 404.

Contoh URL untuk dicoba:

- `/mahasiswa/5025241056` (terdaftar), `/mahasiswa/123` (ditolak regex), `/mahasiswa/9999999999` (format benar, tidak terdaftar)
- `/agent` (default), `/agent/tokenpilot`, `/agent/tema-lain` (tidak dikenal)
- `/hitung-ipk/3.5/3.75`, `/hitung-ipk/5/3` (di luar rentang), `/hitung-ipk/abc/3` (ditolak regex)
- `/halaman-ngawur` (fallback)

## Struktur Kode

```
routes/web.php                        # definisi rute, semua memakai name()
app/Http/Controllers/PageController.php    # Tugas 4: beranda, profil, ide-agent, kirim ide
app/Http/Controllers/ProfilController.php  # Tugas 2: mahasiswa, agent, IPK, dashboard, fallback
app/Data/DataStatis.php               # data diri, riwayat studi, tema agent, kategori ide (array statis)
app/Providers/AppServiceProvider.php  # View Composer: $modeGelap, $kueriMode, $urlGantiMode
resources/css/app.css                 # entry Vite: Tailwind + profil.css
resources/css/profil.css              # CSS sendiri, variabel warna tema terang/gelap
resources/js/app.js                   # tombol tutup <x-status-banner>
resources/views/
    layouts/app.blade.php             # master layout
    components/info-card.blade.php
    components/status-banner.blade.php
    home.blade.php                    # beranda
    profil.blade.php                  # /profil-mahasiswa
    ide-agent.blade.php               # /ide-agent
    mahasiswa/show.blade.php, agent.blade.php, ipk.blade.php,
    dashboard/*.blade.php, errors/404.blade.php   # halaman Tugas 2
tests/Feature/Tugas4Test.php          # spesifikasi Tugas 4 dan kedua challenge
tests/Feature/RuteProfilTest.php      # spesifikasi Tugas 2
```

## Pengujian

```bash
php artisan test
vendor/bin/pint --test
```
