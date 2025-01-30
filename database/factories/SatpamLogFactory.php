<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SatpamLog>
 */
class SatpamLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal'=>now()->date(),
            'jam_masuk'=>fake()->time(),
            'jam_keluar'=>time(),
            'nama_tamu'=>fake()->string(),
            'no_hp'=>fake()->string(),
            'keperluan'=>fake()->text(),
            'bertemu_siapa'=>fake()->string(),
            'ada_janji'=>fake()->boolean(),
        ];
    }
}
