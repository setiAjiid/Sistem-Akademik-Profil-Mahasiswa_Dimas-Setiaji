<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $angkatan = fake()->numberBetween(2021, 2025);

        return [
            // NRP 10 digit: 5025 + 2 digit angkatan + 4 digit urutan.
            'nrp' => '5025'.substr((string) $angkatan, 2).fake()->unique()->numerify('####'),
            'nama' => fake()->name(),
            'departemen_id' => Departemen::factory(),
            'angkatan' => $angkatan,
            'ipk' => fake()->randomFloat(2, 2.0, 4.0),
            'is_active' => fake()->boolean(90),
        ];
    }

    /** State untuk mahasiswa yang sedang cuti akademik. */
    public function cuti(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
