<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(3),
        ];
    }

    /** State untuk proposal yang sudah dinilai. */
    public function dinilai(?User $penilai = null): static
    {
        return $this->state(fn (array $attributes) => [
            'nilai' => fake()->numberBetween(60, 100),
            'catatan' => fake()->sentence(),
            'dinilai_oleh' => $penilai?->id ?? User::factory()->dosen(),
        ]);
    }
}
