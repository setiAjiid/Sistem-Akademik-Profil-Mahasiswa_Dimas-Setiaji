<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Memeriksa spesifikasi Tugas Pertemuan 5: role, middleware cek.peran,
 * Gate input-nilai, ProposalPolicy, dan proteksi tampilan di Blade.
 */
class Tugas5Test extends TestCase
{
    use RefreshDatabase;

    public function test_akun_baru_dari_register_otomatis_menjadi_mahasiswa(): void
    {
        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password-kuat-123',
            'password_confirmation' => 'password-kuat-123',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertSame('mahasiswa', User::where('email', 'budi@example.com')->value('role'));
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_area_admin(): void
    {
        $this->get('/admin/proposal')->assertRedirect(route('login'));
    }

    public function test_mahasiswa_ditolak_middleware_cek_peran_dengan_pesan_error(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/proposal')
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_dosen_dan_asdos_boleh_membuka_area_admin(): void
    {
        foreach ([User::factory()->dosen(), User::factory()->asdos()] as $factory) {
            $this->actingAs($factory->create())
                ->get('/admin/proposal')
                ->assertOk();
        }
    }

    public function test_dosen_tidak_boleh_membuka_area_proposal_mahasiswa(): void
    {
        $this->actingAs(User::factory()->dosen()->create())
            ->get('/proposal')
            ->assertRedirect(route('dashboard'));
    }

    public function test_mahasiswa_bisa_membuat_proposal(): void
    {
        $mahasiswa = User::factory()->create();

        $this->actingAs($mahasiswa)
            ->post('/proposal', [
                'judul' => 'Agent pemantau kesehatan database',
                'deskripsi' => 'Agent yang memeriksa performa query dan memberi saran indeks.',
            ])
            ->assertRedirect(route('proposal.index'));

        $this->assertDatabaseHas('proposals', [
            'user_id' => $mahasiswa->id,
            'judul' => 'Agent pemantau kesehatan database',
        ]);
    }

    public function test_hanya_pembuat_proposal_yang_boleh_menyunting(): void
    {
        $pembuat = User::factory()->create();
        $proposal = Proposal::factory()->for($pembuat, 'pembuat')->create();

        $this->actingAs($pembuat)->get("/proposal/{$proposal->id}/ubah")->assertOk();

        $this->actingAs(User::factory()->create())
            ->put("/proposal/{$proposal->id}", ['judul' => 'Diganti orang lain', 'deskripsi' => str_repeat('x', 30)])
            ->assertForbidden();

        $this->assertNotSame('Diganti orang lain', $proposal->fresh()->judul);
    }

    public function test_gate_input_nilai_hanya_untuk_dosen_dan_asdos(): void
    {
        $this->assertTrue(User::factory()->dosen()->make()->can('input-nilai'));
        $this->assertTrue(User::factory()->asdos()->make()->can('input-nilai'));
        $this->assertFalse(User::factory()->make()->can('input-nilai'));
    }

    public function test_dosen_bisa_memberi_nilai(): void
    {
        $dosen = User::factory()->dosen()->create();
        $proposal = Proposal::factory()->create();

        $this->actingAs($dosen)
            ->put("/admin/proposal/{$proposal->id}/nilai", ['nilai' => 88, 'catatan' => 'Sudah bagus'])
            ->assertRedirect();

        $this->assertSame(88, $proposal->fresh()->nilai);
        $this->assertSame($dosen->id, $proposal->fresh()->dinilai_oleh);
    }

    public function test_mahasiswa_tidak_bisa_memberi_nilai_lewat_request_manual(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put("/admin/proposal/{$proposal->id}/nilai", ['nilai' => 100])
            ->assertRedirect(route('dashboard'));

        $this->assertNull($proposal->fresh()->nilai);
    }

    public function test_nilai_di_luar_rentang_ditolak(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->dosen()->create())
            ->put("/admin/proposal/{$proposal->id}/nilai", ['nilai' => 150])
            ->assertSessionHasErrors('nilai');
    }

    public function test_link_input_nilai_hanya_terlihat_oleh_pengelola(): void
    {
        $proposal = Proposal::factory()->create();

        $this->actingAs(User::factory()->asdos()->create())
            ->get('/admin/proposal')
            ->assertSee(route('admin.proposal.show', $proposal));

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertDontSee('Penilaian Proposal');
    }
}
