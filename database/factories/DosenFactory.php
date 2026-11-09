<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // NIP 18 digit angka.
            'nip' => fake()->unique()->numerify('##################'),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'departemen_id' => Departemen::factory(),
        ];
    }
}
