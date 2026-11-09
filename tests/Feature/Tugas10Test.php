<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Memeriksa spesifikasi Tugas Pertemuan 10: dashboard AdminLTE, RBAC Spatie,
 * widget statistik, serta pencarian dan filter dengan when() dan withQueryString().
 */
class Tugas10Test extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->dosen()->admin()->create();
    }

    private function staf(): User
    {
        return User::factory()->asdos()->staf()->create();
    }

    public function test_seeder_membuat_role_admin_dan_staf_dengan_permission_yang_benar(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = Role::findByName('admin');
        $staf = Role::findByName('staf');

        $this->assertTrue($admin->hasPermissionTo('hapus-mahasiswa'));
        $this->assertTrue($admin->hasPermissionTo('hapus-dosen'));
        $this->assertFalse($staf->hasPermissionTo('hapus-mahasiswa'));
        $this->assertFalse($staf->hasPermissionTo('hapus-dosen'));
        $this->assertTrue($staf->hasPermissionTo('edit-mahasiswa'));
        $this->assertSame(9, $admin->permissions()->count());
        $this->assertSame(7, $staf->permissions()->count());
    }

    public function test_seeder_role_aman_dijalankan_dua_kali(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(RoleAndPermissionSeeder::class);

        $this->assertSame(2, Role::count());
    }

    public function test_hanya_admin_dan_staf_yang_bisa_membuka_panel(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())->get('/admin/dashboard')->assertForbidden();
        // Dosen tanpa role Spatie juga tidak bisa masuk, role kolom users tidak cukup.
        $this->actingAs(User::factory()->dosen()->create())->get('/admin/dashboard')->assertForbidden();

        $this->actingAs($this->admin())->get('/admin/dashboard')->assertOk();
        $this->actingAs($this->staf())->get('/admin/dashboard')->assertOk();
    }

    public function test_dashboard_memakai_layout_adminlte_dan_menampilkan_tiga_widget_dinamis(): void
    {
        Mahasiswa::factory()->count(4)->create(['is_active' => true]);
        Mahasiswa::factory()->cuti()->count(1)->create();
        Dosen::factory()->count(2)->create();
        Tugas::factory()->count(3)->create(['tenggat' => now()->addWeek()]);
        Tugas::factory()->create(['tenggat' => now()->subWeek()]);

        $this->actingAs($this->admin())
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Total Mahasiswa')
            ->assertSee('Total Dosen')
            ->assertSee('Tugas Aktif')
            ->assertSee('small-box', false)
            ->assertViewHas('totalMahasiswa', 5)
            ->assertViewHas('mahasiswaAktif', 4)
            ->assertViewHas('totalDosen', 2)
            ->assertViewHas('totalTugasAktif', 3);
    }

    public function test_staf_tidak_bisa_menghapus_lewat_request_manual(): void
    {
        $mahasiswa = Mahasiswa::factory()->create();
        $dosen = Dosen::factory()->create();
        $staf = $this->staf();

        $this->actingAs($staf)->delete("/admin/mahasiswa/{$mahasiswa->id}")->assertForbidden();
        $this->actingAs($staf)->delete("/admin/dosen/{$dosen->id}")->assertForbidden();

        $this->assertModelExists($mahasiswa);
        $this->assertModelExists($dosen);
    }

    public function test_admin_bisa_menghapus(): void
    {
        $mahasiswa = Mahasiswa::factory()->create();

        $this->actingAs($this->admin())->delete("/admin/mahasiswa/{$mahasiswa->id}")->assertRedirect();

        $this->assertModelMissing($mahasiswa);
    }

    public function test_staf_masih_bisa_mengubah_data(): void
    {
        $mahasiswa = Mahasiswa::factory()->create();

        $this->actingAs($this->staf())
            ->put("/admin/mahasiswa/{$mahasiswa->id}", [
                'nrp' => $mahasiswa->nrp,
                'nama' => 'Nama Diubah Staf',
                'departemen_id' => $mahasiswa->departemen_id,
                'angkatan' => $mahasiswa->angkatan,
            ])
            ->assertRedirect(route('admin.mahasiswa.index'));

        $this->assertSame('Nama Diubah Staf', $mahasiswa->fresh()->nama);
    }

    public function test_tombol_hapus_hanya_dirender_untuk_admin(): void
    {
        Mahasiswa::factory()->create();

        // Form hapus memakai method spoofing DELETE, jadi itu penandanya.
        $this->actingAs($this->admin())->get('/admin/mahasiswa')->assertSee('value="DELETE"', false);
        $this->actingAs($this->staf())->get('/admin/mahasiswa')->assertDontSee('value="DELETE"', false)->assertSee('Ubah');
    }

    public function test_login_admin_diarahkan_ke_dashboard_admin_dan_mahasiswa_ke_portal(): void
    {
        $admin = User::factory()->dosen()->admin()->create(['email' => 'admin@example.com']);
        $mahasiswa = User::factory()->create(['email' => 'mhs@example.com']);

        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard', absolute: false));
        $this->assertAuthenticatedAs($admin);

        $this->post('/logout');

        $this->post('/login', ['email' => 'mhs@example.com', 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($mahasiswa);
    }

    public function test_pencarian_berdasarkan_nama_atau_nrp(): void
    {
        Mahasiswa::factory()->create(['nama' => 'Budi Santoso', 'nrp' => '5025240001']);
        Mahasiswa::factory()->create(['nama' => 'Sari Dewi', 'nrp' => '5025240002']);
        Mahasiswa::factory()->create(['nama' => 'Tono Wijaya', 'nrp' => '5025240003']);

        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/mahasiswa?search=budi')
            ->assertViewHas('mahasiswas', fn ($d) => $d->count() === 1 && $d->first()->nama === 'Budi Santoso');

        $this->actingAs($admin)->get('/admin/mahasiswa?search=5025240002')
            ->assertViewHas('mahasiswas', fn ($d) => $d->count() === 1 && $d->first()->nama === 'Sari Dewi');

        // Tanpa keyword, semua data tampil.
        $this->actingAs($admin)->get('/admin/mahasiswa')
            ->assertViewHas('mahasiswas', fn ($d) => $d->total() === 3);
    }

    public function test_filter_departemen_dan_status_bisa_digabung(): void
    {
        $if = Departemen::factory()->create();
        $si = Departemen::factory()->create();
        Mahasiswa::factory()->count(2)->for($if)->create(['is_active' => true]);
        Mahasiswa::factory()->for($if)->cuti()->create();
        Mahasiswa::factory()->for($si)->create(['is_active' => true]);

        $admin = $this->admin();

        $this->actingAs($admin)->get("/admin/mahasiswa?departemen_id={$if->id}")
            ->assertViewHas('mahasiswas', fn ($d) => $d->total() === 3);

        $this->actingAs($admin)->get("/admin/mahasiswa?departemen_id={$if->id}&status=cuti")
            ->assertViewHas('mahasiswas', fn ($d) => $d->total() === 1);

        $this->actingAs($admin)->get('/admin/mahasiswa?status=aktif')
            ->assertViewHas('mahasiswas', fn ($d) => $d->total() === 3);
    }

    public function test_filter_tetap_ada_di_link_halaman_berikutnya(): void
    {
        Mahasiswa::factory()->count(12)->create(['nama' => 'Cari Saya']);

        $this->actingAs($this->admin())
            ->get('/admin/mahasiswa?search=Cari')
            ->assertSee('search=Cari', false)
            ->assertViewHas('mahasiswas', fn ($d) => str_contains($d->nextPageUrl(), 'search=Cari'));
    }

    public function test_pencarian_dosen(): void
    {
        Dosen::factory()->create(['nama' => 'Prof. Hendra', 'nip' => '198001012005011001']);
        Dosen::factory()->create(['nama' => 'Dr. Maya', 'nip' => '198502022010012002']);

        $this->actingAs($this->admin())->get('/admin/dosen?search=maya')
            ->assertViewHas('dosens', fn ($d) => $d->count() === 1 && $d->first()->nama === 'Dr. Maya');
    }

    public function test_input_pencarian_aneh_tidak_merusak_halaman(): void
    {
        Mahasiswa::factory()->create();

        $this->actingAs($this->admin())->get('/admin/mahasiswa?search[]=a&status=ngawur&departemen_id=abc')->assertOk();
        $this->actingAs($this->admin())->get("/admin/mahasiswa?search=' OR 1=1 --")
            ->assertViewHas('mahasiswas', fn ($d) => $d->total() === 0);
    }
}
