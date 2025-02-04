<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perusahaan::create([
            'nama'=>'MONSTER LAUT INDONESIA',
            'kode'=>'MLINDO'
        ]);
        Perusahaan::create([
            'nama'=>'MONSTER LAUT INTERNASIONAL',
            'kode'=>'MLINTER'
        ]);
    }
}
