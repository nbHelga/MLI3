<?php

namespace Database\Seeders;

use App\Models\NonEmployee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NonEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NonEmployee::create(attributes:[
            'nik'=>'3578154207970001',
            'status_aktif'=>'ACTIVE',	 
            'nama_ktp'=>'RANA ADELISA',
            'nama'=>'RANA',
            'gender'=>'WANITA',
            'kk'=>'3317091112230002',
            'sim_jenis_1'=>'A',
            'sim_masa_berlaku_1'=>'2028-12-31',
            'sim_jenis_2'=>null,
            'sim_masa_berlaku_2'=>null,
            'kode_perusahaan'=>'MLINDO',
            'tempat_lahir'=>'SURABAYA',
            'tgl_lahir'=>'1997-07-02',
            'usia'=>'27',
            'alamat_ktp'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            'alamat_skrg'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            'agama'=>'ISLAM',
            'status_nikah'=>'KAWIN',
            'kewarganegaraan'=>'WNI',
            'no_hp'=>'085730743323',
            'no_hp_rekan_1'=>'082135948540',
            'no_hp_rekan_1_ket'=>'SUAMI (AZIZ)',
            'no_hp_rekan_2'=>'08123282274',
            'no_hp_rekan_2_ket'=>'IBU (WIWIK)',
            'tgl_masuk'=>'2024-05-20',
    
            ]);
    }
}
