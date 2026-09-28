<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Memeriksa spesifikasi Tugas Mandiri Pertemuan 4: tiga halaman di PageController,
 * master layout, komponen <x-info-card> dan <x-status-banner>, formulir ide, dan dua challenge.
 */
class Tugas4Test extends TestCase
{
    public function test_tiga_rute_wajib_mengarah_ke_page_controller(): void
    {
        $router = app('router')->getRoutes();

        foreach (['/', 'profil-mahasiswa', 'ide-agent'] as $uri) {
            $rute = $router->match(request()->create($uri));
            $this->assertSame('App\Http\Controllers\PageController', $rute->getControllerClass(), "Rute /{$uri}");
        }
    }

    public function test_semua_halaman_memakai_master_layout_yang_sama(): void
    {
        foreach (['home', 'profil', 'ide-agent'] as $nama) {
            $this->get(route($nama))
                ->assertOk()
                ->assertSee('Tugas Mandiri PBKK Pertemuan 4')      // footer layout
                ->assertSee('| Sistem Akademik Profil Mahasiswa', false); // title dinamis
        }
    }

    public function test_profil_memakai_komponen_info_card(): void
    {
        $this->get(route('profil'))
            ->assertOk()
            ->assertSee('Data diri')
            ->assertSee('Ringkasan studi')
            ->assertSee('Prestasi')
            ->assertSee('rounded-2xl border', false); // class dari components/info-card
    }

    public function test_ide_agent_menampilkan_rancangan_dan_formulir(): void
    {
        $this->get(route('ide-agent'))
            ->assertOk()
            ->assertSee('Rancangan platform')
            ->assertSee('Test Runner')
            ->assertSee('name="_token"', false) // @csrf
            ->assertSee('Kirim ide');
    }

    public function test_kirim_ide_valid_menampilkan_banner_sukses_dan_daftar_ide(): void
    {
        $this->post(route('ide-agent.kirim'), [
            'nama' => 'Dimas',
            'judul' => 'Agent peninjau PR',
            'kategori' => 'produktivitas',
            'deskripsi' => 'Agent yang meninjau pull request dan memberi saran perbaikan.',
        ])->assertRedirect(route('ide-agent'));

        $this->get(route('ide-agent'))
            ->assertSee('Ide terkirim')
            ->assertSee('Agent peninjau PR')
            ->assertSee('Produktivitas Developer')
            ->assertSee('bg-green-50', false); // <x-status-banner tipe="success">
    }

    public function test_kirim_ide_tidak_valid_menampilkan_banner_error(): void
    {
        $this->from(route('ide-agent'))
            ->post(route('ide-agent.kirim'), [
                'nama' => '',
                'judul' => 'Ide',
                'kategori' => 'tidak-ada',
                'deskripsi' => 'terlalu pendek',
            ])
            ->assertRedirect(route('ide-agent'));

        // Pesan error dicek langsung dari banner. (assertSessionHasErrors() tidak dipakai karena
        // setelah dipanggil, $errors di request berikutnya dalam test yang sama jadi kosong.)
        $this->get(route('ide-agent'))
            ->assertSee('Ide belum terkirim')
            ->assertSee('bg-red-50', false) // <x-status-banner tipe="error">
            ->assertSee('Nama wajib diisi.')
            ->assertSee('Kategori yang dipilih tidak tersedia.')
            ->assertSee('Deskripsi minimal 20 karakter.')
            ->assertDontSee('Judul ide wajib diisi.') // judul sudah terisi, jadi tidak error
            ->assertSee('terlalu pendek'); // old() mengisi ulang form
    }

    /** Challenge 1 */
    public function test_mode_dark_menambah_class_dark_di_html(): void
    {
        $this->get(route('ide-agent'))
            ->assertDontSee('<html lang="id" class="dark">', false)
            ->assertSee('bg-slate-50', false);

        $this->get(route('ide-agent', ['mode' => 'dark']))
            ->assertSee('<html lang="id" class="dark">', false)
            ->assertSee('bg-[#0b1120]', false)
            ->assertSee('Mode gelap aktif');
    }

    public function test_mode_dark_terbawa_di_tautan_navbar(): void
    {
        $this->get(route('ide-agent', ['mode' => 'dark']))
            ->assertSee(route('profil', ['mode' => 'dark']), false);
    }

    /** Challenge 2 */
    public function test_beranda_dengan_parameter_user_menampilkan_sapaan(): void
    {
        $this->get('/beranda?user=Andi')
            ->assertOk()
            ->assertSee('Selamat datang, Andi!');

        $this->get('/beranda')
            ->assertOk()
            ->assertDontSee('data-status-banner', false);
    }

    public function test_parameter_user_di_escape_supaya_aman_dari_xss(): void
    {
        $this->get('/beranda?user=<script>alert(1)</script>')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }
}
