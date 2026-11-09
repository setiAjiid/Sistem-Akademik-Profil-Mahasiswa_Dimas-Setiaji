# Sistem Akademik Profil Mahasiswa

Tugas Mandiri PBKK, dikerjakan bertahap di repo yang sama:

- **Pertemuan 9 (terbaru):** Upload foto mahasiswa dan API JSON dengan API Resource.
- **Pertemuan 8:** Relasi Eloquent, eager loading, dan seeder Faker.
- **Pertemuan 7:** CRUD Mahasiswa dan Dosen dengan validasi Form Request.
- **Pertemuan 6:** Migrations dan Eloquent ORM. Skema departemen, dosen, mahasiswa, tugas.
- **Pertemuan 5:** Middleware, Autentikasi, dan Otorisasi. Login Breeze, role, middleware `cek.peran`, Gate, dan Policy. Tag [`tugas-5`](https://github.com/setiAjiid/Sistem-Akademik-Profil-Mahasiswa_Dimas-Setiaji/tree/tugas-5).
- **Pertemuan 4:** Blade Templating & Integrasi Frontend Modern. Master layout, Blade components, Vite + Tailwind.
- **Pertemuan 2:** Laravel Local Sandbox (routing dasar). Versi saat dikumpulkan ada di tag [`tugas-2`](https://github.com/setiAjiid/Sistem-Akademik-Profil-Mahasiswa_Dimas-Setiaji/tree/tugas-2).

| | |
|---|---|
| Nama | Dimas Setiaji |
| NRP | 5025241056 |
| Kelas / Kelompok | B / B1 |
| Framework | Laravel 13, PHP 8.5, Vite 8, Tailwind CSS 4 |

Mini-website akademik pribadi berisi profil diri, rancangan platform Agentic AI kelompok (TokenPilot), dan formulir pengumpulan ide. Sejak Tugas 5 ada portal login untuk mahasiswa, dosen, dan asdos.

## Cara Menjalankan

```bash
composer install
pnpm install            # atau npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # file database SQLite (Windows: type nul > database\database.sqlite)
php artisan migrate --seed       # buat tabel dan isi akun uji coba
```

Lalu jalankan dua terminal:

```bash
pnpm dev                # terminal 1: Vite (CSS, JS, font)
php artisan serve       # terminal 2: server Laravel
```

Buka <http://localhost:8000>. Tanpa `pnpm dev`, jalankan `pnpm build` sekali agar `public/build` terisi.

## Akun Uji Coba

Dibuat oleh `php artisan db:seed`. Semua memakai password `password`.

| Role | Email |
|---|---|
| Dosen | `dosen@example.com` |
| Asdos | `asdos@example.com` |
| Mahasiswa | `mahasiswa@example.com` |

Saat `APP_ENV=local`, halaman login punya tombol **Akun uji coba** untuk masuk tanpa mengetik password (`/login/as/dosen`, `/login/as/asdos`, `/login/as/mahasiswa`). Di environment lain rute itu menjawab 404.

## Tugas 9: Upload File dan API JSON

**Upload foto mahasiswa** (form tambah dan ubah di `/admin/mahasiswa`):

- Kolom baru `foto` berisi path file di disk `public`. File disimpan di `storage/app/public/foto-mahasiswa`.
- Validasi: harus gambar JPG atau PNG, maksimal 2 MB.
- Saat foto diganti atau mahasiswa dihapus, file lama ikut dihapus. Mengubah data tanpa memilih foto baru tidak menghapus foto lama.
- Jalankan `php artisan storage:link` sekali supaya foto bisa dibuka dari browser.

**API JSON read-only** (publik, dibatasi 30 permintaan per menit):

| Endpoint | Keterangan |
|---|---|
| `GET /api/mahasiswa` | Daftar terpaginasi. Parameter opsional `departemen_id` dan `per_page` (maksimal 50) |
| `GET /api/mahasiswa/{id}` | Detail satu mahasiswa, 404 kalau tidak ada |

Bentuk JSON diatur `MahasiswaResource`, jadi kolom internal seperti `created_at` dan `departemen_id` tidak ikut keluar. Departemen hanya ikut tampil kalau sudah di-eager load (`whenLoaded`).

## Tugas 8: Relasi Eloquent dan Seeder

Relasi antar model:

| Model | Relasi |
|---|---|
| `Departemen` | `hasMany` mahasiswas, dosens, tugas |
| `Dosen` | `belongsTo` departemen, `hasMany` tugas |
| `Mahasiswa` | `belongsTo` departemen |
| `Tugas` | `belongsTo` departemen dan dosen |

- **Eager loading**: daftar mahasiswa dan dosen memakai `with('departemen')`, jadi nama departemen untuk 10 baris diambil dalam 1 query tambahan, bukan 10 (masalah N+1). Ada test yang menghitung jumlah query-nya.
- **withCount**: halaman `/admin/departemen` menampilkan jumlah mahasiswa, dosen, dan tugas per departemen dengan `withCount(['mahasiswas', 'dosens', 'tugas'])`.
- **Seeder Faker**: `php artisan db:seed` mengisi 6 departemen, 24 dosen (4 per departemen), 60 mahasiswa, 25 tugas, dan 3 akun uji coba. Tugas selalu diberikan dosen dari departemen yang sama.

## Tugas 7: CRUD Mahasiswa dan Dosen

Halaman kelola data di area pengelola (login sebagai dosen atau asdos):

| URL | Fungsi |
|---|---|
| `/admin/mahasiswa` | Daftar (10 per halaman), tambah, ubah, hapus mahasiswa |
| `/admin/dosen` | Daftar (10 per halaman), tambah, ubah, hapus dosen |

- Rute dibuat dengan `Route::resource(...)->except('show')`, di dalam grup `/admin` yang dijaga `cek.peran:dosen,asdos`.
- Validasi ada di Form Request (`SimpanMahasiswaRequest`, `SimpanDosenRequest`) dengan pesan Bahasa Indonesia. NRP harus 10 digit dan NIP 18 digit. Keduanya unik, dan aturan `unique` mengabaikan data yang sedang diubah.
- Form tambah dan ubah memakai satu partial `_form.blade.php`. Setelah simpan, user diarahkan kembali ke daftar dengan pesan sukses.
- Departemen diisi oleh `DepartemenSeeder` (6 departemen FTEIC).

## Tugas 6: Migrations dan Eloquent

Skema database akademik. Semua tabel dibuat lewat migration (`database/migrations/`), dan tiap tabel punya model serta factory.

| Tabel | Model | Kolom utama |
|---|---|---|
| `departemens` | `Departemen` | `kode` (unik), `nama` (unik) |
| `dosens` | `Dosen` | `nip` (unik), `nama`, `email`, `departemen_id` |
| `mahasiswas` | `Mahasiswa` | `nrp` (unik), `nama`, `departemen_id`, `angkatan`, `ipk`, `is_active` |
| `tugas` | `Tugas` | `judul`, `deskripsi`, `departemen_id`, `dosen_id`, `tenggat` |

- Kolom yang sering dicari (`nama`, `judul`) diberi indeks, dan `nrp`/`nip` unik.
- Foreign key memakai `constrained()`. Menghapus departemen ikut menghapus mahasiswa, dosen, dan tugasnya (`cascadeOnDelete`). Menghapus dosen hanya mengosongkan `dosen_id` pada tugas (`nullOnDelete`).
- Model memakai `#[Fillable]`, `casts()` (`ipk` desimal, `is_active` boolean, `tenggat` tanggal), dan scope `aktif()` pada `Mahasiswa` dan `Tugas`.

## Tugas 5: Middleware, Autentikasi, dan Otorisasi

Tiga area halaman:

| Area | URL | Siapa yang boleh |
|---|---|---|
| Publik | `/`, `/profil-mahasiswa`, `/ide-agent`, dan halaman lain | Semua orang |
| Mahasiswa | `/proposal` (daftar, buat, ubah) | User login dengan role `mahasiswa` |
| Dosen dan asdos | `/admin/proposal` (tinjau dan beri nilai) | User login dengan role `dosen` atau `asdos` |

Pemenuhan spesifikasi:

- **Breeze**: register, login, logout, dan edit profil berfungsi. Akun baru otomatis berrole `mahasiswa`. Setelah login, user masuk ke `/portal`.
- **Kolom `role`**: migration `add_role_to_users_table` (string, default `mahasiswa`).
- **Custom middleware**: `App\Http\Middleware\CekPeran`, alias `cek.peran` di `bootstrap/app.php`. Dipakai sebagai `cek.peran:dosen,asdos`. Tamu diarahkan ke login. User yang rolenya tidak cocok dikembalikan ke `/portal` dengan pesan error.
- **Gate global**: `input-nilai` di `AppServiceProvider`, hanya lolos untuk dosen dan asdos.
- **Policy**: `ProposalPolicy`. Hanya mahasiswa pembuat proposal yang boleh menyunting judul dan isinya, selain itu 403.
- **Proteksi di Blade**: link dan form input nilai dibungkus `@can('input-nilai')`. Tombol "Ubah" proposal dibungkus `@can('update', $proposal)`. Pengecekan diulang di controller, jadi tidak bisa ditembus lewat request manual.
- **Challenge**: `throttle:3,1` pada `POST /proposal` (maksimal 3 kali per menit), dan tombol login instan akun uji coba.

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
