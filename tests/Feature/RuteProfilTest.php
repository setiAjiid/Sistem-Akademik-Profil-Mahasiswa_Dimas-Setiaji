<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Memeriksa spesifikasi Tugas Mandiri Pertemuan 2: tiga rute wajib, named routes,
 * regex NRP, kalkulator IPK, prefix /dashboard, dan fallback route.
 */
class RuteProfilTest extends TestCase
{
    public function test_home_menampilkan_sambutan_dan_profil_singkat(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Selamat datang')
            ->assertSee('Dimas Setiaji')
            ->assertSee('5025241056');
    }

    public function test_detail_mahasiswa_dengan_nrp_terdaftar(): void
    {
        $this->get(route('mahasiswa.show', ['nrp' => '5025241056']))
            ->assertOk()
            ->assertSee('Dimas Setiaji')
            ->assertSee('Teknik Informatika');
    }

    public function test_nrp_valid_tetapi_tidak_terdaftar_mengembalikan_404(): void
    {
        $this->get('/mahasiswa/9999999999')
            ->assertNotFound()
            ->assertSee('Halaman Tidak Ditemukan');
    }

    /** Challenge 1: where() hanya menerima 10 digit angka. */
    public function test_regex_nrp_menolak_format_selain_10_digit(): void
    {
        $this->get('/mahasiswa/123')->assertNotFound();
        $this->get('/mahasiswa/50252410567')->assertNotFound();
        $this->get('/mahasiswa/abcdefghij')->assertNotFound();
    }

    public function test_agent_tanpa_tema_memakai_nilai_default(): void
    {
        $this->get(route('agent.show'))
            ->assertOk()
            ->assertSee('General Assistant Agent');
    }

    public function test_agent_dengan_tema_tokenpilot_menampilkan_penjelasan(): void
    {
        $this->get(route('agent.show', ['tema' => 'tokenpilot']))
            ->assertOk()
            ->assertSee('TokenPilot')
            ->assertSee('Teknologi yang dipakai');
    }

    public function test_agent_dengan_tema_tidak_dikenal_memberi_pesan(): void
    {
        $this->get(route('agent.show', ['tema' => 'tema-aneh']))
            ->assertOk()
            ->assertSee('tidak dikenal');
    }

    /** Challenge 2 */
    public function test_kalkulator_ipk_menghitung_jumlah_dan_rata_rata(): void
    {
        $this->get(route('ipk.hitung', ['ip1' => '3.5', 'ip2' => '3.75']))
            ->assertOk()
            ->assertSee('7.25')  // jumlah
            ->assertSee('3.63'); // rata-rata 3.625, dibulatkan dua desimal
    }

    public function test_kalkulator_ipk_menolak_nilai_di_luar_rentang(): void
    {
        $this->get(route('ipk.hitung', ['ip1' => '5', 'ip2' => '3']))
            ->assertOk()
            ->assertSee('rentang 0 sampai 4');
    }

    public function test_kalkulator_ipk_menolak_input_bukan_angka_lewat_regex(): void
    {
        $this->get('/hitung-ipk/abc/3')->assertNotFound();
    }

    /** Challenge 3 */
    public function test_rute_dashboard_berada_di_prefix_dan_bernama_dashboard(): void
    {
        $this->assertSame(url('/dashboard'), route('dashboard.index'));
        $this->assertSame(url('/dashboard/riwayat-studi'), route('dashboard.riwayat'));

        $this->get(route('dashboard.index'))->assertOk()->assertSee('IPK');
        $this->get(route('dashboard.riwayat'))->assertOk()->assertSee('Dasar Pemrograman');
    }

    public function test_fallback_route_menangani_url_tidak_dikenal(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman Tidak Ditemukan')
            ->assertSee('halaman-yang-tidak-ada');
    }

    public function test_semua_rute_aplikasi_memiliki_nama(): void
    {
        $tanpaNama = collect(app('router')->getRoutes()->getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods()))
            // rute bawaan framework (/up, /_ignition, dll.) dilewati
            ->reject(fn ($r) => str_starts_with($r->uri(), 'up') || str_starts_with($r->uri(), '_'))
            ->filter(fn ($r) => $r->getName() === null)
            ->map(fn ($r) => $r->uri())
            ->values()
            ->all();

        $this->assertSame([], $tanpaNama, 'Rute berikut belum memakai name(): '.implode(', ', $tanpaNama));
    }
}
