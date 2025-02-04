<?php

namespace Database\Seeders;

use App\Models\AssetForklift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForkliftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssetForklift::create(attributes:[
            'nama'=>'FORKLIFT SUMITOMO',
            'merk'=>'',
            'qty'=>'2',
            'lokasi'=>'CS 01',
        ]);
        AssetForklift::create(attributes:[
            'nama'=>'FORKLIFT LINDE',
            'merk'=>'R20S DAN R20 DOUBLE DEEP',
            'qty'=>'2',
            'lokasi'=>'CS 02',
        ]);
        AssetForklift::create(attributes:[
            'nama'=>'FORKLIFT LINDE',
            'merk'=>'R16',
            'qty'=>'1',
            'lokasi'=>'PT MASL',
        ]);
    }
}
