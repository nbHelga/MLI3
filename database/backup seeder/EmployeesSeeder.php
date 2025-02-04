<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employees;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employees::create(attributes:[
            'id'=>'MLIIOF0001',
            'nama'=>'RANA ADELISA',
            'nama_panggil'=>'RANA',
            // 'gender'=>'WANITA',
            // 'nik'=>'3578154207970001',	 
            // 'kk'=>'3317091112230002',
            // 'sim_jenis'=>'A',
            // 'sim_masa_berlaku'=>'2028-12-31',
            // 'kode_perusahaan'=>'MLINDO',
            // // 'nama_perusahaan'=>'MONSTER LAUT INDONESIA',
            // 'departemen'=>'IOF',
            // 'jabatan'=>'INTERNAL OFFICE 1',
            // 'tempat_lahir'=>'SURABAYA',
            // 'tgl_lahir'=>'1997-07-02',
            // 'usia'=>'27',
            // 'alamat_ktp'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            // 'alamat_skrg'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            // 'agama'=>'ISLAM',
            // 'status_nikah'=>'KAWIN',
            // 'kewarganegaraan'=>'WNI',
            // 'pendidikan'=>'S2',
            // 'nama_sekolah'=>'UNIVERSITAS AIRLANGGA',
            // 'email'=>'rana.adelisa@gmail.com',
            // 'email_company'=>'',
            // 'no_hp'=>'085730743323',
            // 'no_hp_rekan_1'=>'082135948540',
            // 'no_hp_rekan_1_ket'=>'SUAMI (AZIZ)',
            // 'no_hp_rekan_2'=>'08123282274',
            // 'no_hp_rekan_2_ket'=>'IBU (WIWIK)',
            // 'tgl_masuk'=>'2024-05-20',
            // 'tgl_keluar'=>null,
        ]);
        Employees::create([
            'id'=>'MLIFIN0002',
            'nama'=>'EKADIAN KARTIKA PUTRI',
            'nama_panggil'=>'EKA',
            // 'gender'=>'WANITA',
            // 'nik'=>'3318084710010001',	 
            // 'kk'=>'3318083112040009',
            // 'sim_jenis'=>'C',
            // 'sim_masa_berlaku'=>'2028-12-31',
            // 'kode_perusahaan'=>'MLINTER',
            // // 'nama_perusahaan'=>'MONSTER LAUT INTERNASIONAL',
            // 'departemen'=>'FIN',
            // 'jabatan'=>'FINANCE 2',
            // 'tempat_lahir'=>'PATI',
            // 'tgl_lahir'=>'2001-10-07',
            // 'usia'=>'23',
            // 'alamat_ktp'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            // 'alamat_skrg'=>'DS. PANTIHARJO RT 01 RW 001 KEC. KALIORI KAB. REMBANG',
            // 'agama'=>'ISLAM',
            // 'status_nikah'=>'KAWIN',
            // 'kewarganegaraan'=>'WNI',
            // 'pendidikan'=>'S2',
            // 'nama_sekolah'=>'UNIVERSITAS AIRLANGGA',
            // 'email'=>'rana.adelisa@gmail.com',
            // 'email_company'=>'',
            // 'no_hp'=>'085730743323',
            // 'no_hp_rekan_1'=>'082135948540',
            // 'no_hp_rekan_1_ket'=>'SUAMI (AZIZ)',
            // 'no_hp_rekan_2'=>'08123282274',
            // 'no_hp_rekan_2_ket'=>'IBU (WIWIK)',
            // 'tgl_masuk'=>'2024-05-20',
            // 'tgl_keluar'=>null,
        ]);

        Employees::create(attributes:[
            'id'=>'MLIMKT0003',
            'nama'=>'KEVIN PRAYOGA',
            'nama_panggil'=>'KEVIN',
            // 'gender'=>'PRIA',
            // 'nik'=>'3318072910970001',	 
            // 'kk'=>'3317091106200005',
            // 'sim_jenis'=>'A',
            // 'sim_masa_berlaku'=>null,
            // 'kode_perusahaan'=>'MLINTER',
            // // 'nama_perusahaan'=>'MONSTER LAUT INTERNASIONAL',
            // 'departemen'=>'MKT',
            // 'jabatan'=>'MARKETING 3',
            // 'tempat_lahir'=>'PATI',
            // 'tgl_lahir'=>'1997-10-29',
            // 'usia'=>'27',
            // 'alamat_ktp'=>'DS. MOJOWARNO RT 003 / 002 KEC. KALIORI KAB. REMBANG',
            // 'alamat_skrg'=>'DS. MOJOWARNO RT 003 / 002 KEC. KALIORI KAB. REMBANG',
            // 'agama'=>'ISLAM',
            // 'status_nikah'=>'KAWIN',
            // 'kewarganegaraan'=>'WNI',
            // 'pendidikan'=>'SMK',
            // 'nama_sekolah'=>'SMK BINA TUNAS BHAKTI JUWANA',
            // 'email'=>'ipinu2151@gmail.com',
            // 'email_company'=>'',
            // 'no_hp'=>'088233236206',
            // 'no_hp_rekan_1'=>'083122647902',
            // 'no_hp_rekan_1_ket'=>'ISTRI (SITI AINI)',
            // 'no_hp_rekan_2'=>'089635366633',
            // 'no_hp_rekan_2_ket'=>'IBU (TASMIAH)',
            // 'tgl_masuk'=>'2022-12-24',
            // 'tgl_keluar'=>null,
        ]);
    }
}
