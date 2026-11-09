<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tugas>
 */
class TugasFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'departemen_id' => Departemen::factory(),
            'dosen_id' => null,
            'tenggat' => fake()->dateTimeBetween('-1 month', '+2 months'),
        ];
    }
}
