<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DepartemenSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Memeriksa relasi Eloquent, eager loading, dan seeder Faker (Pertemuan 8).
 */
class Tugas8Test extends TestCase
{
    use RefreshDatabase;

    public function test_relasi_antar_model(): void
    {
        $departemen = Departemen::factory()->create();
        $dosen = Dosen::factory()->for($departemen)->create();
        $mahasiswa = Mahasiswa::factory()->for($departemen)->create();
        $tugas = Tugas::factory()->for($departemen)->for($dosen)->create();

        $this->assertTrue($mahasiswa->departemen->is($departemen));
        $this->assertTrue($dosen->departemen->is($departemen));
        $this->assertTrue($tugas->dosen->is($dosen));
        $this->assertTrue($departemen->mahasiswas->first()->is($mahasiswa));
        $this->assertTrue($departemen->dosens->first()->is($dosen));
        $this->assertTrue($dosen->tugas->first()->is($tugas));
        $this->assertTrue($departemen->tugas->first()->is($tugas));
    }

    public function test_tugas_tetap_ada_saat_dosennya_dihapus(): void
    {
        $dosen = Dosen::factory()->create();
        $tugas = Tugas::factory()->for($dosen)->create();

        $dosen->delete();

        $this->assertNull($tugas->fresh()->dosen_id);
    }

    public function test_daftar_mahasiswa_tidak_n_plus_1(): void
    {
        Mahasiswa::factory()->count(10)->create();

        DB::enableQueryLog();
        $this->actingAs(User::factory()->dosen()->admin()->create())->get('/admin/mahasiswa')->assertOk();

        // Hitung query ke tabel departemens saja. Eager loading = 1 query untuk 10 baris,
        // ditambah 1 query untuk isi dropdown filter. Tanpa eager loading jumlahnya 10 atau lebih.
        $kueriDepartemen = collect(DB::getQueryLog())
            ->filter(fn (array $kueri) => str_contains($kueri['query'], 'from "departemens"'))
            ->count();

        $this->assertLessThanOrEqual(2, $kueriDepartemen, "Query departemens: {$kueriDepartemen}");
    }

    public function test_ringkasan_departemen_menghitung_relasi(): void
    {
        $departemen = Departemen::factory()->create();
        Mahasiswa::factory()->count(3)->for($departemen)->create();
        Dosen::factory()->count(2)->for($departemen)->create();

        $this->actingAs(User::factory()->asdos()->staf()->create())
            ->get('/admin/departemen')
            ->assertOk()
            ->assertViewHas('departemens', function ($daftar) {
                $d = $daftar->first();

                return $d->mahasiswas_count === 3 && $d->dosens_count === 2 && $d->tugas_count === 0;
            });
    }

    public function test_seeder_mengisi_puluhan_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(6, Departemen::count());
        $this->assertSame(24, Dosen::count());
        $this->assertSame(60, Mahasiswa::count());
        $this->assertSame(25, Tugas::count());
        $this->assertSame(3, User::count());
    }

    public function test_seeder_aman_dijalankan_dua_kali_untuk_departemen(): void
    {
        $this->seed(DepartemenSeeder::class);
        $this->seed(DepartemenSeeder::class);

        $this->assertSame(6, Departemen::count());
    }
}
