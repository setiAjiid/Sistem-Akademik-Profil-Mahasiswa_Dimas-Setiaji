<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Memeriksa dua challenge Tugas 5: rate limiting proposal dan login instan akun uji coba.
 */
class Tugas5ChallengeTest extends TestCase
{
    use RefreshDatabase;

    public function test_kirim_proposal_dibatasi_tiga_kali_per_menit(): void
    {
        $mahasiswa = User::factory()->create();
        $data = [
            'judul' => 'Proposal contoh',
            'deskripsi' => 'Deskripsi proposal contoh yang cukup panjang.',
        ];

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($mahasiswa)->post('/proposal', $data)->assertRedirect(route('proposal.index'));
        }

        $this->actingAs($mahasiswa)->post('/proposal', $data)->assertStatus(429);
        $this->assertDatabaseCount('proposals', 3);
    }

    public function test_login_instan_berhasil_di_environment_local(): void
    {
        $this->app['env'] = 'local';
        $dosen = User::factory()->dosen()->create();

        $this->get('/login/as/dosen')->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($dosen);
    }

    public function test_login_instan_ditolak_di_luar_environment_local(): void
    {
        $this->app['env'] = 'production';
        User::factory()->dosen()->create();

        $this->get('/login/as/dosen')->assertNotFound();
        $this->assertGuest();
    }

    public function test_login_instan_menolak_role_yang_tidak_dikenal(): void
    {
        $this->app['env'] = 'local';

        $this->get('/login/as/admin')->assertNotFound();
        $this->assertGuest();
    }
}
