<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Memeriksa skema database (migrations) dan model Eloquent Pertemuan 6.
 */
class Tugas6Test extends TestCase
{
    use RefreshDatabase;

    public function test_semua_tabel_dan_kolom_penting_tersedia(): void
    {
        $kolom = [
            'departemens' => ['kode', 'nama'],
            'dosens' => ['nip', 'nama', 'email', 'departemen_id'],
            'mahasiswas' => ['nrp', 'nama', 'departemen_id', 'angkatan', 'ipk', 'is_active'],
            'tugas' => ['judul', 'deskripsi', 'departemen_id', 'dosen_id', 'tenggat'],
        ];

        foreach ($kolom as $tabel => $daftar) {
            $this->assertTrue(Schema::hasTable($tabel), "Tabel {$tabel}");
            $this->assertTrue(Schema::hasColumns($tabel, $daftar), "Kolom tabel {$tabel}");
        }
    }

    public function test_model_bisa_disimpan_dengan_mass_assignment(): void
    {
        $dept = Departemen::create(['kode' => 'IF', 'nama' => 'Teknik Informatika']);
        $mhs = Mahasiswa::create([
            'nrp' => '5025241056',
            'nama' => 'Dimas Setiaji',
            'departemen_id' => $dept->id,
            'angkatan' => 2024,
            'ipk' => 3.75,
        ]);

        $this->assertDatabaseHas('mahasiswas', ['nrp' => '5025241056']);
        $this->assertTrue($mhs->fresh()->is_active, 'Default is_active harus true.');
    }

    public function test_cast_ipk_dan_tenggat(): void
    {
        $mhs = Mahasiswa::factory()->create(['ipk' => 3.5])->fresh();
        $tugas = Tugas::factory()->create(['tenggat' => '2026-12-01'])->fresh();

        $this->assertSame('3.50', $mhs->ipk);
        $this->assertTrue($tugas->tenggat->isSameDay('2026-12-01'));
    }

    public function test_nrp_dan_nip_harus_unik(): void
    {
        Mahasiswa::factory()->create(['nrp' => '5025241056']);
        Dosen::factory()->create(['nip' => '198001012005011001']);

        $this->expectException(QueryException::class);
        Mahasiswa::factory()->create(['nrp' => '5025241056']);
    }

    public function test_nip_ganda_ditolak_database(): void
    {
        Dosen::factory()->create(['nip' => '198001012005011001']);

        $this->expectException(QueryException::class);
        Dosen::factory()->create(['nip' => '198001012005011001']);
    }

    public function test_scope_aktif_pada_mahasiswa_dan_tugas(): void
    {
        Mahasiswa::factory()->create(['is_active' => true]);
        Mahasiswa::factory()->cuti()->create();
        Tugas::factory()->create(['tenggat' => now()->addDays(3)]);
        Tugas::factory()->create(['tenggat' => now()->subDays(3)]);

        $this->assertSame(1, Mahasiswa::aktif()->count());
        $this->assertSame(1, Tugas::aktif()->count());
    }

    public function test_menghapus_departemen_ikut_menghapus_mahasiswanya(): void
    {
        $dept = Departemen::factory()->create();
        Mahasiswa::factory()->count(2)->create(['departemen_id' => $dept->id]);

        $dept->delete();

        $this->assertDatabaseCount('mahasiswas', 0);
    }
}
