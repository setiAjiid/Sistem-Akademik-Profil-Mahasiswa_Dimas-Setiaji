# Sistem Akademik Profil Mahasiswa

Tugas Mandiri PBKK Pertemuan 2: Laravel Local Sandbox (Instalasi dan Routing Dasar).

| | |
|---|---|
| Nama | Dimas Setiaji |
| NRP | 5025241056 |
| Kelas / Kelompok | B / B1 |
| Framework | Laravel 13, PHP 8.5 |

Aplikasi profil akademik statis (tanpa database) yang menampilkan data diri, riwayat studi, dan ide proyek akhir platform Agentic AI (TokenPilot). Fitur routing yang dipakai: named routes, parameter wajib dan opsional, regex constraint, route grouping, dan fallback route.

## Cara Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka <http://localhost:8000>.

## Daftar Rute

Daftar lengkap bisa dilihat dengan `php artisan route:list --except-vendor`. CSS ada di `resources/css/` dan dimuat lewat Vite (`@vite`), font di-host lokal lewat `@fonts`.

| Rute | Nama | Keterangan |
|---|---|---|
| `GET /` | `home` | Rute 1. Sambutan dan profil singkat |
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
app/Http/Controllers/ProfilController.php
app/Data/DataStatis.php               # data diri, riwayat studi, tema agent (array statis)
app/Providers/AppServiceProvider.php  # View::share() NRP pemilik untuk navbar
resources/css/app.css, profil.css    # Tailwind + CSS sendiri, diproses Vite
resources/views/
    layouts/app.blade.php             # layout dan navbar, semua tautan lewat route()
    home.blade.php
    mahasiswa/show.blade.php
    agent.blade.php                   # penjelasan TokenPilot
    ipk.blade.php                     # kalkulator IPK, ada form kecil untuk menyusun URL
    dashboard/index.blade.php
    dashboard/riwayat.blade.php
    errors/404.blade.php              # dipakai fallback route dan abort(404)
tests/Feature/RuteProfilTest.php      # memeriksa seluruh spesifikasi tugas
```

## Pengujian

```bash
php artisan test
vendor/bin/pint --test
```
