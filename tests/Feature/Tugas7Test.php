<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Memeriksa CRUD Mahasiswa dan Dosen (Pertemuan 7): akses, validasi, simpan, ubah, hapus.
 */
class Tugas7Test extends TestCase
{
    use RefreshDatabase;

    private function pengelola(): User
    {
        return User::factory()->dosen()->create();
    }

    /** @return array<string, mixed> */
    private function dataMahasiswa(array $timpa = []): array
    {
        return array_merge([
            'nrp' => '5025241056',
            'nama' => 'Dimas Setiaji',
            'departemen_id' => Departemen::factory()->create()->id,
            'angkatan' => 2024,
            'ipk' => '3.75',
            'is_active' => '1',
        ], $timpa);
    }

    public function test_halaman_crud_hanya_untuk_dosen_dan_asdos(): void
    {
        $this->get('/admin/mahasiswa')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get('/admin/mahasiswa')
            ->assertRedirect(route('dashboard'));

        $this->actingAs($this->pengelola())->get('/admin/mahasiswa')->assertOk();
        $this->actingAs($this->pengelola())->get('/admin/dosen')->assertOk();
    }

    public function test_menambah_mahasiswa(): void
    {
        $this->actingAs($this->pengelola())
            ->post('/admin/mahasiswa', $this->dataMahasiswa())
            ->assertRedirect(route('admin.mahasiswa.index'));

        $this->assertDatabaseHas('mahasiswas', ['nrp' => '5025241056', 'nama' => 'Dimas Setiaji']);
    }

    public function test_validasi_mahasiswa_menolak_data_tidak_valid(): void
    {
        $this->actingAs($this->pengelola())
            ->post('/admin/mahasiswa', $this->dataMahasiswa(['nrp' => '123', 'ipk' => '5', 'departemen_id' => 999]))
            ->assertSessionHasErrors(['nrp', 'ipk', 'departemen_id']);

        $this->assertDatabaseCount('mahasiswas', 0);
    }

    public function test_nrp_ganda_ditolak_tapi_nrp_sendiri_boleh_saat_ubah(): void
    {
        $ada = Mahasiswa::factory()->create(['nrp' => '5025241056']);

        $this->actingAs($this->pengelola())
            ->post('/admin/mahasiswa', $this->dataMahasiswa())
            ->assertSessionHasErrors('nrp');

        $this->actingAs($this->pengelola())
            ->put("/admin/mahasiswa/{$ada->id}", $this->dataMahasiswa(['nama' => 'Nama Baru']))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.mahasiswa.index'));

        $this->assertSame('Nama Baru', $ada->fresh()->nama);
    }

    public function test_checkbox_aktif_yang_tidak_dicentang_menjadi_false(): void
    {
        $mhs = Mahasiswa::factory()->create(['is_active' => true]);
        $data = $this->dataMahasiswa(['nrp' => $mhs->nrp]);
        unset($data['is_active']);

        $this->actingAs($this->pengelola())->put("/admin/mahasiswa/{$mhs->id}", $data);

        $this->assertFalse($mhs->fresh()->is_active);
    }

    public function test_menghapus_mahasiswa(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->actingAs($this->pengelola())
            ->delete("/admin/mahasiswa/{$mhs->id}")
            ->assertRedirect(route('admin.mahasiswa.index'));

        $this->assertModelMissing($mhs);
    }

    public function test_crud_dosen(): void
    {
        $pengelola = $this->pengelola();
        $dept = Departemen::factory()->create();
        $data = ['nip' => '198001012005011001', 'nama' => 'Dr. Budi', 'email' => 'budi@its.ac.id', 'departemen_id' => $dept->id];

        $this->actingAs($pengelola)->post('/admin/dosen', $data)->assertRedirect(route('admin.dosen.index'));
        $dosen = Dosen::firstWhere('nip', '198001012005011001');
        $this->assertNotNull($dosen);

        $this->actingAs($pengelola)->put("/admin/dosen/{$dosen->id}", ['nama' => 'Dr. Budi, M.T.'] + $data);
        $this->assertSame('Dr. Budi, M.T.', $dosen->fresh()->nama);

        $this->actingAs($pengelola)->post('/admin/dosen', $data)->assertSessionHasErrors('nip');
        $this->actingAs($pengelola)->post('/admin/dosen', ['nip' => '12'] + $data)->assertSessionHasErrors('nip');

        $this->actingAs($pengelola)->delete("/admin/dosen/{$dosen->id}");
        $this->assertModelMissing($dosen);
    }

    public function test_daftar_mahasiswa_dipaginasi_10_per_halaman(): void
    {
        Mahasiswa::factory()->count(12)->create();

        $this->actingAs($this->pengelola())
            ->get('/admin/mahasiswa')
            ->assertOk()
            ->assertViewHas('mahasiswas', fn ($data) => $data->count() === 10 && $data->total() === 12);
    }
}
