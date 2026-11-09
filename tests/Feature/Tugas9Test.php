<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Memeriksa upload foto mahasiswa dan API JSON (Pertemuan 9).
 */
class Tugas9Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @return array<string, mixed> */
    private function data(array $timpa = []): array
    {
        return array_merge([
            'nrp' => '5025241056',
            'nama' => 'Dimas Setiaji',
            'departemen_id' => Departemen::factory()->create()->id,
            'angkatan' => 2024,
            'is_active' => '1',
        ], $timpa);
    }

    private function pengelola(): User
    {
        return User::factory()->asdos()->create();
    }

    public function test_foto_diunggah_dan_path_disimpan(): void
    {
        $this->actingAs($this->pengelola())
            ->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->image('dimas.jpg', 300, 300)]))
            ->assertRedirect(route('admin.mahasiswa.index'));

        $mhs = Mahasiswa::firstWhere('nrp', '5025241056');
        $this->assertNotNull($mhs->foto);
        Storage::disk('public')->assertExists($mhs->foto);
    }

    public function test_file_bukan_gambar_atau_terlalu_besar_ditolak(): void
    {
        $pengelola = $this->pengelola();

        $this->actingAs($pengelola)
            ->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->create('virus.pdf', 100, 'application/pdf')]))
            ->assertSessionHasErrors('foto');

        $this->actingAs($pengelola)
            ->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->image('besar.jpg')->size(3000)]))
            ->assertSessionHasErrors('foto');

        $this->assertDatabaseCount('mahasiswas', 0);
    }

    public function test_mengganti_foto_menghapus_foto_lama(): void
    {
        $pengelola = $this->pengelola();
        $this->actingAs($pengelola)->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->image('lama.jpg')]));
        $mhs = Mahasiswa::firstWhere('nrp', '5025241056');
        $fotoLama = $mhs->foto;

        $this->actingAs($pengelola)->put("/admin/mahasiswa/{$mhs->id}", $this->data([
            'departemen_id' => $mhs->departemen_id,
            'foto' => UploadedFile::fake()->image('baru.png'),
        ]));

        Storage::disk('public')->assertMissing($fotoLama);
        Storage::disk('public')->assertExists($mhs->fresh()->foto);
    }

    public function test_ubah_data_tanpa_foto_baru_tidak_menghapus_foto_lama(): void
    {
        $pengelola = $this->pengelola();
        $this->actingAs($pengelola)->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->image('lama.jpg')]));
        $mhs = Mahasiswa::firstWhere('nrp', '5025241056');
        $fotoLama = $mhs->foto;

        $this->actingAs($pengelola)->put("/admin/mahasiswa/{$mhs->id}", $this->data([
            'departemen_id' => $mhs->departemen_id,
            'nama' => 'Nama Baru',
        ]));

        $this->assertSame($fotoLama, $mhs->fresh()->foto);
        Storage::disk('public')->assertExists($fotoLama);
    }

    public function test_menghapus_mahasiswa_menghapus_fotonya(): void
    {
        $pengelola = $this->pengelola();
        $this->actingAs($pengelola)->post('/admin/mahasiswa', $this->data(['foto' => UploadedFile::fake()->image('a.jpg')]));
        $mhs = Mahasiswa::firstWhere('nrp', '5025241056');

        $this->actingAs($pengelola)->delete("/admin/mahasiswa/{$mhs->id}");

        Storage::disk('public')->assertMissing($mhs->foto);
    }

    public function test_api_daftar_mahasiswa_berbentuk_json_terpaginasi(): void
    {
        Mahasiswa::factory()->count(12)->create();

        $this->getJson('/api/mahasiswa')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 12)
            ->assertJsonStructure(['data' => [['id', 'nrp', 'nama', 'angkatan', 'ipk', 'status', 'foto', 'departemen' => ['kode', 'nama']]]]);
    }

    public function test_api_bisa_difilter_dan_dibatasi_per_halaman(): void
    {
        $dept = Departemen::factory()->create();
        Mahasiswa::factory()->count(3)->for($dept)->create();
        Mahasiswa::factory()->count(2)->create();

        $this->getJson("/api/mahasiswa?departemen_id={$dept->id}")->assertJsonCount(3, 'data');
        $this->getJson('/api/mahasiswa?per_page=2')->assertJsonCount(2, 'data');
        $this->getJson('/api/mahasiswa?per_page=999')->assertJsonPath('meta.per_page', 50);
    }

    public function test_api_detail_mahasiswa_dan_404(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->getJson("/api/mahasiswa/{$mhs->id}")
            ->assertOk()
            ->assertJsonPath('data.nrp', $mhs->nrp);

        $this->getJson('/api/mahasiswa/99999')->assertNotFound();
    }

    public function test_api_tidak_membocorkan_field_internal(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->getJson("/api/mahasiswa/{$mhs->id}")
            ->assertJsonMissingPath('data.created_at')
            ->assertJsonMissingPath('data.departemen_id');
    }
}
