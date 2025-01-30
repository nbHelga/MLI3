<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_ktp'=>fake()->sentence(2),
            'nama_panggil'=>fake()->sentence(1),
            'gender'=>fake()->sentence(1),
            'nik'=>fake()->unique()->number(16),
            'kk'=>fake()->unique()->number(16),
            'kode_perusahaan'=>fake()->char(3),
            'nama_perusahaan'=>fake()->sentence(5),
            'departemen'=>fake()->sentence(2),
            'jabatan'=>fake()->sentence(1),
            'tempat_lahir'=>fake()->sentence(1),
            'tgl_lahir',fake()->date(),
            'usia'=>fake()->number(2),
            'alamat_ktp'=>fake()->text(),
            'alamat_skrg'=>fake()->text(),
            'agama'=>fake()->sentence(1),
            'status_nikah'=>fake()->sentence(2),
            'kewarganegaraan'=>Str('WNI'),
            'pendidikan'=>fake()->char(3),
            'nama_sekolah'=>fake()->sentence(5),
            'email'=>fake()->unique()->safeEmail(),
            'email_company'=>fake()->safeEmail(),
            'no_hp'=>fake()->unique()->number(16),
            'no_hp_rekan_1'=>fake()->unique()->number(16),
            'no_hp_rekan_1_ket'=>fake()->text(),
            'no_hp_rekan_2'=>fake()->unique()->number(16),
            'no_hp_rekan_2_ket'=>fake()->text(),
            'tgl_masuk'=>now(),
            ];
    }
}
